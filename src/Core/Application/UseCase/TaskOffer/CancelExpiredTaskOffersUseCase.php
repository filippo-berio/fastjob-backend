<?php

namespace App\Core\Application\UseCase\TaskOffer;

use App\Core\Domain\Service\TaskOffer\CancelExpiredTaskOffersService;

class CancelExpiredTaskOffersUseCase
{
    public function __construct(
        protected CancelExpiredTaskOffersService $cancelExpiredTaskOffersService,
    ) {
    }

    public function cancel()
    {
        $this->cancelExpiredTaskOffersService->cancel();
    }
}
