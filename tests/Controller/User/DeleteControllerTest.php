<?php

declare(strict_types=1);

namespace RashinMe\Controller\User;

use RashinMe\Entity\User;
use RashinMe\FunctionalTestCase;
use Symfony\Component\HttpFoundation\Response;

class DeleteControllerTest extends FunctionalTestCase
{
    private const API_URL = '/api/users/%s';

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
            'message' => 'You\'re not allowed to delete users',
        ]);
    }

    public function testUserNotFound(): void
    {
        $this->login('test@user.com');

        $this->sendRequest('DELETE', sprintf(self::API_URL, 123));

        $this->assertStatusCodeEqualsTo(Response::HTTP_NOT_FOUND);
        $this->assertJsonEqualsData([
            'code' => Response::HTTP_NOT_FOUND,
            'message' => 'User not found',
        ]);
    }

    public function testDeleteUser(): void
    {
        $user = $this->createUser();

        $this->login('test@user.com');

        $this->sendRequest('DELETE', sprintf(self::API_URL, $user->getId()));

        $this->assertStatusCodeEqualsTo(Response::HTTP_OK);
    }

    private function createUser(): User
    {
        $user = new User(
            'name',
            'password',
            'fname',
            'lname',
        );

        $this->saveEntities($user);

        return $user;
    }
}
