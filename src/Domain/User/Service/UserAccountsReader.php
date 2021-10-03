<?php

namespace SIMDE\Ginger\Domain\User\Service;

use SIMDE\Ginger\Domain\User\Data\User;
use SIMDE\Ginger\Domain\User\Repository\UserAccountsReaderRepository;
use SIMDE\Ginger\Domain\User\Repository\UserCreatorRepository;
use SIMDE\Ginger\Exception\ValidationException;

final class UserAccountsReader
{
    private $accountsReaderRepository;
    private $userCreatorRepository;

    public function __construct(UserAccountsReaderRepository $accountsReaderRepository, UserCreatorRepository $userCreatorRepository)
    {
        $this->accountsReaderRepository = $accountsReaderRepository;
        $this->userCreatorRepository = $userCreatorRepository;
    }

    public function getUserByLogin(string $login): User
    {
        // Validation
        if (empty($login))
            throw new ValidationException('User login required');

        return $this->accountsReaderRepository->getUserByLogin($login);
    }

    public function getUserByCard(string $card): User
    {
        // Validation
        if (empty($card))
            throw new ValidationException('Card UID required');

        return $this->accountsReaderRepository->getUserByCard($card);
    }
}