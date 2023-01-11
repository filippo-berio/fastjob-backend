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

#[Entity]
class Task
{
    const STATUS_WAIT = 'wait';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_CLOSED = 'closed';
    const STATUS_DELETED = 'deleted';

    #[Id]
    #[GeneratedValue]
    #[Column]
    private ?int $id = null;

    #[Column]
    private string $title;

    #[Column]
    private string $status = self::STATUS_WAIT;

    #[ManyToOne]
    private Profile $employer;

    #[Column]
    private DateTimeImmutable $createdAt;

    #[ManyToMany(targetEntity: Category::class)]
    /** @var Collection<Category> $categories */
    private Collection $categories;

    #[Column(nullable: true)]
    private ?int $price;

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

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function transferProgress()
    {
        $this->status = self::STATUS_IN_PROGRESS;
    }

    public function close()
    {
        $this->status = self::STATUS_CLOSED;
    }

    public function delete()
    {
        $this->status = self::STATUS_DELETED;
    }

    public function restore()
    {
        if ($this->status === self::STATUS_DELETED) {
            $this->status = self::STATUS_WAIT;
        }
    }
}
