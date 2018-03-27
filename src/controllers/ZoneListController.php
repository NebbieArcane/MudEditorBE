<?php
/**
 * Created by PhpStorm.
 * User: enricomoretti
 * Date: 16/03/18
 * Time: 18:07
 */

namespace app\controllers;

use app\models\ZoneList as ZoneListModel;

class ZoneListController {

    function createZone(array $fields) {
        $zone = zoneListModel::create($fields);
        return $zone;
    }

    static function getAll(){
        return ZoneListModel::findAll();
    }

    static function findOne(int $id) {
        return ZoneListModel::findOne($id);
    }

    static function findByUserId(int $userId, $log) {
        return ZoneListModel::findByUserId($userId, $log);
    }

}