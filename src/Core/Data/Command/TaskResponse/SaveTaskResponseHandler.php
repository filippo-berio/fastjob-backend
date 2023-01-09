<?php

namespace App\Core\Data\Command\TaskResponse;

use App\Core\Entity\TaskResponse;
use App\CQRS\CommandHandlerInterface;
use App\CQRS\CommandInterface;
use Doctrine\ORM\EntityManagerInterface;

class SaveTaskResponseHandler implements CommandHandlerInterface
{
    public function __construct(
        private EntityManagerInterface $em
    ) {
    }

    public function handle(CommandInterface $command): TaskResponse
    {
        /** @var SaveTaskResponse $command */
        $this->em->persist($command->taskResponse);
        $this->em->flush();
        return $command->taskResponse;
    }
}
