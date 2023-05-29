<?php

declare(strict_types=1);

namespace RashinMe\Controller\Property;

use RashinMe\Security\Permissions;
use RashinMe\Service\Property\PropertyService;
use RashinMe\Service\Property\Dto\PropertiesData;
use RashinMe\Service\ErrorInterface;
use RashinMe\Service\Response\ResponseFactoryInterface;
use RashinMe\View\PropertiesView;
use RashinMe\View\ErrorView;
use Ser\DtoRequestBundle\Attributes\Dto;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class UpdateController
{
    public function __construct(
        private readonly ResponseFactoryInterface $responseFactory,
        private readonly AuthorizationCheckerInterface $security,
        private readonly PropertyService $propertyService,
    ) {
    }

    public function __invoke(#[Dto] PropertiesData $propertiesData): Response
    {
        if (!$this->security->isGranted(Permissions::ADMIN)) {
            return $this->responseFactory->forbidden("You're not allowed to update configuration");
        }

        $result = $this->propertyService->updateProperties($propertiesData);

        if ($result instanceof ErrorInterface) {
            return $this->responseFactory->createResponse(ErrorView::create($result), 400);
        }

        return $this->responseFactory->createResponse(PropertiesView::create($result));
    }
}
