<?php

namespace App\Domain\Membership\Service;

use App\Domain\User\Data\User;
use App\Domain\Membership\Data\Membership;
use App\Domain\Membership\Repository\MembershipReaderRepository;
use App\Exception\ValidationException;

final class MembershipReader
{
    private $membershipReaderRepository;

    public function __construct(MembershipReaderRepository $membershipReaderRepository)
    {
        $this->membershipReaderRepository = $membershipReaderRepository;
    }

    public function getCardsByUser(User $user): Array
    {
        // Validation
        if (!isset($user->id) || empty($user->id)) {
            throw new ValidationException('Full user required to retrieve memberships', [], 400);
        }

        return $this->membershipReaderRepository->getMembershipsByUser($user);
    }
}