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
