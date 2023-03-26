<?php

namespace App\Core\Domain\Entity;

use App\Core\Domain\Entity\Trait\EventDispatcherEntityTrait;
use App\Core\Domain\Event\Task\Offer\TaskOfferEvent;
use App\Location\Entity\Address;
use DateTimeImmutable;
use DateTimeInterface;

class Task
{
    use EventDispatcherEntityTrait;

    const STATUS_REVIEW = 'review';
    const STATUS_WAIT = 'wait';
    const STATUS_OFFERED = 'offered';
    const STATUS_WORK = 'work';
    const STATUS_REJECTED = 'rejected';
    const STATUS_CANCELED = 'canceled';
    const STATUS_FINISHED = 'finished';
    const STATUS_DELETED = 'deleted';

    const STATUSES = [
        self::STATUS_REVIEW,
        self::STATUS_WAIT,
        self::STATUS_OFFERED,
        self::STATUS_WORK,
        self::STATUS_REJECTED,
        self::STATUS_CANCELED,
        self::STATUS_FINISHED,
        self::STATUS_DELETED,
    ];

    const EXECUTOR_STATUSES = [
        self::STATUS_OFFERED,
        self::STATUS_WORK,
        self::STATUS_FINISHED,
    ];

    const STATUSES_NO_SWIPES = [
        self::STATUS_WORK,
        self::STATUS_REJECTED,
        self::STATUS_FINISHED,
        self::STATUS_DELETED,
        self::STATUS_REVIEW,
    ];

    protected ?int $id = null;
    protected string $title;
    protected string $status;
    protected Profile $author;
    protected DateTimeInterface $createdAt;
    protected ?int $price;
    protected ?Address $address;
    protected ?DateTimeInterface $deadline;
    protected ?string $description;
    protected bool $remote;
    /** @var Category[] */
    protected array $categories;
    protected ?Profile $executor;
    /** @var SwipeMatch[] */
    protected array $matches;
    /** @var TaskOffer[] */
    protected array $offers;
    protected array $photos;

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
        $this->status = self::STATUS_REVIEW;
        $this->setCategories($categories);
        $this->price = $price;
        $this->address = $address;
        $this->deadline = $deadline;
        $this->description = $description;
        $this->remote = $remote;

        $this->matches = [];
        $this->offers = [];
        $this->photos = [];
    }

    public function __toString(): string
    {
        return $this->title;
    }

    public function addPhoto(string $path)
    {
        $this->photos[] = $path;
    }

    public function getPhotos(): array
    {
        return $this->photos;
    }

    public function getOffers(): array
    {
        return $this->offers;
    }

    public function getExecutor(): ?Profile
    {
        return $this->executor;
    }

    public function getMatches(): array
    {
        return $this->matches;
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

    public function getCreatedAt(): DateTimeInterface
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

    public function approve()
    {
        $this->status = self::STATUS_WAIT;
        return $this;
    }

    public function reject()
    {
        $this->status = self::STATUS_REJECTED;
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
