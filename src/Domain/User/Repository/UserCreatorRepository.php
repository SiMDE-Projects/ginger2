<?php

namespace App\Domain\User\Repository;

use App\Domain\User\Data\User;
use App\Domain\Card\Service\CardCreator;
use PDO;

class UserCreatorRepository
{
    private $connection;
    private $cardCreator;

    public function __construct(PDO $connection, CardCreator $cardCreator)
    {
        $this->connection = $connection;
        $this->cardCreator = $cardCreator;
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

        foreach($userData->cards as $index => $card)
            $this->cardCreator->createCard($userData, $card);

        // We created this user, we can't have existing memberships
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
