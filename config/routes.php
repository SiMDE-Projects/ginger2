<?php
use Slim\Routing\RouteCollectorProxy;
use Slim\App;
return function (App $app) {

    $app->get(BASE_PATH . '/{login}', \SIMDE\Ginger\Action\UserReadAction::class);
    $app->get(BASE_PATH . '/mail/{mail}', \SIMDE\Ginger\Action\UserReadAction::class);
    $app->get(BASE_PATH . '/badge/{card}', \SIMDE\Ginger\Action\UserReadAction::class);
    $app->get(BASE_PATH . '/find/{partinfo}', \SIMDE\Ginger\Action\UserFindAction::class);
    $app->get(BASE_PATH . '/{login}/cotisations', \SIMDE\Ginger\Action\MembershipReadAction::class);
    // $group->get('/stats', \SIMDE\Ginger\Action\StatsReadAction::class);

    $app->post(BASE_PATH . '/{login}/cotisations', \SIMDE\Ginger\Action\MembershipCreateAction::class);
    // $group->post('/{login}/edit', \SIMDE\Ginger\Action\UserEditAction::class);

    // $group->delete('/cotisations/{cotisation}', \SIMDE\Ginger\Action\MembershipDeleteAction::class);
};
