<?php

namespace App\Core\Domain\Exception\Photo;

use App\Core\Domain\Exception\BaseException;

class ImageNsfwException extends BaseException
{
    public function __construct()
    {
        parent::__construct("На вашей фотографии жопа", 403);
    }
}
