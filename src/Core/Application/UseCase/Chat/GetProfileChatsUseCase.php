<?php

namespace App\Core\Application\UseCase\Chat;

use App\Chat\DTO\UserChat;
use App\Chat\Entity\DirectChat;
use App\Chat\Service\Direct\GetPersonChatsService;
use App\Core\Domain\Entity\Profile;

class GetProfileChatsUseCase
{
    public function __construct(
        private GetPersonChatsService $personChatsService,
    ) {
    }

    /**
     * @param Profile $profile
     * @return UserChat[]
     */
    public function get(Profile $profile): array
    {
        return $this->personChatsService->get($profile);
    }
}
