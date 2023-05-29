<?php

declare(strict_types=1);

namespace RashinMe\Controller\Skill\Section;

use RashinMe\FunctionalTestCase;
use Symfony\Component\HttpFoundation\Response;

class CreateControllerTest extends FunctionalTestCase
{
    private const API_URL = '/api/sections';

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
            'message' => 'You\'re not allowed to create skill sections',
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
                'name' => 'This value should not be blank.',
            ]
        ]);
    }

    public function testCreateSkillSection(): void
    {
        $requestData = [
            'name' => 'Job name',
        ];

        $this->login('test@user.com');

        $this->sendRequest('POST', self::API_URL, $requestData);

        $this->assertStatusCodeEqualsTo(Response::HTTP_OK);
        $this->assertJsonContainsData($requestData);
    }
}
