<?php

namespace App\Review\Infrastructure\Gateway;

use App\Core\Application\UseCase\Profile\GetProfileByIdUseCase;
use App\Review\Domain\Entity\Profile;

class ProfileGateway
{
    public function __construct(
        private GetProfileByIdUseCase $getProfileByIdUseCase,
    ) {
    }

    public function getProfile(int $id): ?Profile
    {
        $coreProfile = $this->getProfileByIdUseCase->get($id);
        return $coreProfile ? new Profile($coreProfile->getId()) : null;
    }
}
