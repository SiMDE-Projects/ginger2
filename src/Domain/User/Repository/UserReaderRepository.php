<?php

namespace App\Domain\User\Repository;

use App\Domain\User\Data\UserReaderData;
use DomainException;
use PDO;

/**
 * Repository.
 */
class UserReaderRepository
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
     * Get user by the given user id.
     *
     * @param int $userId The user id
     *
     * @throws DomainException
     *
     * @return UserReaderData The user data
     */
    public function getUserByLogin(string $login): UserReaderData
    {
        $sql = "SELECT * FROM personne WHERE login like :id;";
        $statement = $this->connection->prepare($sql);
        $statement->execute(['id' => $login]);

        $row = $statement->fetch();

        if (!$row) {
            throw new DomainException(sprintf('User not found: %s', $userId));
        }

        // Map array to data object
        $user = new UserReaderData();
        $user->id = (int)$row['id'];
        $user->username = (string)$row['login'];
        $user->firstName = (string)$row['prenom'];
        $user->lastName = (string)$row['nom'];
        $user->email = (string)$row['mail'];

        return $user;
    }
}
