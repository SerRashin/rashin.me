<?php

declare(strict_types=1);

namespace RashinMe\View;

use RashinMe\Entity\Job;
use RashinMe\View\Job\CompanyView;
use RashinMe\View\Job\DateView;

class JobView
{
    /**
     * @param Job $job
     *
     * @return array<string, mixed>
     */
    public static function create(Job $job): array
    {
        return [
            'id' => $job->getId(),
            'name' => $job->getName(),
            'type' => $job->getType(),
            'description' => $job->getDescription(),
            'company' => CompanyView::create($job->getCompany()),
            'from' => DateView::create($job->getFromDate()),
            'to' => $job->getToDate() ? DateView::create($job->getToDate()) : null,
        ];
    }
}
