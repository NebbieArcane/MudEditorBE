<?php

use Monolog\Logger;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Views\Twig;
use DI\Bridge\Slim\App;
use app\controllers\Api;
use Psr\Log\LoggerInterface;


$auth=function (Request $request,Response $response, $next) {
    if ($request->getQueryParam('mammoletta','fumo')!='gemma') {
        return $response->withStatus(404,"User not allowed");
    }
    return $next($request,$response);
};

// Routes
/**
 * @var App $app
 */


$app->add(function (Request $request, Response $response, $next) {
    if($request->getMethod() !== 'OPTIONS') {
        return $next($request, $response);
    }

    $response = $response->withHeader('Access-Control-Allow-Origin', '*');
    $response = $response->withHeader('Access-Control-Allow-Methods', $request->getHeaderLine('Access-Control-Request-Method'));
    $response = $response->withHeader('Access-Control-Allow-Headers', $request->getHeaderLine('Access-Control-Request-Headers'));

    return $next($request, $response);
});

$app->group('/api/v1/', function () {
    $this->get('describe',[Api::class,'describe']);
    $this->get('zones[/{zone}[/rooms[{room}]]]',[Api::class,'read']);
    $this->put('zones[/{zone}[/rooms[{room}]]]',[Api::class,'write']);
    $this->post('zones[/{zone}[/rooms[{room}]]]',[Api::class,'create']);
    $this->delete('zones[/{zone}[/rooms[{room}]]]',[Api::class,'create']);
    $this->get('dbzones[/{zoneId}]', [Api::class, 'dbRead']);
    $this->any('dbzones[/{zoneId}]', [Api::class, 'dbWrite']);
}
)->add($auth);



$app->get('/test[/{name}]', function (LoggerInterface $logger, Request $request, Twig $renderer, Response $response, string $name=null) {
    // Sample log message
    $logger->info("Slim-Skeleton '/test' route");
    
    // Render index view
    return $renderer->render($response, 'index.twig',['name'=>$name]);
});
$app->get('/', function (Response $response) {
        return $response->withRedirect("/editor/index.html",302);
});

