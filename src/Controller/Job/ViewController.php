<?php

declare(strict_types=1);

namespace RashinMe\Controller\Job;

use RashinMe\Service\Job\JobService;
use RashinMe\Service\Response\ResponseFactoryInterface;
use RashinMe\View\JobView;
use Symfony\Component\HttpFoundation\Response;

/**
 * View job controller
 */
final class ViewController
{
    public function __construct(
        private readonly ResponseFactoryInterface $responseFactory,
        private readonly JobService $jobService,
    ) {
    }

    public function __invoke(int $id): Response
    {
        $job = $this->jobService->getJobById($id);

        if ($job === null) {
            return $this->responseFactory->notFound("Job not found");
        }

        return $this->responseFactory->createResponse(JobView::create($job));
    }
}
