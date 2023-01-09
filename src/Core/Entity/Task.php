<?php

namespace App\Core\Entity;

use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToOne;

#[Entity]
class Task
{
    #[Id]
    #[GeneratedValue]
    #[Column]
    private ?int $id = null;

    #[Column(length: 255)]
    private string $title;

    #[ManyToOne]
    private Profile $employer;

    private DateTimeImmutable $createdAt;

    #[ManyToMany(targetEntity: Category::class)]
    /** @var Collection<Category> $categories */
    private Collection $categories;

    #[Column(nullable: true)]
    private ?int $price;

    #[Column(options: ['default' => 0])]
    private bool $archived = false;

    public function __construct(
        string $title,
        Profile $employer,
        array $categories,
        ?int $price = null,
    ) {
        $this->title = $title;
        $this->employer = $employer;
        $this->createdAt = new DateTimeImmutable();
        $this->categories = new ArrayCollection($categories);
        $this->price = $price;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * @return Category[]
     */
    public function getCategories(): array
    {
        return $this->categories->toArray();
    }

    public function getEmployer(): Profile
    {
        return $this->employer;
    }

    public function restore()
    {
        $this->archived = false;
    }

    public function archive()
    {
        $this->archived = true;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }
}
