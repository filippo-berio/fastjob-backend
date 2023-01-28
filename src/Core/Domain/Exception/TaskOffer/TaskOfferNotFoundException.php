<?php

namespace App\Core\Domain\Exception\TaskOffer;

use App\Core\Domain\Exception\BaseException;

class TaskOfferNotFoundException extends BaseException
{
    public function __construct()
    {
        parent::__construct('Не найден оффер', 404);
    }
}
