<?php

namespace App\Action;

use App\Domain\User\Service\UserReader;
use App\Domain\User\Service\UserMembershipCreator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/* Creates user membership */
final class UserMembershipCreateAction
{
    private $userReader;
    private $userMembershipCreator;

    public function __construct(UserReader $userReader, UserMembershipCreator $userMembershipCreator)
    {
        $this->userReader = $userReader;
        $this->userMembershipCreator = $userMembershipCreator;
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args = []
    ): ResponseInterface {

        // Collect input from the HTTP request && Invoke the Domain with inputs and retain the result
        $data = (array)$request->getParsedBody();
        $userData = $this->userReader->getUserDetailsByLogin((string)$args['login']);

        $membershipId = $this->userMembershipCreator->createUserMembership($userData, (string)$data['debut'], (string)$data['fin'], (int)$data['montant']);

        // Transform the result into the JSON representation
        $result = [
            "result" => $membershipId,
        ];

        // Build the HTTP response
        $response->getBody()->write((string)json_encode($result));

        return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
    }
}
