<?php

namespace SIMDE\Ginger\Domain\User\Service;

use SIMDE\Ginger\Domain\Card\Service\CardCreator;
use SIMDE\Ginger\Domain\User\Data\User;
use SIMDE\Ginger\Domain\User\Repository\UserCreatorRepository;
use SIMDE\Ginger\Domain\User\Repository\UserReaderRepository;
use SIMDE\Ginger\Exception\UserNotFoundException;
use SIMDE\Ginger\Exception\ValidationException;

final class UserReader
{
    private UserReaderRepository  $userReaderRepository;
    private UserAccountsReader    $userAccountsReader;
    private CardCreator           $cardCreator;
    private UserCreatorRepository $userCreatorRepository;

    public function __construct(
        UserReaderRepository  $userReaderRepository,
        UserAccountsReader    $userAccountsReader,
        UserCreatorRepository $userCreatorRepository,
        CardCreator           $cardCreator
    )
    {
        $this->userReaderRepository  = $userReaderRepository;
        $this->userAccountsReader    = $userAccountsReader;
        $this->cardCreator           = $cardCreator;
        $this->userCreatorRepository = $userCreatorRepository;
    }

    public function getUserDetailsByMail(string $mail): User
    {
        $user = $this->userReaderRepository->getUserByMail($mail);

        return $this->handleUserSync($user);
    }

    private function handleUserSync(?User $userDb, ?string $login = null): User
    {
        if (!$userDb || !$userDb->login) {
            $userAccounts = $this->userAccountsReader->getUserByLogin($login);
            return $this->userCreatorRepository->insertUser($userAccounts);
        }

        if ($userDb->type !== 4) {
            $userAccounts              = $this->userAccountsReader->getUserByLogin($userDb->login);
            $userAccounts->id          = $userDb->id;
            $userAccounts->memberships = $userDb->memberships;
            $updatedUser               = $this->userCreatorRepository->updateUser($userAccounts);
            if (!$userDb->overrides["card"]) {
                $updatedUser->cards = $this->cardCreator->syncCards($userDb, $userAccounts->cards);
            }
            return $updatedUser;
        }
        return $this->updateLastAccessAttribute($userDb->login);
    }

    private function updateLastAccessAttribute($login): User
    {
        $this->userReaderRepository->updateLastAccessAttribute($login);
        return $this->userReaderRepository->getUserByLogin($login);
    }

    public function getUserDetailsByCard(string $card): User
    {
        $user = false;
        try {
            $user = $this->userReaderRepository->getUserByCard($card);
        }
        catch (UserNotFoundException $e) {
        } // Do nothing, not found in db is not fatal
        if ($user) {
            return $this->updateLastAccessAttribute($user->login);
        }
        $userAccounts = $this->userAccountsReader->getUserByCard($card);
        return $this->getUserDetailsByLogin($userAccounts->login);
    }

    public function getUserDetailsByLogin(string $login): User
    {
        $user = null;
        try {
            $user = $this->userReaderRepository->getUserByLogin($login);
        }
        catch (UserNotFoundException $e) {
        } // Do nothing, not found in db is not fatal
        return $this->handleUserSync($user, $login);
    }

    public function getUsersDetailsLikeLogin(string $partInfo): array
    {
        return $this->userReaderRepository->getUsersLikeLogin($partInfo);
    }
}