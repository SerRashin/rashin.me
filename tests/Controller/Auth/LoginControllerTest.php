<?php

declare(strict_types=1);

namespace RashinMe\Controller\Auth;

use RashinMe\FunctionalTestCase;

class LoginControllerTest extends FunctionalTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    public function testLoginWithInvalidCredentials(): void
    {
        $this->sendRequest('POST', '/api/login', [
            'email' => 'test@user.com',
            'password' => 'invalid',
        ]);

        $this->assertJsonEqualsData([
            'error' => 'Invalid credentials.',
        ]);
    }

    public function testLogin(): void
    {
        $this->registerUser('test@user.com', 'pwd');

        $this->sendRequest('POST', '/api/login', [
            'email' => 'test@user.com',
            'password' => 'pwd',
        ]);

        $this->assertJsonEqualsData([
            'code' => 200,
            'message' => 'success login'
        ]);
    }
}
