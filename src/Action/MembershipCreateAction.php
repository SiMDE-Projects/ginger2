<?php

namespace SIMDE\Ginger\Action;

use JsonException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SIMDE\Ginger\Domain\Application\Data\Permission;
use SIMDE\Ginger\Domain\Application\Service\ApplicationReaderService;
use SIMDE\Ginger\Domain\Membership\Service\MembershipCreator;
use SIMDE\Ginger\Domain\User\Service\UserReader;

/* Creates user membership */

final class MembershipCreateAction
{
    private UserReader $userReader;
    private MembershipCreator $membershipCreator;
    private ApplicationReaderService $applicationReaderService;

    public function __construct(UserReader $userReader, MembershipCreator $membershipCreator, ApplicationReaderService $applicationReaderService)
    {
        $this->userReader = $userReader;
        $this->membershipCreator = $membershipCreator;
        $this->applicationReaderService = $applicationReaderService;
    }

    /**
     * @throws JsonException
     */
    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface      $response,
        array                  $args = []
    ): ResponseInterface
    {
        $this->applicationReaderService->checkPermissions(
            $request->getAttribute("application"),
            [
                Permission::LOGIN_CAN_READ,
                Permission::MEMBERSHIPS_CAN_CREATE
            ]
        );
        // Get POST data, retreive user and create membership
        $data = (array)json_decode($request->getBody()->getContents());
        $user = $this->userReader->getUserDetailsByLogin((string)$args['login']);
        $membership = $this->membershipCreator->createMembership($user, $data['debut'], $data['fin'], $data['montant']);

        // Transform the result into the JSON representation
        $result = [
            "result" => $membership->id,
        ];

        // Build the HTTP response
        $response->getBody()->write((string)json_encode($result, JSON_THROW_ON_ERROR));

        return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
    }
}
