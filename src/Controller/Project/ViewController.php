<?php

declare(strict_types=1);

namespace RashinMe\Controller\Project;

use RashinMe\Service\Project\ProjectService;
use RashinMe\Service\Response\ResponseFactoryInterface;
use RashinMe\View\ProjectView;
use Symfony\Component\HttpFoundation\Response;

final class ViewController
{
    public function __construct(
        private readonly ResponseFactoryInterface $responseFactory,
        private readonly ProjectService $projectService,
    ) {
    }

    public function __invoke(int $id): Response
    {
        $project = $this->projectService->getProjectById($id);

        if ($project === null) {
            return $this->responseFactory->notFound("Project not found");
        }

        return $this->responseFactory->createResponse(ProjectView::create($project));
    }
}
