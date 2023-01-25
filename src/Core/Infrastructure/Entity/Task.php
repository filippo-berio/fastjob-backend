<?php

namespace App\Core\Infrastructure\Entity;

use App\Core\Domain\Entity\Category as DomainCategory;
use App\Core\Domain\Entity\Profile as DomainProfile;
use App\Core\Domain\Entity\Task as DomainTask;
use App\Location\Entity\Address;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\PostLoad;
use Symfony\Component\Serializer\Annotation\Groups;

#[Entity]
#[HasLifecycleCallbacks]
class Task extends DomainTask
{

    #[Id]
    #[GeneratedValue]
    #[Column]
    #[Groups(['task_full'])]
    protected ?int $id = null;

    #[Column]
    #[Groups(['task_full'])]
    protected string $title;

    #[Column]
    protected string $status = self::STATUS_WAIT;

    #[ManyToOne(targetEntity: Profile::class)]
    #[Groups(['task_full'])]
    protected DomainProfile $author;

    #[Column]
    #[Groups(['task_full'])]
    protected DateTimeImmutable $createdAt;

    #[ManyToMany(targetEntity: Category::class)]
    #[Groups(['task_full'])]
    /** @var Collection<DomainCategory> $categories */
    protected Collection $doctrineCategories;

    #[Column(nullable: true)]
    #[Groups(['task_full'])]
    protected ?int $price;

    #[ManyToOne]
    #[Groups(['task_full'])]
    protected ?Address $address;

    #[Column(type: 'datetime', nullable: true)]
    #[Groups(['task_full'])]
    protected ?DateTimeInterface $deadline;

    #[Column(nullable: true)]
    #[Groups(['task_full'])]
    protected ?string $description;

    #[Column(type: 'smallint')]
    #[Groups(['task_full'])]
    protected bool $remote;

    #[PostLoad]
    public function initCategories()
    {
        $this->categories = $this->doctrineCategories->toArray();
    }

    protected function setCategories(array $categories)
    {
        $this->doctrineCategories = new ArrayCollection($categories);
        return parent::setCategories($categories);
    }
}
