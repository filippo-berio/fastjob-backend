<?php

namespace App\Auth\Service\Token;

use App\Auth\Command\RefreshToken\Save\SaveRefreshToken;
use App\Auth\Entity\RefreshToken;
use App\Auth\Entity\User;
use App\Auth\Query\RefreshToken\FindByUser\FindRefreshTokenByUser;
use App\Auth\Service\UuidGenerator;
use App\CQRS\Bus\CommandBusInterface;
use App\CQRS\Bus\QueryBusInterface;

class GetRefreshTokenService
{
    public function __construct(
        private QueryBusInterface $queryBus,
        private CommandBusInterface $commandBus,
        private UuidGenerator $uuidGenerator,
    ) {
    }

    public function getOrCreate(User $user): RefreshToken
    {
        $refreshToken = $this->queryBus->query(new FindRefreshTokenByUser($user));
        if (!$refreshToken) {
            $refreshToken = new RefreshToken($user, $this->uuidGenerator->generate());
            $refreshToken = $this->commandBus->execute(new SaveRefreshToken($refreshToken));
        }
        return $refreshToken;
    }
}
