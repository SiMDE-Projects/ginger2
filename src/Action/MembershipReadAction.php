<?php

namespace App\Action;

use App\Domain\User\Service\UserReader;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/* Return all memberships of a user */
final class MembershipReadAction
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

        // Get a User from the login
        $userData =  $this->userReader->getUserDetailsByLogin((string)$args['login']);

        // Only keep the memberships
        $membershipsResults = [];
        foreach ($userData->memberships as $membership) {
            $membershipsResults[] = [
                "id" => $membership->id,
                "debut" => $membership->debut,
                "fin" => $membership->fin,
                "montant" => $membership->montant,
            ];
        }
        $result = $membershipsResults;

        // Build the HTTP response
        $response->getBody()->write((string)json_encode($result));

        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }
}
