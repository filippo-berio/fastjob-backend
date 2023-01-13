<?php

namespace App\Core\Security;

use App\Core\Service\Auth\AuthenticateService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class AccessRefreshTokenAuthenticator extends AbstractAuthenticator
{
    public function __construct(
        private AuthenticateService $authenticateService,
    ) {
    }

    public function supports(Request $request): ?bool
    {
        return $this->getAccessToken($request) && $this->getRefreshToken($request);
    }

    public function authenticate(Request $request): Passport
    {
        $accessToken = $this->getAccessToken($request);
        $refreshToken = $this->getRefreshToken($request);
        $user = $this->authenticateService->authenticate($accessToken, $refreshToken);

        return new SelfValidatingPassport(
            new UserBadge($user->getId(), function () use ($user) {
                return $user;
            })
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $data = [
            'message' => strtr($exception->getMessageKey(), $exception->getMessageData())
        ];

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }

    private function getAccessToken(Request $request): ?string
    {
        sscanf(
            $request->headers->get('Authorization') ?? '',
            'Bearer %s',
            $accessToken
        );
        return $accessToken;
    }

    private function getRefreshToken(Request $request): string
    {
        return $request->headers->get('x-refresh-token');
    }
}
