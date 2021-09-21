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
        $user = null;
        if (isset($args['login']))
            $user = $this->userReader->getUserDetailsByLogin((string)$args['login']);
        else if (isset($args['mail']))
            $user = $this->userReader->getUserDetailsByMail((string)$args['mail']);
        else if (isset($args['card']))
            $user = $this->userReader->getUserDetailsByCard((string)$args['card']);

        // Transform the result into the JSON representation
        $cardsResults = [];
        foreach ($user->cards as $card) {
            if($card->removed_at === null) {
              $cardsResults[] = [
                  "uid" => $card->uid,
                  "type" => $card->type,
                  "created_at" => $card->created_at,
              ];
            }
        }

        $result = [
            'login' => $user->login,
            'prenom' => $user->prenom,
            'nom' => $user->nom,
            'mail' => $user->mail,
            'type' => $user->getFullType(),
            'is_adulte' => $user->is_adulte ? true : false,
            'is_cotisant' => $user->getCotisationStatus(),
            'last_access' => $user->last_access,
            'badge_uid' => empty($cardsResults) ? null : $cardsResults[0]["uid"],
            'cards' => $cardsResults,
        ];

        // Build the HTTP response
        $response->getBody()->write((string)json_encode($result));

        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }
}
