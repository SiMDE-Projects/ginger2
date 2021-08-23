<?php

namespace App\Action;

use App\Domain\User\Service\UserReader;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Action
 */
final class UserReadAction
{
    /**
     * @var UserReader
     */
    private $userReader;

    /**
     * The constructor.
     *
     * @param UserReader $userReader The user reader
     */
    public function __construct(UserReader $userReader)
    {
        $this->userReader = $userReader;
    }

    /**
     * Invoke.
     *
     * @param ServerRequestInterface $request The request
     * @param ResponseInterface $response The response
     * @param array<mixed> $args The route arguments
     *
     * @return ResponseInterface The response
     */
    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args = []
    ): ResponseInterface {

        // Collect input from the HTTP request && Invoke the Domain with inputs and retain the result
        $userData = null;
        if (isset($args['login']))
            $userData = $this->userReader->getUserDetailsByLogin((string)$args['login']);
        else if (isset($args['mail']))
            $userData = $this->userReader->getUserDetailsByMail((string)$args['mail']);
        else if (isset($args['card']))
            $userData = $this->userReader->getUserDetailsByCard((string)$args['card']);

        // Transform the result into the JSON representation
        $isCotisant = false;
        foreach ($userData->memberships as $mem)
            if(strtotime($mem["debut"]) <= strtotime(date("Y-m-d")) && strtotime($mem["fin"]) >= strtotime(date("Y-m-d")))
                $isCotisant = true;

        $type = "ext";
        switch($userData->type) {
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

        $result = [
            'login' => $userData->login,
            'prenom' => $userData->prenom,
            'nom' => $userData->nom,
            'mail' => $userData->mail,
            'type' => $type,
            'is_adulte' => $userData->is_adulte ? true : false,
            'is_cotisant' => $isCotisant,
            'badge_uid' => empty($userData->cards) ? null : $userData->cards[0]["uid"],
            'cards' => $userData->cards,
            //'memberships' => $userData->memberships,
        ];

        // Build the HTTP response
        $response->getBody()->write((string)json_encode($result));

        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }
}
