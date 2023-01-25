<?php

namespace App\Core\Infrastructure\Query\Profile;

use App\Core\Infrastructure\Entity\Profile;
use App\Core\Domain\Query\Profile\FindProfileByPhone;
use App\CQRS\Bus\QueryBusInterface;
use App\CQRS\QueryHandlerInterface;
use App\CQRS\QueryInterface;
use Doctrine\ORM\EntityManagerInterface;

class FindProfileByPhoneHandler implements QueryHandlerInterface
{
    use FillUserTrait;

    public function __construct(
        private EntityManagerInterface $em,
        private QueryBusInterface $queryBus,
    ) {
    }

    /**
     * @param FindProfileByPhone $query
     * @return Profile|null
     */
    public function handle(QueryInterface $query): ?Profile
    {
        $profile = $this->em->getRepository(Profile::class)
            ->createQueryBuilder('p')
            ->innerJoin('p.user', 'u')
            ->andWhere('u.phone = :phone')
            ->setParameter('phone', $query->phone)
            ->getQuery()
            ->getOneOrNullResult();
        return $profile ? $this->fillUser($profile, $this->queryBus) : null;
    }

    public function getQueryClass(): string
    {
        return FindProfileByPhone::class;
    }
}
