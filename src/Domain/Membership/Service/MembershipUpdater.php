<?php

namespace SIMDE\Ginger\Domain\Membership\Service;

use SIMDE\Ginger\Domain\Membership\Data\Membership;
use SIMDE\Ginger\Domain\Membership\Repository\MembershipUpdaterRepository;

final class MembershipUpdater
{
    private $membershipUpdaterRepository;

    public function __construct(MembershipUpdaterRepository $membershipUpdaterRepository)
    {
        $this->membershipUpdaterRepository = $membershipUpdaterRepository;
    }

    public function update(Membership $membership): void
    {
        $this->membershipUpdaterRepository->update($membership);
    }
}