<?php

namespace SIMDE\Ginger\Domain\Card\Data;

use DateTime;

final class Card
{
    /** @var int */
    public int $id;

    /** @var int */
    public int $user_id;

    /** @var string */
    public string $uid;

    /** @var int */
    public int $type;

    /** @var DateTime|null */
    public ?DateTime $created_at = null;

    /** @var DateTime|null */
    public ?DateTime $last_access = null;

    /** @var DateTime|null */
    public ?DateTime $removed_at = null;
}
