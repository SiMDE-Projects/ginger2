<?php

namespace SIMDE\Ginger\Action;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SIMDE\Ginger\Domain\Membership\Service\MembershipCreator;
use SIMDE\Ginger\Domain\User\Service\UserReader;

/* Creates user membership */

final class MembershipCreateAction
{
    private $userReader;
    private $membershipCreator;

    public function __construct(UserReader $userReader, MembershipCreator $membershipCreator)
    {
        $this->userReader        = $userReader;
        $this->membershipCreator = $membershipCreator;
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface      $response,
        array                  $args = []
    ): ResponseInterface
    {

        // Get POST data, retreive user and create membership
        $data       = (array)json_decode($request->getBody()->getContents());
        $user       = $this->userReader->getUserDetailsByLogin((string)$args['login']);
        $membership = $this->membershipCreator->createMembership($user, $data['debut'], $data['fin'], $data['montant']);

        // Transform the result into the JSON representation
        $result = [
            "result" => $membership->id,
        ];

        // Build the HTTP response
        $response->getBody()->write((string)json_encode($result));

        return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
    }
}
