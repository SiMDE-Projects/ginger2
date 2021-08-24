<?php

namespace App\Domain\Card\Service;

use App\Domain\User\Data\User;
use App\Domain\Card\Data\Card;
use App\Domain\Card\Repository\CardReaderRepository;
use App\Exception\ValidationException;

final class CardReader
{
    private $cardReaderRepository;

    public function __construct(CardReaderRepository $cardReaderRepository)
    {
        $this->cardReaderRepository = $cardReaderRepository;
    }

    public function getCardsByUser(User $user): Array
    {
        // Validation
        if (!isset($user->id) || empty($user->id)) {
            throw new ValidationException('Full user required to retrieve cards from cache', [], 400);
        }

        return $this->cardReaderRepository->getCardsByUser($user);
    }
}