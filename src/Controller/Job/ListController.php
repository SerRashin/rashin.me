<?php

declare(strict_types=1);

namespace RashinMe\Controller\Job;

use RashinMe\Entity\Job;
use RashinMe\Service\Job\Dto\JobFilter;
use RashinMe\Service\Job\Dto\JobSort;
use RashinMe\Service\Job\JobService;
use RashinMe\Service\Response\Dto\CollectionChunk;
use RashinMe\Service\Response\ResponseFactoryInterface;
use RashinMe\View\JobView;
use RashinMe\View\PaginatedView;
use Symfony\Component\HttpFoundation\Response;

/**
 * List of jobs controller
 */
final class ListController
{
    public function __construct(
        private readonly ResponseFactoryInterface $responseFactory,
        private readonly JobService $jobService,
    ) {
    }

    public function __invoke(JobFilter $filter, JobSort $sort): Response
    {
        $jobs = $this->jobService->getJobs($filter, $sort);
        $count = $this->jobService->getCount($filter);

        $collection = new CollectionChunk(
            $filter->limit,
            $filter->offset,
            $count,
            $jobs
        );

        return $this->responseFactory->createResponse(
            PaginatedView::create($collection, function (Job $job) {
                return JobView::create($job);
            })
        );
    }
}
