<?php

namespace SIMDE\Ginger\Domain\User\Data;

use DateTime;
use SIMDE\Ginger\Domain\Card\Data\Card;

final class User
{
    /** @var int */
    public int $id;

    /** @var string */
    public string $login;

    /** @var string */
    public string $prenom;

    /** @var string */
    public string $nom;

    /** @var string */
    public string $mail;

    /** @var array */
    public array $overrides = [];

    /** @var int */
    public int $type;

    /** @var bool */
    public bool $is_adulte;

    /** @var Card[] */
    public array $cards = [];

    /** @var array */
    public array $memberships;

    /** @var DateTime|null */
    public ?DateTime $last_access = null;

    /** @var DateTime|null */
    public ?DateTime $last_sync = null;

    /** @var DateTime */
    public DateTime $created_at;

    // Convert int type to string type for old ginger compatibility response
    public function getFullType(): string
    {
        switch ($this->type) {
            case 0:
                $type = "etu";
                break;
            case 1:
                $type = "escom";
                break;
            case 2:
                $type = "pers";
                break;
            case 3:
                $type = "escompers";
                break;
            case 4:
                $type = "ext";
                break;
            case 5:
                $type = "SEJOURNANT";
                break;
            case 6:
                $type = "TEMPORAIRE";
                break;
            case 7:
                $type = "PERSONNEL DE RECHERCHE";
                break;
            case 8:
                $type = "ESCC ETU";
                break;
            case 9:
                $type = "VISITEUR ESCOM";
                break;
            case 10:
                $type = "ESCC PERSONNEL";
                break;
            case 11:
                $type = "ETU THESE";
                break;
            case 12:
                $type = "SEJOURNANT ETU";
                break;
            case 13:
                $type = "SOCIETE";
                break;
            case 14:
                $type = "VISITEUR";
                break;
            case 15:
                $type = "BUTC";
                break;
            default:
                $type = "error";
                break;
        }
        return $type;
    }

    // Based on the memberships determine if one is active
    public function getCotisationStatus(): bool
    {
        $isCotisant = false;
        foreach (array_reverse($this->memberships) as $mem) {
            if (strtotime($mem->debut) <= strtotime(date("Y-m-d")) && strtotime($mem->fin) >= strtotime(date("Y-m-d"))) {
                $isCotisant = true;
                break;
            }
        }
        return $isCotisant;
    }

    /**
     * @return bool
     */
    public function isCacheExpired(): bool
    {
        return $this->last_sync === null || $this->last_sync->diff(new DateTime("now"), true)->i > 5;
    }
}