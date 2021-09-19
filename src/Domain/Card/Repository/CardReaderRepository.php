<?php

namespace App\Domain\Card\Repository;

use App\Domain\User\Data\User;
use App\Domain\Card\Data\Card;
use App\Exception\ValidationException;
use PDO;

class CardReaderRepository
{
    private $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function getCardsByUser(User $user): Array
    {
        // Get all cards details
        $sql = "SELECT * FROM cards WHERE user_id = :id AND removed_at IS NULL ORDER BY type DESC;";
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
            $card->removed_at = $cardData["removed_at"];
            $card->created_at = $cardData["created_at"];
            $cards[] = $card;
        }

        return $cards;
    }
}
