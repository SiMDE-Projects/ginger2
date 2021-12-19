<?php

namespace SIMDE\Ginger\Action;

use DateTime;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SIMDE\Ginger\Domain\Application\Data\Application;
use SIMDE\Ginger\Domain\Application\Data\Permission;
use SIMDE\Ginger\Domain\Application\Service\ApplicationReaderService;
use SIMDE\Ginger\Domain\Card\Data\Card as DataCard;
use SIMDE\Ginger\Domain\User\Data\User as DataUser;
use SIMDE\Ginger\Domain\User\Service\UserReader;
use SIMDE\Ginger\Exception\ForbiddenException;

/* Get user base on various full informations */

final class UserReadAction
{
    private $userReader;
    private $applicationReaderService;

    public function __construct(UserReader $userReader, ApplicationReaderService $applicationReaderService)
    {
        $this->userReader               = $userReader;
        $this->applicationReaderService = $applicationReaderService;
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface      $response,
        array                  $args = []
    ): ResponseInterface
    {
        $application = $request->getAttribute("application");
        // Depending on the information we have, get User object based on it
        $user = null;
        if (isset($args['login'])) {
            $this->isAllowed($request->getAttribute("application"), [Permission::LOGIN_CAN_READ]);
            $user = $this->userReader->getUserDetailsByLogin((string)$args['login']);
        } else if (isset($args['mail'])) {
            $this->isAllowed($request->getAttribute("application"), [Permission::MAIL_CAN_READ]);
            $user = $this->userReader->getUserDetailsByMail((string)$args['mail']);
        } else if (isset($args['card'])) {
            $this->isAllowed($request->getAttribute("application"), [Permission::CARDS_CAN_READ, Permission::CARDS_CAN_READ_LIST]);
            $user = $this->userReader->getUserDetailsByCard((string)$args['card']);
        }
        $user = $this->applyOverrides($user);

        // Transform the result into the JSON representation
        $cardsResults = [];
        foreach ($user->cards as $card) {
            if ($card->removed_at === null) {
                $cardsResults[] = [
                    "uid"        => $card->uid,
                    "type"       => $card->type,
                    "created_at" => $card->created_at,
                ];
            }
        }

        $result = [
            'login'       => $user->login,
            'prenom'      => $user->prenom,
            'nom'         => $user->nom,
            'mail'        => $user->mail,
            'type'        => $user->getFullType(),
            'is_adulte'   => $user->is_adulte,
            'is_cotisant' => $user->getCotisationStatus(),
            'last_access' => $user->last_access?$user->last_access->format("Y-m-d H:i:s"):null,
        ];

        if ($this->applicationReaderService->isAllowed($application, Permission::CARDS_CAN_READ_LIST)) {
            $result['cards']     = $cardsResults;
            $result['badge_uid'] = empty($cardsResults) ? null : $cardsResults[0]["uid"];
        }
        if ($this->applicationReaderService->isAllowed($application, Permission::CARDS_CAN_READ)) {
            $result['badge_uid'] = empty($cardsResults) ? null : $cardsResults[0]["uid"];
        }

        // Build the HTTP response
        $response->getBody()->write((string)json_encode($result));

        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }

    private function isAllowed(Application $application, array $permissions)
    {
        $allowed = false;
        foreach ($permissions as $permission) {
            $allowed = $this->applicationReaderService->isAllowed($application, $permission);
            if ($allowed) {
                return;
            }
        }
        throw new ForbiddenException("Missing permission for this application");
    }

    private function applyOverrides(DataUser $user)
    {
        if ($user->overrides["prenom"]) {
            $user->prenom = $user->overrides["prenom"];
        }
        if ($user->overrides["nom"]) {
            $user->nom = $user->overrides["nom"];
        }
        if ($user->overrides["mail"]) {
            $user->mail = $user->overrides["mail"];
        }
        if ($user->overrides["type"]) {
            $user->type = $user->overrides["type"];
        }
        if ($user->overrides["is_adulte"] != '') {
            $user->is_adulte = $user->overrides["is_adulte"];
        }
        if ($user->overrides["card"]) {
            $card        = new DataCard();
            $card->uid   = $user->overrides["card"];
            $card->type  = 2;
            $user->cards = [$card];
        }
        return $user;
    }
}
