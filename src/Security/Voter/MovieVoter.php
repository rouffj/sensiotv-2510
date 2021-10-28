<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class MovieVoter extends Voter
{
    /**
    protected function supports(string $attribute, $subject): bool
    {
        return method_exists($suject, 'getUser');
        // https://symfony.com/doc/current/security/voters.html
        //return in_array($attribute, ['MOVIE_SHOW'])
        //    && $subject instanceof \App\Entity\Movie;
    }
     */

    protected function supports(string $attribute, $subject): bool
    {
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, ['MOVIE_SHOW'])
            && $subject instanceof \App\Entity\Movie;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        /**
        if ($subject->getUser() === $user) {
            return true;
        }
         */

        $movie = $subject;
        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case 'MOVIE_SHOW':
                // We allow movie watching only if the released date is after the user birthday
                if ($movie->getReleaseDate() >= $user->getBirthday()) {
                    return true;
                }
                break;
        }

        return false;
    }
}
