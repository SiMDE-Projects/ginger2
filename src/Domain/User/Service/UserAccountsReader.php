<?php

namespace App\Domain\User\Service;

use App\Domain\User\Data\UserReaderData;
use App\Domain\User\Repository\UserAccountsReaderRepository;
use App\Domain\User\Repository\UserCreatorRepository;
use App\Exception\ValidationException;

/**
 * Service.
 */
final class UserAccountsReader
{
    /**
     * @var UserAccountsReaderRepository
     */
    private $accountsReaderRepository;

    /**
     * @var UserCreatorRepository
     */
    private $userCreatorRepository;

    /**
     * The constructor.
     *
     * @param UserAccountsReaderRepository $accountsReaderRepository The repository
     * @param UserCreatorRepository $userCreatorRepository The repository
     */
    public function __construct(UserAccountsReaderRepository $accountsReaderRepository, UserCreatorRepository $userCreatorRepository)
    {
        $this->accountsReaderRepository = $accountsReaderRepository;
        $this->userCreatorRepository = $userCreatorRepository;
    }

    /**
     * Read a user by the given user login.
     *
     * @param string $login The user login
     *
     * @throws ValidationException
     *
     * @return UserReaderData The user data
     */
    public function getUserByLogin(string $login): UserReaderData
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

    /**
     * Read a user by the given card uid.
     *
     * @param string $card The card uid
     *
     * @throws ValidationException
     *
     * @return UserReaderData The user data
     */
    public function getUserByCard(string $card): UserReaderData
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