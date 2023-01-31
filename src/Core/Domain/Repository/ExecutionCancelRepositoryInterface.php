<?php

namespace App\Core\Domain\Repository;

use App\Core\Domain\Entity\ExecutionCancel;

interface ExecutionCancelRepositoryInterface
{
    public function save(ExecutionCancel $executionCancel): ExecutionCancel;
}
