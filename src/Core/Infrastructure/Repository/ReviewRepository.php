<?php

namespace App\Core\Infrastructure\Repository;

use App\Core\Domain\Entity\Profile;
use App\Core\Domain\Entity\Review as DomainReview;
use App\Core\Domain\Query\Profile\FindProfileById;
use App\Core\Domain\Repository\ReviewRepositoryInterface;
use App\Core\Infrastructure\Entity\Review;
use App\Core\Infrastructure\Gateway\ReviewGateway;
use App\CQRS\Bus\QueryBusInterface;
use App\Review\Domain\Entity\Review as ExternalReview;
use Doctrine\ORM\EntityManagerInterface;

class ReviewRepository implements ReviewRepositoryInterface
{
    public function __construct(
        private ReviewGateway $reviewGateway,
        private QueryBusInterface $queryBus,
        private EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * @param Review $review
     * @return Review
     */
    public function save(DomainReview $review): DomainReview
    {
        $external = $this->reviewGateway->saveReview($review);
        $review->setId($external->getId());
        $this->entityManager->persist($review);
        $this->entityManager->flush();
        return $review;
    }

    public function findForTarget(Profile $profile): array
    {
        $external = $this->reviewGateway->findForTarget($profile);
        return array_map(
            fn(ExternalReview $review) => $this->buildReviewFromExternal($review),
            $external
        );
    }

    private function buildReviewFromExternal(ExternalReview $external): Review
    {
        /** @var Review $review */
        $review = $this->entityManager->getRepository(Review::class)->find($external->getId());
        $author = $this->queryBus->query(new FindProfileById($external->getAuthor()->getId()));
        $target = $this->queryBus->query(new FindProfileById($external->getTarget()->getId()));

        $review->setAuthor($author);
        $review->setTarget($target);
        $review->setRating($external->getRating());
        $review->setComment($external->getComment());
        return $review;
    }
}
