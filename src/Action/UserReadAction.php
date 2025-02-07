<?php

namespace SIMDE\Ginger\Action;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SIMDE\Ginger\Domain\Application\Data\Permission;
use SIMDE\Ginger\Domain\Application\Service\ApplicationReaderService;
use SIMDE\Ginger\Domain\Card\Data\Card as DataCard;
use SIMDE\Ginger\Domain\Proxy\UTCotizProxy;
use SIMDE\Ginger\Domain\User\Data\User as DataUser;
use SIMDE\Ginger\Domain\User\Service\UserReader;

/* Get user base on various full informations */

final class UserReadAction
{
    private UserReader $userReader;
    private ApplicationReaderService $applicationReaderService;

    public function __construct(UserReader $userReader, ApplicationReaderService $applicationReaderService,private UTCotizProxy $proxy)
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
        $result = null;
        if (isset($args['login'])) {
            $this->applicationReaderService->checkPermissions(
                $request->getAttribute("application"),
                [
                    Permission::LOGIN_CAN_READ
                ]
            );
            //make request to utcotiz api
            $result = $this->proxy->getUserByLogin((string)$args['login']);
        } else if (isset($args['card'])) {
            $this->applicationReaderService->checkPermissions(
                $request->getAttribute("application"),
                [
                    Permission::CARDS_CAN_READ,
                    Permission::CARDS_CAN_READ_LIST
                ],
                true
            );
            $result = $this->proxy->getUserByCard((string)$args['card']);
        }

        $response->getBody()->write((string)json_encode($result));

        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }
}
