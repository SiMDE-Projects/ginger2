<?php

namespace App\Domain\User\Service;

use App\Domain\User\Data\User;
use App\Domain\User\Service\UserAccountsReader;
use App\Domain\User\Repository\UserReaderRepository;
use App\Domain\User\Repository\UserCreatorRepository;
use App\Exception\ValidationException;
use App\Exception\UserNotFoundException;

final class UserReader
{
    private $userReaderRepository;
    private $userAccountsReader;
    private $userCreatorRepository;

    public function __construct(UserReaderRepository $userReaderRepository, UserAccountsReader $userAccountsReader, UserCreatorRepository $userCreatorRepository)
    {
        $this->userReaderRepository = $userReaderRepository;
        $this->userAccountsReader = $userAccountsReader;
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
                $userData = $this->userAccountsReader->getUserByLogin($login);

                if(!$user || !$user->id) {
                    $user = $this->userCreatorRepository->insertUser($userData);
                } else if($user->type != 4) {
                    // TODO: Some more work to do here
                    $userData->id = $user->id;
                    $userData->memberships = $user->memberships;
                    $user = $this->userCreatorRepository->updateUser($userData);
                }
            }
        }
        return $user;
    }

    public function getUserDetailsByMail(string $mail): User
    {
        // Validation
        if (empty($mail))
            throw new ValidationException('User email required');

        $user = $this->userReaderRepository->getUserByMail($mail);

        if($user->type != 4) {
            $userData = $this->userAccountsReader->getUserByLogin($user->login);
            $userData->id = $user->id;
            $userData->memberships = $user->memberships;
            $user = $this->userCreatorRepository->updateUser($userData);
        }

        return $user;
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
                $userData = $this->userAccountsReader->getUserByCard($card);

                if(!$user || !$user->id) {
                    $user = $this->userCreatorRepository->insertUser($userData);
                } else if($user->type != 4) {
                    $userData->id = $user->id;
                    $userData->memberships = $user->memberships;
                    $user = $this->userCreatorRepository->updateUser($userData);
                }
            }
        }

        return $user;
    }

    public function getUsersDetailsLikeLogin(string $partInfo): Array
    {
        // Validation
        if (empty($partInfo))
            throw new ValidationException('Partial info required');

        $user = $this->userReaderRepository->getUsersLikeLogin($partInfo);

        return $user;
    }
}