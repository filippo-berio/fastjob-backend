<?php

namespace App\Core\Data\Query\User;

use App\Core\Entity\User;
use App\CQRS\QueryHandlerInterface;
use App\CQRS\QueryInterface;
use Doctrine\ORM\EntityManagerInterface;

class FindUserByPhoneHandler implements QueryHandlerInterface
{
    public function __construct(
        private EntityManagerInterface $em
    ) {
    }

    public function handle(QueryInterface $query): ?User
    {
        /** @var FindUserByPhone $query */
        return $this->em->getRepository(User::class)
            ->createQueryBuilder('u')
            ->andWhere('u.phone = :phone')
            ->setParameter('phone', $query->phone)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
