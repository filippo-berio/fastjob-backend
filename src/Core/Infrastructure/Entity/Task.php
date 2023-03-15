<?php

namespace App\Core\Infrastructure\Entity;

use App\Core\Domain\Entity\Category as DomainCategory;
use App\Core\Domain\Entity\Profile as DomainProfile;
use App\Core\Domain\Entity\SwipeMatch;
use App\Core\Domain\Entity\Task as DomainTask;
use App\Location\Entity\Address;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Index;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\PostLoad;
use Symfony\Component\Serializer\Annotation\Context;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;

#[Entity]
#[HasLifecycleCallbacks]
#[Index(['status'])]
#[Index(['remote'])]
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
    #[Groups(['task_full'])]
    protected string $status;

    #[ManyToOne(targetEntity: Profile::class)]
    #[Groups(['task_full'])]
    protected DomainProfile $author;

    #[Column(type: 'datetime')]
    #[Context(normalizationContext: [DateTimeNormalizer::FORMAT_KEY => DateTime::ATOM])]
    #[Groups(['task_full'])]
    protected DateTimeInterface $createdAt;

    #[Groups(['task_full'])]
    protected array $categories;

    #[ManyToMany(targetEntity: Category::class)]
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

    #[Groups(['task_private'])]
    protected ?DomainProfile $executor = null;

    #[Groups(['task_private'])]
    #[MaxDepth(2)]
    protected array $matches;

    #[Groups(['task_private'])]
    #[MaxDepth(2)]
    protected array $offers;

    /** @var Collection<TaskOffer>  */
    #[OneToMany(mappedBy: 'task', targetEntity: TaskOffer::class)]
    protected Collection $doctrineOffers;

    #[Groups(['task_full'])]
    #[Column(type: 'array', nullable: true)]
    protected array $photos;

    #[PostLoad]
    public function postLoad()
    {
        $this->categories = $this->doctrineCategories->toArray();
        $this->offers = $this->doctrineOffers->toArray();
    }

    /**
     * @param SwipeMatch[] $matches
     * @return void
     */
    public function setMatches(array $matches): void
    {
        $this->matches = $matches;
    }

    protected function setCategories(array $categories)
    {
        $this->doctrineCategories = new ArrayCollection($categories);
        return parent::setCategories($categories);
    }

    public function getDoctrineCategories(): Collection
    {
        return $this->doctrineCategories;
    }

    public function setExecutor(?DomainProfile $executor): void
    {
        $this->executor = $executor;
    }

    public function addPhotoPrefix(string $prefix)
    {
        foreach ($this->photos as &$photo) {
            $photo = "$prefix$photo";
        }
    }

    public function removePhotoPrefix(string $prefix)
    {
        foreach ($this->photos as &$photo) {
            $photo = str_replace($prefix, '', $photo);
        }
    }
}
