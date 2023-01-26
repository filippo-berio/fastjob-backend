<?php

namespace App\Core\Infrastructure\Entity;

use App\Core\Domain\DTO\Profile\UpdateProfileDTO;
use App\Core\Domain\Entity\Profile as DomainProfile;
use App\Core\Domain\Entity\User;
use App\Core\Domain\Entity\User as DomainUser;
use App\Location\Entity\City;
use DateTimeImmutable;
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

    #[Column]
    protected int $userId;

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
    public function init()
    {
        $this->categories = $this->doctrineCategories->toArray();
    }

    public function __construct(DomainUser $user, string $firstName, DateTimeImmutable $birthDate)
    {
        parent::__construct($user, $firstName, $birthDate);
        $this->userId = $user->getId();
    }

    public function setUser(User $user)
    {
        $this->user = $user;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function update(UpdateProfileDTO $updateProfileDTO)
    {
        parent::update($updateProfileDTO);
        $this->setCategories($updateProfileDTO->categories);
    }

    private function setCategories(array $categories)
    {
        $this->doctrineCategories = new ArrayCollection($categories);
    }

    public function getRoles(): array
    {
        return [];
    }

    public function eraseCredentials()
    {
    }

    public function getUserIdentifier(): string
    {
        return $this->userId;
    }
}
