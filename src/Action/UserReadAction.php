<?php

namespace App\Action;

use App\Domain\User\Service\UserReader;
use App\Domain\Application\Service\ApplicationReaderService;
use App\Domain\Application\Data\Application;
use App\Domain\Application\Data\Permission;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use App\Exception\ForbiddenException;

/* Get user base on various full informations */
final class UserReadAction
{
    private $userReader;
    private $applicationReaderService;
  
    public function __construct(UserReader $userReader, ApplicationReaderService $applicationReaderService)
    {
        $this->userReader = $userReader;
        $this->applicationReaderService = $applicationReaderService;
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args = []
    ): ResponseInterface {

        // Depending on the information we have, get User object based on it
        $user = null;
        if (isset($args['login'])) {
          //$this->isAllowed($request->getAttribute("application"), Permission::LOGIN_CAN_READ);
          $user = $this->userReader->getUserDetailsByLogin((string)$args['login']);
        } else if (isset($args['mail'])) {
          $user = $this->userReader->getUserDetailsByMail((string)$args['mail']);
        } else if (isset($args['card'])) {
          //$this->isAllowed($request->getAttribute("application"), Permission::LOGIN_CAN_READ);
          $user = $this->userReader->getUserDetailsByCard((string)$args['card']);
        }

        // Transform the result into the JSON representation
        $cardsResults = [];
        foreach ($user->cards as $card) {
            if($card->removed_at === null) {
              $cardsResults[] = [
                  "uid" => $card->uid,
                  "type" => $card->type,
                  "created_at" => $card->created_at,
              ];
            }
        }

        $result = [
            'login' => $user->login,
            'prenom' => $user->prenom,
            'nom' => $user->nom,
            'mail' => $user->mail,
            'type' => $user->getFullType(),
            'is_adulte' => $user->is_adulte ? true : false,
            'is_cotisant' => $user->getCotisationStatus(),
            'last_access' => $user->last_access,
            'badge_uid' => empty($cardsResults) ? null : $cardsResults[0]["uid"],
            'cards' => $cardsResults,
        ];

        // Build the HTTP response
        $response->getBody()->write((string)json_encode($result));

        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }
    
    private function isAllowed(Application $application, string $permission)
    {
      if(!$this->applicationReaderService->isAllowed($application, $permission)){
          throw new ForbiddenException("Missing permission for this application");  
      }
    }
}
