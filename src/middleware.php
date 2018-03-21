<?php
use Bairwell\MiddlewareCors;

// Application middleware

// e.g: $app->add(new \Slim\Csrf\Guard);
$app->add(new MiddlewareCors());
