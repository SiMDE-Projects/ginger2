<?php
use Slim\Routing\RouteCollectorProxy;
use Slim\App;

return function (App $app) {
    $app->get(BASE_PATH . '/{login}', \App\Action\UserReadAction::class);
    $app->get(BASE_PATH . '/mail/{mail}', \App\Action\UserReadAction::class);
    $app->get(BASE_PATH . '/badge/{card}', \App\Action\UserReadAction::class);
    $app->get(BASE_PATH . '/find/{partinfo}', \App\Action\UserFindAction::class);
    $app->get(BASE_PATH . '/{login}/cotisations', \App\Action\UserMembershipsReadAction::class);
    // $group->get('/stats', \App\Action\StatsReadAction::class);

    $app->post(BASE_PATH . '/{login}/cotisations', \App\Action\UserMembershipCreateAction::class);
    // $group->post('/{login}/edit', \App\Action\UserEditAction::class);

    // $group->delete('/cotisations/{cotisation}', \App\Action\MembershipDeleteAction::class);
};
