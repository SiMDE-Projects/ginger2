<?php

namespace App\Domain\User\Repository;

use App\Domain\User\Data\UserReaderData;
use DomainException;

/**
 * Repository.
 */
class UserAccountsReaderRepository
{
    /**
     * Constructor.
     */
    public function __construct() {}

    /**
     * Get user by the given user login.
     *
     * @param int $userLogin The user's login
     *
     * @throws DomainException
     *
     * @return UserReaderData The user data
     */
    public function getUserByLogin(string $userLogin): UserReaderData
    {
        //make request
        $userDetails = $this->callAccountsApi("getUserInfo", array("username" => $userLogin));
        return $this->buildUserObject($userDetails);
    }

    /**
     * Get user by any of his cards.
     *
     * @param int $userCard any of the user's cards
     *
     * @throws DomainException
     *
     * @return UserReaderData The user data
     */
    public function getUserByCard(string $userCard): UserReaderData
    {
        //make request
        $userDetails = $this->callAccountsApi("cardLookup", array("serialNumber" => $userCard));
        return $this->buildUserObject($userDetails);
    }

    /**
     * Makes a request to Accounts
     *
     * @param string $endpoint the endpoint on Accounts API
     * @param array $params the different parameters
     *
     * @throws DomainException
     *
     * @return UserReaderData The user data
     */
    public function callAccountsApi(string $endpoint, array $params)
    {
        $url = "http://picasso-ws.localhost/$endpoint?";
        foreach ($params as $key => $param)
            $url .= "$key=$param&";

        $ch = curl_init($url);
        curl_setopt_array($ch, array(
            CURLOPT_USERAGENT => "Ginger",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 1
        ));

        $result = curl_exec($ch);

        if(curl_errno($ch) != 0)
            throw new DomainException("Network exception while calling Accounts");
        elseif(curl_getinfo($ch, CURLINFO_HTTP_CODE) != 200)
            throw new DomainException("Accounts returned " + curl_getinfo($ch, CURLINFO_HTTP_CODE) + " status");
        else
            return json_decode($result);
    }

    private function buildUserObject($accountsData) {
        // Map array to data object
        $user = new UserReaderData();
        $user->login = (string)$accountsData->username;
        $user->mail = (string)$accountsData->mail;
        $user->is_adulte = (bool)$accountsData->legalAge;

        $user->prenom = ucwords(strtolower((string)$accountsData->firstName), "\t-'");
        $user->nom = strtoupper((string)$accountsData->lastName);

        switch((string)$accountsData->profile) {
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
        }

        foreach ($accountsData->cards as $typeCard => $detailCard) {
            $serialArray = str_split($detailCard->cardSerialNumber, 2);
            $detailCard->cardSerialNumber = implode("", array_reverse($serialArray));

            $user->cards[] = array(
                "uid" => strtoupper($detailCard->cardSerialNumber),
                "type" => ($typeCard == "Desfire" ? 1 : 0),
                "created_at" => \DateTime::createFromFormat("U", (int)($detailCard->cardStartDate/1000))->format("Y-m-d H:i:s"),
            );

            usort($user->cards, function ($a, $b) {
                return $a['type'] < $b['type'];
            });
        }
        return $user;
    }
}
