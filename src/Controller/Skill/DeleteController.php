<?php

declare(strict_types=1);

namespace RashinMe\Controller\Skill;

use RashinMe\Security\Permissions;
use RashinMe\Service\Response\ResponseFactoryInterface;
use RashinMe\Service\Skill\SkillService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

final class DeleteController
{
    public function __construct(
        private readonly ResponseFactoryInterface $responseFactory,
        private readonly AuthorizationCheckerInterface $security,
        private readonly SkillService $skillService,
    ) {
    }

    public function __invoke(int $id): Response
    {
        if (!$this->security->isGranted(Permissions::ADMIN)) {
            return $this->responseFactory->forbidden("You're not allowed to delete skills");
        }

        $skill = $this->skillService->getSkillById($id);

        if ($skill === null) {
            return $this->responseFactory->notFound("Skill not found");
        }

        $this->skillService->deleteSkill($skill);

        return $this->responseFactory->createResponse();
    }
}
