<?php

namespace App\Core\Query\Task\FindNextTasksForProfile;

use App\Core\Entity\Category;
use App\Core\Entity\Profile;
use App\Core\Entity\Task;
use App\Core\Entity\TaskSwipe;
use App\Core\Query\Task\FindByProfile\FindTaskByAuthor;
use App\Core\Repository\PendingTaskRepository;
use App\Core\Repository\ProfileNextTaskRepository;
use App\Core\Repository\TaskSwipeRepository;
use App\CQRS\Bus\QueryBusInterface;
use App\CQRS\QueryHandlerInterface;
use App\CQRS\QueryInterface;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;

class FindNextTasksForProfileHandler implements QueryHandlerInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private QueryBusInterface $queryBus,
        private ProfileNextTaskRepository $nextTaskRepository,
        private PendingTaskRepository $pendingTaskRepository,
        private TaskSwipeRepository $taskSwipeRepository,
    ) {
    }

    /**
     * @param FindNextTasksForProfile $query
     * @return Task[]
     */
    public function handle(QueryInterface $query): array
    {
        $queryBuilder = $this->entityManager->getRepository(Task::class)->createQueryBuilder('t');
        $queryBuilder->andWhere('t.status = :waitStatus');
        $queryBuilder->andWhere('t.deadline is null or t.deadline > :now');
        $this->categories($queryBuilder);
        $this->city($queryBuilder);
        $queryBuilder->setMaxResults($query->count);

        if ($exclude = $this->getExcluded($query->profile)) {
            $queryBuilder->andWhere('t.id not in (:excludeIds)');
        }

        return $queryBuilder
            ->setParameters($this->getParameters($query->profile, $exclude))
            ->getQuery()
            ->getResult();
    }

    private function city(QueryBuilder $queryBuilder): QueryBuilder
    {
        return $queryBuilder
            ->leftJoin('t.address', 'address')
            ->andWhere('t.remote = 1 or address.city = :profileCity');
    }

    private function categories(QueryBuilder $queryBuilder): QueryBuilder
    {
        return $queryBuilder
            ->innerJoin('t.categories', 'taskCategory')
            ->andWhere('taskCategory in (:profileCategories)');
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

        $exclude = [...$generatedTasks, ...$swipedTasks, ...$profileTasks];

        if ($pending = $this->pendingTaskRepository->get($profile)) {
            $exclude[] = $pending;
        }

        return array_map(
            fn(Task $task) => $task->getId(),
            $exclude
        );
    }

    /**
     * @param Profile $profile
     * @param Task[] $excluded
     * @return array
     */
    private function getParameters(Profile $profile, array $excluded): array
    {
        $params = [
            'waitStatus' => Task::STATUS_WAIT,
            'profileCategories' => array_map(
                fn(Category $category) => $category->getId(),
                $profile->getCategories()
            ),
            'now' => new DateTimeImmutable(),
            'profileCity' => $profile->getCity(),
        ];
        if (!empty($excluded)) {
            $params['excludeIds'] = $excluded;
        }
        return $params;
    }
}
