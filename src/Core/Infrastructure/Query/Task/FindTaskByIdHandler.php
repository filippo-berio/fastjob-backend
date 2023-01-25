<?php

namespace App\Core\Infrastructure\Query\Task;

use App\Core\Infrastructure\Entity\Task;
use App\Core\Domain\Query\Task\FindTaskById;
use App\CQRS\QueryHandlerInterface;
use App\CQRS\QueryInterface;
use Doctrine\ORM\EntityManagerInterface;

class FindTaskByIdHandler implements QueryHandlerInterface
{
    public function __construct(
        private EntityManagerInterface $em
    ) {
    }

    /**
     * @param FindTaskById $query
     * @return Task|null
     */
    public function handle(QueryInterface $query): ?Task
    {
        /** @var FindTaskById $query */
        return $this->em->getRepository(Task::class)->find($query->id);
    }

    public function getQueryClass(): string
    {
        return FindTaskById::class;
    }
}
