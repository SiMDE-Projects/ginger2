<?php

namespace SIMDE\Ginger\Domain\Membership\Repository;

use PDO;
use SIMDE\Ginger\Domain\Membership\Data\Membership;

class MembershipCreatorRepository
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function insertMembership(Membership $membership): Membership
    {
        $row = [
            'user_id' => $membership->user_id,
            'debut' => $membership->debut,
            'fin' => $membership->fin,
            'montant' => $membership->montant,
            'created_at' => $membership->created_at,
        ];

        $sql = "INSERT INTO `memberships` SET
            `user_id` = :user_id,
            `debut` = :debut,
            `fin` = :fin,
            `montant` = :montant,
            `created_at` = :created_at,
            `deleted_at` = NULL";

        $this->connection->prepare($sql)->execute($row);
        $membership->id = (int)$this->connection->lastInsertId();

        return $membership;
    }
}
