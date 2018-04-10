<?php
namespace app\controllers;

use Psr\Log\LoggerInterface;
use Slim\Http\Request;
use Slim\Http\Response;
use app\services\Conf;
use app\services\Parser;

class Api {
    /**
     * 
     * @var Response $response
     * @var Request $request
     */
    /**
     * 
     * @var \app\services\TypeInterface[] $subservice
     * @var Conf $conf
     * @var LoggerInterface $logger
     * @var parser $parser;
     */
    private $subservice,$conf,$logger,$zoneService;
    
    function __construct(Conf $conf,LoggerInterface $logger,Parser $parser) {
        $this->conf=$conf;
        $this->logger=$logger;
        $this->parser=$parser;
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
    function read(Response $response,$zone=null,$type=null,$id=null) {
        switch ($type) {
            case "rooms": 
                return $this->listRooms($response,$zone,$id);
            case "mobs":
                return $this->listMobs($response,$zone,$id);
            default:
                return $this->listZones($response,$zone,null);
        }
    }
    
    public function listZones(Response $response, $zone = null) {
        if ($zone) {
            return $response->withJson($this->parser->parseZone($zone));
        }
        return $response->withJson($this->parser->parseIndex());
    }
    public function listRooms(Response $response, $zone, $id = null) {
        return $response->withJson($this->parser->parseRooms($zone,$id));
    }
    public function listMobs(Response $response, $zone, $id = null) {
    }
    
}
