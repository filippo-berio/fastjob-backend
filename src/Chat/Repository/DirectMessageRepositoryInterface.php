<?php

namespace App\Chat\Repository;

use App\Chat\Entity\DirectMessage;

interface DirectMessageRepositoryInterface
{
    public function save(DirectMessage $message): DirectMessage;
}
