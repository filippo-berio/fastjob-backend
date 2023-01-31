<?php

namespace App\Review\Infrastructure\Repository;

use App\Review\Domain\Entity\Profile;
use App\Review\Domain\Repository\ProfileRepositoryInterface;
use App\Review\Infrastructure\Gateway\ProfileGateway;

class ProfileRepository implements ProfileRepositoryInterface
{
    public function __construct(
        private ProfileGateway $profileGateway,
    ) {
    }

    public function find(int $id): ?Profile
    {
        $coreProfile = $this->profileGateway->getProfile($id);
        return $coreProfile ? new Profile($coreProfile->getId()) : null;
    }
}
