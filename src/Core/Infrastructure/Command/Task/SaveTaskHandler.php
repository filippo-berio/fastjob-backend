<?php

namespace App\Core\Infrastructure\Command\Task;

use App\Core\Domain\Command\Task\Save\SaveTask;
use App\Core\Infrastructure\Entity\Task;
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

    public function getCommandClass(): string
    {
        return SaveTask::class;
    }
}
