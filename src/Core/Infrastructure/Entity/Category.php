<?php

namespace App\Core\Infrastructure\Entity;

use App\Core\Domain\Entity\Category as DomainCategory;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToOne;
use Symfony\Component\Serializer\Annotation\Groups;

#[Entity]
class Category extends DomainCategory
{

    #[Id]
    #[GeneratedValue]
    #[Column]
    #[Groups(['category_full'])]
    protected ?int $id = null;

    #[Column]
    #[Groups(['category_full'])]
    protected string $title;

    #[Column(type: 'smallint')]
    #[Groups(['category_full'])]
    protected bool $remote;

    #[ManyToOne(targetEntity: Category::class)]
    protected ?DomainCategory $parent;
}
