<?php

namespace App\Core\Command\ExecutorSwipe\Create;

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

    /**
     * @param CreateExecutorSwipe $command
     * @return ExecutorSwipe
     */
    public function handle(CommandInterface $command): ExecutorSwipe
    {
        $this->em->persist($command->executorSwipe);
        $this->em->flush();
        return $command->executorSwipe;
    }
}
