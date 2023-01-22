<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

abstract class BaseFixtures extends Fixture
{
    protected abstract function getEntity(): string;

    protected function save(array $objects, ObjectManager $manager)
    {
        foreach ($objects as $object) {
            $manager->persist($object);
        }
        $manager->flush();
    }

    public function addReference($name, $object): void
    {
        $entity = $this->getEntity();
        $parts = explode('\\', $entity);
        $prefix = array_pop($parts);
        parent::addReference($prefix . $name, $object);
    }

    /**
     * @template T
     * @param string $name
     * @param class-string<T>|null $class
     * @return T
     */
    public function getReference($name, ?string $class = null): object
    {
        $prefix = '';
        if ($class) {
            $parts = explode('\\', $class);
            $prefix = array_pop($parts);
        }
        return parent::getReference($prefix . $name, $class);
    }
}
