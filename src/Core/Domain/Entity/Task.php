<?php

namespace App\Core\Domain\Entity;

use App\Core\Domain\Entity\Trait\EventDispatcherEntityTrait;
use App\Core\Domain\Event\Task\Offer\TaskOfferEvent;
use App\Core\Domain\Exception\TaskOffer\TaskOfferExistsException;
use App\Location\Entity\Address;
use DateTimeImmutable;
use DateTimeInterface;

class Task
{
    use EventDispatcherEntityTrait;

    const STATUS_WAIT = 'wait';
    const STATUS_OFFERED = 'offered';
    const STATUS_WORK = 'work';
    const STATUS_CANCELED = 'canceled';
    const STATUS_FINISHED = 'finished';
    const STATUS_DELETED = 'deleted';

    protected ?int $id = null;
    protected string $title;
    protected string $status;
    protected Profile $author;
    protected DateTimeImmutable $createdAt;
    protected ?int $price;
    protected ?Address $address;
    protected ?DateTimeInterface $deadline;
    protected ?string $description;
    protected bool $remote;
    /** @var Category[] */
    protected array $categories;

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
        $this->resetStatus();
        $this->setCategories($categories);
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
        return $this->categories;
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

    public function isWait()
    {
        return $this->status === self::STATUS_WAIT;
    }

    public function isOffered(): bool
    {
        return $this->status === self::STATUS_OFFERED;
    }

    public function resetStatus()
    {
        $this->status = self::STATUS_WAIT;
    }

    public function offer(Profile $executor)
    {
        if ($this->isOffered()) {
            throw new TaskOfferExistsException();
        }
        $this->dispatch(new TaskOfferEvent($this, $executor));
        $this->status = self::STATUS_OFFERED;
    }

    public function cancel()
    {
        $this->status = self::STATUS_CANCELED;
    }

    public function delete()
    {
        $this->status = self::STATUS_DELETED;
    }

    public function finish()
    {
        $this->status = self::STATUS_FINISHED;
    }

    public function restore()
    {
        if ($this->status === self::STATUS_DELETED) {
            $this->status = self::STATUS_WAIT;
        }
    }

    public function acceptOffer()
    {
        $this->status = self::STATUS_WORK;
    }

    public function isInWork()
    {
        return $this->status === self::STATUS_WORK;
    }

    /**
     * @param Category[] $categories
     */
    protected function setCategories(array $categories)
    {
        $this->categories = $categories;
    }
}
