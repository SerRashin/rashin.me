<?php

declare(strict_types=1);

namespace RashinMe\Controller\Project;

use RashinMe\Entity\Project;
use RashinMe\Service\Project\Filter\ProjectFilter;
use RashinMe\Service\Project\Filter\ProjectSort;
use RashinMe\Service\Project\ProjectService;
use RashinMe\Service\Response\Dto\CollectionChunk;
use RashinMe\Service\Response\ResponseFactoryInterface;
use RashinMe\View\PaginatedView;
use RashinMe\View\ProjectView;
use Symfony\Component\HttpFoundation\Response;

final class ListController
{
    public function __construct(
        private readonly ResponseFactoryInterface $responseFactory,
        private readonly ProjectService $projectService,
    ) {
    }

    public function __invoke(ProjectFilter $filter, ProjectSort $sort): Response
    {
        $projects = $this->projectService->getProjects($filter, $sort);
        $count = $this->projectService->getCount($filter);

        $collection = new CollectionChunk(
            $filter->limit,
            $filter->offset,
            $count,
            $projects,
        );

        return $this->responseFactory->createResponse(
            PaginatedView::create($collection, fn (Project $project) => ProjectView::create($project))
        );
    }
}
