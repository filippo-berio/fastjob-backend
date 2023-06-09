<?php

namespace App\Core\Domain\Entity;

use App\Chat\Entity\PersonInterface as ChatPersonInterface;
use App\Core\Domain\DTO\Profile\UpdateProfileDTO;
use App\Location\Entity\City;
use DateTimeImmutable;

class Profile implements ChatPersonInterface
{
    const MINIMAL_AGE = 16;

    protected int $id;
    protected User $user;
    protected string $firstName;
    protected DateTimeImmutable $birthDate;
    protected ?string $lastName = null;
    protected ?string $description = null;
    protected array $categories = [];
    protected ?City $city = null;
    /** @var ProfilePhoto[] */
    protected array $photos;
    /** @var Review[] */
    protected array $reviews;

    public function __construct(
        User $user,
        string $firstName,
        DateTimeImmutable $birthDate,
    ) {
        $this->user = $user;
        $this->firstName = $firstName;
        $this->birthDate = $birthDate;
        $this->photos = [];
        $this->reviews = [];
    }

    public function getPhotos(): array
    {
        return $this->photos;
    }

    /**
     * @return Category[]
     */
    public function getCategories(): array
    {
        return $this->categories;
    }

    public function update(UpdateProfileDTO $updateProfileDTO) {
        $this->firstName = $updateProfileDTO->firstName ?? $this->firstName;
        $this->categories = $updateProfileDTO->categories;
        $this->lastName = $updateProfileDTO->lastName;
        $this->description = $updateProfileDTO->description;
        $this->city = $updateProfileDTO->city;
    }

    public function getId(): int
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

    public function getCity(): ?City
    {
        return $this->city;
    }

    public function getMainPhoto(): ?ProfilePhoto
    {
        foreach ($this->photos as $photo) {
            if ($photo->isMain()) {
                return $photo;
            }
        }
        return null;
    }

    public function getName(): string
    {
        return $this->getFirstName();
    }

    public function getPhotoPath(): ?string
    {
        return $this->getMainPhoto()?->getPath();
    }

    public function getReviews(): array
    {
        return $this->reviews;
    }
}
