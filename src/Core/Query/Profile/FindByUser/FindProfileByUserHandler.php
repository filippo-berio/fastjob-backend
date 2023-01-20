<?php

namespace App\Core\Query\Profile\FindByUser;

use App\Core\Entity\Profile;
use App\CQRS\QueryHandlerInterface;
use App\CQRS\QueryInterface;
use Doctrine\ORM\EntityManagerInterface;

class FindProfileByUserHandler implements QueryHandlerInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
    }

    /**
     * @param FindProfileByUser $query
     * @return Profile|null
     */
    public function handle(QueryInterface $query): ?Profile
    {
        return $this->entityManager
            ->getRepository(Profile::class)
            ->findOneBy([
                'user' => $query->user
            ]);
    }
}
