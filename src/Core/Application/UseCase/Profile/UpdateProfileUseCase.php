<?php

namespace App\Core\Application\UseCase\Profile;

use App\Core\Domain\DTO\Profile\UpdateProfileDTO;
use App\Core\Domain\Entity\Profile;
use App\Core\Domain\Exception\Category\CategoryNotFoundException;
use App\Core\Domain\Service\Profile\UpdateProfileService;
use App\Core\Infrastructure\Repository\CategoryRepository;
use App\Location\Exception\CityNotFoundException;
use App\Location\UseCase\City\GetCityByIdUseCase;

class UpdateProfileUseCase
{
    public function __construct(
        private UpdateProfileService $updateProfileService,
        private GetCityByIdUseCase $getCityByIdUseCase,
        private CategoryRepository $categoryRepository,
    ) {
    }

    public function update(
        Profile $profile,
        string  $firstName,
        array   $categoryIds = [],
        ?string $lastName = null,
        ?string $description = null,
        ?int    $cityId = null,
    ): Profile {
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
