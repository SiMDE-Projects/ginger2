<?php

use Slim\App;

return function (App $app) {
    $app->get('/', \App\Action\HomeAction::class)->setName('home');
    $app->get('/users/{id}', \App\Action\UserReadAction::class)->setName('users-get');
    $app->post('/users', \App\Action\UserCreateAction::class)->setName('users-post');
    
    // $app->group('/v2', function (Group $group) {
    //   // récupération des stats
    //   $group->get('/stats', function (Request $request, Response $response) {
    //       $response->getBody()->write('TODO stats');
    //       return $response;
    //   });
    // 
    //   // récupération d'un utilisateur
    //   $group->get('/{login}',function (Request $request, Response $response, $args) {
    //   	$r = $myAuth->ginger->getPersonneDetails($login);
    //   	$group->render('success.json.php', array('result'=>$r));
    //   });
    // 
    //   // récupération d'un utilisateur par mail
    //   $group->get('/mail/{mail}', function (Request $request, Response $response, $args) {
    //   	$r = $myAuth->ginger->getPersonneDetailsByMail($mail);
    //   	$group->render('success.json.php', array('result'=>$r));
    //   });
    // 
    //   // récupération d'un utilisateur par badge
    //   $group->get('/badge/{card}', function (Request $request, Response $response, $args) {
    //   	$r = $myAuth->ginger->getPersonneDetailsByCard($card);
    //   	$group->render('success.json.php', array('result'=>$r));
    //   });
    // 
    //   // récupération des cotisations
    //   $group->get('/{login}/cotisations',function (Request $request, Response $response, $args) {
    //   	$r = $myAuth->ginger->getPersonneCotisations($login);
    //   	$group->render('success.json.php', array('result'=>$r));
    //   });
    // 
    //   // recherche d'une personne
    //   $group->get('/find/{loginpart}',function (Request $request, Response $response, $args){
    //   	$r = $myAuth->ginger->findPersonne($loginpart);
    //   	$group->render('success.json.php', array('result'=>$r));
    //   });
    // 
    //   // ajout d'une cotisation
    //   $group->post('/{login}/cotisations',function (Request $request, Response $response, $args) {
    //   	$debut = $args['debut'];
    //   	$fin = $args['fin'];
    //   	$montant = $args['montant'];
    //   	if (empty($debut) or empty($fin) or empty($montant))
    //   		throw new \Koala\ApiException(400);
    // 
    //   	$r = $myAuth->ginger->addCotisation($login, strtotime($debut), strtotime($fin), $montant);
    //   	$group->render('success.json.php', array('result'=>$r));
    //   });
    // 
    //   // Edition des données d'une personne
    //   $group->post('/{login}/edit', function (Request $request, Response $response, $args) {
    //   	$prenom = $args['prenom'];
    //   	$nom =  $args['nom'];
    //   	$mail =  $args['mail'];
    //   	$is_adulte =  $args['is_adulte'];
    //   	if (empty($nom) || empty($prenom) || empty($mail) || empty($is_adulte))
    //   		throw new \Koala\ApiException(400);
    // 
    //   	$r = $myAuth->ginger->setPersonne($login, $prenom, $nom, $mail, $is_adulte);
    //   	$group->render('success.json.php', array('result' => $r));
    //   });
    // 
    //   // suppression d'une cotisation
    //   $group->delete('/cotisations/{cotisation}', function (Request $request, Response $response, $args) {
    //   	$r = $myAuth->ginger->deleteCotisation($cotisation);
    //   	$group->render('success.json.php', array('result'=>$r));
    //   });
    // });
    
};
