<?php

declare(strict_types=1);

namespace RashinMe\Service\User;

use Doctrine\Common\Collections\Collection;
use RashinMe\Service\ErrorInterface;
use RashinMe\Service\User\Dto\UserData;
use RashinMe\Service\User\Dto\UserFilter;
use RashinMe\Service\User\Model\UserInterface;

/**
 * User interface
 */
interface UserServiceInterface
{
    /**
     * Update user
     *
     * @param UserInterface $user
     * @param UserData $userData
     *
     * @return UserInterface
     */
    public function updateUser(UserInterface $user, UserData $userData): UserInterface;

    /**
     * Delete user
     *
     * @param UserInterface $user
     *
     * @return void
     */
    public function deleteUser(UserInterface $user): void;

    /**
     * Get user by user id
     *
     * @param int $id user id
     *
     * @return UserInterface|null
     */
    public function getUserById(int $id): ?UserInterface;


    /**
     * Get list of users
     *
     * @param UserFilter $filter
     *
     * @return Collection<int, UserInterface>
     */
    public function getUsers(UserFilter $filter): Collection;

    /**
     * Get count of users
     *
     * @param UserFilter $filter
     *
     * @return int
     */
    public function getCount(UserFilter $filter): int;
}
