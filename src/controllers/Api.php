<?php

namespace app\controllers;

use app\services\Conf;
use app\services\Zone;
use Psr\Http\Message\RequestInterface;
use Psr\Log\LoggerInterface;
use Slim\Http\Request;
use Slim\Http\Response;

class Api {
    /**
     *
     * @var Response $response
     * @var Request $request
     */

    function __construct(Conf $conf, LoggerInterface $logger, Zone $zoneService, ZoneListController $zoneList) {
        $this->conf = $conf->aree;
        $this->logger = $logger;
        $this->zoneService = $zoneService;
        $this->zoneList = $zoneList;
    }

    function describe(Request $request, Response $response) {
        // return $response->withJson('{"kakka"}');
        $ref = new \ReflectionClass($this->zoneService);
        $methods = $ref->getMethods(\ReflectionMethod::IS_PUBLIC);
        /**
         * @var \ReflectionMethod $method
         */
        $answer = [];
        foreach ($methods as $method) {
            if ($method->name != "__construct") {
                $answer[$method->name] = $method->getDocComment();
            }
        }
        return $response->withJson($answer);
    }

    function read(Response $response, $zone = null, $room = null) {
        if (empty($zone)) {
            return $this->zoneService->zones($response);
        }
        if ($zone) {
            return $this->zoneService->zone($response, $zone);
        }
    }

    function dbRead(Response $response, $zone = null) {
        $controller = new ZoneListController();
        $controller->setLog($this->logger);

        if (empty($zone)) {
            return $response->withJson($controller->getZoneList());
        } else {
            return $response->withJson(ZoneListController::getZone($zone));
        }
    }

    function dbWrite(Response $response, RequestInterface $request) {
        $r = [
            'status' => 'OK',
            'err_code' => 0,
            'reason' => '',
            'zone' => null
        ];
        try {
            $controller = new ZoneListController();
            $controller->setLog($this->logger);

            $cl = $request->getHeader('Content-Length')[0];
            $params = json_decode($request->getBody()->read($cl), true);
            $zone = $controller->createZone($params);
            $r['zone'] = $zone;
        } catch (\Exception $e) {
            $this->logger->info($e->getMessage());
            $r['status'] = 'KO';
            $r['err_code'] = $e->getCode();
            $r['reason'] = $e->getMessage();
        }
        return $response->withJson($r);
    }

    /*function pippo(Response $response, $uid) {
        return $response->withJson(ZoneListController::findByUserId($uid, $this->logger));
    }*/

}

