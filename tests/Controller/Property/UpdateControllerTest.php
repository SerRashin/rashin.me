<?php

declare(strict_types=1);

namespace RashinMe\Controller\Property;

use RashinMe\FunctionalTestCase;
use Symfony\Component\HttpFoundation\Response;

class UpdateControllerTest extends FunctionalTestCase
{
    private const API_URL = '/api/properties';

    protected function setUp(): void
    {
        parent::setUp();

        $this->registerAdmin('test@user.com');
    }

    public function testAccessForbidden(): void
    {
        $this->sendRequest('PATCH', self::API_URL, []);

        $this->assertStatusCodeEqualsTo(Response::HTTP_FORBIDDEN);
        $this->assertJsonEqualsData([
            'code' => 403,
            'message' => 'You\'re not allowed to update configuration',
        ]);
    }

    public function testValidationErrors(): void
    {
        $this->login('test@user.com');
        $response = $this->sendRequest('PATCH', self::API_URL, []);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJsonEqualsData([]);
    }
}
