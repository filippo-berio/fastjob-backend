<?php

namespace App\Core\Domain\Entity;

class User
{
    public function __construct(
        protected int $id,
        protected string $phone,
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }
}
