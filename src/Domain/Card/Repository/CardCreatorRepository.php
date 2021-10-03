<?php

namespace SIMDE\Ginger\Domain\Card\Repository;

use SIMDE\Ginger\Domain\Card\Data\Card;
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
    
    public function flagRemoved(Card $card): void
    {
      $row = [
          'user_id' => $card->user_id,
          'uid' => $card->uid
      ];
      
      $sql = "UPDATE cards SET
          removed_at = NOW()
          WHERE
          user_id = :user_id
          AND uid = :uid";
      $this->connection->prepare($sql)->execute($row);
    }
}
