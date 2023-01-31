<?php

namespace App\Core\Infrastructure\Repository;

use App\Core\Domain\Entity\ExecutionCancel as DomainExecutionCancel;
use App\Core\Domain\Repository\ExecutionCancelRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

class ExecutionCancelRepository implements ExecutionCancelRepositoryInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function save(DomainExecutionCancel $executionCancel): DomainExecutionCancel
    {
        $this->entityManager->persist($executionCancel);
        $this->entityManager->flush();
        return $executionCancel;
    }
}
