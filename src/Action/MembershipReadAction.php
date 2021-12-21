<?php

namespace SIMDE\Ginger\Action;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SIMDE\Ginger\Domain\User\Service\UserReader;

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
        ResponseInterface      $response,
        array                  $args = []
    ): ResponseInterface
    {
        // Get a User from the login
        $user = $this->userReader->getUserDetailsByLogin((string)$args['login']);

        // Only keep the memberships details we want
        $membershipsResults = [];
        foreach ($user->memberships as $membership) {
            $membershipsResults[] = [
                "id"      => $membership->id,
                "debut"   => $membership->debut,
                "fin"     => $membership->fin,
                "montant" => $membership->montant,
            ];
        }
        $result = $membershipsResults;

        // Build the HTTP response
        $response->getBody()->write((string)json_encode($result));

        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }
}
