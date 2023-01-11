<?php

namespace App\Core\Data\Command\ExecutorSwipe\Create;

use App\Core\Entity\ExecutorSwipe;
use App\CQRS\CommandHandlerInterface;
use App\CQRS\CommandInterface;
use Doctrine\ORM\EntityManagerInterface;

class CreateExecutorSwipeHandler implements CommandHandlerInterface
{
    public function __construct(
        private EntityManagerInterface $em
    ) {
    }

    public function handle(CommandInterface $command): ExecutorSwipe
    {
        /** @var CreateExecutorSwipe $command */
        $this->em->persist($command->executorSwipe);
        $this->em->flush();
        return $command->executorSwipe;
    }
}
