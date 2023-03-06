<?php

namespace App\Core\Domain\Entity;

use JsonSerializable;

class ProfilePhoto implements JsonSerializable
{
    protected ?int $id = null;
    protected Profile $profile;
    protected string $path;
    protected bool $main;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isMain(): bool
    {
        return $this->main;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getProfile(): Profile
    {
        return $this->profile;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'path' => $this->path,
            'main' => $this->main,
        ];
    }
}
