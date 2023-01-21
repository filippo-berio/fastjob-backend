<?php

namespace App\Auth\Command\User\Save;

use App\Auth\Entity\User;
use App\CQRS\CommandHandlerInterface;
use App\CQRS\CommandInterface;
use Doctrine\ORM\EntityManagerInterface;

class SaveUserHandler implements CommandHandlerInterface
{
    public function __construct(
        private EntityManagerInterface $em
    ) {
    }

    public function handle(CommandInterface $command): User
    {
        /** @var SaveUser $command */
        $this->em->persist($command->user);
        $this->em->flush();
        return $command->user;
    }
}
