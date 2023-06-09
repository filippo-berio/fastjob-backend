<?php

namespace App\Core\Domain\Entity;

use DateTimeImmutable;

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

    public function getTask(): Task
    {
        return $this->task;
    }

    public function getProfile(): Profile
    {
        return $this->profile;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isAccepted(): bool
    {
        return $this->status === self::STATUS_ACCEPTED;
    }

    public function reject()
    {
        $this->status = self::STATUS_REJECTED;
    }

    public function accept()
    {
        $this->status = self::STATUS_ACCEPTED;
    }

    public function cancel()
    {
        $this->status = self::STATUS_CANCELED;
    }

    public function isCanceled()
    {
        return $this->status === self::STATUS_CANCELED;
    }
}
