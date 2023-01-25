<?php

namespace App\Core\Domain\Exception\TaskSwipe;

use App\Core\Domain\Exception\BaseException;

class CantSwipeOwnTask extends BaseException
{
    public function __construct()
    {
        parent::__construct('Вы не можете свайпнуть собственную задачу', 403);
    }
}
