<?php

namespace App\Core\Repository;

use App\Core\Entity\Profile;
use App\Core\Entity\Task;
use App\Core\Entity\TaskSwipe;
use Doctrine\ORM\EntityManagerInterface;

class TaskSwipeRepository
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
    }

    public function save(TaskSwipe $taskSwipe): TaskSwipe
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

    public function findByProfileAndTask(Profile $profile, Task $task): ?TaskSwipe
    {
        return $this->entityManager->getRepository(TaskSwipe::class)->findOneBy([
            'profile' => $profile,
            'task' => $task,
        ]);
    }
}
