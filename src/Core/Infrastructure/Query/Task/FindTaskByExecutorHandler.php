<?php

namespace App\Core\Infrastructure\Query\Task;

use App\Core\Domain\Entity\TaskOffer;
use App\Core\Domain\Query\Task\FindTaskByExecutor;
use App\Core\Domain\Entity\Task;
use App\CQRS\QueryInterface;
use Doctrine\ORM\EntityManagerInterface;

class FindTaskByExecutorHandler extends BaseTaskQueryHandler
{
    public function __construct(
        EntityManagerInterface $entityManager,
    ) {
        parent::__construct($entityManager);
    }

    /**
     * @param FindTaskByExecutor $query
     * @return Task[]
     */
    public function handle(QueryInterface $query): array
    {
        // TODO проверка что исполнитель не отказывался от задач
        $offers = $this->entityManager->getRepository(\App\Core\Infrastructure\Entity\TaskOffer::class)
            ->createQueryBuilder('to')
            ->andWhere('to.status = :acceptStatus')
            ->setParameter('acceptStatus', TaskOffer::STATUS_ACCEPTED)
            ->andWhere('identity(to.profile) = :profile')
            ->setParameter('profile', $query->executor->getId())
            ->getQuery()
            ->getResult();
        return array_map(
            fn(TaskOffer $taskOffer) => $taskOffer->getTask(),
            $offers
        );
    }

    public function getQueryClass(): string
    {
        return FindTaskByExecutor::class;
    }
}
