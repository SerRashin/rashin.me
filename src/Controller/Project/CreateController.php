<?php

declare(strict_types=1);

namespace RashinMe\Controller\Project;

use RashinMe\Security\Permissions;
use RashinMe\Service\ErrorInterface;
use RashinMe\Service\Project\Dto\ProjectData;
use RashinMe\Service\Project\ProjectService;
use RashinMe\Service\Response\ResponseFactoryInterface;
use RashinMe\Service\Validation\ValidationServiceInterface;
use RashinMe\View\ErrorView;
use RashinMe\View\ProjectView;
use Ser\DtoRequestBundle\Attributes\Dto;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

final class CreateController
{
    public function __construct(
        private readonly ResponseFactoryInterface $responseFactory,
        private readonly AuthorizationCheckerInterface $security,
        private readonly ProjectService $projectService,
        private readonly ValidationServiceInterface $validationService,
    ) {
    }

    public function __invoke(#[Dto] ProjectData $projectData): Response
    {
        if (!$this->security->isGranted(Permissions::ADMIN)) {
            return $this->responseFactory->forbidden("You're not allowed to create projects");
        }

        $validationError = $this->validationService->validate($projectData);

        if ($validationError !== null) {
            return $this->responseFactory->createResponse(
                ErrorView::create($validationError),
                400
            );
        }

        $result = $this->projectService->addProject($projectData);

        if ($result instanceof ErrorInterface) {
            return $this->responseFactory->createResponse(ErrorView::create($result), 400);
        }

        return $this->responseFactory->createResponse(ProjectView::create($result));
    }
}
