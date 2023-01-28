<?php

namespace App\Tests\SlowFunctional\Core\UseCase\TaskOffer;

use App\Core\Application\UseCase\TaskOffer\CancelExpiredTaskOffersUseCase;
use App\Core\Infrastructure\Entity\TaskOffer;
use App\Core\Domain\Event\TaskOffer\Cancel\CancelTaskOffersEvent;
use App\DataFixtures\Core\TaskOfferFixtures;
use App\Tests\SlowFunctional\SlowFunctionalTest;

class CancelExpiredTaskOffersUseCaseTest extends SlowFunctionalTest
{
    public function testSuccess()
    {
        $useCase = $this->getDependency(CancelExpiredTaskOffersUseCase::class);
        $useCase->cancel();
        $event = $this->assertAsyncEventDispatched(CancelTaskOffersEvent::class);
        $this->messenger()->process();
        $this->assertEmpty($event->offers);
        sleep(2);
        $useCase->cancel();
        $event = $this->assertAsyncEventDispatched(CancelTaskOffersEvent::class);
        $this->assertEquals(TaskOfferFixtures::OFFER_1, $event->offers[0]->getId());
        $this->messenger()->process();
        $offer = $this->getEntity(TaskOffer::class, TaskOfferFixtures::OFFER_1);
        $this->assertTrue($offer->isCanceled());
        $this->assertTrue($offer->getTask()->isOffered());
    }
}
