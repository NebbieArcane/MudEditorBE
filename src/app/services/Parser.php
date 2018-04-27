<?php
/**
 * Created by PhpStorm.
 * User: enricomoretti
 * Date: 20/04/18
 * Time: 09:53
 */

namespace app\services;


class Parser {
    protected $const, $logger, $gitPath;

    public function __construct($logger) {
        $this->const = new Constants();
        $this->logger = $logger;
    }

    /**
     * @param string $str
     * @return string
     */
    protected function tildeRework(string $str): string {
        $str = trim($str);
        $str = str_ireplace('~', '', $str);
        return $str;
    }

    /**
     * @param $riga
     * @return array
     */
    protected function parseFlagString($riga): array {
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

    /**
     * @param $exits
     * @return array
     */
    protected function parseExits($exits): array {
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
            $exit['exitType'] = $this->const::exitTypes[$exit[4]];
            unset($exit[4]);
            $exit['keyVnum'] = (int)$this->const::exitTypes[$exit[5]];
            unset($exit[5]);
            $exit['nextRoom'] = (int)$this->const::exitTypes[$exit[6]];
            unset($exit[6]);
            $result[] = $exit;
        }
        return $result;
    }

    /**
     * @param $extras
     * @return array
     */
    protected function parseExtras($extras): array {
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

    /**
     * @param string $act
     * @return array
     */
    protected function parseActBitVector(string $act): array {
        $result = [];
        $acts = explode('|', $act);
        foreach ($acts as $a) {
            $result[] = $this->const::actFlags[$a];
        }
        return $result;
    }

    /**
     * @param string $aff
     * @return array
     */
    protected function parseAffectBitvector(string $aff): array {
        $result = [];

        $affs = explode('|', $aff);
        foreach ($affs as $a) {
            $result[] = $this->const::affectionFlags[$a];
        }
        return $result;
    }

    /**
     * @param string $rawParams
     * @param array $mob
     */
    protected function parseTypeAMob(string $rawParams, array &$mob) {
        $typeA = '/([\d]+)\v(.+)\v(.+)\v(.+)/';
        preg_match_all($typeA, $rawParams, $a, PREG_SET_ORDER);
        $mob['num_attack'] = (int)trim($a[0][1]);
        $extra = "{$a[0][2]}\n{$a[0][3]}\n{$a[0][4]}\n#";
        $this->parseMobExtra($extra, $mob);
    }

    /**
     * @param string $rawParams
     * @param array $mob
     */
    protected function parseMobExtra(string $rawParams, array &$mob) {
        $typeN = '/([-\d]+)\s([-\d]+)\s([-\d]+)\s([-\d]+)\s([\d]d[\d]\+[\d]+)\v([-\d]+)\s([\d]+)\s([\d]+)\s([\d]+)\v([\d]+)\s([\d]+)\s([\d]+)\s([\d|]+)\s([\d|]+)\s([\d|]+)/';
        preg_match_all($typeN, $rawParams, $n, PREG_SET_ORDER);

        $mob['level'] = (int)$n[0][1];
        $mob['thac0'] = (int)$n[0][2];
        $mob['ac'] = (int)$n[0][3];
        $mob['hp_bonus'] = (int)$n[0][4];
        $mob['damage'] = $n[0][5];
        if ((int)$n[0][6] < 0) {
            $mob['gold_exp'] = true;
            $mob['gold'] = (int)$n[0][7];
        } else {
            $mob['gold_exp'] = false;
            $mob['gold'] = (int)$n[0][6];
            $mob['exp_mod'] = (int)$n[0][7];
        }
        $mob['bonus_exp'] = (int)$n[0][8];
        $mob['race'] = $this->const::races[(int)$n[0][9]];
        $mob['load_position'] = $this->const::position[$n[0][10]];
        $mob['default_position'] = $this->const::position[$n[0][11]];
        if ($n[0][12] >= 4) {
            $mob['sex'] = $this->const::sex[(int)$n[0][12] - 3];
            $mob['resi'] = $this->parseImmunities($n[0][13]);
            $mob['immu'] = $this->parseImmunities($n[0][14]);
            $mob['susc'] = $this->parseImmunities($n[0][15]);
        } else {
            $mob['sex'] = $this->const::sex[(int)$n[0][12]];
        }
    }

    /**
     * @param string $resi
     * @return array
     */
    protected function parseImmunities($aff): array {
        $result = [];
        $affs = explode('|', $aff);
        foreach ($affs as $a) {
            $result[] = $this->const::immunities[$a];
        }
        return $result;
    }

    /**
     * @param string $rawParams
     * @param array $mob
     */
    protected function parseTypeLMob(string $rawParams, array &$mob) {
        $typeL = '/([\d]+)\v(.+)\v(.+)\v(.+)\v(.*)\v(.*)\v(.*)/';
        preg_match_all($typeL, $rawParams, $l, PREG_SET_ORDER);
        $mob['num_attack'] = (int)trim($l[0][1]);
        $extra = "{$l[0][2]}\n{$l[0][3]}\n{$l[0][4]}\n#";
        $this->parseMobExtra($extra, $mob);
        $mob['in_room_sound'] = $l[0][5];
        $mob['next_room_sound'] = $l[0][7];
    }

    protected function parseObjSecondBlock(string $rawParams, array &$obj) {
        $re = '/([-\d]+)\s([-\d|]+)\s([-\d|]+)\v([-\d]+)\s([-\d]+)\s([-\d]+)\s([-\d+])\v([-\d]+)\s([-\d]+)\s([-\d]+)/';
        preg_match_all($re, $rawParams, $block, PREG_SET_ORDER);
        $extra = preg_replace($re, '', $rawParams);
        if (!empty($block)) {
            $itemType = (int)$block[0][1];
            $typeParser = 'type' . $block[0][1];
            $obj['item_type'] = $this->const::itemType[$itemType];
            $obj['item_affect'] = $this->parseItemAffect($block[0][2]);
            $obj['item_wear'] = $this->parseWearFlags($block[0][3]);
            $objectValues = [
                'val0' => $block[0][4],
                'val1' => $block[0][5],
                'val2' => $block[0][6],
                'val3' => $block[0][7]
            ];
            $obj['obj_gen_values'] = $objectValues;
            $obj['obj_weigth'] = $block[0][8];
            $obj['obj_value'] = $block[0][9];
            $obj['obj_rent_cost'] = $block[0][10];
            $e = preg_split('/\v/', $extra, -1, PREG_SPLIT_NO_EMPTY);
            $extraAffects = [];
            $extraDescription = '';
            if (!empty($e)) {
                $lastEle = '';
                $extraAffects = [];
                $extraDescription = [];
                $extraDescriptionString = '';
                $extraDescCount = 0;
                foreach ($e as $ele) {
                    $ele = trim($ele);
                    switch ($ele) {
                        case 'E':
                            $lastEle = 'E';
                            $extraDescCount = 0;
                            break;
                        case 'A';
                            $lastEle = 'A';
                            break;
                        default:
                            switch ($lastEle) {
                                case 'E':
                                    switch ($extraDescCount) {
                                        case 0:
                                            $extraDescCount++;
                                            $ele = str_ireplace('~', '', $ele);
                                            $keywords = explode(' ', $ele);
                                            $keywordList = [];
                                            foreach ($keywords as $keyword) {
                                                $keywordList[] = $keyword;
                                            }
                                            $extraDescription['keyword_list'] = $keywordList;
                                            break;
                                        default:
                                            $extraDescCount++;
                                            if (strlen($ele) == 1 && strcmp($ele, '~') == 0) {
                                                $extraDescription['description'] = trim($extraDescriptionString);
                                                $extraDescriptionString = '';
                                            } else {
                                                $extraDescriptionString .= ' ' . $ele;
                                            }
                                            break;
                                    }
                                    break;
                                case 'A':
                                    $eleExtraValue = explode(' ', $ele);
                                    $extraAffect = [
                                        $this->const::objExtraAffects[$eleExtraValue[0]] => $eleExtraValue[1]];
                                    $extraAffects[] = $extraAffect;
                                    break;
                            }
                            break;
                    }
                }
            }
            $obj['extra_affects'] = $extraAffects;
            $obj['extra_description'] = $extraDescription;
        }
    }

    protected function parseItemAffect(string $affString): array {
        $result = [];
        $affs = explode('|', $affString);
        foreach ($affs as $a) {
            $result[] = $this->const::itemAffect[$a];
        }
        return $result;
    }

    protected function parseWearFlags(string $wearFlag): array {
        $result = [];
        $affs = explode('|', $wearFlag);
        foreach ($affs as $a) {
            $result[] = $this->const::wearFlag[$a];
        }
        return $result;
    }

    protected function parseShopBitvector(string $shopBv): array {
        $result = [];
        $sbv = explode('|', $shopBv);
        foreach ($sbv as $bit) {
            $result[] = $this->const::shopBV[$bit];
        }
        return $result;
    }

    protected function parseShopWithWho(string $ww): array {
        $result = [];
        $www = explode('|', $ww);
        foreach ($www as $w) {
            $result[] = $this->const::withWho[$w];
        }
        return $result;
    }

}