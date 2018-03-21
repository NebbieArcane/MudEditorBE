<?php
if (PHP_SAPI == 'cli-server') {
    // To help the built-in PHP dev server, check if the request was actually for
    // something which should probably be served as a static file
    $url  = parse_url($_SERVER['REQUEST_URI']);
    $file = __DIR__ . $url['path'];
    if (is_file($file)) {
        return false;
    }
}
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../src/startDb.php';

function print_nice($o) {
    echo '<pre>';
    print_r($o);
    echo '</pre>';
}
use DI\ContainerBuilder;

session_start();


//$app = new \Slim\App($settings);
$app = new class() extends \DI\Bridge\Slim\App {
    protected function configureContainer(ContainerBuilder $builder)
    {
        // Instantiate the app
        /**
         * 
         * @var ContainerBuilder $builder
         */
        $builder->addDefinitions(__DIR__ . '/../src/settings.php');
        $builder->addDefinitions(__DIR__ . '/../src/dependencies.php');
    }
};


// Register middleware
require __DIR__ . '/../src/middleware.php';

// Register routes
require __DIR__ . '/../src/routes.php';

// Run app
$app->run();

