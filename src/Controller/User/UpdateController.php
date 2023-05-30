<?php

declare(strict_types=1);

namespace RashinMe\Controller\User;

use RashinMe\Security\Permissions;
use RashinMe\Service\ErrorInterface;
use RashinMe\Service\Response\ResponseFactoryInterface;
use RashinMe\Service\User\Dto\UserData;
use RashinMe\Service\User\UserServiceInterface;
use RashinMe\Service\Validation\ValidationServiceInterface;
use RashinMe\View\ErrorView;
use RashinMe\View\UserView;
use Ser\DtoRequestBundle\Attributes\Dto;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

final class UpdateController
{
    public function __construct(
        private readonly ResponseFactoryInterface $responseFactory,
        private readonly UserServiceInterface $userService,
        private readonly AuthorizationCheckerInterface $security,
        private readonly ValidationServiceInterface $validationService,
    ) {
    }

    public function __invoke(int $id, #[Dto] UserData $userData): Response
    {
        if (!$this->security->isGranted(Permissions::ADMIN)) {
            return $this->responseFactory->forbidden("You can't have access to modify user data");
        }

        $user = $this->userService->getUserById($id);

        if ($user === null) {
            return $this->responseFactory->notFound("User not found");
        }

        $validationError = $this->validationService->validate($userData);

        if ($validationError !== null) {
            return $this->responseFactory->createResponse(
                ErrorView::create($validationError),
                400
            );
        }

        return $this->responseFactory->createResponse(UserView::create(
            $this->userService->updateUser($user, $userData)
        ));
    }
}
