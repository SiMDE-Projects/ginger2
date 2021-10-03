<?php

namespace SIMDE\Ginger\Domain\Card\Data;

final class Card
{
    /** @var int */
    public $id;

    /** @var int */
    public $user_id;

    /** @var int */
    public $uid;

    /** @var int */
    public $type;

    /** @var DateTime */
    public $created_at;
    
    /** @var DateTime */
    public $last_access;
    
    /** @var DateTime */
    public $removed_at;
}
