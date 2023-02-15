<?php

namespace App\Auth\Service;

use App\Auth\Entity\User;
use App\Auth\Query\User\FindById\FindUserById;
use App\Auth\Repository\AccessTokenRepository;
use App\Auth\Service\Token\GetRefreshTokenService;
use App\Auth\Service\Token\RefreshAccessTokenService;
use App\Lib\CQRS\Bus\QueryBusInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class AuthenticateService
{
    public function __construct(
        private QueryBusInterface $queryBus,
        private GetRefreshTokenService $refreshTokenService,
        private RefreshAccessTokenService $refreshAccessTokenService,
        private AccessTokenRepository $accessTokenRepository,
    ) {
    }

    public function authenticate(string $accessToken, ?string $refreshToken = null): User
    {
        $user = $this->getUser($accessToken);
        $actualToken = $this->accessTokenRepository->findByUser($user);
        if ($actualToken?->getValue() !== $accessToken) {
            if (!$refreshToken) {
                $this->throwException();
            }
            $accessToken = $this->refreshAccessToken($user, $refreshToken);
        }
        $user->setAccessToken($accessToken);
        return $user;
    }

    private function refreshAccessToken(User $user, string $refreshToken): string
    {
        $actualRefreshToken = $this->refreshTokenService->getOrCreate($user);
        if ($actualRefreshToken->getToken() !== $refreshToken) {
            $this->throwException();
        }
        return $this->refreshAccessTokenService->refresh($user);
    }

    private function getUser(string $accessToken): User
    {
        $id = $this->getUserId($accessToken);
        $user = $this->queryBus->query(new FindUserById($id));
        if (!$user) {
            $this->throwException();
        }
        return $user;
    }

    private function getUserId(string $accessToken): int
    {
        $payload = explode('.', $accessToken)[1];
        $payload = base64_decode($payload);
        $payload = json_decode($payload, true);

        $id = $payload['userId'] ?? null;
        if (!$id) {
            $this->throwException();
        }
        return $id;
    }

    private function throwException()
    {
        throw new AuthenticationException();
    }
}
