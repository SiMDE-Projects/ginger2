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
    public function getUserDetails(string $login): UserReaderData
    {
        // Validation
        if (empty($login)) {
            throw new ValidationException('User login required');
        }

        $user = $this->repository->getUserByLogin($login);

        return $user;
    }
}