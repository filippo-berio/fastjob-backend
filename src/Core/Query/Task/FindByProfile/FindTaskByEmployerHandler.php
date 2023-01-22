<?php

namespace App\Core\Query\Task\FindByProfile;

use App\Core\Entity\Task;
use App\CQRS\QueryHandlerInterface;
use App\CQRS\QueryInterface;
use Doctrine\ORM\EntityManagerInterface;

class FindTaskByEmployerHandler implements QueryHandlerInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * @param FindTaskByEmployer $query
     * @return Task[]
     */
    public function handle(QueryInterface $query): array
    {
        return $this->entityManager->getRepository(Task::class)
            ->createQueryBuilder('t')
            ->andWhere('t.employer = :employer')
            ->setParameter('employer', $query->profile)
            ->getQuery()
            ->getResult();
    }
}
