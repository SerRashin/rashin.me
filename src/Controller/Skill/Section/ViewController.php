<?php

declare(strict_types=1);

namespace RashinMe\Controller\Skill\Section;

use RashinMe\Service\ErrorInterface;
use RashinMe\Service\Response\ResponseFactoryInterface;
use RashinMe\Service\Skill\SectionService;
use RashinMe\View\ErrorView;
use RashinMe\View\SectionView;
use Symfony\Component\HttpFoundation\Response;

final class ViewController
{
    public function __construct(
        private readonly ResponseFactoryInterface $responseFactory,
        private readonly SectionService $sectionService,
    ) {
    }

    public function __invoke(int $id): Response
    {
        $section = $this->sectionService->getSectionById($id);

        if ($section === null) {
            return $this->responseFactory->notFound("Section not found");
        }

        return $this->responseFactory->createResponse(SectionView::create($section));
    }
}
