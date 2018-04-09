<?php
namespace app\services;

use Psr\Log\LoggerInterface;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 *
 * @author giovanni
 *        
 */
 class Parser
{
    const resetTypes=[
        0=>'Never',
        1=>'IfEmpty',
        2=>'Always',
    ];
    const fearTypes=[
        1=>'OP_SEX',
        2=>'OP_RACE',
        3=>'OP_CHAR',
        4=>'OP_CLASS',
        5=>'OP_EVIL',
        6=>'OP_GOOD',
        7=>'OP_VNUM',
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
    const exitDir=[
        0=>'North',
        1=>'East',
        2=>'South',
        3=>'West',
        4=>'Up',
        5=>'Down'
    ];
    const exitTypes=[
        0=>'NoDoor',
        1=>'Door',
        2=>'PickproofDoor'
    ];
    const exitStates=[
        0=>'Open',
        1=>'Closed',
        2=>'Locked'
    ];
    const roomFlags=[
        1    =>'DARK', //    Room is dark.
        2    =>'DEATH', //  Room is a death trap; char ``dies'' (no xp lost).
        4    =>'NOMOB', //  MOBs (monsters) cannot enter room.
        8    =>'INDOORS', //  Room is indoors.
        16   =>'PEACEFUL', //  Room is peaceful (violence not allowed).
        32   =>'SOUNDPROOF', //  Shouts, gossips, etc. won't be heard in room.
        64   =>'NOTRACK', //  ``track'' can't find a path through this room.
        128  =>'NOMAGIC', //  All magic attempted in this room will fail.
        256  =>'TUNNEL', //  Only one person allowed in room at a time.
        512  =>'PRIVATE', //  Cannot teleport in or GOTO if two people here.
        1024 =>'GODROOM', //  Only LVL_GOD and above allowed to enter.
        2048 =>'HOUSE', //  Reserved for internal use.  Do not set.
        4096 =>'HOUSE_CRASH', //  Reserved for internal use.  Do not set.
        8192 =>'ATRIUM', //  Reserved for internal use.  Do not set.
        16384=>'OLC', //  Reserved for internal use.  Do not set.
        32768=>'BFS_MARK', //  Reserved for internal use.  Do not set.
        32768*2=>'OVERFLOW_1', //  Reserved for internal use.  Do not set.
        32768*4=>'OVERFLOW_2', //  Reserved for internal use.  Do not set.
        
    ];
    const sectorTypes=[
        "Inside",// Indoors (small number of move points needed).
        "City",// The streets of a city.
        "Field", // An open field.
        "Forest",// A dense forest.
        "Hills",// Low foothills.
        "Mountains",// Steep mountain regions.
        "Water Swim",// Water (swimmable).
        "Water NoSwim",// Unswimmable water - boat required for passage.
        "Air",
        "Underwater",
        "Desert",
        "Tree",
        "Dark City",
        0 =>'INSIDE', // Indoors (small number of move points needed).
        1 =>'CITY', // The streets of a city.
        2 =>'FIELD', // An open field.
        3 =>'FOREST', // A dense forest.
        4 =>'HILLS', // Low foothills.
        5 =>'MOUNTAIN', // Steep mountain regions.
        6 =>'WATER_SWIM', // Water (swimmable).
        7 =>'WATER_NOSWIM', // Unswimmable water - boat required for passage.
        8 =>'UNDERWATER', // Underwater.
        9 =>'FLYING' // Wheee!
    ];
    const actFlags=[
        'ACT_SPEC'=>(1<<0),  /* special routine to be called if exist   */
        'ACT_SENTINEL'=>(1<<1),  /* this mobile not to be moved             */
        'ACT_SCAVENGER'=>(1<<2),  /* pick up stuff lying around              */
        'ACT_ISNPC'=>(1<<3),  /* This bit is set for use with IS_NPC()   */
        'ACT_NICE_THIEF'=>(1<<4),  /* Set if a thief should NOT be killed     */
        'ACT_AGGRESSIVE'=>(1<<5),  /* Set if automatic attack on NPC's        */
        'ACT_STAY_ZONE'=>(1<<6),  /* MOB Must stay inside its own zone       */
        'ACT_WIMPY'=>(1<<7),  /* MOB Will flee when injured, and if      */
        /* aggressive only attack sleeping players */
        'ACT_ANNOYING'=>(1<<8),  /* MOB is so utterly irritating that other */
        /* monsters will attack it...              */
        'ACT_HATEFUL'=>(1<<9),  /* MOB will attack a PC or NPC matching a  */
        /* specified name              */  
        'ACT_AFRAID'=>(1<<10),  /* MOB is afraid of a certain PC or NPC,   */
        /* and will always run away ....  */
        'ACT_IMMORTAL'=>(1<<11),  /* MOB is a natural event, can't be kiled  */
        'ACT_HUNTING'=>(1<<12),  /* MOB is hunting someone                  */
        'ACT_DEADLY'=>(1<<13),  /* MOB has deadly poison                   */
        'ACT_POLYSELF'=>(1<<14),  /* MOB is a polymorphed person             */
        'ACT_META_AGG'=>(1<<15),  /* MOB is _very_ aggressive                */
        'ACT_GUARDIAN'=>(1<<16),  /* MOB will guard master                   */
        'ACT_ILLUSION'=>(1<<17),  /* MOB is illusionary                      */
        'ACT_HUGE'=>(1<<18),  /* MOB is too large to go indoors          */
        'ACT_SCRIPT'=>(1<<19),  /* MOB has a script assigned to it DO NOT SET */
        'ACT_GREET'=>(1<<20),  /* MOB greets people */
        'ACT_MAGIC_USER'=>(1<<21),
        'ACT_WARRIOR'=>(1<<22),
        'ACT_CLERIC'=>(1<<23),
        'ACT_THIEF'=>(1<<24),
        'ACT_DRUID'=>(1<<25),
        'ACT_MONK'=>(1<<26),
        'ACT_BARBARIAN'=>(1<<27),
        'ACT_PALADIN'=>(1<<28),
        'ACT_RANGER'=>(1<<29),
        'ACT_PSI'=>(1<<30),
        'ACT_ARCHER'=>(1<<31)
    ];
    

    /**
     * 
     * @var string $gitPath
     * @var LoggerInterface $logger
     */
    protected $gitPath,$logger;
    /**
     */
    public function __construct(Conf $conf,LoggerInterface $logger) {
        $this->gitPath=$conf->aree['git'];
        $this->logger=$logger;
    }
    
    public function parseIndex() {
        $fname=$this->gitPath.'aree.index';
        $result=$this->getCachedData($fname);
        if (empty($result)) {
            $list=file($fname);
            $this->logger->info("Reading area list from {$fname}");
            foreach($list as $zona) {
                list($start,$end,$path,$name)=explode(':',$zona);
                $result[]=['start'=>intval($start),'end'=>intval($end),'path'=>trim(basename($path)),'name'=>trim($name)];
            }
            $this->cacheData($fname, $result);
        }
        return $result;
    }
    public function parseZone($zone) {
        $fname=$this->gitPath."src/$zone/$zone.zon";
        $answer=$this->getCachedData($fname);
        if (empty($answer)) {
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
                    $code=array_shift($match);
                    $ifFlag=array_shift($match);
                    // Now index in $match is consistent with arg[n] in db.cpp
                    $arg1=intval($match[1]);
                    $arg2=intval($match[2]);
                    $arg3=intval($match[3]);
                    $arg4=intval($match[4]);
                    $arg5=intval($match[5]);
                    $command=['_debug'=>$line,'code'=>$code,'comment'=>$comment,'if'=>$ifFlag,'vnum'=>$arg1];
                    // Index 1 is always a vnum
                    switch($code) {
                        case 'C':
                        case 'M':
                        case 'O':
                            $command['worldCap']=$arg2;
                            if ($code=="C") {
                                $command['act']=$this->parseFlagString($match[3]);
                            }
                            else {
                                $command['room']=$arg3;
                            }
                            $command['roomCap']=$arg4;
                            break;
                        case 'E':
                            $command['worldCap']=$arg2;
                            $command['slot']=$arg3;
                            $command['slotDesc']=self::eqSlots[intval($arg3)];
                        case 'G':
                            $command['worldCap']=$arg2;
                            break;
                        case 'R':
                            $command['notImplemented']=true;
                            $command['room']=$arg2;
                            $command['roomCap']=$arg3;
                            break;
                        case 'P':
                            $command['worldCap']=$arg2;
                            $command['into']=$arg3;
                            break;
                        case 'D':
                            $command['exit']=self::exitDir[$arg2];
                            $command['state']=self::exitStates[$arg3];
                            break;
                        case 'Z':
                            unset($command['vnum']);
                            break;
                        case 'F':
                            unset($command['vnum']);
                            $command['fearType']=self::fearTypes[$arg1];
                            $command['fearValue']=$arg2;
                            break;
                        case 'H':
                            unset($command['vnum']);
                            $command['hateType']=self::fearTypes[$arg1];
                            $command['hateValue']=$arg2;
                            break;
                    }
                }
                $commands[]=$command;
            }
            $answer['actions']=$commands;
        }
        return $answer;
    }
    public function parseRooms($zone) {
        $fname=$this->gitPath."src/$zone/$zone.wld";
        $answer=$this->getCachedData($fname);
        if (empty($answer)) {
            //preg_match_all('/#(\d+)\v+([^~]*)~\v([^~]*)~\v\d+\s+([\d|]+)\s+(\d+)(.*)\vS\s*\v/', file_get_contents($fname),$rooms);
            $body=file_get_contents($fname);
            $exit='\vD(\d)([^~]*?)~([^~]*?)~\v([-\d]+) ([-\d]+) ([-\d]+) ([-\d]+)';
            $extra='\v(E)([^~]*?)~([^~]*?)~';
            preg_match_all('/#([-\d]+)\v+([^~]+)~\v([^~]+)~\v([-\d]+)\s+([-\d|]+)\s+([-\d+])(.*?)\vS\v/s', $body,$rooms,PREG_SET_ORDER);
            foreach ($rooms as $room) {
                $room['_debug']=$room[0];unset($room['0']);
                $room['id']=intval($room[1]);unset($room[1]);
                $room['name']=$room[2];unset($room[2]);
                $room['description']=$room[3];unset($room[3]);
                $room['reserved']=intval($room[4]);unset($room[4]);
                $room['_debugFlags']=$room[5];
                $room['flags']=$this->parseFlagString($room[5]);unset($room[5]);
                $room['_debugSector']=$room[6];
                $room['sectorType']=self::sectorTypes[intval($room[6])];unset($room[6]);
                preg_match_all("/$exit/s",$room[7],$exits,PREG_SET_ORDER);
                preg_match_all("/$extra/s",$room[7],$extras,PREG_SET_ORDER);
                unset($room[7]);
                $room['exits']=$this->parseExits($exits);
                
                $room['extras']=$this->parseExtras($extras);
                //Exits and extra are repeatable, they need to be parsed alone
                $answer[$room['id']]=$room;            
            }
        }
        return $answer;
        
    }
    protected function parseExtras($extras) {
        
    }
    protected function parseExits($exits) {
        $result=[];
        array_walk($exits,'trim');
        foreach ($exits as $exit) {
            $exit['_debug']=$exit[0];unset($exit[0]);
            $exit['direction']=self::exitDir[$exit[1]];unset($exit[1]);
            $exit['description']=$exit[2];unset($exit[2]);
            $exit['keywords']=explode(' ',$exit[3]);unset($exit[3]);
            $exit['exitType']=self::exitTypes[$exit[4]];unset($exit[4]);
            $exit['keyVnum']=intval(self::exitTypes[$exit[5]]);unset($exit[5]);
            $exit['nextRoom']=intval(self::exitTypes[$exit[6]]);unset($exit[6]);
            $result[]=$exit;
        }
        return $result;
    }
    protected function parseFlagString($riga) {
        $flags=[];
        foreach (explode('|',$riga) as $digits) {
            if (!empty($digits)) {
                if (is_numeric($digits)) {
                    $digit=intval($digits);
                    foreach (self::roomFlags as $bit=>$desc) {
                        if ($bit & $digit) { //bit set
                            $flags[]=self::roomFlags[intval($digits)];
                        }
                    }
                }
                else {
                    foreach(str_split($digits) as $digit) {
                        $flags[]=self::roomFlags[ord($digit)];
                    }
                    
                }
            }
        }
        return $flags;
        
    }
    protected function cacheData($fname,$data) {
        $data->md5=md5_file($fname);
        file_put_contents($fname.'.json', json_encode($data,JSON_NUMERIC_CHECK | JSON_PRETTY_PRINT));
    }
    protected function getCachedData($fname) {
        $cachefile=$fname.'.json';
        if (file_exists($cachefile)) {
            $cached=json_decode(file_get_contents($cachefile));
            if ($cached->md5=md5_file($fname)) {
                return $cached;
            }
            else {
                unlink($cachefile);
            }
        }
        return false;
    }
}

