<?php

declare(strict_types=1);

namespace RashinMe\Controller\Job;

use RashinMe\Security\Permissions;
use RashinMe\Service\ErrorInterface;
use RashinMe\Service\Job\Dto\JobData;
use RashinMe\Service\Job\JobService;
use RashinMe\Service\Response\ResponseFactoryInterface;
use RashinMe\View\ErrorView;
use RashinMe\View\JobView;
use Ser\DtoRequestBundle\Attributes\Dto;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Add job controller
 */
final class CreateController
{
    public function __construct(
        private readonly ResponseFactoryInterface $responseFactory,
        private readonly AuthorizationCheckerInterface $security,
        private readonly JobService $jobService,
    ) {
    }

    public function __invoke(#[Dto] JobData $jobData): Response
    {
        if (!$this->security->isGranted(Permissions::ADMIN)) {
            return $this->responseFactory->forbidden("You're not allowed to create jobs");
        }

        $result = $this->jobService->addJob($jobData);

        if ($result instanceof ErrorInterface) {
            return $this->responseFactory->createResponse(ErrorView::create($result), 400);
        }

        return $this->responseFactory->createResponse(JobView::create($result));
    }
}
