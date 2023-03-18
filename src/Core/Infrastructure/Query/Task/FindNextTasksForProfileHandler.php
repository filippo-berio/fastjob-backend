<?php

namespace App\Core\Infrastructure\Query\Task;

use App\Core\Domain\Query\Task\FindNextTasksForProfile;
use App\Core\Domain\Query\Task\FindTaskByAuthor;
use App\Core\Domain\Repository\PendingTaskRepositoryInterface;
use App\Core\Domain\Repository\ProfileNextTaskRepositoryInterface;
use App\Core\Domain\Repository\TaskSwipeRepositoryInterface;
use App\Core\Infrastructure\Entity\Category;
use App\Core\Infrastructure\Entity\Profile;
use App\Core\Infrastructure\Entity\Task;
use App\Core\Infrastructure\Entity\TaskSwipe;
use App\Lib\CQRS\Bus\QueryBusInterface;
use App\Lib\CQRS\QueryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;

class FindNextTasksForProfileHandler extends BaseTaskQueryHandler
{
    public function __construct(
        EntityManagerInterface             $entityManager,
        private QueryBusInterface                  $queryBus,
        private ProfileNextTaskRepositoryInterface $nextTaskRepository,
        private PendingTaskRepositoryInterface     $pendingTaskRepository,
        private TaskSwipeRepositoryInterface       $taskSwipeRepository,
    ) {
        parent::__construct($entityManager);
    }

    public function getQueryClass(): string
    {
        return FindNextTasksForProfile::class;
    }

    /**
     * @param FindNextTasksForProfile $query
     * @return Task[]
     */
    public function handle(QueryInterface $query): array
    {
        $queryBuilder = $this->entityManager->getRepository(Task::class)->createQueryBuilder('t');
        $this->filterFreeTaskStatus($queryBuilder);
        $this->filterDeadlineNotExpired($queryBuilder);
        $this->categories($queryBuilder, $query->profile);
        $this->city($queryBuilder, $query->profile);
        $queryBuilder->setMaxResults($query->count);

        if ($exclude = $this->getExcluded($query->profile)) {
            $this->exclude($queryBuilder, $exclude);
        }

        return $queryBuilder
            ->getQuery()
            ->getResult();
    }

    private function city(QueryBuilder $queryBuilder, Profile $profile): QueryBuilder
    {
        return $queryBuilder
            ->leftJoin('t.address', 'address')
            ->andWhere('t.remote = 1 or address.city = :profileCity')
            ->setParameter('profileCity', $profile->getCity());
    }

    private function categories(QueryBuilder $queryBuilder, Profile $profile): QueryBuilder
    {
        return $queryBuilder
            ->innerJoin('t.doctrineCategories', 'taskCategory')
            ->andWhere('taskCategory in (:profileCategories)')
            ->setParameter('profileCategories', array_map(
                fn(Category $category) => $category->getId(),
                $profile->getCategories()
            ));
    }

    private function getExcluded(Profile $profile): array
    {
        $generatedTasks = $this->nextTaskRepository->get($profile);
        $swipedTasks = $this->taskSwipeRepository->findByProfile($profile);
        $swipedTasks = array_map(
            fn(TaskSwipe $swipe) => $swipe->getTask(),
            $swipedTasks
        );
        $profileTasks = $this->queryBus->query(new FindTaskByAuthor($profile));
        $pending = $this->pendingTaskRepository->get($profile);

        $exclude = [
            ...$generatedTasks,
            ...$swipedTasks,
            ...$profileTasks,
            ...$pending,
        ];

        return array_map(
            fn(Task $task) => $task->getId(),
            $exclude
        );
    }
}
