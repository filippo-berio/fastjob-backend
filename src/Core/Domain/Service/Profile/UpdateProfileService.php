<?php

namespace App\Core\Domain\Service\Profile;

use App\Core\Domain\Command\Profile\SaveProfile;
use App\Core\Domain\DTO\Profile\UpdateProfileDTO;
use App\Core\Domain\Entity\Profile;
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
        return $this->commandBus->execute(new SaveProfile($profile));
    }
}
