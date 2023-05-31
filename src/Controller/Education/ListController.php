<?php

declare(strict_types=1);

namespace RashinMe\Controller\Education;

use RashinMe\Entity\Education;
use RashinMe\Service\Education\EducationService;
use RashinMe\Service\Education\Filter\EducationFilter;
use RashinMe\Service\Education\Filter\EducationSort;
use RashinMe\Service\Response\Dto\CollectionChunk;
use RashinMe\Service\Response\ResponseFactoryInterface;
use RashinMe\View\EducationView;
use RashinMe\View\PaginatedView;
use Symfony\Component\HttpFoundation\Response;

final class ListController
{
    public function __construct(
        private readonly ResponseFactoryInterface $responseFactory,
        private readonly EducationService $educationService,
    ) {
    }

    public function __invoke(EducationFilter $filter, EducationSort $sort): Response
    {
        $educations = $this->educationService->getEducations($filter, $sort);
        $count = $this->educationService->getCount($filter);

        $collection = new CollectionChunk(
            $filter->limit,
            $filter->offset,
            $count,
            $educations
        );

        return $this->responseFactory->createResponse(
            PaginatedView::create($collection, function (Education $education) {
                return EducationView::create($education);
            })
        );
    }
}
