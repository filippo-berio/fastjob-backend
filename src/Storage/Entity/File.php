<?php

namespace App\Storage\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;

#[Entity]
class File
{
    #[Id]
    #[Column]
    #[GeneratedValue]
    private ?int $id = null;

    #[Column]
    private string $path;

    #[Column]
    private string $storage;

    public function __construct(
        string $path,
        string $storage,
    ) {
        $this->path = $path;
        $this->storage = $storage;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getStorage(): string
    {
        return $this->storage;
    }
}
