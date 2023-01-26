<?php

namespace App\Core\Domain\Service\Executor\NextExecutorService;

use Exception;

class NextExecutorServiceFactory
{
    private array $services;

    /**
     * @param iterable<NextExecutorServiceInterface> $services
     */
    public function __construct(
        iterable $services,
    ) {
        foreach ($services as $service) {
            $this->services[$service->getExecutorType()] = $service;
        }
    }

    public function getService(string $nextExecutorType): NextExecutorServiceInterface
    {
        if (!isset($this->services[$nextExecutorType])) {
            throw new Exception('Некорректный nextExecutorType');
        }
        return $this->services[$nextExecutorType];
    }
}
