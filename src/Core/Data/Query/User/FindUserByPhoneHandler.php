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

    /**
     * @param FindUserByPhone $query
     * @return User|null
     */
    public function handle(QueryInterface $query): ?User
    {
        return $this->em->getRepository(User::class)
            ->createQueryBuilder('u')
            ->andWhere('u.phone = :phone')
            ->setParameter('phone', $query->phone)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
