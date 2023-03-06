<?php

namespace App\Core\Domain\Contract;

use App\Core\Domain\Entity\Profile;
use App\Core\Domain\Entity\ProfilePhoto;

interface ProfilePhotoStorageInterface
{
    /** @return ProfilePhoto[] */
    public function getForProfile(Profile $profile): array;

    public function store(Profile $profile, string $file): ProfilePhoto;

    public function delete(ProfilePhoto $profilePhoto);
}
