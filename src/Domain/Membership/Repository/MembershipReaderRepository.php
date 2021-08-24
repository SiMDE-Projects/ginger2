<?php

namespace App\Domain\Membership\Repository;

use App\Domain\User\Data\User;
use App\Domain\Membership\Data\Membership;
use App\Exception\ValidationException;
use PDO;

class MembershipReaderRepository
{
    private $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function getMembershipsByUser(User $user): Array
    {
        // Get all cards details
        $sql = "SELECT * FROM memberships WHERE user_id = :id ORDER BY fin DESC;";
        $statement = $this->connection->prepare($sql);
        $statement->execute(['id' => $user->id]);
        $membershipsData = $statement->fetchAll();

        // Build objetcs
        $memberships = [];
        foreach ($membershipsData as $membershipData) {
            $membership = new Membership;
            $membership->id = $membershipData["id"];
            $membership->user_id = $membershipData["user_id"];
            $membership->debut = $membershipData["debut"];
            $membership->fin = $membershipData["fin"];
            $membership->montant = $membershipData["montant"];
            $membership->created_at = $membershipData["created_at"];
            $membership->deleted_at = $membershipData["deleted_at"];

            $memberships[] = $membership;
        }

        return $memberships;
    }
}
