<?php

namespace App\Core\Domain\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToOne;
use Exception;
use Symfony\Component\Serializer\Annotation\Groups;

#[Entity]
class Category
{
    #[Id]
    #[GeneratedValue]
    #[Column]
    #[Groups(['category_full'])]
    private ?int $id = null;

    #[Column]
    #[Groups(['category_full'])]
    private string $title;


    #[Column(type: 'smallint')]
    #[Groups(['category_full'])]
    private bool $remote;

    #[ManyToOne]
    private ?Category $parent;

    public function __construct(
        string $title,
        ?bool $remote = null,
        ?Category $parent = null
    ) {
        if (!$parent && !isset($remote)) {
            throw new Exception('У категории должен быть либо родитель, либо флаг remote');
        }
        $this->remote = $remote ?? $parent->isRemote();
        $this->title = $title;
        $this->parent = $parent;

    }

    public function isRemote(): bool
    {
        return $this->remote;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getParent(): ?Category
    {
        return $this->parent;
    }
}
