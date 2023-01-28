<?php

namespace App\Core\Domain\Exception\TaskOffer;

use App\Core\Domain\Exception\BaseException;

class TaskOfferExistsException extends BaseException
{
    public function __construct()
    {
        parent::__construct('Для задачи уже выбран исполнитель', 403);
    }
}
