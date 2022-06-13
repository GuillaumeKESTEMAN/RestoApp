<?php

namespace App\Security\Voter;

use App\Entity\Restaurant;
use App\Repository\RestaurantRepository;
use LDAP\Result;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class EditRestaurantVoter extends Voter
{
    protected function supports($attribute, $restaurant): bool
    {
        return 'edit' === $attribute && $restaurant instanceof Restaurant;
    }

    protected function voteOnAttribute($attribute, $restaurant, TokenInterface $token): bool
    {
        return $restaurant->getUserId() === $token->getUser()->getId();
    }
}
