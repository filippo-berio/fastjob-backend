<?php

namespace App\Core\Entity;

use App\Location\Entity\City;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToOne;
use function Symfony\Component\Translation\t;

#[Entity]
class Profile
{
    const MINIMAL_AGE = 16;

    #[Id]
    #[GeneratedValue]
    #[Column]
    private ?int $id = null;

    #[OneToOne]
    private User $user;

    #[Column]
    private string $firstName;

    #[Column]
    private DateTimeImmutable $birthDate;

    #[Column(nullable: true)]
    private ?string $lastName;

    #[Column(nullable: true)]
    private ?string $description = null;

    #[Column(nullable: true)]
    private ?string $photo = null;

    #[ManyToMany(targetEntity: Category::class)]
    /** @var Collection<Category> $categories */
    private Collection $categories;

    #[ManyToOne]
    private ?City $city = null;

    public function __construct(
        User $user,
        string $firstName,
        DateTimeImmutable $birthDate,
    ) {
        $this->user = $user;
        $this->update($firstName, $birthDate);
        $this->categories = new ArrayCollection();
    }

    /**
     * @return Category[]
     */
    public function getCategories(): array
    {
        return $this->categories->toArray();
    }

    /**
     * @param Category[] $categories
     */
    public function setCategories(array $categories)
    {
        $this->categories = new ArrayCollection($categories);
    }

    public function update(
        ?string $firstName = null,
        ?DateTimeImmutable $birthDate = null,
        ?string $lastName = null,
    ) {
        $this->firstName = $firstName ?? $this->firstName;
        $this->lastName = $lastName;
        $this->birthDate = $birthDate ?? $this->birthDate;
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

    public function setBirthDate(DateTimeImmutable $birthDate): void
    {
        $this->birthDate = $birthDate;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function getCity(): ?City
    {
        return $this->city;
    }

    public function setCity(?City $city): void
    {
        $this->city = $city;
    }
}
