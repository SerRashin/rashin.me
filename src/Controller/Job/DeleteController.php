<?php

declare(strict_types=1);

namespace RashinMe\Controller\Job;

use RashinMe\Security\Permissions;
use RashinMe\Service\Job\JobService;
use RashinMe\Service\Response\ResponseFactoryInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Delete job controller
 */
final class DeleteController
{
    public function __construct(
        private readonly ResponseFactoryInterface $responseFactory,
        private readonly AuthorizationCheckerInterface $security,
        private readonly JobService $jobService,
    ) {
    }

    public function __invoke(int $id): Response
    {
        if (!$this->security->isGranted(Permissions::ADMIN)) {
            return $this->responseFactory->forbidden("You're not allowed to delete jobs");
        }

        $job = $this->jobService->getJobById($id);

        if ($job === null) {
            return $this->responseFactory->notFound("Job not found");
        }

        $this->jobService->deleteJob($job);

        return $this->responseFactory->createResponse();
    }
}
