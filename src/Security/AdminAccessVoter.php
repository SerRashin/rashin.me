<?php

declare(strict_types=1);

namespace RashinMe\Security;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

class AdminAccessVoter implements VoterInterface
{
    /**
     * Vote
     *
     * @param TokenInterface $token
     * @param mixed $subject
     * @param string[] $attributes
     *
     * @return int
     */
    public function vote(TokenInterface $token, mixed $subject, array $attributes): int
    {
        $vote = self::ACCESS_ABSTAIN;
        $user = $token->getUser();

        foreach ($attributes as $attribute) {
            if ($attribute !== Permissions::ADMIN) {
                continue;
            }

            if ($user === null) {
                return self::ACCESS_DENIED;
            }

            if (!in_array("ROLE_ADMIN", $user->getRoles())) {
                return self::ACCESS_DENIED;
            }

            $vote = self::ACCESS_GRANTED;
        }

        return $vote;
    }
}
