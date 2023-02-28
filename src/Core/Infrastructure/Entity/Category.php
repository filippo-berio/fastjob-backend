<?php

namespace App\Core\Infrastructure\Entity;

use App\Core\Domain\Entity\Category as DomainCategory;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\PostLoad;
use Symfony\Component\Serializer\Annotation\Groups;

#[Entity]
#[HasLifecycleCallbacks]
class Category extends DomainCategory
{

    #[Id]
    #[GeneratedValue]
    #[Column]
    #[Groups(['category_full', 'category_short'])]
    protected ?int $id = null;

    #[Column]
    #[Groups(['category_full', 'category_short'])]
    protected string $title;

    #[Column(type: 'smallint')]
    #[Groups(['category_full'])]
    protected bool $remote;

    #[ManyToOne(targetEntity: Category::class)]
    protected ?DomainCategory $parent;

    #[Groups(['category_full'])]
    protected array $children;

    #[Groups(['category_full'])]
    protected string $icon;

    #[OneToMany(mappedBy: 'parent', targetEntity: Category::class)]
    /** @var Collection<Category> $doctrineChildren */
    protected Collection $doctrineChildren;

    #[PostLoad]
    public function postLoad()
    {
        $this->children = $this->doctrineChildren->toArray();
        $this->icon   = '<svg xmlns="http://www.w3.org/2000/svg" class="ionicon" viewBox="0 0 512 512"><title>American Football</title><ellipse cx="256" cy="256" rx="267.57" ry="173.44" transform="rotate(-45 256 256.002)" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32"/><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32" d="M334.04 177.96L177.96 334.04M278.3 278.3l-44.6-44.6M322.89 233.7l-44.59-44.59M456.68 211.4L300.6 55.32M211.4 456.68L55.32 300.6M233.7 322.89l-44.59-44.59"/></svg>';
    }
}
