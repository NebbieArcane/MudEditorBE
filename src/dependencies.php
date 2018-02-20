<?php
use Psr\Container\ContainerInterface;
use Slim\Views\Twig;
use Monolog\Logger;
use app\controllers\api;
use Psr\Log\LoggerInterface;



// DIC configuration

return [
    Twig::class => function (ContainerInterface $c) {
        $conf=$c->get('conf')['twig'];
        $twig = new Twig($conf['template_path'], [
            'cache' => $conf['cache_path']
        ]);
        
        $twig->addExtension(new \Slim\Views\TwigExtension(
            $c->get('router'),
            $c->get('request')->getUri()
            ));
        
        return $twig;
    },
    LoggerInterface::class => function (ContainerInterface $c) {
        $conf = $c->get('conf')['logger'];
        $logger = new Logger($conf['name']);
        $logger->pushProcessor(new Monolog\Processor\UidProcessor());
        $logger->pushHandler(new Monolog\Handler\StreamHandler($conf['path'], $conf['level']));
        return $logger;
    },
    api::class => DI\object()
    ->constructorParameter('conf', DI\get('conf')),
];

