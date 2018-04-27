<?php

namespace app\services;

use Psr\Log\LoggerInterface;

/**
 *
 * @author giovanni
 *
 */
class DataParser extends Parser {

    /**
     * @var string
     */
    protected $gitPath;

    /**
     * DataParser constructor.
     * @param Conf $conf
     * @param LoggerInterface $logger
     */
    public function __construct(Conf $conf, LoggerInterface $logger) {
        $this->gitPath = $conf->aree['git'];
        parent::__construct($logger);
    }

    /**
     * @return array|bool
     */
    public function parseIndex() {
        $fname = $this->gitPath . 'aree.index';
        $result = $this->getCachedData($fname);
        if (empty($result)) {
            $list = file($fname);
            $this->logger->info("Reading area list from {$fname}");
            foreach ($list as $zona) {
                list($start, $end, $path, $name) = explode(':', $zona);
                $result[] = ['start' => (int)$start, 'end' => (int)$end, 'path' => trim(basename($path)), 'name' => trim($name)];
            }
            $this->cacheData($fname, $result);
        }
        return $result;
    }

    /**
     * @param string $fname
     * @return bool|array
     */
    protected function getCachedData(string $fname) {
        $cachefile = $fname . '.json';
        if (file_exists($cachefile)) {
            $cached = json_decode(file_get_contents($cachefile), true);

            if ($cached['md5'] = md5_file($fname)) {
                unset($cached['md5']);
                return $cached;
            } else {
                unlink($cachefile);
            }
            /**
             *
             * Scazza con il FE
             *
             * $cached = json_decode(file_get_contents($cachefile));
             * if ($cached->md5 = md5_file($fname)) {
             * unset($cached->md5);
             * $this->logger->debug(var_export($cached, true));
             * return $cached;
             * } else {
             * unlink($cachefile);
             * }
             **/
        }
        return false;
    }

    /**
     * @param string $fname
     * @param array $data
     */
    protected function cacheData(string $fname, array $data): void {
        if (\is_array($data)) {
            $data['md5'] = md5_file($fname);
            file_put_contents($fname . '.json', json_encode($data, JSON_NUMERIC_CHECK | JSON_PRETTY_PRINT));
        } else {
            $data->md5 = md5_file($fname);
            file_put_contents($fname . '.json', json_encode($data, JSON_NUMERIC_CHECK | JSON_PRETTY_PRINT));
        }
    }

    /**
     * @param $zone
     * @return array
     */
    public function parseZone($zone): array {
        $fname = $this->gitPath . "src/$zone/$zone.zon";
        $answer = $this->getCachedData($fname);
        if (empty($answer)) {
            $data = @file_get_contents($fname);
            if ($data) {
                preg_match('/#(\d+)\s*([^~]+)~\s*(\d+)\s*(\d+)\s*(\d+)\s*(.*)/s', $data, $parsed);
            }
            $answer['vnum'] = (int)$parsed[1];
            $answer['name'] = $parsed[2];
            $answer['end'] = (int)$parsed[3];
            $answer['lifespan'] = (int)$parsed[4];
            $answer['resetmode'] = $this->const::resetTypes[$parsed[5]];
            $commands = [];
            foreach (preg_split('/\v+/', $parsed[6]) as $line) {
                list($command, $comment) = explode('*', $line);
                $command = trim($command);
                if (empty($command)) {
                    $code = '*';
                    $command = ['_debug' => $line, 'comment' => $comment, 'code' => $code];
                } else {
                    $match = explode(' ', $command);
                    $code = array_shift($match);
                    $ifFlag = array_shift($match);
                    // Now index in $match is consistent with arg[n] in db.cpp
                    $arg1 = (int)$match[1];
                    $arg2 = (int)$match[2];
                    $arg3 = (int)$match[3];
                    $arg4 = (int)$match[4];
                    $arg5 = (int)$match[5];
                    $command = ['_debug' => $line, 'code' => $code, 'comment' => $comment, 'if' => $ifFlag, 'vnum' => $arg1];
                    // Index 1 is always a vnum
                    switch ($code) {
                        case 'C':
                        case 'M':
                        case 'O':
                            $command['worldCap'] = $arg2;
                            if ($code == 'C') {
                                $command['act'] = $this->parseFlagString($match[3]);
                            } else {
                                $command['room'] = $arg3;
                            }
                            $command['roomCap'] = $arg4;
                            break;
                        case 'E':
                            $command['worldCap'] = $arg2;
                            $command['slot'] = $arg3;
                            $command['slotDesc'] = $this->const::eqSlots[(int)$arg3];
                            break;
                        case 'G':
                            $command['worldCap'] = $arg2;
                            break;
                        case 'R':
                            $command['notImplemented'] = true;
                            $command['room'] = $arg2;
                            $command['roomCap'] = $arg3;
                            break;
                        case 'P':
                            $command['worldCap'] = $arg2;
                            $command['into'] = $arg3;
                            break;
                        case 'D':
                            $command['exit'] = $this->const::exitDir[$arg2];
                            $command['state'] = $this->const::exitStates[$arg3];
                            break;
                        case 'Z':
                            unset($command['vnum']);
                            break;
                        case 'F':
                            unset($command['vnum']);
                            $command['fearType'] = $this->const::fearTypes[$arg1];
                            $command['fearValue'] = $arg2;
                            break;
                        case 'H':
                            unset($command['vnum']);
                            $command['hateType'] = $this->const::fearTypes[$arg1];
                            $command['hateValue'] = $arg2;
                            break;
                    }
                }
                $commands[] = $command;
            }
            $answer['actions'] = $commands;
            $this->cacheData($fname, $answer);
        }
        return $answer;
    }

    /**
     * @param $zone
     * @return array
     */
    public function parseRooms($zone): array {
        $fname = $this->gitPath . "src/$zone/$zone.wld";
        $answer = $this->getCachedData($fname);
        if (empty($answer)) {
            //preg_match_all('/#(\d+)\v+([^~]*)~\v([^~]*)~\v\d+\s+([\d|]+)\s+(\d+)(.*)\vS\s*\v/', file_get_contents($fname),$rooms);
            $body = file_get_contents($fname);
            $exit = '\vD(\d)([^~]*?)~([^~]*?)~\v([-\d]+) ([-\d]+) ([-\d]+) ([-\d]+)';
            $extra = '\v(E)([^~]*?)~([^~]*?)~';
            preg_match_all('/#([-\d]+)\v+([^~]+)~\v([^~]+)~\v([-\d]+)\s+([-\d|]+)\s+([-\d+])(.*?)\vS\v/s', $body, $rooms, PREG_SET_ORDER);
            foreach ($rooms as $room) {
                $room['_debug'] = $room[0];
                unset($room['0']);
                $room['id'] = (int)$room[1];
                unset($room[1]);
                $room['name'] = $room[2];
                unset($room[2]);
                $room['description'] = $room[3];
                unset($room[3]);
                $room['reserved'] = (int)$room[4];
                unset($room[4]);
                $room['_debugFlags'] = $room[5];
                $room['flags'] = $this->parseFlagString($room[5]);
                unset($room[5]);
                $room['_debugSector'] = $room[6];
                $room['sectorType'] = $this->const::sectorTypes[(int)$room[6]];
                unset($room[6]);
                preg_match_all("/$exit/s", $room[7], $exits, PREG_SET_ORDER);
                preg_match_all("/$extra/s", $room[7], $extras, PREG_SET_ORDER);
                unset($room[7]);
                $room['exits'] = $this->parseExits($exits);

                $room['extras'] = $this->parseExtras($extras);
                //Exits and extra are repeatable, they need to be parsed alone
                $answer[$room['id']] = $room;
            }
            $this->cacheData($fname, $answer);
        }
        return $answer;

    }

    /**
     * @param $zone
     * @return array
     */
    public function parseMobs($zone): array {
        $fname = $this->gitPath . "src/$zone/$zone.mob";
        $answer = $this->getCachedData($fname);
        if (empty($answer)) {
            $re = '/#([-\d]+)\v+([^~]+)~\v+([^~]+)~\v+([^~]+)~\v+([^~]+)~\v([-\d|]+)\s([-\d|]+)\s([-\d|]+)\s(\w)(.+?(?=#))/s';
            $body = file_get_contents($fname);
            // Trick to not miss last mob in the file: add '#' at the end
            $body = "$body#";
            preg_match_all($re, $body, $mobs, PREG_SET_ORDER);
            foreach ($mobs as $mob) {
                $mob['_debug'] = $mob[0];
                unset($mob[0]);
                $mob['id'] = (int)$mob[1];
                unset($mob[1]);
                $mob['alias_list'] = $mob[2];
                unset($mob[2]);
                $mob['short_description'] = $mob[3];
                unset($mob[3]);
                $mob['long_description'] = $mob[4];
                unset($mob[4]);
                $mob['detailed_description'] = $mob[5];
                unset($mob[5]);
                $mob['action_bitvector'] = $this->parseActBitVector($mob[6]);
                unset($mob[6]);
                $mob['affected_bitvector'] = $this->parseAffectBitvector($mob[7]);
                unset($mob[7]);
                $mob['align'] = (int)$mob[8];
                unset($mob[8]);
                $mob['type'] = $mob[9];
                unset($mob[9]);
                switch ($mob['type']) {
                    case 'N':
                        $this->parseMobExtra($mob[10], $mob);
                        break;
                    case 'A':
                        $this->parseTypeAMob($mob[10], $mob);
                        break;
                    case 'L':
                        $this->parseTypeLMob($mob[10], $mob);
                        break;
                }
                unset($mob[10]);
                $answer[] = $mob;
            }
            $this->cacheData($fname, $answer);
        }
        return $answer;
    }

    /**
     * @param $zone
     * @return array
     */
    public function parseSpecs($zone): array {
        $fname = $this->gitPath . "src/$zone/$zone.spe";
        $answer = $this->getCachedData($fname);
        if (empty($answer)) {
            $re = '/(\w)\s(\d+)\s(\w+)(.*?(?=\v))/s';
            $body = file_get_contents($fname);
            preg_match_all($re, $body, $specs, PREG_SET_ORDER);
            foreach ($specs as $spec) {
                $spec['_debug'] = $spec[0];
                unset($spec[0]);
                $spec['type'] = $spec[1];
                unset($spec[1]);
                $spec['vnum'] = (int)$spec[2];
                unset($spec[2]);
                $spec['function'] = $spec[3];
                unset($spec[3]);
                $spec['extra'] = trim($spec[4]);
                unset($spec[4]);
                $answer[] = $spec;
            }
            $this->cacheData($fname, $answer);
        }
        return $answer;
    }

    public function parseObjects($zone): array {
        $fname = $this->gitPath . "src/$zone/$zone.obj";
        $answer = $this->getCachedData($fname);
        if (empty($answer)) {
            $re = '/#([-\d]+)\v+([^~]+)~\v+([^~]+)~\v+([^~]+)~(.*?~)\v(.+?(?=#))/s';
            $body = file_get_contents($fname);
            // Trick to not miss last mob in the file: add '#' at the end
            $body = "$body#";
            preg_match_all($re, $body, $objs, PREG_SET_ORDER);
            foreach ($objs as $obj) {
                $obj['_debug'] = $obj[0];
                unset($obj[0]);
                $obj['vnum'] = (int)$obj[1];
                unset($obj[1]);
                $obj['name'] = $this->tildeRework($obj[2]);
                unset($obj[2]);
                $obj['short_desc'] = $this->tildeRework($obj[3]);
                unset($obj[3]);
                $obj['long_desc'] = $this->tildeRework($obj[4]);
                unset($obj[4]);
                $obj['sound'] = $this->tildeRework($obj[5]);
                unset($obj[5]);
                $this->parseObjSecondBlock($obj[6], $obj);
                unset($obj[6]);
                $answer[] = $obj;
            }
            $this->cacheData($fname, $answer);
        }
        return $answer;
    }


}

