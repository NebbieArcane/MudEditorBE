<?php
namespace app\controllers;

use Slim\Http\Response;
use Slim\Http\Request;
use Psr\Log\LoggerInterface;

class api {
    /**
     * 
     * @var Response $response
     * @var Request $request
     */
    
    function __construct($conf,LoggerInterface $logger) {
        $this->conf=$conf['aree'];
        $this->logger=$logger;
    }
    function describe(Request $request,Response $response) {
        $list=get_class_methods(__CLASS__);
        return $response->withJson(["ciao"=>1]);
    }
    function read(Response $response,$zone=null,$room=null) {
        if (empty($zone)) {
            $result=[];
            $fname=$this->conf['git'].'aree.index';
            $list=file($fname);
            $this->logger->info("Reading area list from {$fname}");
            foreach($list as $zona) {
                list($start,$end,$path,$name)=explode(':',$zona);
                $result[]=['start'=>intval($start),'end'=>intval($end),'path'=>basename($path),'name'=>$name];
            }
            return $response->withJson($result);
        }
        if ($zone) {
            $fname=$this->conf['git']."src/$zone/$zone.zon";
            $this->logger->info("Reading init list from {$fname}");
            return $response->withJson(file($fname));
            
        }
        
    }
    
}

