<?php

namespace App\Core\Domain\Contract;

interface ImageNsfwFilterInterface
{
    public function isImageNsfw(string $file): bool;
}
