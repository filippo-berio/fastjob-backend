<?php

namespace App\Location\Entity;

use App\Location\Entity\ValueObject\Coordinates;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Embedded;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToOne;
use JsonSerializable;

#[Entity]
class Address implements JsonSerializable
{
    #[Id]
    #[GeneratedValue]
    #[Column]
    private ?int $id = null;

    #[ManyToOne]
    private City $city;

    #[Column]
    private string $title;

    #[Embedded]
    private Coordinates $coordinates;

    public function __construct(
        City $city,
        string $title,
        Coordinates $coordinates
    ) {
        $this->city = $city;
        $this->title = $title;
        $this->coordinates = $coordinates;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getCity(): City
    {
        return $this->city;
    }

    public function getCoordinates(): Coordinates
    {
        return $this->coordinates;
    }

    public function jsonSerialize(): array
    {
        return [
            'title' => $this->getTitle(),
            'city' => $this->getCity(),
            'coordinates' => $this->getCoordinates(),
        ];
    }
}
