<?php

namespace App\Core\Infrastructure\Query\Profile;

use App\Core\Infrastructure\Entity\Profile;
use App\Core\Domain\Query\Profile\FindProfileByPhone;
use App\CQRS\QueryInterface;

class FindProfileByPhoneHandler extends BaseProfileQueryHandler
{
    /**
     * @param FindProfileByPhone $query
     * @return Profile|null
     */
    public function handle(QueryInterface $query): ?Profile
    {
        $profile = $this->entityManager->getRepository(Profile::class)
            ->createQueryBuilder('p')
            ->innerJoin('p.user', 'u')
            ->andWhere('u.phone = :phone')
            ->setParameter('phone', $query->phone)
            ->getQuery()
            ->getOneOrNullResult();
        return $profile ? $this->fillProfile($profile) : null;
    }

    public function getQueryClass(): string
    {
        return FindProfileByPhone::class;
    }
}
