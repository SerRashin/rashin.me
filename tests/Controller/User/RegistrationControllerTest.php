<?php

declare(strict_types=1);

namespace RashinMe\Controller\User;

use RashinMe\FunctionalTestCase;
use Symfony\Component\HttpFoundation\Response;

class RegistrationControllerTest extends FunctionalTestCase
{
    private const API_URL = '/api/users';

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function testValidationErrors(): void
    {
        $this->sendRequest('POST', self::API_URL, []);

        $this->assertStatusCodeEqualsTo(Response::HTTP_BAD_REQUEST);
        $this->assertJsonEqualsData([
            'message' => 'Validation Error',
            'details' => [
                'firstName' => 'This value should not be blank.',
                'lastName' => 'This value should not be blank.',
                'email' => 'This value should not be blank.',
                'password' => 'This value should not be blank.',
            ]
        ]);
    }

    public function testRegistration(): void
    {
        $requestData = [
            'firstName' => 'Name',
            'lastName' => 'LastName',
            'email' => 'test@user.com',
            'password' => '123ewe14rewa',
        ];

        $this->sendRequest('POST', self::API_URL, $requestData);

        unset($requestData['password']);

        $this->assertStatusCodeEqualsTo(Response::HTTP_CREATED);
        $this->assertJsonContainsData($requestData);
    }
}
