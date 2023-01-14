<?php

namespace App\Core\Service\Profile;

use App\Core\Data\Command\User\SaveUser;
use App\Core\DTO\Profile\CreateProfileDTO;
use App\Core\Entity\Profile;
use App\Core\Exception\Profile\ProfileCreatedException;
use App\Core\Exception\ValidationException;
use App\CQRS\Bus\CommandBusInterface;
use App\Validation\ValidatorInterface;

class CreateProfileService
{
    public function __construct(
        private CommandBusInterface $commandBus,
        private ValidatorInterface $validator,
    ) {
    }

    public function create(CreateProfileDTO $createProfileDTO): Profile
    {
        $this->validate($createProfileDTO);

        $user = $createProfileDTO->user;
        $profile = new Profile(
            $user,
            $createProfileDTO->firstName,
            $createProfileDTO->birthDate,
        );
        $user->setProfile($profile);
        $this->commandBus->handle(new SaveUser($user));
        return $user->getProfile();
    }

    private function validate(CreateProfileDTO $createProfileDTO)
    {
        if ($createProfileDTO->user->getProfile()) {
            throw new ProfileCreatedException();
        }

        $errors = $this->validator->validate($createProfileDTO);
        if (count($errors)) {
            throw new ValidationException(implode(', ', $errors));
        }
    }
}
