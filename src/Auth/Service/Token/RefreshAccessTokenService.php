<?php

namespace App\Auth\Service\Token;

use App\Auth\Command\RefreshToken\Save\SaveRefreshToken;
use App\Auth\Entity\User;
use App\Auth\Service\UuidGenerator;
use App\Lib\CQRS\Bus\CommandBusInterface;

class RefreshAccessTokenService
{
    public function __construct(
        private CreateAccessTokenService $createAccessTokenService,
        private UuidGenerator $uuidGenerator,
        private CommandBusInterface $commandBus,
    ) {
    }

    public function refresh(User $user): string
    {
        $accessToken = $this->createAccessTokenService->create($user);
        $refreshToken = $this->uuidGenerator->generate();
        $user->getRefreshToken()->setToken($refreshToken);
        $this->commandBus->execute(new SaveRefreshToken($user->getRefreshToken()));
        return $accessToken;
    }
}
