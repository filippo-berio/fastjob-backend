<?php

namespace App\Core\Domain\Exception\Review;

use App\Core\Domain\Exception\BaseException;

class ReviewUnavailableException extends BaseException
{
    public function __construct()
    {
        parent::__construct('Отзыв недоступен', 403);
    }
}
