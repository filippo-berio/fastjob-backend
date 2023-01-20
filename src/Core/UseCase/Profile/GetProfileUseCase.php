<?php

namespace App\Core\UseCase\Profile;

use App\Core\Entity\Profile;
use App\Core\Entity\User;
use App\Core\Exception\Profile\ProfileNotFoundException;

class GetProfileUseCase
{
    public function get(User $user): Profile
    {
        if (!$user->getProfile()) {
            throw new ProfileNotFoundException();
        }
        return $user->getProfile();
    }
}
