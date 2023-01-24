<?php

namespace App\Core\UseCase\Profile;

use App\Auth\Entity\User;
use App\Core\DTO\Profile\UpdateProfileDTO;
use App\Core\Entity\Profile;
use App\Core\Exception\Category\CategoryNotFoundException;
use App\Core\Exception\Profile\ProfileNotFoundException;
use App\Core\Query\Profile\FindByUser\FindProfileByUser;
use App\Core\Repository\CategoryRepository;
use App\Core\Service\Profile\UpdateProfileService;
use App\CQRS\Bus\QueryBusInterface;
use App\Location\Exception\CityNotFoundException;
use App\Location\UseCase\City\GetCityByIdUseCase;

class UpdateProfileUseCase
{
    public function __construct(
        private UpdateProfileService $updateProfileService,
        private QueryBusInterface $queryBus,
        private GetCityByIdUseCase $getCityByIdUseCase,
        private CategoryRepository $categoryRepository,
    ) {
    }

    public function update(
        User $user,
        string $firstName,
        array $categoryIds = [],
        ?string $lastName = null,
        ?string $description = null,
        ?int $cityId = null,
    ): Profile {
        if (!$profile = $this->queryBus->query(new FindProfileByUser($user))) {
            throw new ProfileNotFoundException();
        }

        if ($cityId) {
            $city = $this->getCityByIdUseCase->get($cityId);
            if (!$city) {
                throw new CityNotFoundException();
            }
        }

        $categories = empty($categoryIds) ? [] : $this->categoryRepository->findByIds($categoryIds);
        if (count($categories) !== count($categoryIds)) {
            throw new CategoryNotFoundException();
        }

        return $this->updateProfileService->update(
            $profile,
            new UpdateProfileDTO(
                $firstName,
                $categories,
                $lastName,
                $description,
                $cityId ? $city : null
            )
        );
    }
}
