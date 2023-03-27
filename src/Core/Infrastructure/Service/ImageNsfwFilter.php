<?php

namespace App\Core\Infrastructure\Service;

use App\Core\Domain\Contract\ImageNsfwFilterInterface;
use App\Lib\NsfwFilterService\NsfwFilterService;

class ImageNsfwFilter implements ImageNsfwFilterInterface
{
    public function __construct(
        private NsfwFilterService $nsfwFilterService,
    ) {
    }

    public function isImageNsfw(string $file): bool
    {
        return $this->nsfwFilterService->checkImageProbability($file)->nsfw;
    }
}
