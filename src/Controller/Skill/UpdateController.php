<?php

declare(strict_types=1);

namespace RashinMe\Controller\Skill;

use RashinMe\Security\Permissions;
use RashinMe\Service\ErrorInterface;
use RashinMe\Service\Response\ResponseFactoryInterface;
use RashinMe\Service\Skill\Dto\SkillData;
use RashinMe\Service\Skill\SkillService;
use RashinMe\View\ErrorView;
use RashinMe\View\SkillView;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

final class UpdateController
{
    public function __construct(
        private readonly ResponseFactoryInterface $responseFactory,
        private readonly AuthorizationCheckerInterface $security,
        private readonly SkillService $skillService,
    ) {
    }

    public function __invoke(int $id, SkillData $skillData): Response
    {
        if (!$this->security->isGranted(Permissions::ADMIN)) {
            return $this->responseFactory->forbidden("You're not allowed to modify skills");
        }

        $skill = $this->skillService->getSkillById($id);

        if ($skill === null) {
            return $this->responseFactory->notFound("Skill not found");
        }

        $result = $this->skillService->updateSkill($skillData, $skill);

        if ($result instanceof ErrorInterface) {
            return $this->responseFactory->createResponse(ErrorView::create($result), 400);
        }

        return $this->responseFactory->createResponse(SkillView::create($result));
    }
}
