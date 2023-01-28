<?php

namespace App\Core\Domain\Entity;

use DateTimeImmutable;
use DateTimeInterface;

class TaskOffer
{
    const STATUS_WAIT = 'wait';
    const STATUS_ACCEPTED = 'accepted';
    const STATUS_REJECTED = 'rejected';
    const STATUS_CANCELED = 'canceled';

    const STATUSES = [
        self::STATUS_WAIT,
        self::STATUS_ACCEPTED,
        self::STATUS_REJECTED,
    ];

    protected ?int $id = null;
    protected Task $task;
    protected Profile $profile;
    protected string $status;
    protected DateTimeImmutable $createdAt;

    public function __construct(
        Task    $task,
        Profile $profile,
    ) {
        $this->task = $task;
        $this->status = self::STATUS_WAIT;
        $this->profile = $profile;
        $this->createdAt = new DateTimeImmutable();
    }

    public function cancel()
    {
        $this->status = self::STATUS_CANCELED;
    }

    public function isAwaiting()
    {
        return $this->status === self::STATUS_WAIT;
    }
}
