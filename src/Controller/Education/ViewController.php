<?php

declare(strict_types=1);

namespace RashinMe\Controller\Education;

use RashinMe\Service\Education\EducationService;
use RashinMe\Service\Response\ResponseFactoryInterface;
use RashinMe\View\EducationView;
use Symfony\Component\HttpFoundation\Response;

final class ViewController
{
    public function __construct(
        private readonly ResponseFactoryInterface $responseFactory,
        private readonly EducationService $educationService,
    ) {
    }

    public function __invoke(int $id): Response
    {
        $education = $this->educationService->getEducationById($id);

        if ($education === null) {
            return $this->responseFactory->notFound("Education not found");
        }

        return $this->responseFactory->createResponse(EducationView::create($education));
    }
}
