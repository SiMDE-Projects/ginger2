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

    public function insertUser(User $user): User
    {
        $row = [
            'login' => $user->login,
            'prenom' => $user->prenom,
            'nom' => $user->nom,
            'mail' => $user->mail,
            'type' => $user->type ?: 0,
            'is_adulte' => $user->is_adulte
        ];

        $sql = "INSERT INTO users SET
            login = :login,
            nom = :nom,
            prenom = :prenom,
            mail = :mail,
            type = :type,
            is_adulte = :is_adulte;";

        $this->connection->prepare($sql)->execute($row);
        $user->id = (int)$this->connection->lastInsertId();

        foreach($user->cards as $index => $card)
            $this->cardCreator->createCard($user, $card);

        // We created this user, we can't have existing memberships
        $user->memberships = [];

        return $user;
    }

    public function updateUser(User $user): User
    {
        $row = [
            'nom' => $user->nom,
            'prenom' => $user->prenom,
            'mail' => $user->mail,
            'is_adulte' => $user->is_adulte,
            'type' => $user->type,
            'login' => $user->login,
        ];

        $sql = "UPDATE users SET
            nom = :nom,
            prenom = :prenom,
            mail = :mail,
            is_adulte = :is_adulte,
            type = :type,
            last_access = NOW()
            WHERE login = :login;";
        $this->connection->prepare($sql)->execute($row);
        
        $sql = "SELECT last_access FROM users WHERE login=:login;";
        $statement = $this->connection->prepare($sql);
        $statement->execute(['login'=>$user->login]);
        $last_access = $statement->fetch();
        $user->last_access = $last_access['last_access'];
        return $user;
    }
}
