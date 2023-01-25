<?php

namespace App\Core\Infrastructure\Entity;

use App\Auth\Entity\User;
use App\Core\Domain\Entity\Profile as DomainProfile;
use App\Location\Entity\City;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\PostLoad;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

#[Entity]
#[HasLifecycleCallbacks]
class Profile extends DomainProfile implements UserInterface
{

    #[Id]
    #[GeneratedValue]
    #[Column]
    protected ?int $id = null;

    #[OneToOne]
    #[JoinColumn]
    protected User $user;

    #[Column]
    #[Groups(['profile_full'])]
    protected string $firstName;

    #[Column]
    #[Groups(['profile_full'])]
    protected DateTimeImmutable $birthDate;

    #[Column(nullable: true)]
    #[Groups(['profile_full'])]
    protected ?string $lastName = null;

    #[Column(nullable: true)]
    #[Groups(['profile_full'])]
    protected ?string $description = null;

    #[Column(nullable: true)]
    #[Groups(['profile_full'])]
    protected ?string $photoPath = null;

    #[ManyToMany(targetEntity: Category::class)]
    #[Groups(['profile_full'])]
    /** @var Collection<Category> $categories */
    protected Collection $doctrineCategories;

    #[ManyToOne]
    #[Groups(['profile_full'])]
    protected ?City $city = null;

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

    public function getRoles(): array
    {
        return $this->user->getRoles();
    }

    public function eraseCredentials()
    {
        $this->user->eraseCredentials();
    }

    public function getUserIdentifier(): string
    {
        return $this->user->getUserIdentifier();
    }
}
