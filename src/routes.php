<?php

use Monolog\Logger;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Views\Twig;
use DI\Bridge\Slim\App;
use app\controllers\api;


function authenticate(Request $request,Response $response,$next) {
    return $next($request,$response);
}

// Routes
/**
 * @var App $app
 */
$app->group('/api/v1/', function () {
    $this->get('describe',[api::class,'describe'],$request,$response,$obj1,$obj2);
    $this->get('zones[/{zone}[/rooms[{room}]]]',[api::class,'read']);
}
)->add('authenticate');
$app->get('/test[/{name}]', function (Logger $logger, Twig $renderer, Response $response,string $name=null) {
    // Sample log message
    $logger->info("Slim-Skeleton '/test' route");
    
    // Render index view
    return $renderer->render($response, 'index.twig',['name'=>$name]);
});
$app->get('/', function (Response $response) {
        return $response->withRedirect("/editor/index.html",302);
});
        