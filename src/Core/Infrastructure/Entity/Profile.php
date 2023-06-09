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
use Doctrine\ORM\Mapping\Index;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\PostLoad;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;

#[Entity]
#[HasLifecycleCallbacks]
#[Index(['user_id'])]
class Profile extends DomainProfile implements UserInterface
{
    #[Id]
    #[GeneratedValue]
    #[Column]
    #[Groups(['profile_full', 'profile_short', 'chat_person'])]
    protected int $id;

    #[Column]
    protected int $userId;

    #[Column]
    #[Groups(['profile_full', 'profile_short'])]
    protected string $firstName;

    #[Column]
    #[Groups(['profile_full', 'profile_short'])]
    protected DateTimeImmutable $birthDate;

    #[Column(nullable: true)]
    #[Groups(['profile_full', 'profile_short'])]
    protected ?string $lastName = null;

    #[Column(nullable: true)]
    #[Groups(['profile_full', 'profile_short'])]
    protected ?string $description = null;

    #[Groups(['profile_full', 'profile_short'])]
    protected array $categories;

    #[ManyToMany(targetEntity: Category::class)]
    /** @var Collection<Category> $categories */
    protected Collection $doctrineCategories;

    #[ManyToOne]
    #[Groups(['profile_full', 'profile_short'])]
    protected ?City $city = null;

    #[Groups(['profile_full', 'profile_short'])]
    protected array $photos;

    /** @var Collection<ProfilePhoto> */
    #[OneToMany(mappedBy: 'profile', targetEntity: ProfilePhoto::class)]
    protected Collection $doctrinePhotos;

    #[Groups(['profile_full', 'profile_short'])]
    #[MaxDepth(2)]
    protected array $reviews;

    #[PostLoad]
    public function init()
    {
        $this->categories = $this->doctrineCategories->toArray();
        $this->photos = $this->doctrinePhotos->toArray();
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

    #[Groups('chat_person')]
    public function getName(): string
    {
        return parent::getName();
    }

    #[Groups('chat_person')]
    public function getPhotoPath(): ?string
    {
        return $this->getMainPhoto()?->getPath();
    }

    public function setReviews(array $reviews): void
    {
        $this->reviews = $reviews;
    }
}
