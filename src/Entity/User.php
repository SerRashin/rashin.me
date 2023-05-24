<?php

declare(strict_types=1);

namespace RashinMe\Entity;

use RashinMe\Service\User\Model\UserInterface;

/**
 * @inheritDoc
 */
class User implements UserInterface
{
    /**
     * @var int
     */
    private int $id;

    /**
     * @var string[]
     */
    private array $roles = [];

    /**
     * Constructor.
     *
     * @param string $email    User email address
     * @param string $password User password
     */
    public function __construct(
        private string $email,
        private string $password,
        private string $firstName,
        private string $lastName,
    ) {
        $this->id = 0;
    }

    /**
     * @inheritDoc
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @inheritDoc
     */
    public function changeEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @inheritDoc
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @inheritDoc
     */
    public function changePassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * @inheritDoc
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @inheritDoc
     */
    public function changeFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    /**
     * @inheritDoc
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @inheritDoc
     */
    public function changeLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    /**
     * @inheritDoc
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    /**
     * @inheritDoc
     */
    public function eraseCredentials(): void
    {
    }

    /**
     * @inheritDoc
     */
    public function getUserIdentifier(): string
    {
        return $this->getEmail();
    }
}
