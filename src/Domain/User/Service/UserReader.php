<?php

namespace App\Domain\User\Service;

use App\Domain\User\Data\UserReaderData;
use App\Domain\User\Repository\UserReaderRepository;
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
     * The constructor.
     *
     * @param UserReaderRepository $repository The repository
     */
    public function __construct(UserReaderRepository $repository)
    {
        $this->repository = $repository;
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

        $user = $this->repository->getUserByLogin($login);

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

        $user = $this->repository->getUserByMail($mail);

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

        $user = $this->repository->getUserByCard($card);

        return $user;
    }
}