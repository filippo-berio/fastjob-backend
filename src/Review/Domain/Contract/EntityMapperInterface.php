<?php

namespace App\Review\Domain\Contract;

interface EntityMapperInterface
{
    public function persistenceEntity(string $domainEntity): string;
}
