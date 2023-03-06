<?php

namespace App\Storage\Service;

use League\Flysystem\FilesystemOperator;

class Storage implements StorageInterface
{
    public function __construct(private FilesystemOperator $localStorage)
    {
    }

    public function getFile(string $path): string
    {
        return $this->localStorage->read($path);
    }

    public function storeFile(string $path, string $file)
    {
        $this->localStorage->write($path, $file);
    }
}
