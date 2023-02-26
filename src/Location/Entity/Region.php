<?php

namespace App\Location\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use JsonSerializable;

#[Entity]
class Region implements JsonSerializable
{
    #[Id]
    #[GeneratedValue]
    #[Column]
    private ?int $id = null;

    #[Column]
    private string $title;

    public function __construct(string $title)
    {
        $this->title = $title;
    }

    public function jsonSerialize(): array
    {
        return [
            'id'  => $this->getId(),
            'title' => $this->getTitle(),
        ];
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }
}
