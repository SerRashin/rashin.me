<?php

declare(strict_types=1);

namespace RashinMe\Controller\User;

use RashinMe\Service\ErrorInterface;
use RashinMe\Service\Response\ResponseFactoryInterface;
use RashinMe\Service\User\Dto\RegistrationData;
use RashinMe\Service\User\RegistrationService;
use RashinMe\View\UserView;
use RashinMe\View\ErrorView;
use Ser\DtoRequestBundle\Attributes\Dto;
use Symfony\Component\HttpFoundation\Response;

/**
 * Registration controller
 */
final class RegistrationController
{
    public function __construct(
        private readonly RegistrationService $registrationService,
        private readonly ResponseFactoryInterface $responseFactory,
    ) {
    }

    public function __invoke(#[Dto] RegistrationData $registrationData): Response
    {
        $result = $this->registrationService->register($registrationData);

        if ($result instanceof ErrorInterface) {
            return $this->responseFactory->createResponse(ErrorView::create($result), 400);
        }

        return $this->responseFactory->createResponse(UserView::create($result), 201);
    }
}
