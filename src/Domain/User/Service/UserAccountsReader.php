<?php

namespace App\Domain\User\Service;

use App\Domain\User\Data\User;
use App\Domain\User\Repository\UserAccountsReaderRepository;
use App\Domain\User\Repository\UserCreatorRepository;
use App\Exception\ValidationException;

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

        $serialArray = str_split($card, 2);
        $serialNumber = implode("", array_reverse($serialArray));

        return $this->accountsReaderRepository->getUserByCard($serialNumber);
    }
}