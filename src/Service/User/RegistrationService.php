<?php

declare(strict_types=1);

namespace RashinMe\Service\User;

use RashinMe\Entity\User;
use RashinMe\Service\Error;
use RashinMe\Service\ErrorInterface;
use RashinMe\Service\User\Dto\RegistrationData;
use RashinMe\Service\User\Model\UserInterface;
use RashinMe\Service\User\Repository\UserRepositoryInterface;
use RashinMe\Service\Validation\ValidationServiceInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class RegistrationService
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly UserPasswordHasherInterface $userPasswordHasher,
        private readonly ValidationServiceInterface $validationService,
    ) {
    }

    /**
     * Register user
     *
     * @param RegistrationData $registrationData
     *
     * @return UserInterface|ErrorInterface
     */
    public function register(RegistrationData $registrationData): UserInterface|ErrorInterface
    {
        $existsUser = $this->userRepository->findOneByEmail($registrationData->email);

        if ($existsUser !== null) {
            return new Error('Email address already exists.');
        }

        $user = new User(
            $registrationData->email,
            $registrationData->password,
            $registrationData->firstName,
            $registrationData->lastName,
        );

        $hashedPassword = $this->userPasswordHasher->hashPassword(
            $user,
            $registrationData->password
        );

        $user->changePassword($hashedPassword);

        $this->userRepository->save($user);

        return $user;
    }
}
