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
     * Get user by the given user login.
     *
     * @param int $userLogin The user's login
     *
     * @throws DomainException
     *
     * @return UserReaderData The user data
     */
    public function getUserByLogin(string $userLogin): UserReaderData
    {
        $sql = "SELECT * FROM users WHERE login = :login;";
        $statement = $this->connection->prepare($sql);
        $statement->execute(['login' => $userLogin]);

        $row = $statement->fetch();

        return $row ? $this->buildUserObject($row) : new UserReaderData;
    }

    /**
     * Get user by the given user mail.
     *
     * @param int $userLogin The user's mail
     *
     * @throws DomainException
     *
     * @return UserReaderData The user data
     */
    public function getUserByMail(string $userMail): UserReaderData
    {
        $sql = "SELECT * FROM users WHERE mail = :mail;";
        $statement = $this->connection->prepare($sql);
        $statement->execute(['mail' => $userMail]);

        $row = $statement->fetch();

        if (!$row) {
            throw new DomainException(sprintf('User not found: %s', $userMail));
        }

        return $this->buildUserObject($row);
    }

    /**
     * Get user by any of his cards.
     *
     * @param int $userCard any of the user's cards
     *
     * @throws DomainException
     *
     * @return UserReaderData The user data
     */
    public function getUserByCard(string $userCard): UserReaderData
    {
        $sql = "SELECT users.* FROM `cards` JOIN users ON users.id = user_id WHERE uid = :card GROUP BY user_id;";
        $statement = $this->connection->prepare($sql);
        $statement->execute(['card' => $userCard]);

        $row = $statement->fetch();

        return $row ? $this->buildUserObject($row) : new UserReaderData;
    }

    /**
     * Get users similar to a partial information
     *
     * @param int $partInfo Any info about the user
     *
     * @throws DomainException
     *
     * @return UserReaderData[] The users data
     */
    public function getUsersLikeLogin(string $partInfo): Array
    {
        $sql = "SELECT * FROM users WHERE login LIKE :info OR mail LIKE :info OR nom LIKE :info OR prenom LIKE :info LIMIT 10;";
        $statement = $this->connection->prepare($sql);
        $statement->execute(['info' => "%$partInfo%"]);

        $rows = $statement->fetchAll();

        $result = [];
        foreach($rows as $index => $row)
            $result[$index] = $this->buildUserObject($row);

        return $result;
    }

    private function buildUserObject($row) {
        // Map array to data object
        $user = new UserReaderData();
        $user->id = (int)$row['id'];
        $user->login = (string)$row['login'];
        $user->nom = (string)$row['nom'];
        $user->prenom = (string)$row['prenom'];
        $user->mail = (string)$row['mail'];
        $user->type = (string)$row['type'];
        $user->is_adulte = (string)$row['is_adulte'];

        // Get all cards details
        $sql = "SELECT type, uid FROM cards WHERE user_id = :id ORDER BY type DESC;";
        $statement = $this->connection->prepare($sql);
        $statement->execute(['id' => $user->id]);
        $user->cards = $statement->fetchAll();

        // Get all memberships details
        $sql = "SELECT id, debut, fin, montant FROM memberships WHERE user_id = :id ORDER BY fin DESC;";
        $statement = $this->connection->prepare($sql);
        $statement->execute(['id' => $user->id]);
        $user->memberships = $statement->fetchAll();

        return $user;
    }
}
