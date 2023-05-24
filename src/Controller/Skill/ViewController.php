<?php

declare(strict_types=1);

namespace RashinMe\Controller\Skill;

use RashinMe\Service\Response\ResponseFactoryInterface;
use RashinMe\Service\Skill\SkillService;
use RashinMe\View\SkillView;
use Symfony\Component\HttpFoundation\Response;

final class ViewController
{
    public function __construct(
        private readonly ResponseFactoryInterface $responseFactory,
        private readonly SkillService $skillService,
    ) {
    }

    public function __invoke(int $id): Response
    {
        $skill = $this->skillService->getSkillById($id);

        if ($skill === null) {
            return $this->responseFactory->notFound("Skill not found");
        }

        return $this->responseFactory->createResponse(SkillView::create($skill));
    }
}
