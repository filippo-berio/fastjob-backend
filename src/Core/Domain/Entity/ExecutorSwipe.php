<?php

namespace App\Core\Domain\Entity;

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
class ExecutorSwipe extends Swipe
{
    #[Id]
    #[GeneratedValue]
    #[Column]
    #[Groups(['executor_swipe_short'])]
    private ?int $id = null;

    #[ManyToOne]
    protected Task $task;

    #[ManyToOne]
    protected Profile $profile;

    #[Column]
    protected string $type;

    public function getId(): ?int
    {
        return $this->id;
    }
}
