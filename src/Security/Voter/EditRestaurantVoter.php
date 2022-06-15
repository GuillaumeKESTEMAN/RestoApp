<?php

declare(strict_types=1);

namespace App\Security\Voter;

use App\Entity\Restaurant;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class EditRestaurantVoter extends Voter
{
    protected function supports($attribute, $restaurant): bool
    {
        return 'edit' === $attribute && $restaurant instanceof Restaurant;
    }

    protected function voteOnAttribute($attribute, $restaurant, TokenInterface $token): bool
    {
        /* @var Restaurant $restaurant */
        return $restaurant->getUserId() === $token->getUser()?->getId();
    }
}
