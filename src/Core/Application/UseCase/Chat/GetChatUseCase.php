<?php

namespace App\Core\Application\UseCase\Chat;

use App\Chat\Entity\DirectChat;
use App\Chat\Service\Direct\GetDirectChatService;
use App\Core\Domain\Entity\Profile;
use App\Core\Domain\Exception\Profile\ProfileNotFoundException;
use App\Core\Domain\Exception\SwipeMatch\SwipeMatchNotFoundException;
use App\Core\Domain\Query\Profile\FindProfileById;
use App\Core\Domain\Repository\SwipeMatchRepositoryInterface;
use App\Lib\CQRS\Bus\QueryBusInterface;

class GetChatUseCase
{
    public function __construct(
        private GetDirectChatService $getDirectChatService,
        private QueryBusInterface $queryBus,
        private SwipeMatchRepositoryInterface $swipeMatchRepository,
    ) {
    }

    public function get(Profile $profile, int $companionId): DirectChat
    {
        $companion = $this->queryBus->query(new FindProfileById($companionId));
        if (!$companion) {
            throw new ProfileNotFoundException();
        }

        if (!$this->swipeMatchRepository->countByCompanions($profile, $companion)) {
            throw new SwipeMatchNotFoundException();
        }

        return $this->getDirectChatService->getOrCreate($profile, $companion);
    }
}
