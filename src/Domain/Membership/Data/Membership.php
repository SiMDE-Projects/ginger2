<?php

namespace App\Domain\Membership\Data;

final class Membership
{
    /** @var int */
    public $id;

    /** @var int */
    public $user_id;

    /** @var string */
    public $debut;

    /** @var string */
    public $fin;

    /** @var int */
    public $montant;

    /** @var string */
    public $created_at;

    /** @var string */
    public $deleted_at;
}