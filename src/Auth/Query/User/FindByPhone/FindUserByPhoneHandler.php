<?php

namespace App\Auth\Query\User\FindByPhone;

use App\Auth\Entity\User;
use App\Lib\CQRS\QueryHandlerInterface;
use App\Lib\CQRS\QueryInterface;
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

    public function getQueryClass(): string
    {
        return FindUserByPhone::class;
    }
}
