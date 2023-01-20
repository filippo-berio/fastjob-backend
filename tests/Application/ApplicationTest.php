<?php

namespace App\Tests\Application;

use App\Core\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class ApplicationTest extends WebTestCase
{
    protected function notAuthorizedTest(
        KernelBrowser $client,
        string        $method,
        string        $uri,
        array         $parameters = []
    ) {
        $client->request($method, $uri, $parameters);
        $this->assertResponseStatusCodeSame(401);
    }

    protected function setUser(KernelBrowser $client, int $userId)
    {
        /** @var EntityManagerInterface $em */
        $em = static::getContainer()->get(EntityManagerInterface::class);
        $user = $em->getRepository(User::class)->find($userId);
        $client->loginUser($user);
    }

    protected function getResponse(KernelBrowser $client): array
    {
//        dd($client->getResponse());
        return json_decode($client->getResponse()->getContent(), true);
    }
}
