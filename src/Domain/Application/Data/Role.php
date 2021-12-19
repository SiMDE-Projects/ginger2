<?php

namespace SIMDE\Ginger\Domain\Application\Data;

use DateTime;

final class Role
{
    /** @var int */
    public int $id;

    /** @var string */
    public string $name;

    /** @var string */
    public string $description;

    /** @var array */
    public array $permissions;

    /** @var DateTime */
    public DateTime $created_at;
}
