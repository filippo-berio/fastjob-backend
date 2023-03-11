<?php

namespace App\Api\Controller\Core;

use App\Api\Controller\BaseController;
use App\Core\Application\UseCase\Profile\Photo\DeleteProfilePhotoUseCase;
use App\Core\Application\UseCase\Profile\Photo\GetProfilePhotosUseCase;
use App\Core\Application\UseCase\Profile\Photo\MakeProfilePhotoMainUseCase;
use App\Core\Application\UseCase\Profile\Photo\UploadProfilePhotoUseCase;
use App\Core\Domain\Entity\Profile;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[Route('/profile/photo')]
class ProfilePhotoController extends BaseController
{
    #[Route('/upload', methods: ['POST'])]
    public function upload(
        Request $request,
        #[CurrentUser] Profile $profile,
        UploadProfilePhotoUseCase $useCase,
    ): JsonResponse {
        /** @var File $file */
        $file = $request->files->all()[0];
        $photo = $useCase->upload($profile, $file->getContent(), $file->getExtension());
        return $this->json($photo);
    }

    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(
        #[CurrentUser] Profile $profile,
        int $id,
        DeleteProfilePhotoUseCase $useCase,
    ): JsonResponse {
        $useCase->delete($profile, $id);
        return $this->json();
    }

    #[Route('/{id}/make-main', methods: ['POST'])]
    public function makeMain(
        #[CurrentUser] Profile $profile,
        int $id,
        MakeProfilePhotoMainUseCase $useCase,
    ): JsonResponse {
        $useCase->set($profile, $id);
        return $this->json();
    }
}
