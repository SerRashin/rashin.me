<?php

declare(strict_types=1);

namespace RashinMe\Controller\Job;

use RashinMe\FunctionalTestCase;
use Symfony\Component\HttpFoundation\Response;

class CreateControllerTest extends FunctionalTestCase
{
    private const API_URL = '/api/jobs';

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
            'message' => 'You\'re not allowed to create jobs',
        ]);
    }

    public function testValidationErrors(): void
    {
        $requestData = [];

        $this->login('test@user.com');
        $this->sendRequest('POST', self::API_URL, $requestData);

        $this->assertStatusCodeEqualsTo(Response::HTTP_BAD_REQUEST);
        $this->assertJsonEqualsData([
            'message' => 'Validation Error',
            'details' => [
                'company.name' => 'This value should not be blank.',
                'company.url' => 'This value should not be blank.',
                'name' => 'This value should not be blank.',
                'description' => 'This value should not be blank.',
                'type' => 'This value should not be blank.',
                'from.month' => 'This value should not be blank.',
                'from.year' => 'This value should not be blank.',
            ],
        ]);
    }

    public function testCreateJob(): void
    {
        $requestData = [
            'name' => 'Job name',
            'company' => [
                'name' => 'some company',
                'url' => 'https://site.company.com'
            ],
            'description' => 'some long description of job',
            'type' => 'type-name',
            'from' => [
                'month' => 11,
                'year' => 2011,
            ]
        ];

        $this->login('test@user.com');

        $this->sendRequest('POST', self::API_URL, $requestData);

        $this->assertStatusCodeEqualsTo(Response::HTTP_OK);
        $this->assertJsonContainsData($requestData);
    }
}
