<?php

namespace App\Location\Query\City;

use App\CQRS\QueryHandlerInterface;
use App\CQRS\QueryInterface;
use App\Location\Entity\City;
use Doctrine\ORM\EntityManagerInterface;

class FindCityByIdHandler implements QueryHandlerInterface
{
    public function __construct(
        private EntityManagerInterface $em
    ) {
    }

    /**
     * @param FindCityById $query
     * @return ?City
     */
    public function handle(QueryInterface $query): ?City
    {
        return $this->em->getRepository(City::class)->find($query->id);
    }
}
