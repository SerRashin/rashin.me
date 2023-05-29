<?php

declare(strict_types=1);

namespace RashinMe\Controller\Education;

use DateTime;
use RashinMe\Entity\Education;
use RashinMe\FunctionalTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\Date;

class ViewControllerTest extends FunctionalTestCase
{
    private const API_URL = '/api/educations/%s';

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function testEducationNotFound(): void
    {
        $this->sendRequest('GET', sprintf(self::API_URL, 123));

        $this->assertStatusCodeEqualsTo(Response::HTTP_NOT_FOUND);
        $this->assertJsonEqualsData([
            'code' => Response::HTTP_NOT_FOUND,
            'message' => 'Education not found',
        ]);
    }

    public function testEducationView(): void
    {
        $education = $this->createEducation();

        $this->sendRequest('GET', sprintf(self::API_URL, $education->getId()));

        $this->assertStatusCodeEqualsTo(200);
        $this->assertJsonEqualsData([
            'id' => $education->getId(),
            'institution' => $education->getInstitution(),
            'faculty' => $education->getFaculty(),
            'specialization' => $education->getSpecialization(),
            'from' => [
                'month' => $education->getFromDate()->format('m'),
                'year' => $education->getFromDate()->format('Y'),
            ],
            'to' => [
                'month' => $education->getToDate()?->format('m'),
                'year' => $education->getToDate()?->format('Y'),
            ],
        ]);
    }

    private function createEducation(): Education
    {
        $education = new Education(
            'institution',
            'faculty',
            'specialization',
            new DateTime('now'),
            new DateTime('now'),
        );

        $this->saveEntities($education);

        return $education;
    }
}
