<?php

namespace App\Tests\Functional\Core\UseCase\Review;

use App\Core\Application\UseCase\Review\CreateExecutorReviewUseCase;
use App\Core\Application\UseCase\Review\GetProfileReviewsUseCase;
use App\Core\Domain\Exception\Review\ReviewUnavailableException;
use App\Core\Infrastructure\Entity\Profile;
use App\Core\Infrastructure\Entity\Review;
use App\DataFixtures\Core\ProfileFixtures;
use App\DataFixtures\Core\TaskFixtures;
use App\Tests\Functional\FunctionalTest;

class CreateExecutorReviewTest extends FunctionalTest
{
    private Profile $profile;
    private CreateExecutorReviewUseCase $useCase;
    private GetProfileReviewsUseCase $getProfileReviewsUseCase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->profile = $this->getCoreProfile(ProfileFixtures::PROFILE_16);
        $this->useCase = $this->getDependency(CreateExecutorReviewUseCase::class);
        $this->getProfileReviewsUseCase = $this->getDependency(GetProfileReviewsUseCase::class);
    }

    public function testSuccess()
    {
        $before = $this->getProfileReviewsUseCase->get(ProfileFixtures::PROFILE_17);
        $this->useCase->create($this->profile, TaskFixtures::TASK_18, 5, 'comment');
        $after = $this->getProfileReviewsUseCase->get(ProfileFixtures::PROFILE_17);
        $this->assertCount(count($before) + 1, $after);
        /** @var Review $review */
        $review = array_pop($after);
        $this->assertEquals($this->profile->getId(), $review->getAuthor()->getId());
        $this->assertEquals(ProfileFixtures::PROFILE_17, $review->getTarget()->getId());
        $this->assertEquals(5, $review->getRating());
        $this->assertEquals('comment', $review->getComment());
    }

    public function testReviewExists()
    {
        $this->expectException(ReviewUnavailableException::class);
        $this->useCase->create($this->profile, TaskFixtures::TASK_20, 5);
    }

    public function testExecutionCancelled()
    {
        $this->expectException(ReviewUnavailableException::class);
        $this->useCase->create($this->profile, TaskFixtures::TASK_19, 5);
    }
}
