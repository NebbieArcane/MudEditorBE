<?php
namespace app\services;

use Psr\Log\LoggerInterface;
use Slim\Http\Response;

/**
 *
 * @author giovanni
 *        
 */
class Zone
{

    /**
     */
    const resetTypes=[
        0=>'Never',
        1=>'IfEmpty',
        2=>'Always',
    ];    
    const eqSlots=[
        0  =>  'Used as light',
        1  =>  'Worn on right finger',
        2  =>  'Worn on left finger',
        3  =>  'First object worn around neck',
        4  =>  'Second object worn around neck',
        5  =>  'Worn on body',
        6  =>  'Worn on head',
        7  =>  'Worn on legs',
        8  =>  'Worn on feet',
        9  =>  'Worn on hands',
        10 =>  'Worn on arms',
        11 =>  'Worn as shield',
        12 =>  'Worn about body',
        13 =>  'Worn around waist',
        14 =>  'Worn around right wrist',
        15 =>  'Worn around left wrist',
        16 =>  'Wielded as a weapon',
        17 =>  'Held',
    ];
    const exitTypes=[
        0=>'North',
        1=>'East',
        2=>'South',
        3=>'West',
        4=>'Up',
        5=>'Down'
    ];
    const stateTypes=[
        0=>'Open',
        1=>'Closed',
        2=>'Locked'
    ];
    public function __construct(Conf $conf,LoggerInterface $logger) {
        $this->conf=$conf->aree;
        $this->logger=$logger;
    }
    public function zones(Response $response) {
        $result=[];
        $fname=$this->conf['git'].'aree.index';
        $list=file($fname);
        $this->logger->info("Reading area list from {$fname}");
        foreach($list as $zona) {
            list($start,$end,$path,$name)=explode(':',$zona);
            $result[]=['start'=>intval($start),'end'=>intval($end),'path'=>trim(basename($path)),'name'=>trim($name)];
        }
        return $response->withJson($result);
        
    }
    public function zone(Response $response,string $zone) {
        $fname=$this->conf['git']."src/$zone/$zone.zon";
        $this->logger->info("Reading init list from {$fname}");
        $answer=[];
        $data=@file_get_contents($fname);
        if ($data) {
            preg_match('/#(\d+)\s*([^~]+)~\s*(\d+)\s*(\d+)\s*(\d+)\s*(.*)/s',$data,$parsed);
        }
        $answer['vnum']=intval($parsed[1]);
        $answer['name']=$parsed[2];
        $answer['end']=intval($parsed[3]);
        $answer['lifespan']=intval($parsed[4]);
        $answer['resetmode']=self::resetTypes[$parsed[5]];
        $commands=[];
        foreach(preg_split('/\v+/', $parsed[6]) as $line) {
            list($command,$comment)=explode('*',$line);
            $command=trim($command);
            if (empty($command)) {
                $code='*';
                $command=['_debug'=>$line,'comment'=>$comment,'code'=>$code];
            }
            else {
                $match=explode(' ',$command);
                $code=$match[0];
                $command=['_debug'=>$line,'code'=>$code,'comment'=>$comment,'ifFlag'=>(bool) $match[1],'vnum'=>intval($match[2])];
            }
            switch($code) {
                case 'M':
                case 'O':
                    $command['cap']=intval($match[3]);
                    $command['room']=intval($match[4]);
                    $command['reserved']=intval($match[5]);
                    break;
                case 'E':
                    $command['slot']=intval($match[4]);
                    $command['slotDesc']=self::eqSlots[intval($match[4])];
                case 'G':
                    $command['cap']=intval($match[3]);
                    break;
                case 'R':
                    $command['room']=intval($match[2]);
                    $command['vnum']=intval($match[3]);
                    break;
                case 'B':
                    $command['cap']=intval($match[3]);
                    $command['into']=intval($match[4]);
                    break;
                case 'D':
                    $command['exit']=self::exitTypes[intval($match[3])];
                    $command['state']=self::exitStates[intval($match[4])];
                    break;
            }
            $commands[]=$command;
        }
        $answer['actions']=$commands;
        return $response->withJson([$answer,explode("\n",$data)]);
        
    }
}

