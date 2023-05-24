<?php

declare(strict_types=1);

namespace RashinMe\Controller\Project;

use RashinMe\Security\Permissions;
use RashinMe\Service\Project\ProjectService;
use RashinMe\Service\Response\ResponseFactoryInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

final class DeleteController
{
    public function __construct(
        private readonly ResponseFactoryInterface $responseFactory,
        private readonly AuthorizationCheckerInterface $security,
        private readonly ProjectService $projectService,
    ) {
    }

    public function __invoke(int $id): Response
    {
        if (!$this->security->isGranted(Permissions::ADMIN)) {
            return $this->responseFactory->forbidden("You're not allowed to delete projects");
        }

        $project = $this->projectService->getProjectById($id);

        if ($project === null) {
            return $this->responseFactory->notFound("Project not found");
        }

        $this->projectService->deleteProject($project);

        return $this->responseFactory->createResponse();
    }
}
