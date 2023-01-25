<?php

namespace App\Core\Application\UseCase\Profile;

use App\Auth\Entity\User;
use App\Core\Domain\DTO\Profile\CreateProfileDTO;
use App\Core\Domain\Entity\Profile;
use App\Core\Domain\Service\Profile\CreateProfileService;
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
