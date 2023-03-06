<?php

namespace App\Tests\Acceptance\Core\UseCase\Profile;

use App\Core\Application\UseCase\Profile\Photo\MakeProfilePhotoMainUseCase;
use App\DataFixtures\Core\ProfileFixtures;
use App\DataFixtures\Core\ProfilePhotoFixtures;
use App\Tests\Acceptance\AcceptanceTest;

class MakeMainProfilePhotoTest extends AcceptanceTest
{
    public function testChangeMainPhoto()
    {
        $profile = $this->getCoreProfile(ProfileFixtures::PROFILE_5);
        $useCase = $this->getDependency(MakeProfilePhotoMainUseCase::class);
        $photos = $useCase->set($profile, ProfilePhotoFixtures::PROFILE_5_PHOTO_1);
        foreach ($photos as $photo) {
            $this->assertEquals($photo->getId() === ProfilePhotoFixtures::PROFILE_5_PHOTO_1, $photo->isMain());
        }
    }
}
