<?php

namespace App\Domain\User\Repository;

use App\Domain\User\Data\User;
use DomainException;
use PDO;

class UserCreatorRepository
{
    private $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function insertUser(User $userData): User
    {
        $row = [
            'login' => $userData->login,
            'prenom' => $userData->prenom,
            'nom' => $userData->nom,
            'mail' => $userData->mail,
            'type' => $userData->type?:0,
            'is_adulte' => $userData->is_adulte
        ];

        $sql = "INSERT INTO users SET
            login = :login,
            nom = :nom,
            prenom = :prenom,
            mail = :mail,
            type = :type,
            is_adulte = :is_adulte;";

        $this->connection->prepare($sql)->execute($row);
        $userData->id = (int)$this->connection->lastInsertId();

        foreach($userData->cards as $index => $card) {
            $data = array_merge($card, array("user_id" => $userData->id));
            $sql = "INSERT INTO cards SET
                user_id = :user_id,
                type = :type,
                uid = :uid,
                created_at = :created_at;";
            $this->connection->prepare($sql)->execute($data);
        }

        $userData->memberships = [];

        return $userData;
    }

    public function updateUser(User $userData): User
    {
        $row = [
            'nom' => $userData->nom,
            'prenom' => $userData->prenom,
            'mail' => $userData->mail,
            'is_adulte' => $userData->is_adulte,
            'type' => $userData->type,
            'login' => $userData->login,
        ];

        $sql = "UPDATE users SET
            nom = :nom,
            prenom = :prenom,
            mail = :mail,
            is_adulte = :is_adulte,
            type = :type
            WHERE login = :login;";

        $this->connection->prepare($sql)->execute($row);

        // TODO: For the moment, cards are not updated !!!!!!

        return $userData;
    }
}
