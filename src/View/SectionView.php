<?php

declare(strict_types=1);

namespace RashinMe\View;

use RashinMe\Entity\Section;

class SectionView
{
    /**
     * Section
     *
     * @param Section $section
     *
     * @return array<string, mixed>
     */
    public static function create(Section $section): array
    {
        $skillViews = [];

        foreach ($section->getSkills() as $skill) {
            $skillViews[] = SkillView::create($skill);
        }

        return [
            'id' => $section->getId(),
            'name' => $section->getName(),
            'skills' => $skillViews,
        ];
    }
}
