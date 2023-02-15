<?php

namespace App\Lib\Http\Client;


use App\Lib\Http\Method\MethodInterface;

interface ClientInterface
{
    public function request(MethodInterface $method);
}
