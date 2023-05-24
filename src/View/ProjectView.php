<?php

declare(strict_types=1);

namespace RashinMe\View;

use RashinMe\Entity\Project;

class ProjectView
{
    /**
     * @param Project $project
     *
     * @return array<string, mixed>
     */
    public static function create(Project $project): array
    {
        return [
            'id' => $project->getId(),
            'name' => $project->getName(),
            'image' => ImageView::create($project->getImage()),
            'description' => $project->getDescription(),
            'links' => LinksView::create($project->getLinks()),
            'tags' => TagsView::create($project->getTags()),
        ];
    }
}
