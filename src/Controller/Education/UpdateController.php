<?php

declare(strict_types=1);

namespace RashinMe\Controller\Education;

use RashinMe\Security\Permissions;
use RashinMe\Service\Education\Dto\EducationData;
use RashinMe\Service\Education\EducationService;
use RashinMe\Service\ErrorInterface;
use RashinMe\Service\Response\ResponseFactoryInterface;
use RashinMe\View\EducationView;
use RashinMe\View\ErrorView;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

final class UpdateController
{
    public function __construct(
        private readonly ResponseFactoryInterface $responseFactory,
        private readonly AuthorizationCheckerInterface $security,
        private readonly EducationService $educationService,
    ) {
    }

    public function __invoke(int $id, EducationData $educationData): Response
    {
        if (!$this->security->isGranted(Permissions::ADMIN)) {
            return $this->responseFactory->forbidden("You're not allowed to modify educations");
        }

        $education = $this->educationService->getEducationById($id);

        if ($education === null) {
            return $this->responseFactory->notFound("Education not found");
        }

        $result = $this->educationService->updateEducation($education, $educationData);

        if ($result instanceof ErrorInterface) {
            return $this->responseFactory->createResponse(ErrorView::create($result), 400);
        }

        return $this->responseFactory->createResponse(EducationView::create($result));
    }
}
