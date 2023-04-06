<?php

namespace App\Tests\Acceptance\Core\UseCase\Review;

use App\Core\Application\UseCase\Review\CreateExecutorReviewUseCase;
use App\Core\Domain\Exception\Review\ReviewUnavailableException;
use App\Core\Infrastructure\Entity\Profile;
use App\Core\Infrastructure\Entity\Review;
use App\DataFixtures\Core\ProfileFixtures;
use App\DataFixtures\Core\TaskFixtures;
use App\Tests\Acceptance\AcceptanceTest;

class CreateExecutorReviewTest extends AcceptanceTest
{
    private Profile $profile;
    private CreateExecutorReviewUseCase $useCase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->profile = $this->getCoreProfile(ProfileFixtures::PROFILE_16);
        $this->useCase = $this->getDependency(CreateExecutorReviewUseCase::class);
    }

    public function testSuccess()
    {
        $before = $this->getCoreProfile(ProfileFixtures::PROFILE_17)->getReviews();
        $this->useCase->create($this->profile, TaskFixtures::TASK_18, 5, 'comment');

        $after = $this->getCoreProfile(ProfileFixtures::PROFILE_17)->getReviews();
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
