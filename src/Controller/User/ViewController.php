<?php

declare(strict_types=1);

namespace RashinMe\Controller\User;

use RashinMe\Security\Permissions;
use RashinMe\Service\Response\ResponseFactoryInterface;
use RashinMe\Service\User\Model\UserInterface;
use RashinMe\Service\User\UserServiceInterface;
use RashinMe\View\UserView;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

final class ViewController
{
    public function __construct(
        private readonly ResponseFactoryInterface $responseFactory,
        private readonly AuthorizationCheckerInterface $security,
        private readonly UserServiceInterface $userService,
    ) {
    }

    public function __invoke(?int $id, #[CurrentUser]UserInterface $currentUser): Response
    {
        if ($id === null) {
            return $this->responseFactory->createResponse(UserView::create($currentUser));
        }

        if (!$this->security->isGranted(Permissions::ADMIN)) {
            return $this->responseFactory->forbidden("You cant have access to view user data");
        }

        $user = $this->userService->getUserById($id);

        if ($user === null) {
            return $this->responseFactory->notFound("User not found");
        }

        return $this->responseFactory->createResponse(UserView::create($currentUser));
    }
}
