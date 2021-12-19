<?php

namespace SIMDE\Ginger\Domain\Application\Data;

use DateTime;

final class Application
{
    /** @var int */
    public int $id;

    /** @var string */
    public string $key;

    /** @var string */
    public string $name;

    /** @var string */
    public string $owner;

    /** @var array */
    public array $permissions;

    /** @var DateTime */
    public DateTime $created_at;

    /** @var DateTime */
    public DateTime $last_access;

    /** @var DateTime|null */
    public ?DateTime $removed_at;
}
