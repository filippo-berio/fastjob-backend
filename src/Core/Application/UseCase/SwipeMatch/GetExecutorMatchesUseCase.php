<?php

namespace App\Core\Application\UseCase\SwipeMatch;

use App\Core\Domain\Entity\Profile;
use App\Core\Domain\Entity\SwipeMatch;
use App\Core\Domain\Repository\SwipeMatchRepositoryInterface;

class GetExecutorMatchesUseCase
{
    public function __construct(
        protected SwipeMatchRepositoryInterface $matchRepository
    ) {
    }

    /**
     * @param Profile $profile
     * @return SwipeMatch[]
     */
    public function get(Profile $profile): array
    {
        return $this->matchRepository->findForExecutor($profile);
    }
}
