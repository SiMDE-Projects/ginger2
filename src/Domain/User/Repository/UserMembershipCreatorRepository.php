<?php

namespace App\Domain\User\Repository;

use App\Domain\User\Data\UserReaderData;
use DomainException;
use PDO;

/**
 * Repository.
 */
class UserMembershipCreatorRepository
{
    /**
     * @var PDO The database connection
     */
    private $connection;

    /**
     * Constructor.
     *
     * @param PDO $connection The database connection
     */
    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    /**
     * Insert membership given the user login and the dates and the amount.
     *
     * @param UserReaderData $userData The user
     * @param string $debut The user's login
     * @param string $fin The user's login
     * @param int $montant The user's login
     *
     * @throws DomainException
     *
     * @return int The membership id
     */
    public function insertMembership(UserReaderData $userData, string $debut, string $fin, int $montant): int
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
