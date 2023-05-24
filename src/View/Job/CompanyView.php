<?php

declare(strict_types=1);

namespace RashinMe\View\Job;

use RashinMe\Entity\Company;

class CompanyView
{
    /**
     * @param Company $company
     * @return string[]
     */
    public static function create(Company $company): array
    {
        return [
            'name' => $company->getName(),
            'url' => $company->getUrl(),
        ];
    }
}
