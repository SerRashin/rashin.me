<?php

declare(strict_types=1);

namespace RashinMe\Service\User\Repository;

use Doctrine\Common\Collections\Collection;
use RashinMe\Service\User\Filter\UserFilter;
use RashinMe\Service\User\Filter\UserSort;
use RashinMe\Service\User\Model\UserInterface;

/**
 * User repository interface
 */
interface UserRepositoryInterface
{
    /**
     * Save user
     *
     * @param UserInterface $user
     *
     * @return void
     */
    public function save(UserInterface $user): void;

    /**
     * Delete user
     *
     * @param UserInterface $user
     *
     * @return void
     */
    public function delete(UserInterface $user): void;

    /**
     * Find user by email
     *
     * @param string $email
     *
     * @return UserInterface|null
     */
    public function findOneByEmail(string $email): ?UserInterface;

    /**
     * Find user by id
     *
     * @param int $id
     *
     * @return UserInterface|null
     */
    public function findOneById(int $id): ?UserInterface;

    /**
     * Get collection of users
     *
     * @param UserFilter $filter
     * @param UserSort $sort
     *
     * @return Collection<int, UserInterface>
     */
    public function getUsers(UserFilter $filter, UserSort $sort): Collection;

    /**
     * Get count of users
     *
     * @param UserFilter $filter
     *
     * @return int
     */
    public function getCount(UserFilter $filter): int;
}
