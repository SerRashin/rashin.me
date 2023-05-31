<?php

declare(strict_types=1);

namespace RashinMe\Controller\Property;

use RashinMe\Service\Property\Filter\PropertyFilter;
use RashinMe\Service\Property\PropertyService;
use RashinMe\Service\Response\ResponseFactoryInterface;
use RashinMe\View\PropertiesView;
use Symfony\Component\HttpFoundation\Response;

class ListController
{
    public function __construct(
        private readonly ResponseFactoryInterface $responseFactory,
        private readonly PropertyService $propertyService,
    ) {
    }

    public function __invoke(PropertyFilter $propertyFilter): Response
    {
        $properties = $this->propertyService->getProperties($propertyFilter);

        return $this->responseFactory->createResponse(PropertiesView::create($properties));
    }
}
