<?php

namespace App\Lib\NsfwFilterService\Method;

use App\Lib\Http\Method\Method;

class ImageNsfwProbabilityMethod extends Method
{
    public function __construct(
        private string $file
    ) {
    }

    public function getHttpMethod(): string
    {
        return 'POST';
    }

    public function getUri(): string
    {
        return 'image';
    }

    public function buildMultipartData(): ?array
    {
        return [
            [
                'name' => 'file',
                'contents' => $this->file,
                'filename' => 'file_' . uniqid()
            ]
        ];
    }
}
