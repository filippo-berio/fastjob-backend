<?php

namespace App\Lib\Http\Method;

interface MethodInterface
{
    public function getHttpMethod(): string;

    public function getUri(): string;

    public function buildJson(): ?array;

    public function buildQuery(): ?array;
}
