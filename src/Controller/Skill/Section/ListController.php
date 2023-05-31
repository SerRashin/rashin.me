<?php

declare(strict_types=1);

namespace RashinMe\Controller\Skill\Section;

use RashinMe\Entity\Section;
use RashinMe\Service\Response\Dto\CollectionChunk;
use RashinMe\Service\Response\ResponseFactoryInterface;
use RashinMe\Service\Skill\Filter\SectionFilter;
use RashinMe\Service\Skill\Filter\SectionSort;
use RashinMe\Service\Skill\SectionService;
use RashinMe\View\PaginatedView;
use RashinMe\View\SectionView;
use Symfony\Component\HttpFoundation\Response;

final class ListController
{
    public function __construct(
        private readonly ResponseFactoryInterface $responseFactory,
        private readonly SectionService $sectionService,
    ) {
    }

    public function __invoke(SectionFilter $filter, SectionSort $sort): Response
    {
        $skills = $this->sectionService->getSections($filter, $sort);
        $count = $this->sectionService->getCount($filter);

        $collection = new CollectionChunk(
            $filter->limit,
            $filter->offset,
            $count,
            $skills
        );

        return $this->responseFactory->createResponse(
            PaginatedView::create($collection, fn (Section $section) => SectionView::create($section))
        );
    }
}
