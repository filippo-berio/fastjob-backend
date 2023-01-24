<?php

namespace App\Core\Entity;

use App\Location\Entity\Address;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\ManyToOne;
use Symfony\Component\Serializer\Annotation\Groups;

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
    #[Groups(['task_full'])]
    private ?int $id = null;

    #[Column]
    #[Groups(['task_full'])]
    private string $title;

    #[Column]
    private string $status = self::STATUS_WAIT;

    #[ManyToOne]
    #[Groups(['task_full'])]
    private Profile $author;

    #[Column]
    #[Groups(['task_full'])]
    private DateTimeImmutable $createdAt;

    #[ManyToMany(targetEntity: Category::class)]
    #[Groups(['task_full'])]
    /** @var Collection<Category> $categories */
    private Collection $categories;

    #[Column(nullable: true)]
    #[Groups(['task_full'])]
    private ?int $price;

    #[ManyToOne]
    #[Groups(['task_full'])]
    private ?Address $address;

    #[Column(type: 'datetime', nullable: true)]
    #[Groups(['task_full'])]
    private ?DateTimeInterface $deadline;

    #[Column(nullable: true)]
    #[Groups(['task_full'])]
    private ?string $description;

    #[Column(type: 'smallint')]
    #[Groups(['task_full'])]
    private bool $remote;

    public function __construct(
        string             $title,
        Profile            $author,
        array              $categories,
        bool               $remote,
        ?int               $price = null,
        ?Address           $address = null,
        ?DateTimeInterface $deadline = null,
        ?string            $description = null,
    ) {
        $this->title = $title;
        $this->author = $author;
        $this->createdAt = new DateTimeImmutable();
        $this->categories = new ArrayCollection($categories);
        $this->price = $price;
        $this->address = $address;
        $this->deadline = $deadline;
        $this->description = $description;
        $this->remote = $remote;
    }

    public function isRemote(): bool
    {
        return $this->remote;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getDeadline(): ?DateTimeInterface
    {
        return $this->deadline;
    }

    public function getAddress(): ?Address
    {
        return $this->address;
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

    public function getAuthor(): Profile
    {
        return $this->author;
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
