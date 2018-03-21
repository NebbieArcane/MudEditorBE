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

        if (empty($zone)) {
            return $response->withJson(ZoneListController::getAll());
        }
    }

    function dbWrite(Response $response, RequestInterface $request) {

        $this->logger->info("Entro");
        $params = $request->getBody();
        $this->logger->info(print_r($params, true));
        //ZoneListController::createZone($params);
        $r = [
            'status' => 'OK'
        ];

        $this->logger->info($response->getHeaders());
        return $response->withJson($r);
    }

}

