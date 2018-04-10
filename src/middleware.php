<?php
use Bairwell\MiddlewareCors;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

// Application middleware
// e.g: $app->add(new \Slim\Csrf\Guard);
//$app->add(new MiddlewareCors($container->get('settings')['cors']));

// create a log channel
/*
$log = new Logger('name');
$log->pushHandler(new StreamHandler('./logs/app.log', Logger::WARNING));

$container = $app->getContainer();

$log->info($container->get('cors'));
*/
$corsParam = [
    'origin' => '*',
    'allowHeaders' => ['Accept', 'Accept-Language', 'Authorization', 'Content-Type', 'DNT', 'Keep-Alive', 'User-Agent', 'X-Requested-With', 'If-Modified-Since', 'Cache-Control', 'Origin'],
];

$app->add(new MiddlewareCors($corsParam));


//$app->add(new MiddlewareCors());
