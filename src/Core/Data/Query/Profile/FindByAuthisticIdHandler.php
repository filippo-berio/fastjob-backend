<?php

namespace App\Core\Data\Query\Profile;

use App\Core\Entity\Profile;
use App\CQRS\QueryHandlerInterface;
use App\CQRS\QueryInterface;
use Doctrine\ORM\EntityManagerInterface;

class FindByAuthisticIdHandler implements QueryHandlerInterface
{
    public function __construct(
        private EntityManagerInterface $em
    ) {
    }

    public function handle(QueryInterface $query): ?Profile
    {
        /** @param FindByAuthisticId $query */

        return $this->em->getRepository(Profile::class)
            ->createQueryBuilder('p')
            ->andWhere('p.authisticId = :id')
            ->setParameter('id', $query->authisticId)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
