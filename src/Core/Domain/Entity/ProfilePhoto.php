<?php

namespace App\Core\Domain\Entity;

use App\Core\Domain\Entity\Trait\EventDispatcherEntityTrait;
use App\Core\Domain\Event\ProfilePhoto\SetMainPhoto\SetMainPhotoEvent;
use JsonSerializable;

class ProfilePhoto implements JsonSerializable
{
    use EventDispatcherEntityTrait;

    protected ?int $id = null;
    protected Profile $profile;
    protected string $path;
    protected bool $main;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setMain(bool $main): void
    {
        $this->main = $main;
        if ($main) {
            $this->dispatch(new SetMainPhotoEvent($this));
        }
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
