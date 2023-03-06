<?php

namespace App\Storage\Service;


interface StorageInterface
{
    public function getFile(string $path): string;

    public function storeFile(string $path, string $file);
}
