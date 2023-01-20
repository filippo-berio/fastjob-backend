<?php

namespace App\Core\Query\Profile\FindByPhone;

use App\Core\Entity\Profile;
use App\CQRS\QueryHandlerInterface;
use App\CQRS\QueryInterface;
use Doctrine\ORM\EntityManagerInterface;

class FindProfileByPhoneHandler implements QueryHandlerInterface
{
    public function __construct(
        private EntityManagerInterface $em
    ) {
    }

    public function handle(QueryInterface $query): ?Profile
    {
        /** @var FindProfileByPhone $query; */
        return $this->em->getRepository(Profile::class)
            ->createQueryBuilder('p')
            ->innerJoin('p.user', 'u')
            ->andWhere('u.phone = :phone')
            ->setParameter('phone', $query->phone)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
