<?php

namespace App\Review\Domain\Exception\Review;

use App\Review\Domain\Exception\ReviewException;

class ReviewUnavailableException extends ReviewException
{
    public function __construct()
    {
        parent::__construct('Отзыв недоступен', 403);
    }
}
