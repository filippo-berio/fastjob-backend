<?php

namespace App\Chat\Entity;

interface PersonInterface
{
    public function getId(): int;

    public function getName(): string;

    public function getPhotoPath(): ?string;
}
