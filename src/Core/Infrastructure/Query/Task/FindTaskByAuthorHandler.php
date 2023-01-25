<?php

namespace App\Core\Infrastructure\Query\Task;

use App\Core\Domain\Entity\Task;
use App\Core\Domain\Query\Task\FindTaskByAuthor;
use App\CQRS\QueryHandlerInterface;
use App\CQRS\QueryInterface;
use Doctrine\ORM\EntityManagerInterface;

class FindTaskByAuthorHandler implements QueryHandlerInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * @param FindTaskByAuthor $query
     * @return Task[]
     */
    public function handle(QueryInterface $query): array
    {
        return $this->entityManager->getRepository(Task::class)
            ->createQueryBuilder('t')
            ->andWhere('t.author = :author')
            ->setParameter('author', $query->profile)
            ->getQuery()
            ->getResult();
    }

    public function getQueryClass(): string
    {
        return FindTaskByAuthor::class;
    }
}
