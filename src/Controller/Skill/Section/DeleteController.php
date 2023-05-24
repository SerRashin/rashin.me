<?php

declare(strict_types=1);

namespace RashinMe\Controller\Skill\Section;

use RashinMe\Security\Permissions;
use RashinMe\Service\Response\ResponseFactoryInterface;
use RashinMe\Service\Skill\SectionService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

final class DeleteController
{
    public function __construct(
        private readonly ResponseFactoryInterface $responseFactory,
        private readonly AuthorizationCheckerInterface $security,
        private readonly SectionService $sectionService,
    ) {
    }

    public function __invoke(int $id): Response
    {
        if (!$this->security->isGranted(Permissions::ADMIN)) {
            return $this->responseFactory->forbidden("You're not allowed to delete sections");
        }

        $skill = $this->sectionService->getSectionById($id);

        if ($skill === null) {
            return $this->responseFactory->notFound("Section not found");
        }

        $this->sectionService->deleteSection($skill);

        return $this->responseFactory->createResponse();
    }
}
