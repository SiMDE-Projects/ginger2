<?php

namespace SIMDE\Ginger\Domain\Application\Data;

final class Permission
{
    const MEMBERSHIPS_CAN_READ    = "MEMBERSHIPS_CAN_READ";
    const MEMBERSHIPS_CAN_CREATE  = "MEMBERSHIPS_CAN_CREATE";
    const MEMBERSHIPS_CAN_UPDATE  = "MEMBERSHIPS_CAN_UPDATE";
    const MEMBERSHIPS_CAN_DELETE  = "MEMBERSHIPS_CAN_DELETE";
    
    const CARDS_CAN_READ          = "CARDS_CAN_READ";
    const CARDS_CAN_READ_LIST     = "CARDS_CAN_READ_LIST";
    const CARDS_CAN_READ_REMOVED  = "CARDS_CAN_READ_REMOVED";
    const CARDS_CAN_CREATE        = "CARDS_CAN_CREATE";
    const CARDS_CAN_UDPATE        = "CARDS_CAN_UDPATE";
    
    const LOGIN_CAN_READ          = "LOGIN_CAN_READ";
    const LOGIN_CAN_UDPATE        = "LOGIN_CAN_UDPATE";
  
    /** @var int */
    public $id;

    /** @var string */
    public $name;
    
    /** @var string */
    public $description;

    /** @var DateTime */
    public $created_at;
}
