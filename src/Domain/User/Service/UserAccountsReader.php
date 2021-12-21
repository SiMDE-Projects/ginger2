<?php

namespace SIMDE\Ginger\Domain\User\Service;

use SIMDE\Ginger\Domain\User\Data\User;
use SIMDE\Ginger\Domain\User\Repository\UserAccountsReaderRepository;

final class UserAccountsReader
{
    private UserAccountsReaderRepository $accountsReaderRepository;

    public function __construct(UserAccountsReaderRepository $accountsReaderRepository)
    {
        $this->accountsReaderRepository = $accountsReaderRepository;
    }

    public function getUserByLogin(string $login): User
    {
        return $this->accountsReaderRepository->getUserByLogin($login);
    }

    public function getUserByCard(string $card): User
    {
        return $this->accountsReaderRepository->getUserByCard($card);
    }
}