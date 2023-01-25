<?php

namespace App\Core\Infrastructure\Repository;

use App\Core\Infrastructure\Entity\Category;
use App\Core\Domain\Repository\CategoryRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

class CategoryRepository implements CategoryRepositoryInterface
{

    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
    }

    /**
     * @return Category[]
     */
    public function findByIds(array $ids): array
    {
        return $this->entityManager->getRepository(Category::class)
            ->createQueryBuilder('c')
            ->andWhere('c.id in (:id)')
            ->setParameter('id', $ids)
            ->getQuery()
            ->getResult();
    }
}
