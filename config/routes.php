<?php

use SIMDE\Ginger\Action\MembershipCreateAction;
use SIMDE\Ginger\Action\MembershipReadAction;
use SIMDE\Ginger\Action\MembershipUpdateAction;
use SIMDE\Ginger\Action\UserFindAction;
use SIMDE\Ginger\Action\UserReadAction;
use Slim\App;

return function (App $app) {

    $app->get(BASE_PATH . '/{login}', UserReadAction::class);
    $app->get(BASE_PATH . '/mail/{mail}', UserReadAction::class);
    $app->get(BASE_PATH . '/badge/{card}', UserReadAction::class);
    $app->get(BASE_PATH . '/find/{partinfo}', UserFindAction::class);
    $app->get(BASE_PATH . '/{login}/cotisations', MembershipReadAction::class);
    // $group->get('/stats', \SIMDE\Ginger\Action\StatsReadAction::class);

    $app->post(BASE_PATH . '/{login}/cotisations', MembershipCreateAction::class);
    $app->put(BASE_PATH . '/{login}/cotisations/{id_membership}', MembershipUpdateAction::class);
    // $group->post('/{login}/edit', \SIMDE\Ginger\Action\UserEditAction::class);

    // $group->delete('/cotisations/{cotisation}', \SIMDE\Ginger\Action\MembershipDeleteAction::class);
};
