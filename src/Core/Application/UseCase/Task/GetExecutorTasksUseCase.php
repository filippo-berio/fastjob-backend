<?php

namespace App\Core\Application\UseCase\Task;

use App\Core\Domain\DTO\Task\ExecutorTaskList;
use App\Core\Domain\Entity\Profile;
use App\Core\Domain\Entity\SwipeMatch;
use App\Core\Domain\Entity\Task;
use App\Core\Domain\Entity\TaskOffer;
use App\Core\Domain\Entity\TaskSwipe;
use App\Core\Domain\Query\Task\FindTaskByExecutor;
use App\Core\Domain\Repository\SwipeMatchRepositoryInterface;
use App\Core\Domain\Repository\TaskOfferRepositoryInterface;
use App\Core\Domain\Repository\TaskSwipeRepositoryInterface;
use App\Core\Infrastructure\Query\Task\FindTaskByExecutorHandler;
use App\Lib\CQRS\Bus\QueryBusInterface;

class GetExecutorTasksUseCase
{
    public function __construct(
        private TaskOfferRepositoryInterface $taskOfferRepository,
        private SwipeMatchRepositoryInterface $swipeMatchRepository,
        private TaskSwipeRepositoryInterface $taskSwipeRepository,
        private QueryBusInterface $queryBus,
    ) {
    }

    public function get(Profile $profile): ExecutorTaskList
    {
        $work = $this->getWorkTasks($profile);
        $offers = $this->getOfferTasks($profile);
        $matches = $this->getMatchTasks($profile);
        $swipes = $this->getSwipeTasks($profile);

        /** @var Task[][] $priority */
        $priority = [
            &$work,
            &$offers,
            &$matches,
            &$swipes,
        ];

        foreach ($priority as $i => $tasks) {
            $dependentSets = array_slice($priority, $i + 1);
            foreach ($tasks as $id => $task) {
                foreach ($dependentSets as &$dependentSet) {
                    unset($dependentSet[$id]);
                }
            }
        }

        return new ExecutorTaskList(
            array_values($priority[0]),
            array_values($priority[1]),
            array_values($priority[2]),
            array_values($priority[3]),
        );
    }

    /**
     * @param Profile $profile
     * @return Task[]
     */
    private function getWorkTasks(Profile $profile): array
    {
        $work = $this->queryBus->query(new FindTaskByExecutor($profile));
        return $this->combineTaskIds($work);
    }

    /**
     * @param Profile $profile
     * @return Task[]
     */
    private function getOfferTasks(Profile $profile): array
    {
        $taskOffers = $this->taskOfferRepository->findWaitOffersForExecutor($profile);
        return $this->combineTaskIds(array_map(
            fn(TaskOffer $offer) => $offer->getTask(),
            $taskOffers
        ));
    }

    /**
     * @param Profile $profile
     * @return Task[]
     */
    private function getMatchTasks(Profile $profile): array
    {
        $matches = $this->swipeMatchRepository->findForExecutor($profile);
        return $this->combineTaskIds(array_map(
            fn(SwipeMatch $match) => $match->getTask(),
            $matches
        ));
    }

    /**
     * @param Profile $profile
     * @return Task[]
     */
    private function getSwipeTasks(Profile $profile): array
    {
        $swipes = $this->taskSwipeRepository->findByProfile($profile);
        return $this->combineTaskIds(array_map(
            fn(TaskSwipe $swipe) => $swipe->getTask(),
            $swipes
        ));
    }

    /**
     * @param Task[] $tasks
     * @return Task[]
     */
    private function combineTaskIds(array $tasks): array
    {
        return array_combine(
            array_map(
                fn(Task $task) => $task->getId(),
                $tasks
            ),
            $tasks
        );
    }
}
