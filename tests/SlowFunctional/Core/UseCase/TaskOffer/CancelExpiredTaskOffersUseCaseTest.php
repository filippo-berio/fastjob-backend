<?php

namespace App\Tests\SlowFunctional\Core\UseCase\TaskOffer;

use App\Core\Application\UseCase\TaskOffer\CancelExpiredTaskOffersUseCase;
use App\Core\Infrastructure\Entity\TaskOffer;
use App\DataFixtures\Core\TaskOfferFixtures;
use App\Tests\SlowFunctional\SlowFunctionalTest;

class CancelExpiredTaskOffersUseCaseTest extends SlowFunctionalTest
{
    public function testSuccess()
    {
        $useCase = $this->getDependency(CancelExpiredTaskOffersUseCase::class);
        $useCase->cancel();
        sleep(2);
        $useCase->cancel();
        $offer = $this->getEntity(TaskOffer::class, TaskOfferFixtures::OFFER_1);
        $this->assertTrue($offer->isCanceled());
        $this->assertTrue($offer->getTask()->isOffered());
    }
}
