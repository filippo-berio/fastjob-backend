<?php

namespace App\Core\Infrastructure\Query\Profile;

use App\Core\Infrastructure\Entity\Profile;
use App\Core\Domain\Query\Profile\FindProfileByUser;
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

    public function getQueryClass(): string
    {
        return FindProfileByUser::class;
    }
}
