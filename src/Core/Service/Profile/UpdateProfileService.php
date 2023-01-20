<?php

namespace App\Core\Service\Profile;

use App\Core\Command\Profile\Save\SaveProfile;
use App\Core\DTO\Profile\UpdateProfileDTO;
use App\Core\Entity\Profile;
use App\CQRS\Bus\CommandBusInterface;
use App\Validation\ValidatorInterface;

class UpdateProfileService
{
    public function __construct(
        private ValidatorInterface $validator,
        private CommandBusInterface $commandBus,
    ) {
    }

    public function update(Profile $profile, UpdateProfileDTO $updateProfileDTO): Profile
    {
        $this->validator->validate($updateProfileDTO);
        $profile->update($updateProfileDTO);
        return $this->commandBus->handle(new SaveProfile($profile));
    }
}
