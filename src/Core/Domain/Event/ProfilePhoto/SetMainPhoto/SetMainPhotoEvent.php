<?php

namespace App\Core\Domain\Event\ProfilePhoto\SetMainPhoto;

use App\Core\Domain\Entity\ProfilePhoto;
use App\Core\Domain\Event\EventInterface;

class SetMainPhotoEvent implements EventInterface
{
    public function __construct(
        public ProfilePhoto $photo,
    ) {
    }
}
