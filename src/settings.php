<?php
return [
    'settings.displayErrorDetails' => true, // set to false in production
    'settings.addContentLengthHeader' => false, // Allow the web server to send the content-length header
    'conf' => [
        'twig' => [
            'template_path' => __DIR__ . '/../templates/',
            'cache_path' => __DIR__ . '/cache/',
        ],

        // Monolog settings
        'logger' => [
            'name' => 'slim-app',
            'path' => isset($_ENV['docker']) ? 'php://stdout' : __DIR__ . '/../logs/app.log',
            'level' => \Monolog\Logger::DEBUG,
        ],
        'aree' => [
            'git' => is_dir('/data/mud/') ? "/data/mud/Aree/" : "/data/mud/lib2/",
        ],
        'cors' => [
            'origin' => '*',
            'allowHeaders' => ['Accept', 'Accept-Language', 'Authorization', 'Content-Type', 'DNT', 'Keep-Alive', 'User-Agent', 'X-Requested-With', 'If-Modified-Since', 'Cache-Control', 'Origin'],
        ],
    ],
];
