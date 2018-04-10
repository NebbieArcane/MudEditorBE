<?php

use Monolog\Logger;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Views\Twig;
use DI\Bridge\Slim\App;
use app\controllers\Api;
use Psr\Log\LoggerInterface;
use \app\controllers\ZoneListController;


$auth = function (Request $request, Response $response, $next) {
    if ($request->getQueryParam('mammoletta', 'fumo') != 'gemma') {
        return $response->withStatus(404, "User not allowed");
    }
    return $next($request, $response);
};

/*
http://mudbe/api/v1/dbzones
http://mudbe/api/v1/dbzones/1/
http://mudbe/api/v1/dbzones/puzzo/
*/

// Routes
/**
 * @var App $app
 */
$app->group('/api/v1/', function () {
    $this->get('describe', [Api::class, 'describe']);
    $this->get('zones[/{zone}[/rooms[{room}]]]', [Api::class, 'read']);
    $this->put('zones[/{zone}[/rooms[{room}]]]', [Api::class, 'write']);
    $this->post('zones[/{zone}[/rooms[{room}]]]', [Api::class, 'create']);
    $this->delete('zones[/{zone}[/rooms[{room}]]]', [Api::class, 'create']);

    $this->get('dbzones[/{zone}]', [ZoneListController::class, 'getZoneList']);
    $this->post('dbzones[/{zoneId}]', [ZoneListController::class, 'createZone']);


    $this->get('uid[/{uid}]', [Api::class, 'pippo']);
});


$app->get('/test[/{name}]', function (LoggerInterface $logger, Request $request, Twig $renderer, Response $response, string $name = null) {
    // Sample log message
    $logger->info("Slim-Skeleton '/test' route");

    // Render index view
    return $renderer->render($response, 'index.twig', ['name' => $name]);
});
$app->get('/', function (Response $response) {
    return $response->withRedirect("/editor/index.html", 302);
});

