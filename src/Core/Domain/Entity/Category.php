<?php

namespace App\Core\Domain\Entity;

use Exception;

class Category
{
    protected ?int $id = null;
    protected string $title;
    protected bool $remote;
    protected ?Category $parent;
    /** @var Category[] */
    protected array $children;
    protected string $icon;

    public function __construct(
        string $title,
        ?bool $remote = null,
        ?Category $parent = null
    ) {
        if (!$parent && !isset($remote)) {
            throw new Exception('У категории должен быть либо родитель, либо флаг remote');
        }
        $this->remote = $remote ?? $parent->isRemote();
        $this->title  = $title;
        $this->parent = $parent;

    }

    public function getChildren(): array
    {
        return $this->children;
    }

    public function isRemote(): bool
    {
        return $this->remote;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIcon(): string
    {
        return $this->icon;
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
