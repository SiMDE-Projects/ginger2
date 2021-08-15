<?php
use Slim\Routing\RouteCollectorProxy;
use Slim\App;

return function (App $app) {
    $app->get('/{login}', \App\Action\UserReadAction::class);
    $app->get('/mail/{mail}', \App\Action\UserReadAction::class);
    $app->get('/badge/{card}', \App\Action\UserReadAction::class);
    // $app->get('/find/{loginpart}', \App\Action\UserFindAction::class);
    $app->get('/{login}/cotisations', \App\Action\UserMembershipsReadAction::class);
    // $group->get('/stats', \App\Action\StatsReadAction::class);

    // $group->post('/{login}/cotisations', \App\Action\UserMembershipsCreateAction::class);
    // $group->post('/{login}/edit', \App\Action\UserEditAction::class);

    // $group->delete('/cotisations/{cotisation}', \App\Action\MembershipDeleteAction::class);
};
