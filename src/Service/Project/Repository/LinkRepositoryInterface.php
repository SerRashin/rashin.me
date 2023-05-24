<?php

declare(strict_types=1);

namespace RashinMe\Service\Project\Repository;

use RashinMe\Entity\Link;
use RashinMe\Entity\Project;

interface LinkRepositoryInterface
{
    /**
     * Find project links
     *
     * @param Project $project
     *
     * @return Link[]
     */
    public function findLinksByProject(Project $project): array;
}
