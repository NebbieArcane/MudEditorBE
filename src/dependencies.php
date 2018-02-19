<?php
use Psr\Container\ContainerInterface;
use Slim\Views\Twig;
use Monolog\Logger;

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
    Logger::class => function (ContainerInterface $c) {
        $conf = $c->get('conf')['logger'];
        $logger = new Logger($conf['name']);
        $logger->pushProcessor(new Monolog\Processor\UidProcessor());
        $logger->pushHandler(new Monolog\Handler\StreamHandler($conf['path'], $conf['level']));
        return $logger;
    }
];

