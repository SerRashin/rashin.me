<?php

declare(strict_types=1);

namespace RashinMe\Controller\Project;

use RashinMe\Security\Permissions;
use RashinMe\Service\ErrorInterface;
use RashinMe\Service\Project\Dto\ProjectData;
use RashinMe\Service\Project\ProjectService;
use RashinMe\Service\Response\ResponseFactoryInterface;
use RashinMe\View\ErrorView;
use RashinMe\View\ProjectView;
use Ser\DtoRequestBundle\Attributes\Dto;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

final class UpdateController
{
    public function __construct(
        private readonly ResponseFactoryInterface $responseFactory,
        private readonly AuthorizationCheckerInterface $security,
        private readonly ProjectService $projectService,
    ) {
    }

    public function __invoke(int $id, #[Dto] ProjectData $projectData, Request $request): Response
    {
        if (!$this->security->isGranted(Permissions::ADMIN)) {
            return $this->responseFactory->forbidden("You're not allowed to modify projects");
        }

        $project = $this->projectService->getProjectById($id);

        if ($project === null) {
            return $this->responseFactory->notFound("Project not found");
        }

        $result = $this->projectService->updateProject($project, $projectData);

        if ($result instanceof ErrorInterface) {
            return $this->responseFactory->createResponse(ErrorView::create($result), 400);
        }

        return $this->responseFactory->createResponse(ProjectView::create($result));
    }
}
