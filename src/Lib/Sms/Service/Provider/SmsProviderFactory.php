<?php

namespace App\Lib\Sms\Service\Provider;


use RuntimeException;

class SmsProviderFactory
{
    /** @var SmsProviderInterface[] */
    private array $providers;

    /**
     * @param iterable<SmsProviderInterface> $providers
     * @param string $provider
     */
    public function __construct(
        iterable $providers,
        private string $provider,
    ) {
        foreach ($providers as $provider) {
            $this->providers[$provider->getAlias()] = $provider;
        }
    }

    public function create(): SmsProviderInterface
    {
        if (!isset($this->providers[$this->provider])) {
            throw new RuntimeException("Несуществующий провайдер $this->provider");
        }
        return $this->providers[$this->provider];
    }
}
