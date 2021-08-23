<?php

namespace App\Domain\User\Service;

use App\Domain\User\Data\User;
use App\Domain\User\Repository\UserMembershipCreatorRepository;
use App\Exception\ValidationException;

/**
 * Service.
 */
final class UserMembershipCreator
{
    private UserMembershipCreatorRepository $repository;

    /**
     * The constructor.
     *
     * @param UserMembershipCreatorRepository $repository The repository
     */
    public function __construct(
        UserMembershipCreatorRepository $repository
    ) {
        $this->repository = $repository;
    }

    /**
     * Create a new user membership.
     *
     * @param User $userData the user object
     * @param string $debut Begin date of the membership
     * @param string $fin End date of the membership
     * @param int $montant the price of the membership
     *
     * @return int The new membership ID
     */
    public function createUserMembership(User $userData, string $debut, string $fin, int $montant): int
    {
        // Check that we are not overlapping any other membership
        foreach($userData->memberships as $index => $membership) {
            if((strtotime($debut) >= strtotime($membership["debut"]) && strtotime($debut) < strtotime($membership["fin"]))
                || (strtotime($fin) > strtotime($membership["debut"]) && strtotime($fin) <= strtotime($membership["fin"])))
                throw new ValidationException("Membership date is overlapping an existing membership.", [], 400);
        }

        // Insert user and get new user ID
        $userId = $this->repository->insertMembership($userData, $debut, $fin, $montant);

        return $userId;
    }
}