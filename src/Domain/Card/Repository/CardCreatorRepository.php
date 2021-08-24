<?php

namespace App\Domain\Card\Repository;

use App\Domain\Card\Data\Card;
use PDO;

class CardCreatorRepository
{
    private $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function insertCard(Card $card): Card
    {
        $row = [
            'user_id' => $card->user_id,
            'uid' => $card->uid,
            'type' => $card->type,
            'created_at' => $card->created_at,
        ];

        $sql = "INSERT INTO cards SET
            user_id = :user_id,
            uid = :uid,
            type = :type,
            created_at = :created_at;";

        $this->connection->prepare($sql)->execute($row);
        $card->id = (int)$this->connection->lastInsertId();

        return $card;
    }
}
