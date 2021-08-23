<?php

namespace App\Action;

use App\Domain\User\Service\UserReader;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/* Get user base on various full informations */
final class UserReadAction
{
    private $userReader;

    public function __construct(UserReader $userReader)
    {
        $this->userReader = $userReader;
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args = []
    ): ResponseInterface {

        // Depending on the information we have, get User object based on it
        $userData = null;
        if (isset($args['login']))
            $userData = $this->userReader->getUserDetailsByLogin((string)$args['login']);
        else if (isset($args['mail']))
            $userData = $this->userReader->getUserDetailsByMail((string)$args['mail']);
        else if (isset($args['card']))
            $userData = $this->userReader->getUserDetailsByCard((string)$args['card']);

        // Transform the result into the JSON representation
        $result = [
            'login' => $userData->login,
            'prenom' => $userData->prenom,
            'nom' => $userData->nom,
            'mail' => $userData->mail,
            'type' => $userData->getFullType(),
            'is_adulte' => $userData->is_adulte ? true : false,
            'is_cotisant' => $userData->getCotisationStatus(),
            'badge_uid' => empty($userData->cards) ? null : $userData->cards[0]["uid"],
            'cards' => $userData->cards,
        ];

        // Build the HTTP response
        $response->getBody()->write((string)json_encode($result));

        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }
}
