<?php

namespace SIMDE\Ginger\Domain\User\Repository;

use DateTime;
use SIMDE\Ginger\Domain\Card\Data\Card;
use SIMDE\Ginger\Domain\User\Data\User;
use SIMDE\Ginger\Exception\AccountsException;
use SIMDE\Ginger\Exception\UserNotFoundException;

/* User accounts */

class UserAccountsReaderRepository
{
    public function __construct()
    {
    }

    public function getUserByLogin(string $userLogin): User
    {
        $userDetails = $this->callAccountsApi("getUserInfo", array("username" => $userLogin));
        return $this->buildUserObject($userDetails);
    }

    public function callAccountsApi(string $endpoint, array $params)
    {
        $url = ACCOUNTS_BASE_URI . "$endpoint?";
        foreach ($params as $key => $param)
            $url .= "$key=$param&";

        $ch = curl_init($url);
        curl_setopt_array($ch, array(
            CURLOPT_USERAGENT      => "Ginger",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 1
        ));

        $result    = curl_exec($ch);
        $errorCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($errorCode == 404)
            throw new UserNotFoundException("User not found");
        elseif (curl_errno($ch) != 0 || $errorCode != 200)
            throw new AccountsException("Network exception while calling Accounts");
        else
            return json_decode($result);
    }

    private function buildUserObject($accountsData): User
    {
        // Map array to data object
        $user            = new User();
        $user->login     = (string)$accountsData->username;
        $user->mail      = (string)$accountsData->mail;
        $user->is_adulte = (bool)$accountsData->legalAge;

        $user->prenom = ucwords(strtolower((string)$accountsData->firstName), "\t-'");
        $user->nom    = strtoupper((string)$accountsData->lastName);

        // No memberships, we come from accounts API
        $user->memberships = [];

        switch ((string)$accountsData->profile) {
            case "ETU UTC":
                $user->type = 0;
                break;
            case "ESCOM ETU":
                $user->type = 1;
                break;
            case "PERSONNEL UTC":
                $user->type = 2;
                break;
            case "PERSONNEL ESCOM":
                $user->type = 3;
                break;
            case "SEJOURNANT":
                $user->type = 5;
                break;
            default:
                $user->type = 99;
                break;
        }

        // Build cards objetcs
        foreach ($accountsData->cards as $typeCard => $detailCard) {
            $serialArray                  = str_split($detailCard->cardSerialNumber, 2);
            $detailCard->cardSerialNumber = implode("", array_reverse($serialArray));

            $card             = new Card;
            $card->uid        = strtoupper($detailCard->cardSerialNumber);
            $card->type       = ($typeCard === "Desfire" ? 1 : 0);
            $card->created_at = DateTime::createFromFormat("U", (int)($detailCard->cardStartDate / 1000));
            $user->cards[]    = $card;
        }

        // Order as cards should be in priority order at all time
        usort($user->cards, function ($a, $b) {
            return $a->type < $b->type;
        });
        return $user;
    }

    public function getUserByCard(string $userCard): User
    {
        $serialArray  = str_split($userCard, 2);
        $serialNumber = implode("", array_reverse($serialArray));
        $userDetails  = $this->callAccountsApi("cardLookup", array("serialNumber" => $serialNumber));
        return $this->buildUserObject($userDetails);
    }
}
