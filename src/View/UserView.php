<?php

declare(strict_types=1);

namespace RashinMe\View;

use RashinMe\Service\User\Model\UserInterface;

class UserView
{
    /**
     * @param UserInterface $user
     *
     * @return array<string, mixed>
     */
    public static function create(UserInterface $user): array
    {
        return [
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'firstName' => $user->getFirstName(),
            'lastName' => $user->getLastName(),
        ];
    }
}
