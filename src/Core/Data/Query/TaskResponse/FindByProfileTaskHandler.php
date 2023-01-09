<?php

namespace App\Core\Data\Query\TaskResponse;

use App\Core\Entity\TaskResponse;
use App\CQRS\QueryHandlerInterface;
use App\CQRS\QueryInterface;
use Doctrine\ORM\EntityManagerInterface;

class FindByProfileTaskHandler implements QueryHandlerInterface
{
    public function __construct(
        private EntityManagerInterface $em
    ) {
    }

    public function handle(QueryInterface $query): ?TaskResponse
    {
        /** @var FindByProfileTask $query */
        return $this->em->getRepository(TaskResponse::class)
            ->createQueryBuilder('tr')
            ->andWhere('tr.task = :task')
            ->andWhere('tr.responder = :profile')
            ->setParameters([
                'task' => $query->task,
                'profile' => $query->profile
            ])
            ->getQuery()
            ->getOneOrNullResult();
    }
}
