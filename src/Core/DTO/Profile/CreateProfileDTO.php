<?php

namespace App\Core\DTO\Profile;

use App\Core\Entity\Profile;
use App\Core\Entity\User;
use App\Validation\Constraints\Name;
use App\Validation\Constraints\OldDate;
use App\Validation\Constraints\YearsPassed;
use DateTimeImmutable;

class CreateProfileDTO
{
    public function __construct(
        public User $user,
        #[Name]
        public string $firstName,
        #[OldDate]
        #[YearsPassed(Profile::MINIMAL_AGE)]
        public DateTimeImmutable $birthDate
    ) {
    }
}
