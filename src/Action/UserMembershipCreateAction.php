<?php

namespace App\Action;

use App\Domain\User\Service\UserReader;
use App\Domain\User\Service\UserMembershipCreator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Action
 */
final class UserMembershipCreateAction
{
    /**
     * @var UserReader
     */
    private $userReader;

    /**
     * @var UserMembershipCreator
     */
    private $userMembershipCreator;

    /**
     * The constructor.
     *
     * @param UserReader $userReader The user reader
     */
    public function __construct(UserReader $userReader, UserMembershipCreator $userMembershipCreator)
    {
        $this->userReader = $userReader;
        $this->userMembershipCreator = $userMembershipCreator;
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
        $data = (array)$request->getParsedBody();
        $userData = $this->userReader->getUserDetailsByLogin((string)$args['login']);

        $membershipId = $this->userMembershipCreator->createUserMembership($userData, (string)$data['debut'], (string)$data['fin'], (int)$data['montant']);

        // Transform the result into the JSON representation
        $result = [
            "result" => $membershipId,
        ];

        // Build the HTTP response
        $response->getBody()->write((string)json_encode($result));

        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }
}
