<?php

declare(strict_types=1);

namespace RashinMe\Controller\Auth;

use Exception;
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
        try {
            $this->security->logout(false);
        } catch (Exception $e) {
            return $this->responseFactory->badRequest('Unable to logout as there is no logged-in user.');
        }

        return $this->responseFactory->createResponse();
    }
}
