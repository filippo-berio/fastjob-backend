<?php

namespace App\Core\Infrastructure\Repository;

use App\Core\Domain\Entity\Profile;
use App\Core\Domain\Entity\ProfilePhoto as DomainProfilePhoto;
use App\Core\Domain\Repository\ProfilePhotoRepositoryInterface;
use App\Core\Infrastructure\Entity\ProfilePhoto;
use Doctrine\ORM\EntityManagerInterface;

class ProfilePhotoRepository implements ProfilePhotoRepositoryInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function findForProfile(Profile $profile): array
    {
        return $this->entityManager->getRepository(ProfilePhoto::class)
            ->findBy([
                'profile' => $profile,
            ]);
    }

    public function save(DomainProfilePhoto $photo): DomainProfilePhoto
    {
        $this->entityManager->persist($photo);
        $this->entityManager->flush();
        return $photo;
    }
}
