<?php

namespace App\Domain\Application\Data;

final class Application
{
    /** @var int */
    public $id;

    /** @var string */
    public $key;
    
    /** @var string */
    public $name;
    
    /** @var string */
    public $owner;
    
    /** @var array */
    public $permissions;

    /** @var DateTime */
    public $created_at;
    
    /** @var DateTime */
    public $last_access;
    
    /** @var DateTime */
    public $removed_at;
}
