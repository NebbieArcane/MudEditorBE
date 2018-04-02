<?php
/**
 * Created by PhpStorm.
 * User: enricomoretti
 * Date: 16/03/18
 * Time: 18:07
 */

namespace app\controllers;

use app\exceptions\ZoneListException;
use app\models\ZoneList;
use Monolog\Logger;
use Psr\Http\Message\RequestInterface;
use Psr\Log\LoggerInterface;
use Slim\Http\Response;

class ZoneListController {

    /**
     * @var Logger
     */
    private $log;
    /**
     * @var ZoneList
     */
    private $model;

    public function __construct(LoggerInterface $logger) {
        $this->log = $logger;
        $this->model = new ZoneList();
        $this->model->setLog($logger);
    }

    function getZone(int $id) {
        return $this->model->get($id)->toArray();
    }

    /**
     * @param Response $response
     * @param null $zone
     * @return mixed
     */
    function getZoneList(Response $response, $zone = null) {
        if (empty($zone)) {
            // all the list
            return $response->withJson($this->model->get()->toArray());
        } else {
            //@TODO: manage single zone
        }
    }

    /**
     * @param Response $response
     * @param RequestInterface $request
     * @return array
     */
    function createZone(Response $response, RequestInterface $request) {

        /**
         * @var ZoneList
         */
        $zone = null;

        $r = [
            'status' => 'OK',
            'err_code' => 0,
            'reason' => '',
            'zone' => null
        ];

        try {
            $cl = $request->getHeader('Content-Length')[0];
            $fields = json_decode($request->getBody()->read($cl), true);

            $zone = $this->model->findByPath($fields['path']);

            if ($zone === null) {
                $zone = new ZoneList();
            } else {
                throw new ZoneListException("Path [{$fields['path']}] already used fon zone [{$zone->name}]", 1000);
            }

            $this->log->debug('Can save new zone');
            foreach ((array)$fields as $k => $v) {
                $zone->$k = $v;
            }

            //@TODO: remove trick for user id when it will be managed
            $zone->userId = 1;
            $this->log->debug('Saving zone: ' . print_r($zone->toArray(), true));

            $zone->save();
            $r['zone'] = $zone->toArray();

        } catch (\Exception $e) {
            $this->log->error($e->getCode() . ' ' . $e->getMessage());
            $r['status'] = 'KO';
            $r['err_code'] = $e->getCode();
            $r['reason'] = $e->getMessage();
        }
        return $response->withJson($r);
    }
}