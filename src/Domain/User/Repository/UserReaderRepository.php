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
        $sql = "SELECT
        u.id,
        u.login,
        u.prenom AS prenom,
        uo.prenom AS overrided_prenom,
        u.nom AS nom,
        uo.nom AS overrided_nom,
        u.mail AS mail,
        uo.mail AS overrided_mail,
        u.type AS type,
        uo.type AS overrided_type,
        u.is_adulte AS is_adulte,
        uo.is_adulte AS overrided_is_adulte,
        uo.card_uid AS overrided_card,
        u.created_at,
        u.last_access
        FROM users u
        LEFT JOIN user_overrides uo
        ON u.id = uo.user_id
        AND (uo.ignored_at IS NULL OR uo.ignored_at < NOW())
        WHERE login = :login;";
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
        $sql = "SELECT
        u.id,
        u.login,
        u.prenom AS prenom,
        uo.prenom AS overrided_prenom,
        u.nom AS nom,
        uo.nom AS overrided_nom,
        u.mail AS mail,
        uo.mail AS overrided_mail,
        u.type AS type,
        uo.type AS overrided_type,
        u.is_adulte AS is_adulte,
        uo.is_adulte AS overrided_is_adulte,
        uo.card_uid AS overrided_card,
        u.created_at,
        u.last_access
        FROM users u
        LEFT JOIN user_overrides uo ON u.id = uo.user_id
        AND (uo.ignored_at IS NULL OR uo.ignored_at < NOW())
        WHERE u.mail = :mail
        OR uo.mail = :mail;";
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
        $sql = "SELECT
        u.id,
        u.login,
        u.prenom AS prenom,
        uo.prenom AS overrided_prenom,
        u.nom AS nom,
        uo.nom AS overrided_nom,
        u.mail AS mail,
        uo.mail AS overrided_mail,
        u.type AS type,
        uo.type AS overrided_type,
        u.is_adulte AS is_adulte,
        uo.is_adulte AS overrided_is_adulte,
        uo.card_uid AS overrided_card,
        u.created_at,
        u.last_access
        FROM `cards` c
        INNER JOIN users u ON u.id = c.user_id
        LEFT JOIN user_overrides uo ON u.id = uo.user_id
        AND (uo.ignored_at IS NULL OR uo.ignored_at < NOW())
        WHERE c.uid LIKE :card
        OR uo.card_uid LIKE :card
        GROUP BY c.user_id;";
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
        $sql = "SELECT
        u.id,
        u.login,
        u.prenom AS prenom,
        uo.prenom AS overrided_prenom,
        u.nom AS nom,
        uo.nom AS overrided_nom,
        u.mail AS mail,
        uo.mail AS overrided_mail,
        u.type AS type,
        uo.type AS overrided_type,
        u.is_adulte AS is_adulte,
        uo.is_adulte AS overrided_is_adulte,
        uo.card_uid AS overrided_card,
        u.created_at,
        u.last_access
        FROM users u
        LEFT JOIN user_overrides uo ON u.id = uo.user_id
        AND (uo.ignored_at IS NULL OR uo.ignored_at < NOW())
        WHERE login LIKE :info
        OR u.mail LIKE :info
        OR u.nom LIKE :info
        OR u.prenom LIKE :info
        OR uo.mail LIKE :info
        OR uo.nom LIKE :info
        OR uo.prenom LIKE :info
        LIMIT 10;";
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
        $user->overrides = [];
        $user->overrides["prenom"] = (string)$row['overrided_prenom'];
        $user->overrides["nom"] = (string)$row['overrided_nom'];
        $user->overrides["mail"] = (string)$row['overrided_mail'];
        $user->overrides["is_adulte"] = (string)$row['overrided_is_adulte'];
        $user->overrides["type"] = (string)$row['overrided_type'];
        $user->overrides["card"] = (string)$row['overrided_card'];
        $user->cards = [];

        // Get all cards details if not overrided
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
