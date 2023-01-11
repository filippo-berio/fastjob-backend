<?php

namespace App\Tests\Integration;

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

    protected function getDependency(string $id)
    {
        return $this->container->get($id);
    }
}
