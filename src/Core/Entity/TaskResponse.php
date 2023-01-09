<?php

namespace App\Core\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\UniqueConstraint;

#[Entity]
#[UniqueConstraint(columns: [
    'task_id',
    'responder_id'
])]
class TaskResponse
{
    #[Id]
    #[GeneratedValue]
    #[Column]
    private ?int $id = null;

    #[ManyToOne]
    private Task $task;

    #[ManyToOne]
    private Profile $responder;

    #[Column(options: ['default' => 0])]
    private bool $accepted;

    #[OneToOne(mappedBy: 'taskResponse', cascade: ['persist', 'remove'])]
    private ?TaskResponseData $data;

    public function __construct(
        Task    $task,
        Profile $responder,
        bool    $accepted,
        ?int $customPrice = null,
    ) {
        $this->task = $task;
        $this->responder = $responder;
        $this->accepted = $accepted;
        if ($accepted && $customPrice) {
            $this->data = new TaskResponseData($this, $customPrice);
        }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getResponder(): Profile
    {
        return $this->responder;
    }

    public function getTask(): Task
    {
        return $this->task;
    }

    public function getData(): ?TaskResponseData
    {
        return $this->data;
    }
}
