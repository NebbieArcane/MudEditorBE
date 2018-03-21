<?php
/**
 * Created by PhpStorm.
 * User: enricomoretti
 * Date: 16/03/18
 * Time: 18:07
 */

namespace app\controllers;

use app\models\ZoneList as zoneListModel;

class ZoneListController {
    public static function createZone(array $fields) {
        $zone = zoneListModel::create($fields);
    }

    public static function getAll(){
        $model = new zoneListModel();
        return $model->findAll();
    }
}