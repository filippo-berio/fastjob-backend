<?php

namespace App\Core\Domain\Command\Task\Save;

use App\Core\Domain\Entity\Task;
use App\CQRS\CommandHandlerInterface;
use App\CQRS\CommandInterface;
use Doctrine\ORM\EntityManagerInterface;

class SaveTaskHandler implements CommandHandlerInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * @param SaveTask $command
     * @return Task
     */
    public function handle(CommandInterface $command): Task
    {
        $this->entityManager->persist($command->task);
        $this->entityManager->flush();
        return $command->task;
    }
}
