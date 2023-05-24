<?php

declare(strict_types=1);

namespace RashinMe\Controller\Resume;

use RashinMe\Service\Resume\GenerateService;

class GenerateController
{
    public function __construct(
        private readonly GenerateService $projectService,
    ) {
    }

    public function __invoke()
    {
        $this->projectService->execute();

    }
}
