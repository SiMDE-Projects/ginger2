<?php

namespace App\Domain\User\Service;

use App\Domain\User\Data\User;
use App\Domain\User\Service\UserAccountsReader;
use App\Domain\User\Repository\UserReaderRepository;
use App\Domain\User\Repository\UserCreatorRepository;
use App\Exception\ValidationException;

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
        if (empty($login)) {
            throw new ValidationException('User login required', [], 400);
        }

        $user = false;
        try {
            $user = $this->userReaderRepository->getUserByLogin($login);
        } catch(DomainException $e) {}
        finally {
            if(!$user || !$user->id || $user->type != 4) {
                $userData = $this->userAccountsReader->getUserByLogin($login);

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

    public function getUserDetailsByMail(string $mail): User
    {
        // Validation
        if (empty($mail)) {
            throw new ValidationException('User email required', [], 400);
        }

        $user = false;
        try {
            $user = $this->userReaderRepository->getUserByMail($mail);
        } catch(DomainException $e) {
            if($user == false)
                throw new ValidationException("User not found by mail : $mail", [], 404);
        }
        finally {
            if($user && $user->id && $user->type != 4) {
                $userData = $this->userAccountsReader->getUserByLogin($user->login);

                $userData->id = $user->id;
                $userData->memberships = $user->memberships;
                $user = $this->userCreatorRepository->updateUser($userData);
            }
        }
        return $user;
    }

    public function getUserDetailsByCard(string $card): User
    {
        // Validation
        if (empty($card)) {
            throw new ValidationException('Card UID required', [], 400);
        }

        $user = false;
        try {
            $user = $this->userReaderRepository->getUserByCard($card);
        } catch(DomainException $e) {}
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
        if (empty($partInfo)) {
            throw new ValidationException('Partial info required', [], 400);
        }

        $user = $this->userReaderRepository->getUsersLikeLogin($partInfo);

        return $user;
    }
}