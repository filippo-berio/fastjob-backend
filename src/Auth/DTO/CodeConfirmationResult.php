<?php

namespace App\Auth\DTO;

class CodeConfirmationResult
{
    public bool $success;
    public ?int $retries = null;
    public ?TokenPair $tokens = null;

    public function __construct(bool $success)
    {
        $this->success = $success;
    }

    public static function buildSuccess(TokenPair $tokens): self
    {
        $result = new static(true);
        $result->tokens = $tokens;
        return $result;
    }

    public static function buildFailure(int $retries)
    {
        $result = new static(false);
        $result->retries = $retries;
        return $result;
    }
}
