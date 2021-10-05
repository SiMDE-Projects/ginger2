<?php

namespace SIMDE\Ginger\Domain\User\Repository;

use SIMDE\Ginger\Domain\User\Data\User;
use SIMDE\Ginger\Domain\Card\Service\CardReader;
use SIMDE\Ginger\Domain\Membership\Service\MembershipReader;
use SIMDE\Ginger\Exception\UserNotFoundException;
use PDO;

class UserReaderRepository
{
    private $connection;
    private $cardReader;
    private $membershipReader;

    public function __construct(PDO $connection, \SIMDE\Ginger\Domain\Card\Service\CardReader $cardReader, MembershipReader $membershipReader)
    {
        $this->connection = $connection;
        $this->cardReader = $cardReader;
        $this->membershipReader = $membershipReader;
    }

    public function getUserByLogin(string $userLogin): User
    {
        $sql = "SELECT u.id, u.login, IFNULL(uo.prenom, u.prenom) AS prenom, IFNULL(uo.nom, u.nom) AS nom, IFNULL(uo.mail, u.mail) AS mail, IFNULL(uo.type, u.type) AS type, IFNULL(uo.is_adulte, u.is_adulte) AS is_adulte, u.created_at, u.last_access FROM users u LEFT JOIN user_overrides uo ON u.id = uo.user_id WHERE login = :login;";
        $statement = $this->connection->prepare($sql);
        $statement->execute(['login' => $userLogin]);
        $row = $statement->fetch();

        if (!$row) {
            throw new UserNotFoundException("User not found by login in db");
        }
        
        return $this->buildUserObject($row);
    }

    public function getUserByMail(string $userMail): User
    {
        $sql = "SELECT u.id, u.login, IFNULL(uo.prenom, u.prenom) AS prenom, IFNULL(uo.nom, u.nom) AS nom, IFNULL(uo.mail, u.mail) AS mail, IFNULL(uo.type, u.type) AS type, IFNULL(uo.is_adulte, u.is_adulte) AS is_adulte, u.created_at, u.last_access FROM users u LEFT JOIN user_overrides uo ON u.id = uo.user_id WHERE mail = :mail;";
        $statement = $this->connection->prepare($sql);
        $statement->execute(['mail' => $userMail]);

        $row = $statement->fetch();

        if (!$row) {
            throw new UserNotFoundException("User not found by mail in db");
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
        $this->updateLastAccessAttribute($row['id']);
        return $this->buildUserObject($row);
    }

    public function getUsersLikeLogin(string $partInfo): Array
    {
        $sql = "SELECT u.id, u.login, IFNULL(uo.prenom, u.prenom) AS prenom, IFNULL(uo.nom, u.nom) AS nom, IFNULL(uo.mail, u.mail) AS mail, IFNULL(uo.type, u.type) AS type, IFNULL(uo.is_adulte, u.is_adulte) AS is_adulte, u.created_at, u.last_access FROM users u LEFT JOIN user_overrides uo ON u.id = uo.user_id WHERE login LIKE :info OR mail LIKE :info OR nom LIKE :info OR prenom LIKE :info LIMIT 10;";
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
        $user->created_at = (string)$row['created_at'];
        $user->last_access = (string)$row['last_access'];

        // Get all cards details
        $user->cards = $this->cardReader->getCardsByUser($user);
        // Get all memberships details
        $user->memberships = $this->membershipReader->getMembershipsByUser($user);

        return $user;
    }
    
    public function updateLastAccessAttribute($login) {
      $sql = "UPDATE `users` SET `last_access` = NOW() WHERE login=:login;";
      $statement = $this->connection->prepare($sql);
      $statement->execute(['login' => $login]);
    }
}
