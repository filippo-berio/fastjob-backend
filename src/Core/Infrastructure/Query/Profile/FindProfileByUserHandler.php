<?php

namespace App\Core\Infrastructure\Query\Profile;

use App\Core\Infrastructure\Entity\Profile;
use App\Core\Domain\Query\Profile\FindProfileByUser;
use App\CQRS\Bus\QueryBusInterface;
use App\CQRS\QueryHandlerInterface;
use App\CQRS\QueryInterface;
use Doctrine\ORM\EntityManagerInterface;

class FindProfileByUserHandler implements QueryHandlerInterface
{
    use FillUserTrait;

    public function __construct(
        private EntityManagerInterface $entityManager,
        private QueryBusInterface $queryBus,
    ) {
    }

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
        return $profile ? $this->fillUser($profile, $this->queryBus) : null;
    }

    public function getQueryClass(): string
    {
        return FindProfileByUser::class;
    }
}
