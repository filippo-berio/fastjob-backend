<?php

namespace App\Core\Security\Authistic;

use App\Authistic\Adapter\AuthisticAdapterInterface;
use App\Authistic\DTO\TokenPair;
use App\Core\Data\Query\Profile\FindByAuthisticId;
use App\Core\Entity\Profile;
use App\CQRS\Bus\QueryBusInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class AuthisticTokenAuthenticator extends AbstractAuthenticator
{
    public function __construct(
        private AuthisticAdapterInterface $authisticAdapter,
        private QueryBusInterface         $queryBus,
    ) {
    }

    public function supports(Request $request): ?bool
    {
        return true;
    }

    public function authenticate(Request $request): Passport
    {
        sscanf(
            $request->headers->get('Authorization') ?? '',
            'Bearer %s',
            $tokens
        );
        if (!$tokens) {
            throw new AuthenticationException();
        }

        [$accessToken, $refreshToken] = explode(':', $tokens);
        $tokenPair = $this->authisticAdapter->authenticate($accessToken, $refreshToken);

        $profile = $this->getProfile($tokenPair);
        if (!$profile) {
            throw new AuthenticationException();
        }

        $user = new User($profile, $tokenPair);

        return new SelfValidatingPassport(
            new UserBadge($profile->getId(), function () use ($user) {
                return $user;
            }
            )
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $data = [
            // you may want to customize or obfuscate the message first
            'message' => strtr($exception->getMessageKey(), $exception->getMessageData())

            // or to translate this message
            // $this->translator->trans($exception->getMessageKey(), $exception->getMessageData())
        ];

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }

    private function getProfile(TokenPair $tokenPair): ?Profile
    {
        $payload = explode('.', $tokenPair->getAccessToken())[1];
        $payload = base64_decode($payload);
        $payload = json_decode($payload, true);
        $authisticId = $payload['userId'];
        $authisticId = 1;

        return $this->queryBus->handle(new FindByAuthisticId($authisticId));
    }
}
