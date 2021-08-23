<?php

namespace App\Domain\User\Data;

final class User
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

    public function getFullType() {
        $type = "ext";
        switch($this->type) {
            case 0:
                $type = "etu";
                break;
            case 1:
                $type = "escom";
                break;
            case 2:
                $type = "pers";
                break;
            case 2:
                $type = "escompers";
                break;
        }
        return $type;
    }

    public function getCotisationStatus() {
        $isCotisant = false;
        foreach ($this->memberships as $mem)
            if(strtotime($mem["debut"]) <= strtotime(date("Y-m-d")) && strtotime($mem["fin"]) >= strtotime(date("Y-m-d")))
                $isCotisant = true;
        return $isCotisant;
    }
}