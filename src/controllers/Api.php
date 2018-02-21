<?php
namespace app\controllers;

use Psr\Log\LoggerInterface;
use Slim\Http\Request;
use Slim\Http\Response;
use app\services\Conf;
use app\services\Zone;

class Api {
    /**
     * 
     * @var Response $response
     * @var Request $request
     */
    
    function __construct(Conf $conf,LoggerInterface $logger,Zone $zoneService) {
        $this->conf=$conf->aree;
        $this->logger=$logger;
        $this->zoneService=$zoneService;
    }
    function describe(Request $request,Response $response) {
        $ref=new \ReflectionClass($this->zoneService);
        $methods=$ref->getMethods(\ReflectionMethod::IS_PUBLIC);
        /**
         * @var \ReflectionMethod $method
         */
        $answer=[];
        foreach ($methods as $method) {
            if ($method->name!="__construct") {
                $answer[$method->name]=$method->getDocComment();
            }
        }
        return $response->withJson($answer);
    }
    function read(Response $response,$zone=null,$room=null) {
        if (empty($zone)) {
            return $this->zoneService->zones($response);
        }
        if ($zone) {
            return $this->zoneService->zone($response,$zone);
        }
        
    }
    
}

