<?php

namespace App\Core\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\OneToOne;

#[Entity]
class TaskResponseData
{
    #[Id]
    #[OneToOne(inversedBy: 'id')]
    #[JoinColumn(name: 'id', referencedColumnName: 'id')]
    private TaskResponse $taskResponse;

    #[Column(nullable: true)]
    private ?int $customPrice;

    #[Column(nullable: true)]
    private ?string $comment;

    public function __construct(
        TaskResponse $taskResponse,
        ?int $customPrice = null,
        ?string $comment = null
    ) {
        $this->taskResponse = $taskResponse;
        $this->comment = $comment;
        $this->customPrice = $customPrice;
    }

    public function getCustomPrice(): ?int
    {
        return $this->customPrice;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }
}
