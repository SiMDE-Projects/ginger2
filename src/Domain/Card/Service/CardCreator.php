<?php

namespace SIMDE\Ginger\Domain\Card\Service;

use SIMDE\Ginger\Domain\User\Data\User;
use SIMDE\Ginger\Domain\Card\Data\Card;
use SIMDE\Ginger\Domain\Card\Repository\CardCreatorRepository;
use SIMDE\Ginger\Exception\ValidationException;

final class CardCreator
{
    private CardCreatorRepository $cardCreatorRepository;

    public function __construct(
        CardCreatorRepository $cardCreatorRepository
    ) {
        $this->cardCreatorRepository = $cardCreatorRepository;
    }

    public function createCard(User $user, Card $newCard): Card
    {
        $newCard->user_id = $user->id;

        return $this->cardCreatorRepository->insertCard($newCard);
    }

    public function syncCards(User $user, ?Array $accountCards): Array
    {
        //Get cards Acounts returned that are not in user object already
        $missingCards = [];
        foreach($accountCards as $accountCard) {
          $found = false;
          foreach($user->cards as $dbCard) {
            if ($accountCard->uid == $dbCard->uid)
              $found = true;
          }
          if(!$found)
            $missingCards[] = $accountCard;
        }

        foreach($missingCards as $card) {
          $user->cards[] = $this->createCard($user, $card);
        }
            
        $this->flagRemovedCards($user, $accountCards);

        usort($user->cards, function ($a, $b) {
            return $a->type < $b->type;
        });

        return $user->cards;
    }
    
    private function flagRemovedCards(User $user, ?Array $accountCards = []): void
    {
        $missingCards = [];        
        foreach($user->cards as $dbCard) {
          $missing = true;
          foreach($accountCards as $accountCard) {
            if ($accountCard->uid == $dbCard->uid) {
              $missing = false;
              break;
            }
          }
          if($missing)
            $missingCards[] = $dbCard;
        }
        foreach($missingCards as $card)
            $this->cardCreatorRepository->flagRemoved($card);
    }
}