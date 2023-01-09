<?php

namespace App\Location\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToOne;

#[Entity]
class City
{
    #[Id]
    #[GeneratedValue]
    #[Column]
    private ?int $id = null;

    #[Column]
    private string $title;

    #[ManyToOne]
    private Region $region;

    public function __construct(
        string $title,
        Region $region
    ) {
        $this->title = $title;
        $this->region = $region;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getRegion(): Region
    {
        return $this->region;
    }
}
