<?php

namespace App\Domain\User\Repository;

use App\Domain\User\Data\User;
use DomainException;
use PDO;

class UserMembershipCreatorRepository
{
    private $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function insertMembership(User $userData, string $debut, string $fin, int $montant): int
    {
        $row = [
            'user_id' => $userData->id,
            'debut' => $debut,
            'fin' => $fin,
            'montant' => $montant,
        ];

        $sql = "INSERT INTO memberships SET
            user_id = :user_id,
            debut = :debut,
            fin = :fin,
            montant = :montant,
            deleted_at = NULL;";

        $this->connection->prepare($sql)->execute($row);

        return (int)$this->connection->lastInsertId();
    }
}
