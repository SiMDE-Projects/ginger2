<?php

namespace App\Action;

use App\Domain\User\Service\UserReader;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Action
 */
final class UserFindAction
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
        $usersData = $this->userReader->getUsersDetailsLikeLogin((string)$args['partinfo']);

        // Transform the result into the JSON representation
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
