<?php

use Monolog\Logger;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Views\Twig;
use DI\Bridge\Slim\App;
use app\controllers\Api;
use Psr\Log\LoggerInterface;


$auth=function (Request $request,Response $response,$next) {
    if ($request->getQueryParam('mammoletta','fumo')!='gemma') {
        return $response->withStatus(404,"User not allowed");
    }
    return $next($request,$response);
};

// Routes
/**
 * @var App $app
 */
$app->group('/api/v1/', function () {
    $this->get('describe',[Api::class,'describe']);
    $this->get('zones[/{zone}[/{type}[/{id}]]]',[Api::class,'read']);
    $this->put('zones[/{zone}[/{type}[/{id}]]]',[Api::class,'write']);
    $this->post('zones[/{zone}[/{type}[/{id}]]]',[Api::class,'create']);
    $this->delete('zones[/{zone}[/{type}[/{id}]]]',[Api::class,'remove']);
}
)->add($auth);

$app->get('/test[/{name}]', function (LoggerInterface $logger, Request $request, Twig $renderer, Response $response,string $name=null) {
    // Sample log message
    $logger->info("Slim-Skeleton '/test' route");
    
    // Render index view
    return $renderer->render($response, 'index.twig',['name'=>$name]);
});
$app->get('/', function (Response $response) {
        return $response->withRedirect("/editor/index.html",302);
});
        