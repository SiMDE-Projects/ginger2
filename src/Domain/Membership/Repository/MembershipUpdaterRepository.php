<?php

namespace SIMDE\Ginger\Domain\Membership\Repository;

use PDO;
use SIMDE\Ginger\Domain\Membership\Data\Membership;

class MembershipUpdaterRepository
{
    private $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function update(Membership $membership): void
    {
        $row = [
            'id' => $membership->id,
            'debut' => $membership->debut,
            'fin' => $membership->fin,
            'montant' => $membership->montant,
        ];

        $sql = "UPDATE `memberships` SET
            `debut` = :debut,
            `fin` = :fin,
            `montant` = :montant
            WHERE `id`= :id";

        $this->connection->prepare($sql)->execute($row);
    }
}
