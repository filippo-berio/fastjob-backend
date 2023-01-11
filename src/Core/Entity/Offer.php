<?php

namespace App\Core\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToOne;

#[Entity]
class Offer
{
    const STATUS_OFFERED = 'offered';
    const STATUS_ACCEPTED = 'accepted';
    const STATUS_REJECTED = 'rejected';

    #[Id]
    #[GeneratedValue]
    #[Column]
    private ?int $id = null;

    #[ManyToOne]
    private Profile $profile;

    #[ManyToOne]
    private Task $task;

    #[Column]
    private string $status = self::STATUS_OFFERED;

    public function __construct(
        Profile $profile,
        Task $task,
    ) {
        $this->profile = $profile;
        $this->task = $task;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getProfile(): Profile
    {
        return $this->profile;
    }

    public function getTask(): Task
    {
        return $this->task;
    }

    public function accept()
    {
        $this->status = self::STATUS_ACCEPTED;
    }

    public function reject()
    {
        $this->status = self::STATUS_REJECTED;
    }
}
