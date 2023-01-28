<?php

namespace App\Core\Infrastructure\Repository;

use App\Core\Domain\Entity\Profile as DomainProfile;
use App\Core\Domain\Entity\Task as DomainTask;
use App\Core\Domain\Entity\TaskOffer as DomainTaskOffer;
use App\Core\Domain\Repository\TaskOfferRepositoryInterface;
use App\Core\Infrastructure\Entity\TaskOffer;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;

class TaskOfferRepository implements TaskOfferRepositoryInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private string $taskOfferLifetime,
    ) {
    }

    public function findExpired(): array
    {
        return $this->entityManager->getRepository(TaskOffer::class)
            ->createQueryBuilder('to')
            ->andWhere('to.createdAt < :expired')
            ->setParameter('expired', new DateTimeImmutable("-$this->taskOfferLifetime"))
            ->getQuery()
            ->getResult();
    }

    public function save(DomainTaskOffer $taskOffer): DomainTaskOffer
    {
        $this->entityManager->persist($taskOffer);
        $this->entityManager->flush();
        return $taskOffer;
    }

    public function findByProfileAndTask(DomainProfile $profile, DomainTask $task): ?DomainTaskOffer
    {
        return $this->entityManager->getRepository(TaskOffer::class)
            ->createQueryBuilder('to')
            ->andWhere('identity(to.task) = :task')
            ->setParameter('task', $task->getId())
            ->andWhere('identity(to.profile) = :profile')
            ->setParameter('profile', $profile->getId())
            ->getQuery()
            ->getOneOrNullResult();
    }
}
