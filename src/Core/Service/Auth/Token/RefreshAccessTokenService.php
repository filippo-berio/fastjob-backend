<?php

namespace App\Core\Service\Auth\Token;

use App\Core\Data\Command\RefreshToken\SaveRefreshToken;
use App\Core\Entity\User;
use App\Core\Service\UuidGenerator;
use App\CQRS\Bus\CommandBusInterface;

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
        $this->commandBus->handle(new SaveRefreshToken($user->getRefreshToken()));
        return $accessToken;
    }
}
