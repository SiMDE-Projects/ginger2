<?php

namespace SIMDE\Ginger\Domain\Card\Service;

use SIMDE\Ginger\Domain\User\Data\User;
use SIMDE\Ginger\Domain\Card\Data\Card;
use SIMDE\Ginger\Domain\Card\Repository\CardReaderRepository;
use SIMDE\Ginger\Exception\ValidationException;

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
        if (!$user || !isset($user->id) || empty($user->id))
            throw new ValidationException('Full user required to retrieve cards from cache');

        return $this->cardReaderRepository->getCardsByUser($user);
    }
}