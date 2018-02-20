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
             'git'=>is_dir('/home/nebbie')?"/home/nebbie/Aree/master/":"/home/giovanni/git/Nebbie/Aree/",
        ]
    ],
];
