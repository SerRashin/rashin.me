<?php

declare(strict_types=1);

namespace RashinMe\Service\Job\Filter;

class JobFilter
{
    public const JOBS_PER_PAGE = 10;

    public int $offset = 0;
    public int $limit;

    public function __construct(
        int $limit,
        int $offset = 0,
    ) {
        $this->limit = $limit;
        $this->offset = $offset;
    }
}
