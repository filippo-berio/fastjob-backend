<?php

namespace App\Core\Infrastructure\Query\Profile;

use App\Core\Domain\Entity\Profile;
use App\Core\Domain\Query\Profile\FindProfileByPhone;
use App\CQRS\QueryHandlerInterface;
use App\CQRS\QueryInterface;
use Doctrine\ORM\EntityManagerInterface;

class FindProfileByPhoneHandler implements QueryHandlerInterface
{
    public function __construct(
        private EntityManagerInterface $em
    ) {
    }

    /**
     * @param FindProfileByPhone $query
     * @return Profile|null
     */
    public function handle(QueryInterface $query): ?Profile
    {
        return $this->em->getRepository(Profile::class)
            ->createQueryBuilder('p')
            ->innerJoin('p.user', 'u')
            ->andWhere('u.phone = :phone')
            ->setParameter('phone', $query->phone)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function getQueryClass(): string
    {
        return FindProfileByPhone::class;
    }
}
