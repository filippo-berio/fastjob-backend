<?php

namespace App\Tests\Acceptance\Core\UseCase\Profile;

use App\Core\Application\UseCase\Profile\Photo\UploadProfilePhotoUseCase;
use App\DataFixtures\Core\ProfileFixtures;
use App\DataFixtures\Core\ProfilePhotoFixtures;
use App\Storage\Service\StorageInterface;
use App\Tests\Acceptance\AcceptanceTest;

class UploadPhotoTest extends AcceptanceTest
{

    /** @dataProvider extensions */
    public function testUploadFile(string $extension)
    {
        $profile = $this->getCoreProfile(ProfileFixtures::PROFILE_1);
        $useCase = $this->getDependency(UploadProfilePhotoUseCase::class);
        $file = ProfilePhotoFixtures::FILE_PATH . $extension;
        $photo = $useCase->upload($profile, file_get_contents($file), $extension);
        $this->assertStringContainsString('http://storage-endpoint/', $photo->getPath());
        $this->assertTrue($photo->isMain());

        // todo useCase
        $shortPath = str_replace('http://storage-endpoint/', '', $photo->getPath());
        $storage = $this->getDependency(StorageInterface::class);
        $actualFile = $storage->getFile($shortPath);

        $this->assertEquals(file_get_contents($file), $actualFile);
    }

    private function extensions()
    {
        return [
            ['jpg'],
            ['png'],
        ];
    }
}
