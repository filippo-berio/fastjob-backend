<?php

namespace App\Tests\Functional\Core\UseCase\Task;

use App\Core\Application\UseCase\Review\GetProfileReviewsUseCase;
use App\Core\Application\UseCase\Task\FinishTaskUseCase;
use App\Core\Application\UseCase\TaskOffer\AcceptOfferUseCase;
use App\Core\Domain\Exception\Task\TaskNotInWorkException;
use App\Core\Infrastructure\Entity\Profile;
use App\Core\Infrastructure\Entity\Task;
use App\DataFixtures\Core\ProfileFixtures;
use App\DataFixtures\Core\TaskFixtures;
use App\Tests\Functional\FunctionalTest;

class FinishTaskUseCaseTest extends FunctionalTest
{
    private FinishTaskUseCase $finishTaskUseCase;
    private Profile $profile;
    private Task $task;
    private AcceptOfferUseCase $acceptOfferUseCase;
    private Profile $author;
    private GetProfileReviewsUseCase $getProfileReviewsUseCase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->finishTaskUseCase = $this->getDependency(FinishTaskUseCase::class);
        $this->acceptOfferUseCase = $this->getDependency(AcceptOfferUseCase::class);
        $this->getProfileReviewsUseCase = $this->getDependency(GetProfileReviewsUseCase::class);
        $this->profile = $this->getCoreProfile(ProfileFixtures::PROFILE_16);
        $this->author = $this->getCoreProfile(ProfileFixtures::PROFILE_15);
        $this->task = $this->getEntity(Task::class, TaskFixtures::TASK_16);
    }

    public function testSuccess()
    {
        $before = $this->getProfileReviewsUseCase->get($this->profile->getId());
        $this->assertEmpty($before);
        $this->acceptOffer();
        $this->finishTaskUseCase->finish($this->author, $this->task->getId(), 4, 'comment3');
        $after = $this->getProfileReviewsUseCase->get($this->profile->getId());
        $this->assertCount(1, $after);
        $review = $after[0];
        $this->assertEquals(4, $review->getRating());
        $this->assertEquals('comment3', $review->getComment());
    }

    public function testTaskNotInWork()
    {
        $this->expectException(TaskNotInWorkException::class);
        $this->finishTaskUseCase->finish($this->author, $this->task->getId(), 5);
    }

    private function acceptOffer()
    {
        $this->acceptOfferUseCase->acceptOffer($this->profile, $this->task->getId());
    }
}