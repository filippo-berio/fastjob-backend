<?php

namespace App\Lib\Http\Method;

abstract class Method implements MethodInterface
{
    public function buildJson(): ?array
    {
        return null;
    }

    public function buildQuery(): ?array
    {
        return null;
    }

    public function buildMultipartData(): ?array
    {
        return null;
    }
}
