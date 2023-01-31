<?php

namespace App\Review\Infrastructure\Gateway;

use App\Core\Application\UseCase\Profile\GetProfileByIdUseCase;
use App\Core\Domain\Entity\Profile as ExternalProfile;

class ProfileGateway
{
    public function __construct(
        private GetProfileByIdUseCase $getProfileByIdUseCase,
    ) {
    }

    public function getProfile(int $id): ?ExternalProfile
    {
        return $this->getProfileByIdUseCase->get($id);
    }
}
