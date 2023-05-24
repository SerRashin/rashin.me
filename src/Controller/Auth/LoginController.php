<?php

declare(strict_types=1);

namespace RashinMe\Controller\Auth;

use RashinMe\Service\Response\Dto\SystemMessage;
use RashinMe\Service\Response\ResponseFactoryInterface;
use RashinMe\Service\User\Model\UserInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

/**
 * Login controller
 */
final class LoginController
{
    public function __construct(
        private readonly ResponseFactoryInterface $responseFactory,
    ) {
    }

    public function __invoke(#[CurrentUser] ?UserInterface $user, RequestStack $request): Response
    {
        if ($user === null) {
            return $this->responseFactory->unauthorized("Bad credentials");
        }

        return $this->responseFactory->createResponse(new SystemMessage(200, "success login"));
    }
}
