<?php

namespace App\Core\Domain\Repository;

use App\Core\Domain\Entity\ProfilePhoto;

interface ProfilePhotoRepositoryInterface
{
    public function save(ProfilePhoto $photo): ProfilePhoto;
}
