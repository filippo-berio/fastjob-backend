<?php

namespace App\Lib\NsfwFilterService\DTO;

readonly class NsfwImageProbability
{
    public function __construct(
        public float $probability,
        public bool $nsfw
    ) {
    }
}
