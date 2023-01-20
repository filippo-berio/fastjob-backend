<?php

namespace App\Core\Query\Category\FindByIds;

use App\Core\Entity\Category;
use App\CQRS\QueryHandlerInterface;
use App\CQRS\QueryInterface;
use Doctrine\ORM\EntityManagerInterface;

class FindCategoriesByIdsHandler implements QueryHandlerInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
    }

    /**
     * @param FindCategoriesByIds $query
     * @return Category[]
     */
    public function handle(QueryInterface $query): array
    {
        return $this->entityManager->getRepository(Category::class)
            ->createQueryBuilder('c')
            ->andWhere('c.id in (:id)')
            ->setParameter('id', $query->ids)
            ->getQuery()
            ->getResult();
    }
}
