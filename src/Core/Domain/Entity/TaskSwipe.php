<?php

namespace App\Core\Domain\Entity;

class TaskSwipe extends Swipe
{
    protected ?int $id = null;
    protected Task $task;
    protected Profile $profile;
    protected ?int $customPrice = null;
    protected string $type;

    public function __construct(
        Task    $task,
        Profile $profile,
        string  $type,
        ?int    $customPrice = null,
    ) {
        parent::__construct($task, $profile, $type);
        if ($type !== Swipe::TYPE_REJECT) {
            $this->customPrice = $customPrice;
        }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCustomPrice(): ?int
    {
        return $this->customPrice;
    }
}
