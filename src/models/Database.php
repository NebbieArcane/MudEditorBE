<?php
/**
 * Created by PhpStorm.
 * User: enricomoretti
 * Date: 16/03/18
 * Time: 17:49
 */

namespace app\models;
use Illuminate\Database\Capsule\Manager as Capsule;

class Database {

    function __construct() {
        $capsule = new Capsule();
        $capsule->addConnection([
            'driver' => DBDRIVER,
            'host' => DBHOST,
            'database' => DBNAME,
            'username' => DBUSER,
            'password' => DBPASS,
            'charset' => 'latin1',
            'collation' => 'latin1_swedish_ci',
            'prefix' => ''
        ]);

        $capsule->bootEloquent();
    }
}