<?php

namespace SIMDE\Ginger\Domain\Membership\Service;

use SIMDE\Ginger\Domain\Membership\Data\Membership;
use SIMDE\Ginger\Domain\Membership\Repository\MembershipReaderRepository;
use SIMDE\Ginger\Domain\User\Data\User;

final class MembershipReader
{
    private $membershipReaderRepository;

    public function __construct(MembershipReaderRepository $membershipReaderRepository)
    {
        $this->membershipReaderRepository = $membershipReaderRepository;
    }

    /**
     * @param User $user
     * @return array
     */
    public function getMembershipsByUser(User $user): array
    {
        return $this->membershipReaderRepository->getMembershipsByUser($user);
    }

    /**
     * @param int $id
     * @return Membership
     */
    public function getMembershipById(int $id): Membership
    {
        return $this->membershipReaderRepository->getMembershipsById($id);
    }
}