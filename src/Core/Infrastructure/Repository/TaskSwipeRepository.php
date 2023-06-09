<?php

namespace App\Core\Infrastructure\Repository;

use App\Core\Domain\Entity\Profile;
use App\Core\Domain\Entity\Task;
use App\Core\Domain\Entity\TaskSwipe as DomainTaskSwipe;
use App\Core\Domain\Repository\TaskSwipeRepositoryInterface;
use App\Core\Infrastructure\Entity\TaskSwipe;
use Doctrine\ORM\EntityManagerInterface;

class TaskSwipeRepository implements TaskSwipeRepositoryInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
    }

    public function save(DomainTaskSwipe $taskSwipe): DomainTaskSwipe
    {
        $this->entityManager->persist($taskSwipe);
        $this->entityManager->flush();
        return $taskSwipe;
    }

    public function findByProfile(Profile $profile): array
    {
        return $this->entityManager->getRepository(TaskSwipe::class)->findBy([
            'profile' => $profile
        ]);
    }

    public function findByProfileAndTask(Profile $profile, Task $task): ?DomainTaskSwipe
    {
        return $this->entityManager->getRepository(TaskSwipe::class)->findOneBy([
            'profile' => $profile,
            'task' => $task,
        ]);
    }
}
