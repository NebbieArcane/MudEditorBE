<?php

use Monolog\Logger;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Views\Twig;

// Routes

$app->get('/editor', function () {
    header("Content-type: text/html");
    readfile(dirname(__DIR__).'/public/editor/index.html');
    // Sample log message
});
$app->get('/[{name}]', function (string $name=null,Logger $logger, Twig $renderer, Response $response) {
    // Sample log message
    $logger->info("Slim-Skeleton '/' route");
    
    // Render index view
    return $renderer->render($response, 'index.twig',['name'=>$name]);
});
        