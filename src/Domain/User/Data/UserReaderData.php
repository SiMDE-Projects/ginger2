<?php

namespace App\Domain\User\Data;

final class UserReaderData
{
    /**
     * @var int
     */
    public $id;

    /** @var string */
    public $username;

    /** @var string */
    public $firstName;

    /** @var string */
    public $lastName;

    /** @var string */
    public $email;
    
    /** @var string */
    public $defaultCard;
    
    /** @var bool */
    public $adult;
    
    /** @var string */
    public $type;
}