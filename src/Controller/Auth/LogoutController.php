<?php

declare(strict_types=1);

namespace RashinMe\Controller\Auth;

use RashinMe\Service\Response\ResponseFactoryInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Logout controller
 */
final class LogoutController
{
    public function __construct(
        private readonly Security $security,
        private readonly ResponseFactoryInterface $responseFactory,
    ) {
    }

    public function __invoke(Request $request): Response
    {
        $this->security->logout(false);

        return $this->responseFactory->createResponse();
    }
}
