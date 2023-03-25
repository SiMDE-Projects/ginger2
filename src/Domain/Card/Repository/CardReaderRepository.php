<?php

namespace SIMDE\Ginger\Domain\Card\Repository;

use DateTime;
use PDO;
use SIMDE\Ginger\Domain\Card\Data\Card;
use SIMDE\Ginger\Domain\User\Data\User;

class CardReaderRepository
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function getCardsByUser(User $user): array
    {
        // Get all cards details
        $sql = "SELECT * FROM `cards` WHERE `user_id` = :id ORDER BY `type` DESC";
        $statement = $this->connection->prepare($sql);
        $statement->execute(['id' => $user->id]);
        $cardsData = $statement->fetchAll();

        // Build objects
        $cards = [];
        foreach ($cardsData as $cardData) {
            $card = new Card;
            $card->id = $cardData["id"];
            $card->user_id = $cardData["user_id"];
            $card->uid = $cardData["uid"];
            $card->type = $cardData["type"];
            $card->removed_at = $cardData["removed_at"] ? DateTime::createFromFormat("Y-m-d H:i:s", $cardData["removed_at"]) : null;
            $card->created_at = DateTime::createFromFormat("Y-m-d H:i:s", $cardData["created_at"]);
            $cards[] = $card;
        }

        return $cards;
    }
}
