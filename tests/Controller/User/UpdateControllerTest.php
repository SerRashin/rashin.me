<?php

declare(strict_types=1);

namespace RashinMe\Controller\User;

use RashinMe\Entity\User;
use RashinMe\FunctionalTestCase;
use Symfony\Component\HttpFoundation\Response;

class UpdateControllerTest extends FunctionalTestCase
{
    private const API_URL = '/api/users/%s';

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
            'message' => 'You can\'t have access to modify user data',
        ]);
    }

    public function testUserNotFound(): void
    {
        $this->login('test@user.com');

        $this->sendRequest('PATCH', sprintf(self::API_URL, 123));

        $this->assertStatusCodeEqualsTo(Response::HTTP_NOT_FOUND);
        $this->assertJsonEqualsData([
            'code' => Response::HTTP_NOT_FOUND,
            'message' => 'User not found',
        ]);
    }

    public function testUpdateUser(): void
    {
        $section = $this->createUser();

        $requestData = [
            'firstName' => 'New Name',
            'lastName' => 'New LastName',
            'password' => 'new password',
        ];

        $this->login('test@user.com');

        $this->sendRequest('PATCH', sprintf(self::API_URL, $section->getId()), $requestData);

        unset($requestData['password']);
        $this->assertStatusCodeEqualsTo(Response::HTTP_OK);
        $this->assertJsonContainsData($requestData);
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
