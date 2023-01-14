<?php

namespace App\Tests\Integration;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class IntegrationTest extends KernelTestCase
{
    protected ContainerInterface $container;

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
     * @return T
     */
    protected function getEntity(string $class, mixed $id)
    {
        $em = $this->getDependency(EntityManagerInterface::class);
        return $em->getRepository($class)->find($id);
    }
}
