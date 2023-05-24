<?php

declare(strict_types=1);

namespace RashinMe\Controller\Skill;

use RashinMe\Entity\Skill;
use RashinMe\Service\Response\Dto\CollectionChunk;
use RashinMe\Service\Response\ResponseFactoryInterface;
use RashinMe\Service\Skill\Dto\SkillFilter;
use RashinMe\Service\Skill\SkillService;
use RashinMe\View\PaginatedView;
use RashinMe\View\SkillView;
use Symfony\Component\HttpFoundation\Response;

final class ListController
{
    public function __construct(
        private readonly ResponseFactoryInterface $responseFactory,
        private readonly SkillService $skillService,
    ) {
    }

    public function __invoke(SkillFilter $filter): Response
    {
        $skills = $this->skillService->getSkills($filter);
        $count = $this->skillService->getCount($filter);

        $collection = new CollectionChunk(
            $filter->limit,
            $filter->offset,
            $count,
            $skills
        );

        return $this->responseFactory->createResponse(
            PaginatedView::create($collection, fn (Skill $skill) => SkillView::create($skill))
        );
    }
}
