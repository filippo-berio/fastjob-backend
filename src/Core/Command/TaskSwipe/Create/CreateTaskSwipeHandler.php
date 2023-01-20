<?php

namespace App\Core\Command\TaskSwipe\Create;

use App\Core\Entity\TaskSwipe;
use App\CQRS\CommandHandlerInterface;
use App\CQRS\CommandInterface;
use Doctrine\ORM\EntityManagerInterface;

class CreateTaskSwipeHandler implements CommandHandlerInterface
{
    public function __construct(
        private EntityManagerInterface $em
    ) {
    }

    public function handle(CommandInterface $command): TaskSwipe
    {
        /** @var CreateTaskSwipe $command */
        $this->em->persist($command->taskSwipe);
        $this->em->flush();
        return $command->taskSwipe;
    }
}
