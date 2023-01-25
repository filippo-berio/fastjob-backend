<?php

namespace App\Core\Domain\Service;

use DateTimeInterface;

class AgeService
{
    public function calculateDiffYears(DateTimeInterface $date1, DateTimeInterface $date2): int
    {
        return $date2->diff($date1)->y;
    }
}
