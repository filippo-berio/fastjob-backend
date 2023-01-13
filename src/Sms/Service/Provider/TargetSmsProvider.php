<?php

namespace App\Sms\Service\Provider;

use App\Http\Client\ClientBuilder;
use App\Http\Client\ClientInterface;
use App\Sms\Exception\SmsServiceException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class TargetSmsProvider implements SmsProviderInterface
{
    private ClientInterface $client;

    public function __construct(
        ClientBuilder $clientBuilder,
//        string $host,
    ) {
//        $this->client = $clientBuilder->build($host);
    }

    public function getAlias(): string
    {
        return 'targetSms';
    }

    public function send(string $phone, string $text): void
    {
        try {
//            $response = $this->client->get('', [
//                'query' => [
//                    'user' => $this->smsSendParams->login,
//                    'pwd' => $this->smsSendParams->password,
//                    'dadr' => $phone,
//                    'sadr' => $this->smsSendParams->sender ?? 'Profitbase',
//                    'text' => $text,
//                    'name_delivery' => 'Acceptance'
//                ]
//            ]);
        } catch (HttpExceptionInterface $exception) {
            throw new SmsServiceException($exception->getMessage());
        }
    }
}
