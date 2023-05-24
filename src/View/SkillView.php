<?php

declare(strict_types=1);

namespace RashinMe\View;

use RashinMe\Entity\Skill;

class SkillView
{
    /**
     * @param Skill $skill
     *
     * @return array<string, mixed>
     */
    public static function create(Skill $skill): array
    {
        return [
            'id' => $skill->getId(),
            'name' => $skill->getName(),
            'sectionId' => $skill->getSection()->getId(),
            'image' => ImageView::create($skill->getImage()),
            'description' => $skill->getDescription(),
        ];
    }
}
