<?php

namespace App\Sms\Service\Provider;


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
        return $this->providers[$provider];
    }
}
