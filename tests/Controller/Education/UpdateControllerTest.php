<?php

declare(strict_types=1);

namespace RashinMe\Controller\Education;

use DateTime;
use RashinMe\Entity\Education;
use RashinMe\FunctionalTestCase;
use Symfony\Component\HttpFoundation\Response;

class UpdateControllerTest extends FunctionalTestCase
{
    private const API_URL = '/api/educations/%s';

    protected function setUp(): void
    {
        parent::setUp();

        $this->registerAdmin('test@user.com');
    }

    public function testAccessForbidden(): void
    {
        $this->sendRequest('PATCH', sprintf(self::API_URL, 123));

        $this->assertStatusCodeEqualsTo(Response::HTTP_FORBIDDEN);
        $this->assertJsonEqualsData([
            'code' => Response::HTTP_FORBIDDEN,
            'message' => 'You\'re not allowed to modify educations',
        ]);
    }

    public function testEducationNotFound(): void
    {
        $this->login('test@user.com');

        $this->sendRequest('PATCH', sprintf(self::API_URL, 123));

        $this->assertStatusCodeEqualsTo(Response::HTTP_NOT_FOUND);
        $this->assertJsonEqualsData([
            'code' => Response::HTTP_NOT_FOUND,
            'message' => 'Education not found',
        ]);
    }

    public function testUpdateEducation(): void
    {
        $education = $this->createEducation();

        $requestData = [
            'institution' => 'changed insаtitution',
            'faculty' => 'changed faculty',
            'specialization' => 'changed specialization',
            'from' => [
                'month' => 1,
                'year' => 2001,
            ],
            'to' => [
                'month' => 1,
                'year' => 2002,
            ],
        ];

        $this->login('test@user.com');

        $this->sendRequest('PATCH', sprintf(self::API_URL, $education->getId()), $requestData);

        $this->assertStatusCodeEqualsTo(Response::HTTP_OK);
        $this->assertJsonContainsData($requestData);
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
