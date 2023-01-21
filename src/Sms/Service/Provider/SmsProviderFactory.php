<?php

namespace App\Sms\Service\Provider;


use RuntimeException;

class SmsProviderFactory
{
    /** @var SmsProviderInterface[] */
    private array $providers;

    /**
     * @param iterable<SmsProviderInterface> $providers
     */
    public function __construct(
        iterable $providers
    ) {
        foreach ($providers as $provider) {
            $this->providers[$provider->getAlias()] = $provider;
        }
    }

    public function create(string $provider): SmsProviderInterface
    {
        if (!isset($this->providers[$provider])) {
            throw new RuntimeException("Несуществующий провайдер $provider");
        }
        return $this->providers[$provider];
    }
}
