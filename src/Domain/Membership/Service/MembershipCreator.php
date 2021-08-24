<?php

namespace App\Domain\Membership\Service;

use App\Domain\User\Data\User;
use App\Domain\Membership\Data\Membership;
use App\Domain\Membership\Repository\MembershipCreatorRepository;
use App\Exception\ValidationException;

final class MembershipCreator
{
    private MembershipCreatorRepository $membershipCreatorRepository;

    public function __construct(
        MembershipCreatorRepository $membershipCreatorRepository
    ) {
        $this->membershipCreatorRepository = $membershipCreatorRepository;
    }

    public function createMembership(User $user, string $debut, string $fin, int $montant): Membership
    {
        // Check that we are not overlapping any other membership
        foreach($user->memberships as $index => $membership)
            if((strtotime($debut) >= strtotime($membership->debut) && strtotime($debut) < strtotime($membership->fin))
                || (strtotime($fin) > strtotime($membership->debut) && strtotime($fin) <= strtotime($membership->fin)))
                throw new ValidationException("Membership date is overlapping an existing membership");

        if(!isset($debut) || empty($debut) || !(bool)strtotime($debut))
            throw new ValidationException("Begin date is invalid");

        if(!isset($fin) || empty($fin) || !(bool)strtotime($fin))
            throw new ValidationException("End date is invalid");

        if(strtotime($debut) >= strtotime($fin))
            throw new ValidationException("Begin date must be inferior than end date");

        if(!isset($montant) || !is_numeric($montant))
            throw new ValidationException("Montant is invalid");

        $membership = new Membership;
        $membership->user_id = $user->id;
        $membership->debut = $debut;
        $membership->fin = $fin;
        $membership->montant = $montant;
        $membership->created_at = date("Y-m-d H:i:s");

        return $this->membershipCreatorRepository->insertMembership($membership);
    }
}