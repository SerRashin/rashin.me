<?php

declare(strict_types=1);

namespace RashinMe\Controller\Education;

use RashinMe\Security\Permissions;
use RashinMe\Service\Education\Dto\EducationData;
use RashinMe\Service\Education\EducationService;
use RashinMe\Service\ErrorInterface;
use RashinMe\Service\Response\ResponseFactoryInterface;
use RashinMe\Service\Validation\ValidationServiceInterface;
use RashinMe\View\EducationView;
use RashinMe\View\ErrorView;
use Ser\DtoRequestBundle\Attributes\Dto;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Add education controller
 */
final class CreateController
{
    public function __construct(
        private readonly ResponseFactoryInterface $responseFactory,
        private readonly AuthorizationCheckerInterface $security,
        private readonly EducationService $educationService,
        private readonly ValidationServiceInterface $validationService,
    ) {
    }

    public function __invoke(#[Dto] EducationData $educationData): Response
    {
        if (!$this->security->isGranted(Permissions::ADMIN)) {
            return $this->responseFactory->forbidden("You're not allowed to create educations");
        }

        $validationError = $this->validationService->validate($educationData);

        if ($validationError !== null) {
            return $this->responseFactory->createResponse(
                ErrorView::create($validationError),
                400
            );
        }

        return $this->responseFactory->createResponse(EducationView::create(
            $this->educationService->addEducation($educationData)
        ));
    }
}
