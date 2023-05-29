<?php

declare(strict_types=1);

namespace RashinMe\Controller\Education;

use DateTime;
use RashinMe\Entity\Education;
use RashinMe\FunctionalTestCase;
use Symfony\Component\HttpFoundation\Response;

class DeleteControllerTest extends FunctionalTestCase
{
    private const API_URL = '/api/educations/%s';

    protected function setUp(): void
    {
        parent::setUp();

        $this->registerAdmin('test@user.com');
    }

    public function testAccessForbidden(): void
    {
        $this->sendRequest('DELETE', sprintf(self::API_URL, 123));

        $this->assertStatusCodeEqualsTo(Response::HTTP_FORBIDDEN);
        $this->assertJsonEqualsData([
            'code' => Response::HTTP_FORBIDDEN,
            'message' => 'You\'re not allowed to delete educations',
        ]);
    }

    public function testEducationNotFound(): void
    {
        $this->login('test@user.com');

        $this->sendRequest('DELETE', sprintf(self::API_URL, 123));

        $this->assertStatusCodeEqualsTo(Response::HTTP_NOT_FOUND);
        $this->assertJsonEqualsData([
            'code' => Response::HTTP_NOT_FOUND,
            'message' => 'Education not found',
        ]);
    }

    public function testDeleteEducation(): void
    {
        $education = $this->createEducation();

        $this->login('test@user.com');

        $this->sendRequest('DELETE', sprintf(self::API_URL, $education->getId()));

        $this->assertStatusCodeEqualsTo(Response::HTTP_OK);
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
