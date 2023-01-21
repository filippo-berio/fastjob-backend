<?php

namespace App\Auth\Command\RefreshToken\Save;

use App\Auth\Entity\RefreshToken;
use App\CQRS\CommandHandlerInterface;
use App\CQRS\CommandInterface;
use Doctrine\ORM\EntityManagerInterface;

class SaveRefreshTokenHandler implements CommandHandlerInterface
{
    public function __construct(
        private EntityManagerInterface $em
    ) {
    }

    /**
     * @param SaveRefreshToken $command
     * @return RefreshToken
     */
    public function handle(CommandInterface $command): RefreshToken
    {
        $this->em->persist($command->refreshToken);
        $this->em->flush();
        return $command->refreshToken;
    }
}
