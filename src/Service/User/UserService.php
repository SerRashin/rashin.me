<?php

declare(strict_types=1);

namespace RashinMe\Service\User;

use Doctrine\Common\Collections\Collection;
use RashinMe\Service\ErrorInterface;
use RashinMe\Service\User\Dto\UserData;
use RashinMe\Service\User\Dto\UserFilter;
use RashinMe\Service\User\Model\UserInterface;
use RashinMe\Service\User\Repository\UserRepositoryInterface;
use RashinMe\Service\Validation\ValidationServiceInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserService implements UserServiceInterface
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly ValidationServiceInterface $validationService,
        private readonly UserPasswordHasherInterface $userPasswordHasher,
    ) {
    }

    public function updateUser(UserInterface $user, UserData $userData): UserInterface|ErrorInterface
    {
        $validationError = $this->validationService->validate($userData);

        if ($validationError !== null) {
            return $validationError;
        }

        $user->changeFirstName($userData->firstName);
        $user->changeLastName($userData->lastName);

        $hashedPassword = $this->userPasswordHasher->hashPassword(
            $user,
            $userData->password
        );

        $user->changePassword($hashedPassword);

        $this->userRepository->save($user);

        return $user;
    }

    public function deleteUser(UserInterface $user): void
    {
        $this->userRepository->delete($user);
    }

    public function getUserById(int $id): ?UserInterface
    {
        return $this->userRepository->findOneById($id);
    }

    /**
     * @inheritDoc
     */
    public function getUsers(UserFilter $filter): Collection
    {
        return $this->userRepository->getUsers($filter);
    }

    public function getCount(UserFilter $filter): int
    {
        return $this->userRepository->getCount($filter);
    }
}
