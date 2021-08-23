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
        if (empty($login)) {
            throw new ValidationException('User login required', [], 400);
        }

        $user = $this->accountsReaderRepository->getUserByLogin($login);
        if(!$user) {
            throw new ValidationException("User not found by login : $login", [], 404);
        }

        return $user;
    }

    public function getUserByCard(string $card): User
    {
        // Validation
        if (empty($card)) {
            throw new ValidationException('Card UID required', [], 400);
        }

        $serialArray = str_split($card, 2);
        $serialNumber = implode("", array_reverse($serialArray));
        $user = $this->accountsReaderRepository->getUserByCard($serialNumber);
        if(!$user) {
            throw new ValidationException("User not found by card : $card", [], 404);
        }

        return $user;
    }
}