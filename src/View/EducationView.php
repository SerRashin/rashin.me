<?php

declare(strict_types=1);

namespace RashinMe\View;

use RashinMe\Entity\Education;
use RashinMe\View\Job\DateView;

class EducationView
{
    /**
     * @param Education $education
     *
     * @return array<string, mixed>
     */
    public static function create(Education $education): array
    {
        return [
            'id' => $education->getId(),
            'institution' => $education->getInstitution(),
            'faculty' => $education->getFaculty(),
            'specialization' => $education->getSpecialization(),
            'from' => DateView::create($education->getFromDate()),
            'to' => $education->getToDate() ? DateView::create($education->getToDate()) : null,
        ];
    }
}
