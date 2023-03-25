<?php

namespace SIMDE\Ginger\Action;

use JsonException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SIMDE\Ginger\Domain\Application\Data\Permission;
use SIMDE\Ginger\Domain\Application\Service\ApplicationReaderService;
use SIMDE\Ginger\Domain\Membership\Data\Membership;
use SIMDE\Ginger\Domain\Membership\Service\MembershipReader;
use SIMDE\Ginger\Domain\Membership\Service\MembershipUpdater;
use SIMDE\Ginger\Domain\User\Service\UserReader;

/* Updates user membership */

final class MembershipUpdateAction
{
    private UserReader $userReader;
    private MembershipUpdater $membershipUpdater;
    private ApplicationReaderService $applicationReaderService;
    private MembershipReader $membershipReader;

    public function __construct(UserReader $userReader, MembershipUpdater $membershipUpdater, MembershipReader $membershipReader, ApplicationReaderService $applicationReaderService)
    {
        $this->userReader = $userReader;
        $this->membershipUpdater = $membershipUpdater;
        $this->applicationReaderService = $applicationReaderService;
        $this->membershipReader = $membershipReader;
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
                Permission::MEMBERSHIPS_CAN_UPDATE
            ]
        );
        $user = $this->userReader->getUserDetailsByLogin((string)$args['login']);
        $membership = $this->membershipReader->getMembershipById($args['id_membership']);
        $data = (array)json_decode($request->getBody()->getContents());
        $m = new Membership();
        $m->id = $data["id"];
        $m->debut = $data["debut"];
        $m->fin = $data["fin"];
        $m->montant = $data["montant"];
        $m->deleted_at = $data["deleted_at"];
        $m->user_id = $user->id;

        if ($membership->user_id !== $user->id) {
            $response->getBody()->write((string)"Forbidden");
            return $response->withHeader('Content-Type', 'application/json')->withStatus(403);
        }

        $this->membershipUpdater->update($m);

        $response->getBody()->write((string)json_encode(["result" => true], JSON_THROW_ON_ERROR));

        return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
    }
}
