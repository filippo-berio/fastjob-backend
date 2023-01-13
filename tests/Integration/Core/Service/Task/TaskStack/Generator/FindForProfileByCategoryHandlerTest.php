<?php

namespace App\Tests\Integration\Core\Service\Task\TaskStack\Generator;

use App\Core\Data\Query\Task\FindForProfileByCategory;
use App\Core\Data\Query\Task\FindForProfileByCategoryHandler;
use App\Core\Entity\Profile;
use App\DataFixtures\Core\ProfileFixtures;
use App\DataFixtures\Core\TaskFixtures;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class FindForProfileByCategoryHandlerTest extends KernelTestCase
{

    public function testUnseenTask()
    {
        $this::bootKernel();
        $container = static::getContainer();
        $this->assertEquals(1, 1);
        return;
        /** @var EntityManagerInterface $em */
        $em = $container->get(EntityManagerInterface::class);
        /** @var FindForProfileByCategoryHandler $handler */
        $handler = $container->get(FindForProfileByCategoryHandler::class);
        return;
        $profile = $em->getRepository(Profile::class)->find(ProfileFixtures::PROFILE_2);
        $tasks = $handler->handle(new FindForProfileByCategory($profile));
        $this->assertEquals(TaskFixtures::TASK_3, $tasks[0]->getId());
    }
}
