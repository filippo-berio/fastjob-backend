<?php

namespace App\Core\Data\Query\Task;

use App\Core\Entity\Task;
use App\CQRS\QueryHandlerInterface;
use App\CQRS\QueryInterface;
use Doctrine\ORM\EntityManagerInterface;

class FindTaskByIdHandler implements QueryHandlerInterface
{
    public function __construct(
        private EntityManagerInterface $em
    ) {
    }

    public function handle(QueryInterface $query): ?Task
    {
        /** @var FindTaskById $query */
        return $this->em->getRepository(Task::class)->find($query->id);
    }
}
