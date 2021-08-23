<?php

namespace App\Domain\User\Repository;

use App\Domain\User\Data\User;
use App\Exception\ValidationException;
use PDO;

class UserReaderRepository
{
    private $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function getUserByLogin(string $userLogin): User
    {
        $sql = "SELECT * FROM users WHERE login = :login;";
        $statement = $this->connection->prepare($sql);
        $statement->execute(['login' => $userLogin]);

        $row = $statement->fetch();

        return $row ? $this->buildUserObject($row) : new User;
    }

    public function getUserByMail(string $userMail): User
    {
        $sql = "SELECT * FROM users WHERE mail = :mail;";
        $statement = $this->connection->prepare($sql);
        $statement->execute(['mail' => $userMail]);

        $row = $statement->fetch();

        if (!$row) {
            throw new ValidationException(sprintf('User not found: %s', $userMail), [], 404);
        }

        return $this->buildUserObject($row);
    }

    public function getUserByCard(string $userCard): User
    {
        $sql = "SELECT users.* FROM `cards` JOIN users ON users.id = user_id WHERE uid = :card GROUP BY user_id;";
        $statement = $this->connection->prepare($sql);
        $statement->execute(['card' => $userCard]);

        $row = $statement->fetch();

        return $row ? $this->buildUserObject($row) : new User;
    }

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
        $user = new User();
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
