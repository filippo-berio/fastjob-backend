<?php

namespace App\Core\Exception\TaskSwipe;

use App\Core\Exception\BaseException;

class CantSwipeOwnTask extends BaseException
{
    public function __construct()
    {
        parent::__construct('Вы не можете свайпнуть собственную задачу', 403);
    }
}
