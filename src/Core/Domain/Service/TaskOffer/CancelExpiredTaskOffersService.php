<?php

namespace App\Core\Domain\Service\TaskOffer;

use App\Core\Domain\Repository\TaskOfferRepositoryInterface;

class CancelExpiredTaskOffersService
{
    public function __construct(
        private TaskOfferRepositoryInterface $taskOfferRepository,
    ) {
    }

    public function cancel()
    {
        $offers = $this->taskOfferRepository->findExpired();
        foreach ($offers as $offer) {
            $offer->cancel();
            $this->taskOfferRepository->save($offer);
        }
    }
}
