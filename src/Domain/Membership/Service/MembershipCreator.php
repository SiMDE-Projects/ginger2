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

    public function createMembership(User $userData, string $debut, string $fin, int $montant): Membership
    {
        // Check that we are not overlapping any other membership
        foreach($userData->memberships as $index => $membership) {
            if((strtotime($debut) >= strtotime($membership->debut) && strtotime($debut) < strtotime($membership->fin))
                || (strtotime($fin) > strtotime($membership->debut) && strtotime($fin) <= strtotime($membership->fin)))
                throw new ValidationException("Membership date is overlapping an existing membership.", [], 400);
        }

        $membership = new Membership;
        $membership->user_id = $userData->id;
        $membership->debut = $debut;
        $membership->fin = $fin;
        $membership->montant = $montant;
        $membership->created_at = date("Y-m-d H:i:s");

        return $this->membershipCreatorRepository->insertMembership($membership);
    }
}