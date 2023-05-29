<?php

declare(strict_types=1);

namespace RashinMe\Controller\User;

use RashinMe\Entity\User;
use RashinMe\FunctionalTestCase;
use RashinMe\Service\User\Model\UserInterface;
use Symfony\Component\HttpFoundation\Response;

class ViewControllerTest extends FunctionalTestCase
{
    private const API_URL = '/api/users/%s';

    protected function setUp(): void
    {
        parent::setUp();

        $this->registerUser('test@user.com');
        $this->registerAdmin('admin@user.com');
    }

    public function testUnauthorized(): void
    {
        $this->sendRequest('GET', sprintf(self::API_URL, 123));

        $this->assertStatusCodeEqualsTo(Response::HTTP_UNAUTHORIZED);
        $this->assertJsonEqualsData([
            'code' => Response::HTTP_UNAUTHORIZED,
            'message' => 'Full authentication is required to access this resource.',
        ]);
    }

    public function testUnauthorizedForbidden(): void
    {
        $this->login('test@user.com');
        $this->sendRequest('GET', sprintf(self::API_URL, 123));

        $this->assertStatusCodeEqualsTo(Response::HTTP_FORBIDDEN);
        $this->assertJsonEqualsData([
            'code' => Response::HTTP_FORBIDDEN,
            'message' => 'You cant have access to view user data',
        ]);
    }

    public function testForbiddenViewNotSelfProfile(): void
    {
        $this->login('test@user.com');
        $user = $this->createUser();

        $this->sendRequest('GET', sprintf(self::API_URL, $user->getId()));

        $this->assertStatusCodeEqualsTo(Response::HTTP_FORBIDDEN);
        $this->assertJsonEqualsData([
            'code' => Response::HTTP_FORBIDDEN,
            'message' => 'You cant have access to view user data',
        ]);
    }

    public function testUnauthorizedCurrentUserViewIfUserNotLoggedIn(): void
    {
        $this->sendRequest('GET', '/api/user');

        $this->assertStatusCodeEqualsTo(Response::HTTP_UNAUTHORIZED);
        $this->assertJsonEqualsData([
            'code' => Response::HTTP_UNAUTHORIZED,
            'message' => 'Full authentication is required to access this resource.',
        ]);
    }

    public function testUserNotFound(): void
    {
        $this->login('admin@user.com');
        $this->sendRequest('GET', sprintf(self::API_URL, 123));

        $this->assertStatusCodeEqualsTo(Response::HTTP_NOT_FOUND);
        $this->assertJsonEqualsData([
            'code' => Response::HTTP_NOT_FOUND,
            'message' => 'User not found',
        ]);
    }

    public function testViewCurrentAuthorizedUser(): void
    {
        $currentUser = $this->login('test@user.com');

        $this->sendRequest('GET', '/api/user');

        $this->assertStatusCodeEqualsTo(200);
        $this->assertInstanceOf(UserInterface::class, $currentUser);
        $this->assertJsonEqualsData([
            'id' => $currentUser->getId(),
            'firstName' => $currentUser->getFirstName(),
            'lastName' => $currentUser->getLastName(),
            'email' => $currentUser->getEmail(),
        ]);
    }

    public function testUserView(): void
    {
        $this->login('admin@user.com');
        $user = $this->createUser();

        $this->sendRequest('GET', sprintf(self::API_URL, $user->getId()));

        $this->assertStatusCodeEqualsTo(200);
        $this->assertJsonEqualsData([
            'id' => $user->getId(),
            'firstName' => $user->getFirstName(),
            'lastName' => $user->getLastName(),
            'email' => $user->getEmail(),
        ]);
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
