<?php

namespace App\Http\Method;

interface MethodInterface
{
    public function getHttpMethod(): string;

    public function getUri(): string;

    public function buildJson(): ?array;

    public function buildQuery(): ?array;
}
