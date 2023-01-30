<?php

namespace App\Review\Domain\Repository;

use App\Review\Domain\Entity\Profile;
use App\Review\Domain\Entity\ReviewAvailability;

interface ReviewAvailabilityRepositoryInterface
{
    public function save(ReviewAvailability $reviewAvailability): ReviewAvailability;

    public function find(Profile $author, Profile $target): ?ReviewAvailability;

    public function delete(ReviewAvailability $reviewAvailability);
}
