<?php

namespace App\Core\UseCase\Profile;

use App\Core\DTO\Profile\CreateProfileDTO;
use App\Core\Entity\Profile;
use App\Core\Entity\User;
use App\Core\Service\Profile\CreateProfileService;
use DateTimeImmutable;

class CreateProfileUseCase
{
    public function __construct(
        private CreateProfileService $createProfileService,
    ) {
    }

    public function create(User $user, string $firstName, string $birthDate): Profile
    {
        return $this->createProfileService->create(
            new CreateProfileDTO(
                $user,
                $firstName,
                new DateTimeImmutable($birthDate)
            )
        );
    }
}
