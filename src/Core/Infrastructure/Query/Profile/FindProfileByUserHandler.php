<?php

namespace App\Core\Infrastructure\Query\Profile;

use App\Core\Domain\Query\Profile\FindProfileByUser;
use App\Core\Infrastructure\Entity\Profile;
use App\Lib\CQRS\QueryInterface;

class FindProfileByUserHandler extends BaseProfileQueryHandler
{
    /**
     * @param FindProfileByUser $query
     * @return Profile|null
     */
    public function handle(QueryInterface $query): ?Profile
    {
        $profile = $this->entityManager
            ->getRepository(Profile::class)
            ->createQueryBuilder('p')
            ->andWhere('p.userId = :id')
            ->setParameter('id', $query->user->getId())
            ->getQuery()
            ->getOneOrNullResult();
        return $profile ? $this->fillProfile($profile) : null;
    }

    public function getQueryClass(): string
    {
        return FindProfileByUser::class;
    }
}
