<?php

namespace App\Core\Infrastructure\Event\Listener\EntityLifecycle\Profile;

use App\Core\Domain\Repository\ReviewRepositoryInterface;
use App\Core\Infrastructure\Entity\Profile;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;

#[AsEntityListener(event: Events::postLoad, entity: Profile::class)]
class ProfilePostLoadListener
{
    public function __construct(
        private ReviewRepositoryInterface $reviewRepository
    ) {
    }

    public function __invoke(Profile $profile)
    {
        $reviews = $this->reviewRepository->findForTarget($profile);
        $profile->setReviews($reviews);
    }
}
