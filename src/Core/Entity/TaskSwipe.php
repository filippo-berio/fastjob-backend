<?php

namespace App\Core\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\UniqueConstraint;
use Symfony\Component\Serializer\Annotation\Groups;

#[Entity]
#[UniqueConstraint(columns: [
    'task_id',
    'profile_id'
])]
class TaskSwipe extends Swipe
{
    #[Id]
    #[GeneratedValue]
    #[Column]
    #[Groups(['task_swipe_short'])]
    private ?int $id = null;

    #[ManyToOne]
    protected Task $task;

    #[ManyToOne]
    protected Profile $profile;

    #[Column(nullable: true)]
    private ?int $customPrice = null;

    #[Column]
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
