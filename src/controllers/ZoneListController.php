<?php
/**
 * Created by PhpStorm.
 * User: enricomoretti
 * Date: 16/03/18
 * Time: 18:07
 */

namespace app\controllers;

use app\models\ZoneIdProvider;
use app\models\ZoneList as ZoneListModel;
use Monolog\Logger;

class ZoneListController {

    /**
     * @var Logger
     */
    private $log;
    /**
     * @var ZoneListModel
     */
    private $model;

    public function __construct() {
        $this->model = new ZoneListModel();
    }

    static function findByUserId(int $userId, $log) {
        return ZoneListModel::findByUserId($userId, $log);
    }

    function getZone(int $id) {
        return $this->model->get($id)->toArray();
    }

    /**
     * @return mixed
     */
    function getZoneList() {
        return $this->model->get()->toArray();
    }

    /**
     * @param array $fields
     * @return ZoneListModel
     */
    function createZone(array $fields) {
        $idProvider = new ZoneIdProvider();

        $id = $idProvider->get()->first();
        $id->id++;

        //$id->save();

        $zoneList = $this->model->get($id->toArray());

        if (empty($zoneList->id)) {

            foreach ($fields as $k => $v) {
                $this->log->info("KEY [$k] - VAL [$v]");
                $this->model->$k = $v;
            }
            $this->model->userId = 1;
            $this->log->info(print_r($this->model->toArray(), true));

            $this->model->save();
        }

        return $this->model - toArray();
    }

    /**
     * @param Logger $log
     */
    public function setLog($log) {
        $this->log = $log;
        return $this;
    }

}