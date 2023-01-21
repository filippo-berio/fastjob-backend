<?php

namespace App\Core\Entity;

use App\Auth\Entity\User;
use App\Core\DTO\Profile\UpdateProfileDTO;
use App\Location\Entity\City;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToOne;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

#[Entity]
class Profile implements UserInterface
{
    const MINIMAL_AGE = 16;

    #[Id]
    #[GeneratedValue]
    #[Column]
    private ?int $id = null;

    #[OneToOne]
    #[JoinColumn]
    private User $user;

    #[Column]
    #[Groups(['profile_full'])]
    private string $firstName;

    #[Column]
    #[Groups(['profile_full'])]
    private DateTimeImmutable $birthDate;

    #[Column(nullable: true)]
    #[Groups(['profile_full'])]
    private ?string $lastName = null;

    #[Column(nullable: true)]
    #[Groups(['profile_full'])]
    private ?string $description = null;

    #[Column(nullable: true)]
    #[Groups(['profile_full'])]
    private ?string $photoPath = null;

    #[ManyToMany(targetEntity: Category::class)]
    #[Groups(['profile_full'])]
    /** @var Collection<Category> $categories */
    private Collection $categories;

    #[ManyToOne]
    #[Groups(['profile_full'])]
    private ?City $city = null;

    public function __construct(
        User $user,
        string $firstName,
        DateTimeImmutable $birthDate,
    ) {
        $this->user = $user;
        $this->firstName = $firstName;
        $this->birthDate = $birthDate;
        $this->categories = new ArrayCollection();
    }

    #[Groups(['profile_full'])]
    public function getPhone(): string
    {
        return $this->user->getPhone();
    }

    /**
     * @return Category[]
     */
    public function getCategories(): array
    {
        return $this->categories->toArray();
    }

    public function update(UpdateProfileDTO $updateProfileDTO) {
        $this->firstName = $updateProfileDTO->firstName ?? $this->firstName;
        $this->setCategories($updateProfileDTO->categories);
        $this->lastName = $updateProfileDTO->lastName;
        $this->description = $updateProfileDTO->description;
        $this->city = $updateProfileDTO->city;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getBirthDate(): DateTimeImmutable
    {
        return $this->birthDate;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getPhotoPath(): ?string
    {
        return $this->photoPath;
    }

    public function getCity(): ?City
    {
        return $this->city;
    }

    /**
     * @param Category[] $categories
     */
    private function setCategories(array $categories)
    {
        $this->categories = new ArrayCollection($categories);
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
