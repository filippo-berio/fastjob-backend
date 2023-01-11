<?php

namespace App\Tests\Application;

use App\Authistic\DTO\TokenPair;
use App\Core\Entity\Profile;
use App\Core\Security\Authistic\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class ApplicationTest extends WebTestCase
{
    protected function setUser(int $profileId, KernelBrowser $client)
    {
        /** @var EntityManagerInterface $em */
        $em = static::getContainer()->get(EntityManagerInterface::class);
        $profile = $em->getRepository(Profile::class)->find($profileId);
        $user = new User($profile, new TokenPair('accessToken', 'refreshToken'));
        $client->loginUser($user);
    }
}
