<?php

namespace App\Review\Infrastructure\Repository;

use App\Core\Application\UseCase\Profile\GetProfileByIdUseCase;
use App\Review\Domain\Entity\Profile;
use App\Review\Domain\Repository\ProfileRepositoryInterface;

class ProfileRepository implements ProfileRepositoryInterface
{
    public function __construct(
        private GetProfileByIdUseCase $getProfileByIdUseCase,
    ) {
    }

    public function find(int $id): ?Profile
    {
        $coreProfile = $this->getProfileByIdUseCase->get($id);
        return $coreProfile ? new Profile($coreProfile->getId()) : null;
    }
}
