<?php

namespace App\Auth\Service\Token;

use App\Auth\Command\RefreshToken\Save\SaveRefreshToken;
use App\Auth\Entity\RefreshToken;
use App\Auth\Entity\User;
use App\Auth\Service\UuidGenerator;
use App\Lib\CQRS\Bus\CommandBusInterface;

class GetRefreshTokenService
{
    public function __construct(
        private CommandBusInterface $commandBus,
        private UuidGenerator $uuidGenerator,
    ) {
    }

    public function getOrCreate(User $user): RefreshToken
    {
        $refreshToken = $user->getRefreshToken();
        if (!$refreshToken) {
            $refreshToken = new RefreshToken($user, $this->uuidGenerator->generate());
            $refreshToken = $this->commandBus->execute(new SaveRefreshToken($refreshToken));
        }
        return $refreshToken;
    }
}
