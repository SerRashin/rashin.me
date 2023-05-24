<?php

declare(strict_types=1);

namespace RashinMe\Service\Skill\Dto;

class SkillFilter
{
    public const SKILLS_PER_PAGE = 10;

    public int $offset = 0;
    public int $limit = 0;

    public function __construct(
        int $limit,
        int $offset = 0,
    ) {
        $this->limit = $limit;
        $this->offset = $offset;
    }
}
