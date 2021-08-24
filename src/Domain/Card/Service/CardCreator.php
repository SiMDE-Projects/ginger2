<?php

namespace App\Domain\Card\Service;

use App\Domain\User\Data\User;
use App\Domain\Card\Data\Card;
use App\Domain\Card\Repository\CardCreatorRepository;
use App\Exception\ValidationException;

final class CardCreator
{
    private CardCreatorRepository $cardCreatorRepository;

    public function __construct(
        CardCreatorRepository $cardCreatorRepository
    ) {
        $this->cardCreatorRepository = $cardCreatorRepository;
    }

    public function createCard(User $userData, Card $newCard): Card
    {
        $newCard->user_id = $userData->id;

        return $this->cardCreatorRepository->insertCard($newCard);
    }
}