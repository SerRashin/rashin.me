<?php

declare(strict_types=1);

namespace RashinMe\Controller\Storage;

use RashinMe\Security\Permissions;
use RashinMe\Service\ErrorInterface;
use RashinMe\Service\Response\ResponseFactoryInterface;
use RashinMe\Service\Storage\UploadService;
use RashinMe\View\ErrorView;
use RashinMe\View\FileView;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class UploadController
{
    public function __construct(
        private readonly ResponseFactoryInterface $responseFactory,
        private readonly AuthorizationCheckerInterface $security,
        private readonly UploadService $uploadService,
    ) {
    }

    public function __invoke(Request $request): Response
    {
        if (!$this->security->isGranted(Permissions::ADMIN)) {
            return $this->responseFactory->forbidden("You can't upload files.");
        }

        /**
         * @var UploadedFile|null $uploadedFile
         */
        $uploadedFile = $request->files->get("file");

        if ($uploadedFile === null) {
            return $this->responseFactory->badRequest("File not selected");
        }

        $result = $this->uploadService->execute($uploadedFile);

        if ($result instanceof ErrorInterface) {
            return $this->responseFactory->createResponse(ErrorView::create($result), 400);
        }

        return $this->responseFactory->createResponse(FileView::create($result), Response::HTTP_CREATED);
    }
}
