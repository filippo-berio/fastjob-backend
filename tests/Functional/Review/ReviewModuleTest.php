<?php

namespace App\Tests\Functional\Review;

use App\DataFixtures\Core\ProfileFixtures;
use App\Review\Application\Exception\Profile\ProfileNotFoundException;
use App\Review\Application\UseCase\CreateReviewUseCase;
use App\Review\Application\UseCase\GetProfileReviewsUseCase;
use App\Review\Domain\Entity\Profile;
use App\Tests\Functional\FunctionalTest;

class ReviewModuleTest extends FunctionalTest
{
    private CreateReviewUseCase $createReviewUseCase;
    private GetProfileReviewsUseCase $getProfileReviewsUseCase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->createReviewUseCase = $this->getDependency(CreateReviewUseCase::class);
        $this->getProfileReviewsUseCase = $this->getDependency(GetProfileReviewsUseCase::class);
    }

    public function testComplex()
    {
        $author = new Profile(1);
        $target = new Profile(2);

        $this->assertEmpty($this->getProfileReviewsUseCase->get($target->getId()));

        $this->createReviewUseCase->create($author, $target->getId(), 5, 'comment1');
        $reviews = $this->getProfileReviewsUseCase->get($target->getId());

        $this->assertCount(1, $reviews);
        $this->assertEquals(5, $reviews[0]->getRating());
        $this->assertEquals('comment1', $reviews[0]->getComment());


        $this->createReviewUseCase->create($author, $target->getId(), 4, 'comment2');
        $reviews = $this->getProfileReviewsUseCase->get($target->getId());

        $this->assertCount(2, $reviews);
        $this->assertEquals(4, $reviews[1]->getRating());
        $this->assertEquals('comment2', $reviews[1]->getComment());
    }

    public function testNoTarget()
    {
        $author = new Profile(1);
        $this->expectException(ProfileNotFoundException::class);
        $this->createReviewUseCase->create($author, ProfileFixtures::NOT_EXIST_PROFILE, 5);
    }
}
