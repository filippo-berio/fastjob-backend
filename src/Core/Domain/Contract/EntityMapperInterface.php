<?php

namespace App\Core\Domain\Contract;

interface EntityMapperInterface
{
    public function persistenceEntity(string $domainEntity): string;
}
