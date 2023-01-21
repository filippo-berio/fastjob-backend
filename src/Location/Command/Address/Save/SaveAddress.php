<?php

namespace App\Location\Command\Address\Save;

use App\Location\Entity\Address;

class SaveAddress extends \App\CQRS\BaseCommand
{
    public function __construct(
        public Address $address
    ) {
    }
}
