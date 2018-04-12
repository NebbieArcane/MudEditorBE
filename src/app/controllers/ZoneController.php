<?php
/**
 * Created by PhpStorm.
 * User: enricomoretti
 * Date: 27/03/18
 * Time: 18:01
 */

namespace app\controllers;

use app\models\Zone;

class ZoneController {
    static function createZone(array $zone) {
        Zone::create($zone);
    }
}