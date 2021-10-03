<?php

namespace SIMDE\Ginger\Domain\Membership\Service;

use SIMDE\Ginger\Domain\User\Data\User;
use SIMDE\Ginger\Domain\Membership\Data\Membership;
use SIMDE\Ginger\Domain\Membership\Repository\MembershipReaderRepository;
use SIMDE\Ginger\Exception\ValidationException;

final class MembershipReader
{
    private $membershipReaderRepository;

    public function __construct(MembershipReaderRepository $membershipReaderRepository)
    {
        $this->membershipReaderRepository = $membershipReaderRepository;
    }

    public function getMembershipsByUser(User $user): Array
    {
        // Validation
        if (!$user || !isset($user->id) || empty($user->id))
            throw new ValidationException('Full user required to retrieve memberships');

        return $this->membershipReaderRepository->getMembershipsByUser($user);
    }
}