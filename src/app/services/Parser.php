<?php

namespace app\services;

use Psr\Log\LoggerInterface;

/**
 *
 * @author giovanni
 *
 */
class Parser {

    /**
     * @var string $gitPath
     * @var LoggerInterface $logger
     * @var Constants $const
     */
    protected $gitPath, $logger, $const;

    /**
     * Parser constructor.
     * @param Conf $conf
     * @param LoggerInterface $logger
     */
    public function __construct(Conf $conf, LoggerInterface $logger) {
        $this->gitPath = $conf->aree['git'];
        $this->logger = $logger;
        $this->const = new Constants();
    }

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

    protected function getCachedData($fname) {
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

    protected function cacheData($fname, $data) {
        if (\is_array($data)) {
            $data['md5'] = md5_file($fname);
            file_put_contents($fname . '.json', json_encode($data, JSON_NUMERIC_CHECK | JSON_PRETTY_PRINT));
        } else {
            $data->md5 = md5_file($fname);
            file_put_contents($fname . '.json', json_encode($data, JSON_NUMERIC_CHECK | JSON_PRETTY_PRINT));
        }
    }

    public function parseZone($zone) {
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

    protected function parseFlagString($riga) {
        $flags = [];
        foreach (explode('|', $riga) as $digits) {
            if (!empty($digits)) {
                if (is_numeric($digits)) {
                    $digit = (int)$digits;
                    foreach ($this->const::roomFlags as $bit => $desc) {
                        if ($bit & $digit) { //bit set
                            $flags[] = $this->const::roomFlags[(int)$digits];
                        }
                    }
                } else {
                    foreach (str_split($digits) as $digit) {
                        $flags[] = $this->const::roomFlags[ord($digit)];
                    }

                }
            }
        }
        return $flags;

    }

    public function parseRooms($zone) {
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

    protected function parseExits($exits) {
        $result = [];
        //array_walk($exits, 'trim');
        foreach ($exits as $exit) {
            $exit['_debug'] = $exit[0];
            unset($exit[0]);
            $exit['direction'] = $this->const::exitDir[$exit[1]];
            unset($exit[1]);
            $exit['description'] = $exit[2];
            unset($exit[2]);
            $exit['keywords'] = explode(' ', $exit[3]);
            unset($exit[3]);
            $exit['exitType'] = self::exitTypes[$exit[4]];
            unset($exit[4]);
            $exit['keyVnum'] = (int)self::exitTypes[$exit[5]];
            unset($exit[5]);
            $exit['nextRoom'] = (int)self::exitTypes[$exit[6]];
            unset($exit[6]);
            $result[] = $exit;
        }
        return $result;
    }

    /**
     * @param $extras
     * @return array
     */
    protected function parseExtras($extras) {
        $result = [];
        foreach ($extras as $extra) {
            $extra['_debug'] = $extra[0];
            unset($extra[0]);
            $extra['flag'] = $extra[1];
            unset($extra[1]);
            $extra['keyword'] = trim($extra[2]);
            unset($extra[2]);
            $extra['description'] = trim($extra[3]);
            unset($extra[3]);
            $result[] = $extra;
        }
        return $result;
    }

    public function parseMobs($zone) {
        $result = [];
        $fname = $this->gitPath . "src/$zone/$zone.mob";
        $answer = $this->getCachedData($fname);
        if (empty($answer)) {
            $re = '/#([-\d]+)\v+([^~]+)~\v+([^~]+)~\v+([^~]+)~\v+([^~]+)~\v([-\d|]+)\s([-\d|]+)\s([-\d|]+)\s(.*?)\v+([-\d]+)\s([-\d]+)\s([-\d]+)\s([-\d]+)\s([\d]d[\d]\+[\d]+)\v([-\d]+)\s([\d]+)\s([\d]+)\s([\d]+)\v([\d]+)\s([\d]+)\s([\d]+)\s([\d]+)\s([\d|]+)\s([\d]+)/s';
            $body = file_get_contents($fname);
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
                $mob['action_bitvector'] = $this->parseActBv($mob[6]);
                unset($mob[6]);
                $mob['affected_bitvector'] = $this->parseAffectBitvector($mob[7]);
                unset($mob[7]);
                $mob['align'] = (int)$mob[8];
                unset($mob[8]);
                $mob['type'] = $mob[9];
                unset($mob[9]);
                $result[] = $mob;
            }
        }
        return $result;
    }

    protected function parseActBv(string $act) {
        $result = [
            '_debug' => $act,
            'ACT_SPEC' => false,
            'ACT_SENTINEL' => false,
            'ACT_SCAVENGER' => false,
            'ACT_ISNPC' => false,
            'ACT_NICE_THIEF' => false,
            'ACT_AGGRESSIVE' => false,
            'ACT_STAY_ZONE' => false,
            'ACT_WIMPY' => false,
            'ACT_ANNOYING' => false,
            'ACT_HATEFUL' => false,
            'ACT_AFRAID' => false,
            'ACT_IMMORTAL' => false,
            'ACT_HUNTING' => false,
            'ACT_DEADLY' => false,
            'ACT_POLYSELF' => false,
            'ACT_META_AGG' => false,
            'ACT_GUARDIAN' => false,
            'ACT_ILLUSION' => false,
            'ACT_HUGE' => false,
            'ACT_SCRIPT' => false,
            'ACT_GREET' => false,
            'ACT_MAGIC_USER' => false,
            'ACT_WARRIOR' => false,
            'ACT_CLERIC' => false,
            'ACT_THIEF' => false,
            'ACT_DRUID' => false,
            'ACT_MONK' => false,
            'ACT_BARBARIAN' => false,
            'ACT_PALADIN' => false,
            'ACT_RANGER' => false,
            'ACT_PSI' => false,
            'ACT_ARCHER' => false,
        ];

        $acts = explode('|', $act);
        foreach ($acts as $a) {
            $result[$this->const::actFlagsReverse[$a]] = true;
        }
        return $result;
    }

    protected function parseAffectBitvector(string $aff) {
        $result = [
            '_debug' => $aff,
            'AFF_BLIND' => false,
            'AFF_INVISIBLE' => false,
            'AFF_DETECT_ALIGN' => false,
            'AFF_DETECT_INVIS' => false,
            'AFF_DETECT_MAGIC' => false,
            'AFF_SENSE_LIFE' => false,
            'AFF_WATERWALK' => false,
            'AFF_SANCTUARY' => false,
            'AFF_GROUP' => false,
            'AFF_CURSE' => false,
            'AFF_INFRAVISION' => false,
            'AFF_POISON' => false,
            'AFF_PROTECT_EVIL' => false,
            'AFF_PROTECT_GOOD' => false,
            'AFF_SLEEP' => false,
            'AFF_NOTRACK' => false,
            'AFF_UNUSED16' => false,
            'AFF_UNUSED17' => false,
            'AFF_SNEAK' => false,
            'AFF_HIDE' => false,
            'AFF_UNUSED20' => false,
            'AFF_CHARM' => false,
        ];

        $affs = explode('|', $aff);
        foreach ($affs as $a) {
            $result[$this->const::affectionFlagReverse[$a]] = true;
        }
        return $result;
    }
}

