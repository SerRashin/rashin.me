<?php

declare(strict_types=1);

namespace RashinMe\Controller\Property;

use RashinMe\Service\Property\PropertyService;
use RashinMe\Service\Response\ResponseFactoryInterface;
use RashinMe\View\PropertiesView;
use Symfony\Component\HttpFoundation\Response;

class ViewController
{
    public function __construct(
        private readonly ResponseFactoryInterface $responseFactory,
        private readonly PropertyService $propertyService,
    ) {
    }

    public function __invoke(string $name): Response
    {
        $configuration = $this->propertyService->getProperty($name);

        return $this->responseFactory->createResponse(PropertiesView::create($configuration));
    }
}
