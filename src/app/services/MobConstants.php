<?php
/**
 * Created by PhpStorm.
 * User: enricomoretti
 * Date: 26/04/18
 * Time: 14:44
 */

namespace app\services;


class MobConstants extends WorldConstants {
    /**
     * MOB CONSTANTS
     */
    const actFlags = [
        'ACT_SPEC' => (1 << 0),  /* special routine to be called if exist   */
        'ACT_SENTINEL' => (1 << 1),  /* this mobile not to be moved             */
        'ACT_SCAVENGER' => (1 << 2),  /* pick up stuff lying around              */
        'ACT_ISNPC' => (1 << 3),  /* This bit is set for use with IS_NPC()   */
        'ACT_NICE_THIEF' => (1 << 4),  /* Set if a thief should NOT be killed     */
        'ACT_AGGRESSIVE' => (1 << 5),  /* Set if automatic attack on NPC's        */
        'ACT_STAY_ZONE' => (1 << 6),  /* MOB Must stay inside its own zone       */
        'ACT_WIMPY' => (1 << 7),  /* MOB Will flee when injured, and if      */
        /* aggressive only attack sleeping players */
        'ACT_ANNOYING' => (1 << 8),  /* MOB is so utterly irritating that other */
        /* monsters will attack it...              */
        'ACT_HATEFUL' => (1 << 9),  /* MOB will attack a PC or NPC matching a  */
        /* specified name              */
        'ACT_AFRAID' => (1 << 10),  /* MOB is afraid of a certain PC or NPC,   */
        /* and will always run away ....  */
        'ACT_IMMORTAL' => (1 << 11),  /* MOB is a natural event, can't be kiled  */
        'ACT_HUNTING' => (1 << 12),  /* MOB is hunting someone                  */
        'ACT_DEADLY' => (1 << 13),  /* MOB has deadly poison                   */
        'ACT_POLYSELF' => (1 << 14),  /* MOB is a polymorphed person             */
        'ACT_META_AGG' => (1 << 15),  /* MOB is _very_ aggressive                */
        'ACT_GUARDIAN' => (1 << 16),  /* MOB will guard master                   */
        'ACT_ILLUSION' => (1 << 17),  /* MOB is illusionary                      */
        'ACT_HUGE' => (1 << 18),  /* MOB is too large to go indoors          */
        'ACT_SCRIPT' => (1 << 19),  /* MOB has a script assigned to it DO NOT SET */
        'ACT_GREET' => (1 << 20),  /* MOB greets people */
        'ACT_MAGIC_USER' => (1 << 21),
        'ACT_WARRIOR' => (1 << 22),
        'ACT_CLERIC' => (1 << 23),
        'ACT_THIEF' => (1 << 24),
        'ACT_DRUID' => (1 << 25),
        'ACT_MONK' => (1 << 26),
        'ACT_BARBARIAN' => (1 << 27),
        'ACT_PALADIN' => (1 << 28),
        'ACT_RANGER' => (1 << 29),
        'ACT_PSI' => (1 << 30),
        'ACT_ARCHER' => (1 << 31),
        (1 << 0) => 'ACT_SPEC',
        (1 << 1) => 'ACT_SENTINEL',
        (1 << 2) => 'ACT_SCAVENGER',
        (1 << 3) => 'ACT_ISNPC',
        (1 << 4) => 'ACT_NICE_THIEF',
        (1 << 5) => 'ACT_AGGRESSIVE',
        (1 << 6) => 'ACT_STAY_ZONE',
        (1 << 7) => 'ACT_WIMPY',
        (1 << 8) => 'ACT_ANNOYING',
        (1 << 9) => 'ACT_HATEFUL',
        (1 << 10) => 'ACT_AFRAID',
        (1 << 11) => 'ACT_IMMORTAL',
        (1 << 12) => 'ACT_HUNTING',
        (1 << 13) => 'ACT_DEADLY',
        (1 << 14) => 'ACT_POLYSELF',
        (1 << 15) => 'ACT_META_AGG',
        (1 << 16) => 'ACT_GUARDIAN',
        (1 << 17) => 'ACT_ILLUSION',
        (1 << 18) => 'ACT_HUGE',
        (1 << 19) => 'ACT_SCRIPT',
        (1 << 20) => 'ACT_GREET',
        (1 << 21) => 'ACT_MAGIC_USER',
        (1 << 22) => 'ACT_WARRIOR',
        (1 << 23) => 'ACT_CLERIC',
        (1 << 24) => 'ACT_THIEF',
        (1 << 25) => 'ACT_DRUID',
        (1 << 26) => 'ACT_MONK',
        (1 << 27) => 'ACT_BARBARIAN',
        (1 << 28) => 'ACT_PALADIN',
        (1 << 29) => 'ACT_RANGER',
        (1 << 30) => 'ACT_PSI',
        (1 << 31) => 'ACT_ARCHER',
    ];

    const affectionFlags = [
        'AFF_NONE' => 0,
        'AFF_BLIND' => 1 << 0,
        'AFF_INVISIBLE' => 1 << 1,
        'AFF_DETECT_EVIL' => 1 << 2,
        'AFF_DETECT_INVISIBLE' => 1 << 3,
        'AFF_DETECT_MAGIC' => 1 << 4,
        'AFF_SENSE_LIFE' => 1 << 5,
        'AFF_LIFE_PROT' => 1 << 6,
        'AFF_SANCTUARY' => 1 << 7,
        'AFF_DRAGON_RIDE' => 1 << 8,
        'AFF_GROWTH' => 1 << 9,
        'AFF_CURSE' => 1 << 10,
        'AFF_FLYING' => 1 << 11,
        'AFF_POISON' => 1 << 12,
        'AFF_TREE_TRAVEL' => 1 << 13,
        'AFF_PARALYSIS' => 1 << 14,
        'AFF_INFRAVISION' => 1 << 15,
        'AFF_WATERBREATH' => 1 << 16,
        'AFF_SLEEP' => 1 << 17,
        'AFF_TRAVELLING' => 1 << 18,
        'AFF_SNEAK' => 1 << 19,
        'AFF_HIDE' => 1 << 20,
        'AFF_SILENCE' => 1 << 21,
        'AFF_CHARM' => 1 << 22,
        'AFF_FOLLOW' => 1 << 23,
        'AFF_PROTECT_FROM_EVIL' => 1 << 24,
        'AFF_TRUE_SIGHT' => 1 << 25,
        'AFF_SCRYING' => 1 << 26,
        'AFF_FIRESHIELD' => 1 << 27,
        'AFF_GROUP' => 1 << 28,
        'AFF_TELEPATHY' => 1 << 29,
        'AFF_GLOBE_DARKNESS' => 1 << 30,
        'AFF_UNDEF_AFF_1' => 1 << 31,
        0 => 'AFF_NONE',
        1 << 0 => 'AFF_BLIND',
        1 << 1 => 'AFF_INVISIBLE',
        1 << 2 => 'AFF_DETECT_EVIL',
        1 << 3 => 'AFF_DETECT_INVISIBLE',
        1 << 4 => 'AFF_DETECT_MAGIC',
        1 << 5 => 'AFF_SENSE_LIFE',
        1 << 6 => 'AFF_LIFE_PROT',
        1 << 7 => 'AFF_SANCTUARY',
        1 << 8 => 'AFF_DRAGON_RIDE',
        1 << 9 => 'AFF_GROWTH',
        1 << 10 => 'AFF_CURSE',
        1 << 11 => 'AFF_FLYING',
        1 << 12 => 'AFF_POISON',
        1 << 13 => 'AFF_TREE_TRAVEL',
        1 << 14 => 'AFF_PARALYSIS',
        1 << 15 => 'AFF_INFRAVISION',
        1 << 16 => 'AFF_WATERBREATH',
        1 << 17 => 'AFF_SLEEP',
        1 << 18 => 'AFF_TRAVELLING',
        1 << 19 => 'AFF_SNEAK',
        1 << 20 => 'AFF_HIDE',
        1 << 21 => 'AFF_SILENCE',
        1 << 22 => 'AFF_CHARM',
        1 << 23 => 'AFF_FOLLOW ',
        1 << 24 => 'AFF_PROTECT_FROM_EVIL',
        1 << 25 => 'AFF_TRUE_SIGHT',
        1 << 26 => 'AFF_SCRYING',
        1 << 27 => 'AFF_FIRESHIELD',
        1 << 28 => 'AFF_GROUP',
        1 << 29 => 'AFF_TELEPATHY',
        1 << 30 => 'AFF_GLOBE_DARKNESS',
        1 << 31 => 'AFF_UNDEF_AFF_1',
    ];

    const position = [
        0 => 'POSITION_DEAD',       /* Reserved for internal use.  Do not set. */
        1 => 'POSITION_MORTALLYW',  /* Reserved for internal use.  Do not set. */
        2 => 'POSITION_INCAP',      /* Reserved for internal use.  Do not set. */
        3 => 'POSITION_STUNNED',    /* Reserved for internal use.  Do not set. */
        4 => 'POSITION_SLEEPING',   /* The monster is sleeping. */
        5 => 'POSITION_RESTING',    /* The monster is resting. */
        6 => 'POSITION_SITTING',    /* The monster is sitting. */
        7 => 'POSITION_FIGHTING',   /* Reserved for internal use.  Do not set. */
        8 => 'POSITION_STANDING',   /* The monster is standing. */
    ];

    const races = [
        0 => 'RACE_HALFBREED',
        1 => 'RACE_HUMAN',
        2 => 'RACE_ELVEN',
        3 => 'RACE_DWARF',
        4 => 'RACE_HALFLING',
        5 => 'RACE_GNOME',
        6 => 'RACE_REPTILE',
        7 => 'RACE_SPECIAL',
        8 => 'RACE_LYCANTH',
        9 => 'RACE_DRAGON',
        10 => 'RACE_UNDEAD',
        11 => 'RACE_ORC',
        12 => 'RACE_INSECT',
        13 => 'RACE_ARACHNID',
        14 => 'RACE_DINOSAUR',
        15 => 'RACE_FISH',
        16 => 'RACE_BIRD',
        17 => 'RACE_GIANT',
        18 => 'RACE_PREDATOR',
        19 => 'RACE_PARASITE',
        20 => 'RACE_SLIME',
        21 => 'RACE_DEMON',
        22 => 'RACE_SNAKE',
        23 => 'RACE_HERBIV',
        24 => 'RACE_TREE',
        25 => 'RACE_VEGGIE',
        26 => 'RACE_ELEMENT',
        27 => 'RACE_PLANAR',
        28 => 'RACE_DEVIL',
        29 => 'RACE_GHOST',
        30 => 'RACE_GOBLIN',
        31 => 'RACE_TROLL',
        32 => 'RACE_VEGMAN',
        33 => 'RACE_MFLAYER',
        34 => 'RACE_PRIMATE',
        35 => 'RACE_ENFAN',
        36 => 'RACE_DARK_ELF',
        37 => 'RACE_GOLEM',
        38 => 'RACE_SKEXIE',
        39 => 'RACE_TROGMAN',
        40 => 'RACE_PATRYN',
        41 => 'RACE_LABRAT',
        42 => 'RACE_SARTAN',
        43 => 'RACE_TYTAN',
        44 => 'RACE_SMURF',
        45 => 'RACE_ROO',
        46 => 'RACE_HORSE',
        47 => 'RACE_DRAAGDIM',
        48 => 'RACE_ASTRAL',
        49 => 'RACE_GOD',
        50 => 'RACE_GIANT_HILL',
        51 => 'RACE_GIANT_FROST',
        52 => 'RACE_GIANT_FIRE',
        53 => 'RACE_GIANT_CLOUD',
        54 => 'RACE_GIANT_STORM',
        55 => 'RACE_GIANT_STONE',
        56 => 'RACE_DRAGON_RED',
        57 => 'RACE_DRAGON_BLACK',
        58 => 'RACE_DRAGON_GREEN',
        59 => 'RACE_DRAGON_WHITE',
        60 => 'RACE_DRAGON_BLUE',
        61 => 'RACE_DRAGON_SILVER',
        62 => 'RACE_DRAGON_GOLD',
        63 => 'RACE_DRAGON_BRONZE',
        64 => 'RACE_DRAGON_COPPER',
        65 => 'RACE_DRAGON_BRASS',
        66 => 'RACE_UNDEAD_VAMPIRE',
        67 => 'RACE_UNDEAD_LICH',
        68 => 'RACE_UNDEAD_WIGHT',
        69 => 'RACE_UNDEAD_GHAST',
        70 => 'RACE_UNDEAD_SPECTRE',
        71 => 'RACE_UNDEAD_ZOMBIE',
        72 => 'RACE_UNDEAD_SKELETO',
        73 => 'RACE_UNDEAD_GHOUL',
        74 => 'RACE_HALF_ELVEN',
        75 => 'RACE_HALF_OGRE',
        76 => 'RACE_HALF_ORC',
        77 => 'RACE_HALF_GIANT',
        78 => 'RACE_LIZARDMAN',
        79 => 'RACE_DARK_DWARF',
        80 => 'RACE_DEEP_GNOME',
        81 => 'RACE_GNOLL',
        82 => 'RACE_GOLD_ELF',
        83 => 'RACE_WILD_ELF',
        84 => 'RACE_SEA_ELF',
    ];

    const sex = [
        0 => 'NEUTRAL',
        1 => 'MALE',
        2 => 'FEMALE',
    ];

    const immunities = [
        'NONE' => 0,
        'FIRE' => 1 << 0,
        'COLD' => 1 << 1,
        'ELEC' => 1 << 2,
        'ENERGY' => 1 << 3,
        'BLUNT' => 1 << 4,
        'PIERCE' => 1 << 5,
        'SLASH' => 1 << 6,
        'ACID' => 1 << 7,
        'POISON' => 1 << 8,
        'DRAIN' => 1 << 9,
        'SLEEP' => 1 << 10,
        'CHARM' => 1 << 11,
        'HOLD' => 1 << 12,
        'NONMAG' => 1 << 13,
        'PLUS1' => 1 << 14,
        'PLUS2' => 1 << 15,
        'PLUS3' => 1 << 16,
        'PLUS4' => 1 << 17,
        0 => 'NONE',
        1 << 0 => 'FIRE',
        1 << 1 => 'COLD',
        1 << 2 => 'ELEC',
        1 << 3 => 'ENERGY',
        1 << 4 => 'BLUNT',
        1 << 5 => 'PIERCE',
        1 << 6 => 'SLASH',
        1 << 7 => 'ACID',
        1 << 8 => 'POISON',
        1 << 9 => 'DRAIN',
        1 << 10 => 'SLEEP',
        1 << 11 => 'CHARM',
        1 << 12 => 'HOLD',
        1 << 13 => 'NONMAG',
        1 << 14 => 'PLUS1',
        1 << 15 => 'PLUS2',
        1 << 16 => 'PLUS3',
        1 << 17 => 'PLUS4',
    ];
}