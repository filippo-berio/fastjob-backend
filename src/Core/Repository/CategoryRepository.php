<?php

namespace App\Core\Repository;

use App\Core\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;

class CategoryRepository
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
