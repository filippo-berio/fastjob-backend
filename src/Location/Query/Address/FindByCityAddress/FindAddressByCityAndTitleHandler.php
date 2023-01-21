<?php

namespace App\Location\Query\Address\FindByCityAddress;

use App\CQRS\QueryHandlerInterface;
use App\CQRS\QueryInterface;
use App\Location\Entity\Address;
use Doctrine\ORM\EntityManagerInterface;

class FindAddressByCityAndTitleHandler implements QueryHandlerInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
    }

    /**
     * @param FindAddressByCityAndTitle $query
     * @return Address|null
     */
    public function handle(QueryInterface $query): ?Address
    {
        return $this->entityManager->getRepository(Address::class)
            ->createQueryBuilder('a')
            ->andWhere('a.city = :city')
            ->andWhere('a.title = :title')
            ->setParameters([
                'city' => $query->city,
                'title' => $query->title
            ])
            ->getQuery()
            ->getOneOrNullResult();
    }
}
