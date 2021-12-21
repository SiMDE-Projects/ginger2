<?php

namespace SIMDE\Ginger\Domain\Card\Service;

use SIMDE\Ginger\Domain\Card\Repository\CardReaderRepository;
use SIMDE\Ginger\Domain\User\Data\User;

final class CardReader
{
    private CardReaderRepository $cardReaderRepository;

    public function __construct(CardReaderRepository $cardReaderRepository)
    {
        $this->cardReaderRepository = $cardReaderRepository;
    }

    public function getCardsByUser(User $user): array
    {
        return $this->cardReaderRepository->getCardsByUser($user);
    }
}