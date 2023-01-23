<?php

namespace App\Tests\Functional;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use WireMock\Client\WireMock;

abstract class FunctionalTest extends KernelTestCase
{
    protected ContainerInterface $container;

    private const WIREMOCK_HOST = 'fastjob-wiremock';

    protected function bootContainer()
    {
        $this::bootKernel();
        $this->container = $this::getContainer();
    }

    /**
     * @template T
     * @param class-string<T> $id
     * @return T
     */
    protected function getDependency(string $id)
    {
        return $this->container->get($id);
    }

    /**
     * @template T
     * @param class-string<T> $class
     * @param mixed $id
     * @return T|null
     */
    protected function getEntity(string $class, mixed $id)
    {
        $em = $this->getDependency(EntityManagerInterface::class);
        return $em->getRepository($class)->find($id);
    }

    /**
     * @template T
     * @param class-string<T> $class
     * @param array $criteria
     * @return T[]
     */
    protected function getEntityBy(string $class, array $criteria)
    {
        $em = $this->getDependency(EntityManagerInterface::class);
        return $em->getRepository($class)->findBy($criteria);
    }

    protected function createWireMockClient(): WireMock
    {
        $wireMock = WireMock::create(self::WIREMOCK_HOST);
        $this->assertTrue($wireMock->isAlive());
        return $wireMock;
    }
}
