<?php

namespace App\Tests\Functional;

use App\Auth\Entity\User as AuthUser;
use App\Core\Domain\Entity\User;
use App\Core\Domain\Event\EventInterface;
use App\Core\Infrastructure\Entity\Profile;
use App\Core\Infrastructure\Message\Event\EventMessage;
use DAMA\DoctrineTestBundle\Doctrine\DBAL\StaticDriver;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\AssertionFailedError;
use Predis\Client;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use WireMock\Client\WireMock;
use Zenstruck\Messenger\Test\InteractsWithMessenger;

abstract class FunctionalTest extends KernelTestCase
{
    use InteractsWithMessenger;

    protected ContainerInterface $container;

    private const WIREMOCK_HOST = 'fastjob-wiremock';
    private const REDIS_HOST = 'redis://fastjob-redis-test:6379';

    protected function setUp(): void
    {
        $this->bootContainer();
        $this->redisClear();
        $this->messenger()->reset();
    }

    protected function bootContainer()
    {
        if ($this::$booted) {
            return;
        }
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

    protected function redisClear()
    {
        $redis = new Client(self::REDIS_HOST);
        $redis->flushall();
    }

    protected function debugDB()
    {
        StaticDriver::commit();
        dd('debug your fucking db now bitch');
    }

    protected function getCoreUser(int $id): User
    {
        $authUser = $this->getEntity(AuthUser::class, $id);
        return new User($authUser->getId(), $authUser->getPhone());
    }

    protected function getCoreProfile(int $id): Profile
    {
        $profile = $this->getEntity(Profile::class, $id);
        $user = $this->getCoreUser($profile->getUserId());
        $profile->setUser($user);
        return $profile;
    }

    /**
     * @template T
     * @param class-string<T> $event
     * @return T
     */
    protected function assertAsyncEventDispatched(string $event): EventInterface
    {
        $this->messenger()->queue()->assertContains(EventMessage::class);
        foreach ($this->messenger()->queue()->messages() as $message) {
            if ($message instanceof EventMessage) {
                if ($message->event instanceof $event) {
                    return $message->event;
                }
            }
        }
        throw new AssertionFailedError("Failed asserting that $event is dispatched asynchronously");
    }

    protected function assertAsyncEventNotDispatched(string $event)
    {
        foreach ($this->messenger()->queue()->messages() as $message) {
            if ($message instanceof EventMessage) {
                if ($message->event instanceof $event) {
                    throw new AssertionFailedError("Failed asserting that $event is not dispatched asynchronously");
                }
            }
        }
    }
}
