<?php

namespace SIMDE\Ginger\Action;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SIMDE\Ginger\Domain\Application\Data\Permission;
use SIMDE\Ginger\Domain\Application\Service\ApplicationReaderService;
use SIMDE\Ginger\Domain\User\Service\UserReader;

/* Find a user based on partial information about him */

final class UserFindAction
{
    private UserReader $userReader;
    private ApplicationReaderService $applicationReaderService;

    public function __construct(UserReader $userReader, ApplicationReaderService $applicationReaderService)
    {
        $this->userReader = $userReader;
        $this->applicationReaderService = $applicationReaderService;
    }

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
                Permission::MAIL_CAN_READ,
            ]
        );
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
