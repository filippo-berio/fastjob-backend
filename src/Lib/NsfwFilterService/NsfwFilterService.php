<?php

namespace App\Lib\NsfwFilterService;

use App\Lib\Http\Client\ClientBuilder;
use App\Lib\Http\Client\ClientInterface;
use App\Lib\NsfwFilterService\DTO\NsfwImageProbability;
use App\Lib\NsfwFilterService\Method\ImageNsfwProbabilityMethod;

class NsfwFilterService
{
    private ClientInterface $client;

    public function __construct(
        ClientBuilder $clientBuilder,
        string $host,
    ) {
        $this->client = $clientBuilder->build($host);
    }

    public function checkImageProbability(string $image): NsfwImageProbability
    {
        $response = $this->client->request(new ImageNsfwProbabilityMethod($image));
        return new NsfwImageProbability($response['probability'] / 100, $response['nsfw']);
    }
}
