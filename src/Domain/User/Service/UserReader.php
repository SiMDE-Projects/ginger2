<?php

namespace App\Domain\User\Service;

use App\Domain\User\Data\UserReaderData;
use App\Domain\User\Service\UserAccountsReader;
use App\Domain\User\Repository\UserReaderRepository;
use App\Domain\User\Repository\UserCreatorRepository;
use App\Exception\ValidationException;

/**
 * Service.
 */
final class UserReader
{
    /**
     * @var UserReaderRepository
     */
    private $repository;

    /**
     * @var UserAccountsReader
     */
    private $userAccountsReader;

    /**
     * @var UserCreatorRepository
     */
    private $userCreatorRepository;

    /**
     * The constructor.
     *
     * @param UserReaderRepository $repository The repository
     * @param UserAccountsReader $userAccountsReader The Accounts Reader
     */
    public function __construct(UserReaderRepository $repository, UserAccountsReader $userAccountsReader, UserCreatorRepository $userCreatorRepository)
    {
        $this->repository = $repository;
        $this->userAccountsReader = $userAccountsReader;
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
    public function getUserDetailsByLogin(string $login): UserReaderData
    {
        // Validation
        if (empty($login)) {
            throw new ValidationException('User login required');
        }

        $user = false;
        try {
            $user = $this->repository->getUserByLogin($login);
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

    /**
     * Read a user by the given user mail.
     *
     * @param string $mail The user mail
     *
     * @throws ValidationException
     *
     * @return UserReaderData The user data
     */
    public function getUserDetailsByMail(string $mail): UserReaderData
    {
        // Validation
        if (empty($mail)) {
            throw new ValidationException('User email required');
        }

        $user = false;
        try {
            $user = $this->repository->getUserByMail($mail);
        } catch(DomainException $e) {
            if($user == false)
                throw new ValidationException("User not found by mail : $mail");
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

    /**
     * Read a user by the given card uid.
     *
     * @param string $card The card uid
     *
     * @throws ValidationException
     *
     * @return UserReaderData The user data
     */
    public function getUserDetailsByCard(string $card): UserReaderData
    {
        // Validation
        if (empty($card)) {
            throw new ValidationException('Card UID required');
        }

        $user = false;
        try {
            $user = $this->repository->getUserByCard($card);
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

    /**
     * Read users like partInfo.
     *
     * @param string $partInfo a partial information about any user
     *
     * @throws ValidationException
     *
     * @return UserReaderData[] The users data
     */
    public function getUsersDetailsLikeLogin(string $partInfo): Array
    {
        // Validation
        if (empty($partInfo)) {
            throw new ValidationException('Partial info required');
        }

        $user = $this->repository->getUsersLikeLogin($partInfo);

        return $user;
    }
}