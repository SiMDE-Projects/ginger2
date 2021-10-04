<?php

namespace SIMDE\Ginger\Domain\Application\Data;

final class Role
{
    /** @var int */
    public $id;

    /** @var string */
    public $name;
    
    /** @var string */
    public $description;
    
    /** @var array */
    public $permissions;

    /** @var DateTime */
    public $created_at;
}
