<?php

declare(strict_types=1);

namespace RashinMe\Repository;

use RashinMe\Entity\User;
use RashinMe\FunctionalTestCase;
use RashinMe\Service\User\Repository\UserRepositoryInterface;

class UserRepositoryTest extends FunctionalTestCase
{
    private UserRepositoryInterface $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = new UserRepository($this->getEntityManager());
    }

    public function testSaveUser(): void
    {
        $expectedEmail = 'test@user.com';
        $user = new User(
            $expectedEmail,
            'pwd',
            'first name',
            'last name',
        );

        $this->repository->save($user);

        $savedUser = $this->repository->findOneByEmail($expectedEmail);

        $this->assertEquals($user, $savedUser);
    }
}
