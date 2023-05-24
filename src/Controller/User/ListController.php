<?php

declare(strict_types=1);

namespace RashinMe\Controller\User;

use RashinMe\Security\Permissions;
use RashinMe\Service\Response\Dto\CollectionChunk;
use RashinMe\Service\Response\ResponseFactoryInterface;
use RashinMe\Service\User\Dto\UserFilter;
use RashinMe\Service\User\Model\UserInterface;
use RashinMe\Service\User\UserServiceInterface;
use RashinMe\View\PaginatedView;
use RashinMe\View\UserView;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class ListController
{
    public function __construct(
        private readonly UserServiceInterface $userService,
        private readonly AuthorizationCheckerInterface $security,
        private readonly ResponseFactoryInterface $responseFactory,
    ) {
    }

    public function __invoke(UserFilter $filter): Response
    {
        if (!$this->security->isGranted(Permissions::ADMIN)) {
            return $this->responseFactory->forbidden("You're not allowed to delete users");
        }

        $projects = $this->userService->getUsers($filter);
        $count = $this->userService->getCount($filter);

        $collection = new CollectionChunk(
            $filter->limit,
            $filter->offset,
            $count,
            $projects,
        );

        return $this->responseFactory->createResponse(
            PaginatedView::create($collection, function (UserInterface $project) {
                return UserView::create($project);
            })
        );
    }
}
