<?php

declare(strict_types=1);

namespace RashinMe\Controller\Storage;

use RashinMe\Service\Response\ResponseFactoryInterface;
use RashinMe\Service\Storage\StorageService;
use RashinMe\View\FileView;
use Symfony\Component\HttpFoundation\Response;

class ViewController
{
    public function __construct(
        private readonly ResponseFactoryInterface $responseFactory,
        private readonly StorageService $storageService,
    ) {
    }

    public function __invoke(int $id): Response
    {
        $file = $this->storageService->getFileById($id);

        if ($file === null) {
            return $this->responseFactory->notFound("File not found");
        }

        return $this->responseFactory->createResponse(FileView::create($file));
    }
}
