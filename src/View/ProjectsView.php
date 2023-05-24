<?php

declare(strict_types=1);

namespace RashinMe\View;

use RashinMe\Entity\Project;

class ProjectsView
{

    /**
     * @param Project[] $projects
     *
     * @return array<int, mixed>
     */
    public static function create(array $projects): array
    {
        $arr = [];

        foreach ($projects as $project) {
            $arr[] = ProjectView::create($project);
        }

        return $arr;
    }
}
