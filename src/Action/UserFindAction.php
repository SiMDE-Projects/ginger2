<?php

namespace SIMDE\Ginger\Action;

use SIMDE\Ginger\Domain\User\Service\UserReader;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/* Find a user based on partial information about him */
final class UserFindAction
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
        // Get all User objects
        $usersData = $this->userReader->getUsersDetailsLikeLogin((string)$args['partinfo']);

        // Only keep the data we want to show
        $result = [];
        foreach ($usersData as $index => $user) {
            $result[$index]["login"] = $user->login;
            $result[$index]["mail"] = $user->mail;
            $result[$index]["nom"] = $user->nom;
            $result[$index]["prenom"] = $user->prenom;
        }

        // Build the HTTP response
        $response->getBody()->write((string)json_encode($result));

        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }
}
