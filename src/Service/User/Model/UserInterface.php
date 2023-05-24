<?php

declare(strict_types=1);

namespace RashinMe\Service\User\Model;

use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface as SymfonyUserInterface;

/**
 * User model interface
 */
interface UserInterface extends SymfonyUserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * Get user id
     *
     * User ID must be int or UUID.
     *
     * @return int|string|null
     */
    public function getId(): int|string|null;

    /**
     * Get user email
     *
     * @return string
     */
    public function getEmail(): string;

    /**
     * Change user email
     *
     * @param string $email
     *
     * @return void
     */
    public function changeEmail(string $email): void;

    /**
     * Get user password
     *
     * @return string
     */
    public function getPassword(): string;

    /**
     * Change user password
     *
     * @return void
     */
    public function changePassword(string $password): void;

    /**
     * Get user first name
     *
     * @return string
     */
    public function getFirstName(): string;

    /**
     * Change user first name
     *
     * @param string $firstName
     *
     * @return void
     */
    public function changeFirstName(string $firstName): void;

    /**
     * Get user last name
     *
     * @return string
     */
    public function getLastName(): string;

    /**
     * Change user last name
     *
     * @param string $lastName
     *
     * @return void
     */
    public function changeLastName(string $lastName): void;
}
