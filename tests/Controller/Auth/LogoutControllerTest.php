<?php

declare(strict_types=1);

namespace RashinMe\Controller\Auth;

use RashinMe\FunctionalTestCase;

class LogoutControllerTest extends FunctionalTestCase
{
    private const API_URL = '/api/logout';

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function testLogoutWhenNotLoggedIn(): void
    {
        $this->sendRequest('GET', self::API_URL);

        $this->assertJsonEqualsData([
            'code' => 400,
            'message' => 'Unable to logout as there is no logged-in user.'
        ]);
    }

    public function testLogin(): void
    {
        $this->registerUser('test@user.com');
        $this->login('test@user.com');

        $response = $this->sendRequest('GET', self::API_URL);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJsonEqualsData([]);
    }
}
