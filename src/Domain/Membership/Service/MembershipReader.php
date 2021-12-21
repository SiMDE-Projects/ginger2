<?php

namespace SIMDE\Ginger\Domain\Membership\Service;

use SIMDE\Ginger\Domain\Membership\Repository\MembershipReaderRepository;
use SIMDE\Ginger\Domain\User\Data\User;

final class MembershipReader
{
    private $membershipReaderRepository;

    public function __construct(MembershipReaderRepository $membershipReaderRepository)
    {
        $this->membershipReaderRepository = $membershipReaderRepository;
    }

    public function getMembershipsByUser(User $user): array
    {
        return $this->membershipReaderRepository->getMembershipsByUser($user);
    }
}