<?php

namespace App\Core\Query\TaskSwipe\FindByProfile;

use App\Core\Entity\TaskSwipe;
use App\CQRS\QueryHandlerInterface;
use App\CQRS\QueryInterface;
use Doctrine\ORM\EntityManagerInterface;

class FindTaskSwipeByProfileHandler implements QueryHandlerInterface
{
    public function __construct(
        private EntityManagerInterface $em
    ) {
    }

    /**
     * @param FindTaskSwipeByProfile $query
     * @return TaskSwipe[]
     */
    public function handle(QueryInterface $query): array
    {
        return $this->em
            ->getRepository(TaskSwipe::class)
            ->createQueryBuilder('s')
            ->andWhere('s.profile = :profile')
            ->setParameter('profile', $query->profile)
            ->getQuery()
            ->getResult();
    }
}
