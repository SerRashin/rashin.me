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
use Ser\DtoRequestBundle\Attributes\Dto;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

final class CreateController
{
    public function __construct(
        private readonly ResponseFactoryInterface $responseFactory,
        private readonly AuthorizationCheckerInterface $security,
        private readonly SkillService $skillService,
    ) {
    }

    public function __invoke(#[Dto] SkillData $skillData): Response
    {
        if (!$this->security->isGranted(Permissions::ADMIN)) {
            return $this->responseFactory->forbidden('You\'re not allowed to create skills');
        }

        $result = $this->skillService->addSkill($skillData);

        if ($result instanceof ErrorInterface) {
            return $this->responseFactory->createResponse(ErrorView::create($result), 400);
        }

        return $this->responseFactory->createResponse(SkillView::create($result));
    }
}
