<?php

namespace App\Domain\User\Service;

use App\Domain\User\Data\User;
use App\Domain\User\Service\UserAccountsReader;
use App\Domain\Card\Service\CardCreator;
use App\Domain\User\Repository\UserReaderRepository;
use App\Domain\User\Repository\UserCreatorRepository;
use App\Exception\ValidationException;
use App\Exception\UserNotFoundException;

final class UserReader
{
    private $userReaderRepository;
    private $userAccountsReader;
    private $cardCreator;
    private $userCreatorRepository;

    public function __construct(
        UserReaderRepository $userReaderRepository,
        UserAccountsReader $userAccountsReader,
        UserCreatorRepository $userCreatorRepository,
        CardCreator $cardCreator
        )
    {
        $this->userReaderRepository = $userReaderRepository;
        $this->userAccountsReader = $userAccountsReader;
        $this->cardCreator = $cardCreator;
        $this->userCreatorRepository = $userCreatorRepository;
    }

    public function getUserDetailsByLogin(string $login): User
    {
        // Validation
        if (empty($login))
            throw new ValidationException('User login required');

        $user = false;
        try {
            $user = $this->userReaderRepository->getUserByLogin($login);
        } catch(UserNotFoundException $e) {} // Do nothing, not found in db is not fatal
        finally {
            if(!$user || !$user->id || $user->type != 4) {
              return $this->handleUserSync($user, $this->userAccountsReader->getUserByLogin($login));
            } else {
              return $this->updateLastAccessAttribute($user->login);
            }
        }
    }

    public function getUserDetailsByMail(string $mail): User
    {
        // Validation
        if (empty($mail))
            throw new ValidationException('User email required');

        $user = $this->userReaderRepository->getUserByMail($mail);

        if($user->type != 4){
            return $this->handleUserSync($user, $this->userAccountsReader->getUserByLogin($user->login));
        }
        return $this->updateLastAccessAttribute($user->login);
    }

    public function getUserDetailsByCard(string $card): User
    {
        // Validation
        if (empty($card))
            throw new ValidationException('Card UID required');

        $user = false;
        try {
            $user = $this->userReaderRepository->getUserByCard($card);
        } catch(UserNotFoundException $e) {} // Do nothing, not found in db is not fatal
        finally {
            if(!$user || !$user->id || $user->type != 4) {
              return  $this->handleUserSync($user, $this->userAccountsReader->getUserByCard($card));
            } else {
              return $this->updateLastAccessAttribute($user->login);
            }
        }
    }

    public function getUsersDetailsLikeLogin(string $partInfo): Array
    {
        // Validation
        if (empty($partInfo))
            throw new ValidationException('Partial info required');

        $user = $this->userReaderRepository->getUsersLikeLogin($partInfo);

        return $user;
    }

    private function handleUserSync($userDb, User $userAccounts) {
        if(!$userDb || !$userDb->id) {
            return $this->userCreatorRepository->insertUser($userAccounts);
        } else if($user->type != 4) {
            $userAccounts->id = $userDb->id;
            $userAccounts->memberships = $userDb->memberships;

            $updatedUser = $this->userCreatorRepository->updateUser($userAccounts);
            $updatedUser->cards = $this->cardCreator->syncCards($userDb, $userAccounts->cards);
            return $updatedUser;
        } else {
            return $userDb;
        }
    }
    
    private function updateLastAccessAttribute($login) {
      $this->userReaderRepository->updateLastAccessAttribute($login);
      return $this->userReaderRepository->getUserByLogin($login);
    }
}