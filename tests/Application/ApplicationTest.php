<?php

namespace App\Tests\Application;

use App\Auth\Entity\User;
use App\DataFixtures\Auth\UserFixtures;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class ApplicationTest extends WebTestCase
{
    protected function notAuthorizedTest(
        KernelBrowser $client,
        string        $method,
        string        $uri,
        array         $parameters = [],
    ) {
        $client->request($method, $uri, $parameters);
        $this->assertResponseStatusCodeSame(401);
    }

    protected function noProfileTest(
        KernelBrowser $client,
        string        $method,
        string        $uri,
        array         $parameters = [],
    ) {
        $this->setUser($client, UserFixtures::USER_6, User::class);
        $client->request($method, $uri, $parameters);
        $this->assertResponseStatusCodeSame(401);
    }

    protected function setUser(KernelBrowser $client, int $userId, string $userEntity)
    {
        /** @var EntityManagerInterface $em */
        $em = static::getContainer()->get(EntityManagerInterface::class);
        $user = $em->getRepository($userEntity)->find($userId);
        $client->loginUser($user);
    }

    protected function getResponse(KernelBrowser $client): array
    {
        return json_decode($client->getResponse()->getContent(), true);
    }
}
