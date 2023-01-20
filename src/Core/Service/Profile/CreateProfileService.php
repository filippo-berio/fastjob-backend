<?php

namespace App\Core\Service\Profile;

use App\Core\Command\Profile\Save\SaveProfile;
use App\Core\DTO\Profile\CreateProfileDTO;
use App\Core\Entity\Profile;
use App\Core\Exception\Profile\ProfileCreatedException;
use App\Core\Query\Profile\FindByUser\FindProfileByUser;
use App\CQRS\Bus\CommandBusInterface;
use App\CQRS\Bus\QueryBusInterface;
use App\Validation\ValidatorInterface;

class CreateProfileService
{
    public function __construct(
        private CommandBusInterface $commandBus,
        private ValidatorInterface $validator,
        private QueryBusInterface $queryBus,
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
        $this->commandBus->handle(new SaveProfile($profile));
        return $profile;
    }

    private function validate(CreateProfileDTO $createProfileDTO)
    {
        if ($this->queryBus->handle(new FindProfileByUser($createProfileDTO->user))) {
            throw new ProfileCreatedException();
        }

        $this->validator->validate($createProfileDTO);
    }
}
