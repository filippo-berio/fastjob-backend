<?php

namespace App\Console\Command\Core\TaskOffer;

use App\Core\Application\UseCase\TaskOffer\CancelExpiredTaskOffersUseCase;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;

#[AsCommand('core:task-offer:cancel-expired')]
class CancelExpiredTaskOffersCommand extends Command
{
    public function __construct(
        private CancelExpiredTaskOffersUseCase $useCase,
    ) {
        parent::__construct();
    }
}
