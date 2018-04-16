<?php

use app\services\Conf;
use Monolog\Logger;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Slim\Views\Twig;


// DIC configuration

return [
    Twig::class => function (ContainerInterface $c) {
        $conf = $c->get('conf')['twig'];
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

        /** NEW ***/
        $lineFormatter = new \Monolog\Formatter\LineFormatter();
        $streamHandler = new \Monolog\Handler\StreamHandler($conf['path'], $conf['level']);
        $logger->pushProcessor(new Monolog\Processor\UidProcessor());
        $streamHandler->setFormatter($lineFormatter);
        $logger->pushHandler($streamHandler);

        /** OLD */
        //$logger->pushProcessor(new Monolog\Processor\UidProcessor());
        //$logger->pushHandler(new Monolog\Handler\StreamHandler($conf['path'], $conf['level']));
        return $logger;
    },
    Conf::class => DI\object()
        ->constructorParameter('conf', DI\get('conf')),
];

