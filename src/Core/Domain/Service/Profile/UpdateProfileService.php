<?php

namespace App\Core\Domain\Service\Profile;

use App\Core\Domain\Command\Profile\SaveProfile;
use App\Core\Domain\DTO\Profile\UpdateProfileDTO;
use App\Core\Domain\Entity\Category;
use App\Core\Domain\Entity\Profile;
use App\Core\Domain\Event\EventDispatcherInterface;
use App\Core\Domain\Event\Task\GenerateNext\GenerateNextTaskEvent;
use App\Core\Domain\Repository\ProfileNextTaskRepositoryInterface;
use App\Lib\CQRS\Bus\CommandBusInterface;
use App\Lib\Validation\ValidatorInterface;

class UpdateProfileService
{
    public function __construct(
        private ValidatorInterface $validator,
        private CommandBusInterface $commandBus,
        private ProfileNextTaskRepositoryInterface $nextTaskRepository,
        private EventDispatcherInterface $eventDispatcher,
    ) {
    }

    public function update(Profile $profile, UpdateProfileDTO $updateProfileDTO): Profile
    {
        $this->validator->validate($updateProfileDTO);
        if ($this->nextTaskParametersChanged($profile, $updateProfileDTO)) {
            $this->nextTaskRepository->clear($profile);
            $this->eventDispatcher->dispatch(new GenerateNextTaskEvent($profile->getId()));
        }
        $profile->update($updateProfileDTO);
        /** @var Profile $profile */
        $profile = $this->commandBus->execute(new SaveProfile($profile));
        return $profile;
    }

    private function nextTaskParametersChanged(
        Profile $profile,
        UpdateProfileDTO $updateProfileDTO
    ): bool {
        $newCategoryIds = array_map(
            fn(Category $category) => $category->getId(),
            $updateProfileDTO->categories ?? []
        );
        sort($newCategoryIds);

        $oldCategoryIds = array_map(
            fn(Category $category) => $category->getId(),
            $profile->getCategories()
        );
        sort($oldCategoryIds);

        return $updateProfileDTO->city?->getId() !== $profile->getId()
            || $oldCategoryIds != $newCategoryIds;
    }
}
