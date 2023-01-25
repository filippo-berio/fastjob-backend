<?php

namespace App\Core\Domain\DTO\Profile;

use App\Core\Domain\Entity\Profile;
use App\Core\Domain\Entity\User;
use App\Validation\Constraints\Name;
use App\Validation\Constraints\OldDate;
use App\Validation\Constraints\YearsPassed;
use DateTimeImmutable;

readonly class CreateProfileDTO
{
    public User $user;

    #[Name]
    public string $firstName;

    #[OldDate]
    #[YearsPassed(Profile::MINIMAL_AGE)]
    public DateTimeImmutable $birthDate;

    public function __construct(
        User              $user,
        string            $firstName,
        DateTimeImmutable $birthDate
    ) {
        $this->birthDate = $birthDate;
        $this->user = $user;
        $this->firstName = $firstName;
    }
}
