<?php

namespace App\Core\Data\Command\RefreshToken;

use App\Core\Entity\RefreshToken;
use App\CQRS\CommandInterface;
use Doctrine\ORM\EntityManagerInterface;

class SaveRefreshTokenHandler implements \App\CQRS\CommandHandlerInterface
{
    public function __construct(
        private EntityManagerInterface $em
    ) {
    }

    public function handle(CommandInterface $command): RefreshToken
    {
        /** @var SaveRefreshToken $command */
        $this->em->persist($command->refreshToken);
        $this->em->flush();
        return $command->refreshToken;
    }
}
