<?php

namespace App\Core\Domain\Service\Profile;

use App\Core\Domain\Command\Profile\SaveProfile;
use App\Core\Domain\Contract\EntityMapperInterface;
use App\Core\Domain\DTO\Profile\CreateProfileDTO;
use App\Core\Domain\Entity\Profile;
use App\Core\Domain\Exception\Profile\ProfileCreatedException;
use App\Core\Domain\Query\Profile\FindProfileByUser;
use App\CQRS\Bus\CommandBusInterface;
use App\CQRS\Bus\QueryBusInterface;
use App\Validation\ValidatorInterface;

class CreateProfileService
{
    public function __construct(
        private CommandBusInterface $commandBus,
        private ValidatorInterface $validator,
        private QueryBusInterface $queryBus,
        private EntityMapperInterface $entityMapper,
    ) {
    }

    public function create(CreateProfileDTO $createProfileDTO): Profile
    {
        $this->validate($createProfileDTO);

        $user = $createProfileDTO->user;
        $entity = $this->entityMapper->persistenceEntity(Profile::class);
        $profile = new $entity(
            $user,
            $createProfileDTO->firstName,
            $createProfileDTO->birthDate,
        );
        $this->commandBus->execute(new SaveProfile($profile));
        return $profile;
    }

    private function validate(CreateProfileDTO $createProfileDTO)
    {
        if ($this->queryBus->query(new FindProfileByUser($createProfileDTO->user))) {
            throw new ProfileCreatedException();
        }

        $this->validator->validate($createProfileDTO);
    }
}
