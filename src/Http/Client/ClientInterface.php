<?php

namespace App\Http\Client;


use App\Http\Method\MethodInterface;

interface ClientInterface
{
    public function request(MethodInterface $method);
}
