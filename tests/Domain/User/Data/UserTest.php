<?php

namespace SIMDE\Ginger\Test\Domain\User\Data;

use DateTime;
use PHPUnit\Framework\TestCase;
use SIMDE\Ginger\Domain\Membership\Data\Membership;
use SIMDE\Ginger\Domain\User\Data\User;

class UserTest extends TestCase
{
    public function testCacheExpired(): void
    {
        $user = new User();
        $user->last_sync = new DateTime('-6 minutes');
        $this->assertTrue($user->isCacheExpired());
    }

    public function testCacheValid(): void
    {
        $user = new User();
        $user->last_sync = new DateTime('now');
        $this->assertFalse($user->isCacheExpired());
    }

    public function testIsCacheExpiredWithNull(): void
    {
        $user = new User();
        $this->assertTrue($user->isCacheExpired());
    }

    public function testNoMembership(): void
    {
        $user = new User();
        $user->memberships = [];
        $this->assertFalse($user->getCotisationStatus());
    }

    public function testNoActiveMembership(): void
    {
        $user = new User();
        $membership = new Membership();
        $membership->id = 1;
        $membership->user_id = 1;
        $membership->debut = (new DateTime("-1 year"))->format('Y-m-d');
        $membership->fin = (new DateTime('-1 day'))->format('Y-m-d');
        $membership->montant = 20;
        $membership->created_at = new DateTime();
        $membership->deleted_at = null;
        $user->memberships[] = $membership;
        $this->assertFalse($user->getCotisationStatus());
    }

    public function testActiveMembership(): void
    {
        $user = new User();
        $membership = new Membership();
        $membership->id = 1;
        $membership->user_id = 1;
        $membership->debut = (new DateTime("now"))->format('Y-m-d');
        $membership->fin = (new DateTime('+1 year'))->format('Y-m-d');
        $membership->montant = 20;
        $membership->created_at = new DateTime();
        $membership->deleted_at = null;
        $user->memberships[] = $membership;
        $this->assertTrue($user->getCotisationStatus());
    }
}
