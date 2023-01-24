<?php

namespace App\Location\Repository;

use App\Location\Entity\Address;
use App\Location\Entity\City;
use Doctrine\ORM\EntityManagerInterface;

class AddressRepository
{

    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
    }

    public function save(Address $address): Address
    {
        $this->entityManager->persist($address);
        $this->entityManager->flush();
        return $address;
    }

    public function findByCityAndTitle(City $city, string $title): ?Address
    {
        return $this->entityManager->getRepository(Address::class)
            ->createQueryBuilder('a')
            ->andWhere('a.city = :city')
            ->andWhere('a.title = :title')
            ->setParameters([
                'city' => $city,
                'title' => $title
            ])
            ->getQuery()
            ->getOneOrNullResult();
    }
}
