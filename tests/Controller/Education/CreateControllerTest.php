<?php

declare(strict_types=1);

namespace RashinMe\Controller\Education;

use RashinMe\FunctionalTestCase;
use Symfony\Component\HttpFoundation\Response;

class CreateControllerTest extends FunctionalTestCase
{
    private const API_URL = '/api/educations';

    protected function setUp(): void
    {
        parent::setUp();

        $this->registerAdmin('test@user.com');
    }

    public function testAccessForbidden(): void
    {
        $this->sendRequest('POST', self::API_URL, []);

        $this->assertStatusCodeEqualsTo(Response::HTTP_FORBIDDEN);
        $this->assertJsonEqualsData([
            'code' => 403,
            'message' => 'You\'re not allowed to create educations',
        ]);
    }

    public function testValidationErrors(): void
    {
        $this->login('test@user.com');
        $this->sendRequest('POST', self::API_URL, []);

        $this->assertStatusCodeEqualsTo(Response::HTTP_BAD_REQUEST);
        $this->assertJsonEqualsData([
            'message' => 'Validation Error',
            'details' => [
                'from.month' => 'This value should not be blank.',
                'from.year' => 'This value should not be blank.',
                'institution' => 'This value should not be blank.',
                'faculty' => 'This value should not be blank.',
                'specialization' => 'This value should not be blank.',
            ]
        ]);
    }

    public function testCreateEducation(): void
    {
        $requestData = [
            'institution' => 'institution',
            'faculty' => 'faculty',
            'specialization' => 'specialization',
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

        $this->sendRequest('POST', self::API_URL, $requestData);

        $this->assertStatusCodeEqualsTo(Response::HTTP_OK);
        $this->assertJsonContainsData($requestData);
    }
}
