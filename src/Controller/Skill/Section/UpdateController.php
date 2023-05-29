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

final class UpdateController
{
    public function __construct(
        private readonly ResponseFactoryInterface $responseFactory,
        private readonly AuthorizationCheckerInterface $security,
        private readonly SectionService $sectionService,
    ) {
    }

    public function __invoke(int $id, #[Dto] SectionData $sectionData): Response
    {
        if (!$this->security->isGranted(Permissions::ADMIN)) {
            return $this->responseFactory->forbidden("You're not allowed to modify sections");
        }

        $section = $this->sectionService->getSectionById($id);

        if ($section === null) {
            return $this->responseFactory->notFound("Section not found");
        }

        $result = $this->sectionService->updateSection($sectionData, $section);

        if ($result instanceof ErrorInterface) {
            return $this->responseFactory->createResponse(ErrorView::create($result), 400);
        }

        return $this->responseFactory->createResponse(SectionView::create($result));
    }
}
