<?php

declare(strict_types=1);

namespace RashinMe\Controller\User;

use RashinMe\Security\Permissions;
use RashinMe\Service\Response\ResponseFactoryInterface;
use RashinMe\Service\User\UserServiceInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

final class DeleteController
{
    public function __construct(
        private readonly ResponseFactoryInterface $responseFactory,
        private readonly AuthorizationCheckerInterface $security,
        private readonly UserServiceInterface $userService,
    ) {
    }

    public function __invoke(int $id): Response
    {
        if (!$this->security->isGranted(Permissions::ADMIN)) {
            return $this->responseFactory->forbidden("You're not allowed to delete users");
        }

        $user = $this->userService->getUserById($id);

        if ($user === null) {
            return $this->responseFactory->notFound("User not found");
        }

        $this->userService->deleteUser($user);

        return $this->responseFactory->createResponse();
    }
}
