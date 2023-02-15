<?php

namespace App\Auth\Command\User\Save;

use App\Auth\Entity\User;
use App\Lib\CQRS\CommandHandlerInterface;
use App\Lib\CQRS\CommandInterface;
use Doctrine\ORM\EntityManagerInterface;

class SaveUserHandler implements CommandHandlerInterface
{
    public function __construct(
        private EntityManagerInterface $em
    ) {
    }

    /**
     * @param SaveUser $command
     * @return User
     */
    public function handle(CommandInterface $command): User
    {
        $this->em->persist($command->user);
        $this->em->flush();
        return $command->user;
    }

    public function getCommandClass(): string
    {
        return SaveUser::class;
    }
}
