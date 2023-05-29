<?php

declare(strict_types=1);

namespace RashinMe\Controller\Skill\Section;

use RashinMe\Security\Permissions;
use RashinMe\Service\ErrorInterface;
use RashinMe\Service\Response\ResponseFactoryInterface;
use RashinMe\Service\Skill\Dto\SectionData;
use RashinMe\Service\Skill\SectionService;
use RashinMe\View\ErrorView;
use RashinMe\View\SectionView;
use Ser\DtoRequestBundle\Attributes\Dto;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

final class CreateController
{
    public function __construct(
        private readonly ResponseFactoryInterface $responseFactory,
        private readonly AuthorizationCheckerInterface $security,
        private readonly SectionService $sectionService,
    ) {
    }

    public function __invoke(#[Dto] SectionData $sectionData): Response
    {
        if (!$this->security->isGranted(Permissions::ADMIN)) {
            return $this->responseFactory->forbidden("You're not allowed to create skill sections");
        }

        $result = $this->sectionService->addSection($sectionData);

        if ($result instanceof ErrorInterface) {
            return $this->responseFactory->createResponse(ErrorView::create($result), 400);
        }

        return $this->responseFactory->createResponse(SectionView::create($result));
    }
}
