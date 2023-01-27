<?php

namespace App\Core\Application\UseCase\SwipeMatch;

use App\Core\Application\DTO\TaskMatches;
use App\Core\Domain\Entity\Profile;
use App\Core\Domain\Entity\SwipeMatch;
use App\Core\Domain\Query\Task\FindTaskByAuthor;
use App\Core\Domain\Repository\SwipeMatchRepositoryInterface;
use App\CQRS\Bus\QueryBusInterface;

class GetAuthorMatchesUseCase
{
    public function __construct(
        protected SwipeMatchRepositoryInterface $matchRepository,
        protected QueryBusInterface $queryBus,
    ) {
    }

    /**
     * @param Profile $profile
     * @return TaskMatches[]
     */
    public function get(Profile $profile): array
    {
        $result = [];
        $tasks = $this->queryBus->query(new FindTaskByAuthor($profile));
        foreach ($tasks as $task) {
            $matches = $this->matchRepository->findForTask($task);
            $result[] = new TaskMatches($task, $matches);
        }
        return $result;
    }
}
