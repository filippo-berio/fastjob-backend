<?php

namespace App\Core\Infrastructure\Gateway;

use App\Core\Domain\Entity\Review;
use App\Core\Domain\Entity\Profile;
use App\Core\Domain\Query\Profile\FindProfileById;
use App\CQRS\Bus\QueryBusInterface;
use App\Review\Application\UseCase\CreateReviewUseCase;
use App\Review\Application\UseCase\GetProfileReviewsUseCase;
use App\Review\Domain\Entity\Profile as ReviewProfile;
use App\Review\Domain\Entity\Review as ExternalReview;

class ReviewGateway
{
    public function __construct(
        private CreateReviewUseCase $createReviewUseCase,
        private GetProfileReviewsUseCase $getProfileReviewsUseCase,
        private QueryBusInterface $queryBus,
    ) {
    }

    public function saveReview(Review $review)
    {
        $this->createReviewUseCase->create(
            new ReviewProfile($review->getAuthor()->getId()),
            $review->getTarget()->getId(),
            $review->getRating(),
            $review->getComment()
        );
    }

    /**
     * @param Profile $profile
     * @return Review[]
     */
    public function findForTarget(Profile $profile): array
    {
        $reviews = $this->getProfileReviewsUseCase->get($profile->getId());
        return array_map(
            fn(ExternalReview $review) => $this->buildReviewFromExternal($review),
            $reviews
        );
    }

    private function buildReviewFromExternal(ExternalReview $review): Review
    {
        $author = $this->queryBus->query(new FindProfileById($review->getAuthor()->getId()));
        $target = $this->queryBus->query(new FindProfileById($review->getTarget()->getId()));
        return new Review(
            $author,
            $target,
            $review->getRating(),
            $review->getComment()
        );
    }
}
