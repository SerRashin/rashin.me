<?php

declare(strict_types=1);

namespace RashinMe\View\Job;

use DateTimeInterface;

class DateView
{
    /**
     * @param DateTimeInterface $dateTime
     *
     * @return int[]
     */
    public static function create(DateTimeInterface $dateTime): array
    {
        return [
            'month' => (int) $dateTime->format("m"),
            'year' => (int) $dateTime->format("Y"),
        ];
    }
}
