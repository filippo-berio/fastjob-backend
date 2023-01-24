<?php

namespace App\Location\Repository;

use App\Location\Entity\City;
use Doctrine\ORM\EntityManagerInterface;

class CityRepository
{

    public function __construct(
        private EntityManagerInterface $em
    ) {
    }

    public function find(int $id): ?City
    {
        return $this->em->getRepository(City::class)->find($id);
    }
}
