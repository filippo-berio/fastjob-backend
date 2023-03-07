<?php

namespace App\Api\Controller\Storage;

use App\Api\Controller\BaseController;
use App\Storage\Service\StorageInterface;
use finfo;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/storage')]
class StorageController extends BaseController
{
    #[Route('/{path}', requirements: ['path' => '.+'])]
    public function getFile(
        string $path,
        StorageInterface $storageService,
    ): Response {
        $file = $storageService->getFile($path);
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        return new Response($file, headers: [
            'Content-Type' => $finfo->buffer($file),
        ]);
    }
}
