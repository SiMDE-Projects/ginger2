<?php

namespace App\Domain\User\Data;

final class UserReaderData
{
    /** @var int */
    public $id;

    /** @var string */
    public $login;

    /** @var string */
    public $prenom;

    /** @var string */
    public $nom;

    /** @var string */
    public $mail;

    /** @var int */
    public $type;

    /** @var bool */
    public $is_adulte;

    /** @var array */
    public $cards;

    /** @var array */
    public $memberships;
}