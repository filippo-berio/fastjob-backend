<?php

namespace App\Core\Infrastructure\Query\Task;

use App\Core\Domain\Query\Task\FindAvailableReviewTasksForExecutor;
use App\Core\Infrastructure\Entity\Task;
use App\Core\Infrastructure\Repository\ReviewRepository;
use App\CQRS\QueryInterface;
use Doctrine\ORM\EntityManagerInterface;

class FindAvailableReviewTasksForExecutorHandler extends BaseTaskQueryHandler
{
    public function __construct(
        EntityManagerInterface $entityManager,
        private ReviewRepository $reviewRepository,
    ) {
        parent::__construct($entityManager);
    }

    /**
     * @param FindAvailableReviewTasksForExecutor $query
     * @return Task[]
     */
    public function handle(QueryInterface $query): array
    {
        $qb = $this->entityManager->getRepository(Task::class)
            ->createQueryBuilder('t');
        $this->joinAcceptedTaskOffers($qb);
        $tasks = $qb
            ->andWhere('identity(to.profile) = :profile')
            ->setParameter('profile', $query->executor->getId())
            ->andWhere('t.status = :finished')
            ->setParameter('finished', Task::STATUS_FINISHED)
            ->getQuery()
            ->getResult();

        foreach ($tasks as $i => $task) {
            $reviews = $this->reviewRepository->findForTask($task);
            foreach ($reviews as $review) {
                if ($review->getAuthor()->getId() === $query->executor->getId()) {
                    unset($tasks[$i]);
                    break;
                }
            }
        }

        return $tasks;
    }

    public function getQueryClass(): string
    {
        return FindAvailableReviewTasksForExecutor::class;
    }
}
