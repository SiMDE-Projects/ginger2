<?php

namespace App\Domain\User\Repository;

use App\Domain\User\Data\User;
use App\Domain\Card\Service\CardReader;
use App\Domain\Membership\Service\MembershipReader;
use App\Exception\UserNotFoundException;
use PDO;

class UserReaderRepository
{
    private $connection;
    private $cardReader;
    private $membershipReader;

    public function __construct(PDO $connection, \App\Domain\Card\Service\CardReader $cardReader, MembershipReader $membershipReader)
    {
        $this->connection = $connection;
        $this->cardReader = $cardReader;
        $this->membershipReader = $membershipReader;
    }

    public function getUserByLogin(string $userLogin): User
    {
        $sql = "SELECT * FROM users WHERE login = :login;";
        $statement = $this->connection->prepare($sql);
        $statement->execute(['login' => $userLogin]);

        $row = $statement->fetch();

        if (!$row) {
            throw new UserNotFoundException("User not found by login in bd");
        }

        return $this->buildUserObject($row);
    }

    public function getUserByMail(string $userMail): User
    {
        $sql = "SELECT * FROM users WHERE mail = :mail;";
        $statement = $this->connection->prepare($sql);
        $statement->execute(['mail' => $userMail]);

        $row = $statement->fetch();

        if (!$row) {
            throw new UserNotFoundException("User not found by mail in bd");
        }

        return $this->buildUserObject($row);
    }

    public function getUserByCard(string $userCard): User
    {
        $sql = "SELECT users.* FROM `cards` JOIN users ON users.id = user_id WHERE uid = :card GROUP BY user_id;";
        $statement = $this->connection->prepare($sql);
        $statement->execute(['card' => $userCard]);

        $row = $statement->fetch();

        if (!$row) {
            throw new UserNotFoundException("User not found by card in bd");
        }

        return $this->buildUserObject($row);
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
        $user->cards = $this->cardReader->getCardsByUser($user);

        // Get all memberships details
        $user->memberships = $this->membershipReader->getMembershipsByUser($user);

        return $user;
    }
}
