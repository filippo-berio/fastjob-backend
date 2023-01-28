<?php

namespace App\Tests\Functional\Core\UseCase\TaskOffer;

use App\Core\Application\UseCase\TaskOffer\AcceptOfferUseCase;
use App\Core\Application\UseCase\TaskOffer\RejectTaskOfferUseCase;
use App\Core\Domain\Exception\TaskOffer\TaskOfferNotFoundException;
use App\DataFixtures\Core\ProfileFixtures;
use App\DataFixtures\Core\TaskFixtures;
use App\Tests\Functional\FunctionalTest;

class RejectTaskOfferUseCaseTest extends FunctionalTest
{
    public function testSuccess()
    {
        $useCase = $this->getDependency(RejectTaskOfferUseCase::class);
        $profile = $this->getCoreProfile(ProfileFixtures::PROFILE_16);
        $useCase->reject($profile, TaskFixtures::TASK_16);
        $this->expectException(TaskOfferNotFoundException::class);
        $useCase->reject($profile, TaskFixtures::TASK_16);
    }

    public function testError()
    {
        $useCase = $this->getDependency(RejectTaskOfferUseCase::class);
        $profile = $this->getCoreProfile(ProfileFixtures::PROFILE_17);
        $this->expectException(TaskOfferNotFoundException::class);
        $useCase->reject($profile, TaskFixtures::TASK_16);
    }
}
