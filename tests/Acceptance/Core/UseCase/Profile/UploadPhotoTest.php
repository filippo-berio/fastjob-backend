<?php

namespace App\Tests\Acceptance\Core\UseCase\Profile;

use App\Core\Application\UseCase\Profile\Photo\GetProfilePhotosUseCase;
use App\Core\Application\UseCase\Profile\Photo\UploadProfilePhotoUseCase;
use App\Core\Domain\Exception\Photo\ImageNsfwException;
use App\DataFixtures\Core\ProfileFixtures;
use App\DataFixtures\Core\ProfilePhotoFixtures;
use App\Storage\Service\StorageInterface;
use App\Tests\Acceptance\AcceptanceTest;
use WireMock\Client\WireMock;
use WireMock\Matching\UrlMatchingStrategy;

class UploadPhotoTest extends AcceptanceTest
{

    public function testImageNsfw()
    {
        $this->setupNsfwServiceWiremock(80, true);
        $profile = $this->getCoreProfile(ProfileFixtures::PROFILE_1);
        $useCase = $this->getDependency(UploadProfilePhotoUseCase::class);
        $file = ProfilePhotoFixtures::FILE_PATH . 'png';
        $this->expectException(ImageNsfwException::class);
        $useCase->upload($profile, file_get_contents($file));
    }

    /** @dataProvider extensions */
    public function testUploadFile(string $extension)
    {
        $wireMock = $this->setupNsfwServiceWiremock(20, false);

        $profile = $this->getCoreProfile(ProfileFixtures::PROFILE_1);

        $getProfilePhotosUseCase = $this->getDependency(GetProfilePhotosUseCase::class);
        $photosB4 = $getProfilePhotosUseCase->get($profile);

        $useCase = $this->getDependency(UploadProfilePhotoUseCase::class);
        $file = ProfilePhotoFixtures::FILE_PATH . $extension;
        $useCase->upload($profile, file_get_contents($file));

        $wireMock->verify(WireMock::postRequestedFor($this->buildMatchingStrategy()));

        $photos = $getProfilePhotosUseCase->get($profile);

        $this->assertCount(count($photosB4) + 1, $photos);
        $photo = array_pop($photos);

        $this->assertStringContainsString('http://storage-endpoint/', $photo->getPath());
        $this->assertTrue($photo->isMain());

        $shortPath = str_replace('http://storage-endpoint/', '', $photo->getPath());
        $storage = $this->getDependency(StorageInterface::class);
        $actualFile = $storage->getFile($shortPath);

        $this->assertEquals(file_get_contents($file), $actualFile);
    }

    private function setupNsfwServiceWiremock(int $probability, bool $nsfw): WireMock
    {
        $wireMock = $this->createWireMockClient();
        $urlMatchingStrategy = $this->buildMatchingStrategy();
        $wireMock->stubFor(WireMock::post($urlMatchingStrategy)
            ->willReturn(
                WireMock::aResponse()
                    ->withStatus(200)
                    ->withBody(json_encode([
                        'probability' => $probability,
                        'nsfw' => $nsfw
                    ]))
            )
        );
        return $wireMock;
    }

    private function buildMatchingStrategy(): UrlMatchingStrategy
    {
        return WireMock::urlMatching('^\/nsfw/image$');
    }

    private function extensions()
    {
        return [
            ['jpg'],
            ['png'],
        ];
    }
}
