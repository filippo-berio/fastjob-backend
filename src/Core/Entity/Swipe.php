<?php

namespace App\Core\Entity;

use Exception;

abstract class Swipe
{
    const TYPE_ACCEPT = 'accept';
    const TYPE_REJECT = 'reject';

    const TYPES = [
        self::TYPE_ACCEPT,
        self::TYPE_REJECT,
    ];

    protected Task $task;
    protected Profile $profile;
    protected string $type;

    public function __construct(
        Task $task,
        Profile $profile,
        string $type
    ) {
        $this->task = $task;
        $this->profile = $profile;
        if (!in_array($type, $this::TYPES)) {
            throw new Exception('Unexpected swipe type');
        }
        $this->type = $type;
    }

    public function getProfile(): Profile
    {
        return $this->profile;
    }

    public function getTask(): Task
    {
        return $this->task;
    }

    public function getType(): string
    {
        return $this->type;
    }
}
