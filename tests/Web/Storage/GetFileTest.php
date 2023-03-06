<?php

namespace App\Tests\Web\Storage;

use App\DataFixtures\Core\ProfilePhotoFixtures;
use App\Tests\Web\WebTest;

class GetFileTest extends WebTest
{
    const EXPECTED = ProfilePhotoFixtures::TEST_PATH . '1.jpg';

    public function testGettingFile()
    {
        $this->client->request('GET', '/storage/profile-photo1.jpg');
        $file = $this->client->getResponse()->getContent();
        $this->assertEquals(file_get_contents(self::EXPECTED), $file);
    }
}
