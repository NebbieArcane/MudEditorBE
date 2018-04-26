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
            $result[$this->const::actFlags[$a]] = true;
        }
        return $result;
    }

    /**
     * @param string $aff
     * @return array
     */
    protected function parseAffectBitvector(string $aff): array {
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
            $result[$this->const::affectionFlags[$a]] = true;
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
        $result = [
            '_debug' => $aff,
            'FIRE' => false,
            'COLD' => false,
            'ELEC' => false,
            'ENERGY' => false,
            'BLUNT' => false,
            'PIERCE' => false,
            'SLASH' => false,
            'ACID' => false,
            'POISON' => false,
            'DRAIN' => false,
            'SLEEP' => false,
            'CHARM' => false,
            'HOLD' => false,
            'NONMAG' => false,
            'PLUS1' => false,
            'PLUS2' => false,
            'PLUS3' => false,
            'PLUS4' => false,
        ];
        $affs = explode('|', $aff);
        foreach ($affs as $a) {
            $result[$this->const::immunities[$a]] = true;
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
        //prendo gli extra, se ci sono
        $extra = trim(preg_replace($re, '', $rawParams));
        if (!empty($block)) {
            $itemType = (int)$block[0][1];
            $obj['item_type'] = $this->const::itemType[$itemType];
            $obj['item_affect'] = $this->parseItemAffect($block[0][2]);
            $obj['item_wear'] = $this->parseWearFlags($block[0][3]);
        }


    }

    protected function parseItemAffect(string $affString): array {
        $result = [
            'ITEM_GLOW' => false,
            'ITEM_HUM' => false,
            'ITEM_METAL' => false,
            'ITEM_MINERAL' => false,
            'ITEM_ORGANIC' => false,
            'ITEM_INVISIBLE' => false,
            'ITEM_MAGIC' => false,
            'ITEM_NODROP' => false,
            'ITEM_BLESS' => false,
            'ITEM_ANTI_GOOD' => false,
            'ITEM_ANTI_EVIL' => false,
            'ITEM_ANTI_NEUTRAL' => false,
            'ITEM_ANTI_CLERIC' => false,
            'ITEM_ANTI_MAGE' => false,
            'ITEM_ANTI_THIEF' => false,
            'ITEM_ANTI_FIGHTER' => false,
            'ITEM_BRITTLE' => false,
            'ITEM_RESISTANT' => false,
            'ITEM_IMMUNE' => false,
            'ITEM_ANTI_MEN' => false,
            'ITEM_ANTI_WOMEN' => false,
            'ITEM_ANTI_SUN' => false,
            'ITEM_ANTI_BARBARIAN' => false,
            'ITEM_ANTI_RANGER' => false,
            'ITEM_ANTI_PALADIN' => false,
            'ITEM_ANTI_PSI' => false,
            'ITEM_ANTI_MONK' => false,
            'ITEM_ANTI_DRUID' => false,
            'ITEM_ONLY_CLASS' => false,
            'ITEM_DIG' => false,
            'ITEM_SCYTHE' => false,
            'ITEM_ANTI_SORCERER' => false,
        ];
        $affs = explode('|', $affString);
        foreach ($affs as $a) {
            $result[$this->const::itemAffect[$a]] = true;
        }
        return $result;
    }

    protected function parseWearFlags(string $wearFlag): array {
        $result = [
            'ITEM_TAKE' => false,
            'ITEM_WEAR_FINGER' => false,
            'ITEM_WEAR_NECK' => false,
            'ITEM_WEAR_BODY' => false,
            'ITEM_WEAR_HEAD' => false,
            'ITEM_WEAR_LEGS' => false,
            'ITEM_WEAR_FEET' => false,
            'ITEM_WEAR_HANDS' => false,
            'ITEM_WEAR_ARMS' => false,
            'ITEM_WEAR_SHIELD' => false,
            'ITEM_WEAR_ABOUT' => false,
            'ITEM_WEAR_WAISTE' => false,
            'ITEM_WEAR_WRIST' => false,
            'ITEM_WIELD' => false,
            'ITEM_HOLD' => false,
            'ITEM_THROW' => false,
            'ITEM_LIGHT_SOURCE' => false,
            'ITEM_WEAR_BACK' => false,
            'ITEM_WEAR_EAR' => false,
            'ITEM_WEAR_EYE' => false,
        ];
        $affs = explode('|', $wearFlag);
        foreach ($affs as $a) {
            $result[$this->const::wearFlag[$a]] = true;
        }
        return $result;
    }
}