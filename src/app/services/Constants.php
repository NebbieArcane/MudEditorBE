<?php
/**
 * Created by PhpStorm.
 * User: enricomoretti
 * Date: 16/04/18
 * Time: 07:17
 */

namespace app\services;


class Constants {
    /**
     * Zone CONSTANTS
     */
    const resetTypes = [
        0 => 'Never',
        1 => 'IfEmpty',
        2 => 'Always',
    ];

    const fearTypes = [
        1 => 'OP_SEX',
        2 => 'OP_RACE',
        3 => 'OP_CHAR',
        4 => 'OP_CLASS',
        5 => 'OP_EVIL',
        6 => 'OP_GOOD',
        7 => 'OP_VNUM',
    ];

    const eqSlots = [
        0 => 'Used as light',
        1 => 'Worn on right finger',
        2 => 'Worn on left finger',
        3 => 'First object worn around neck',
        4 => 'Second object worn around neck',
        5 => 'Worn on body',
        6 => 'Worn on head',
        7 => 'Worn on legs',
        8 => 'Worn on feet',
        9 => 'Worn on hands',
        10 => 'Worn on arms',
        11 => 'Worn as shield',
        12 => 'Worn about body',
        13 => 'Worn around waist',
        14 => 'Worn around right wrist',
        15 => 'Worn around left wrist',
        16 => 'Wielded as a weapon',
        17 => 'Held',
    ];

    const exitDir = [
        0 => 'North',
        1 => 'East',
        2 => 'South',
        3 => 'West',
        4 => 'Up',
        5 => 'Down'
    ];

    const exitStates = [
        0 => 'Open',
        1 => 'Closed',
        2 => 'Locked'
    ];

    /**
     * ZONE AND ROOM Constants
     */
    const roomFlags = [
        1 => 'DARK', //    Room is dark.
        2 => 'DEATH', //  Room is a death trap; char ``dies'' (no xp lost).
        4 => 'NOMOB', //  MOBs (monsters) cannot enter room.
        8 => 'INDOORS', //  Room is indoors.
        16 => 'PEACEFUL', //  Room is peaceful (violence not allowed).
        32 => 'SOUNDPROOF', //  Shouts, gossips, etc. won't be heard in room.
        64 => 'NOTRACK', //  ``track'' can't find a path through this room.
        128 => 'NOMAGIC', //  All magic attempted in this room will fail.
        256 => 'TUNNEL', //  Only one person allowed in room at a time.
        512 => 'PRIVATE', //  Cannot teleport in or GOTO if two people here.
        1024 => 'GODROOM', //  Only LVL_GOD and above allowed to enter.
        2048 => 'HOUSE', //  Reserved for internal use.  Do not set.
        4096 => 'HOUSE_CRASH', //  Reserved for internal use.  Do not set.
        8192 => 'ATRIUM', //  Reserved for internal use.  Do not set.
        16384 => 'OLC', //  Reserved for internal use.  Do not set.
        32768 => 'BFS_MARK', //  Reserved for internal use.  Do not set.
        32768 * 2 => 'OVERFLOW_1', //  Reserved for internal use.  Do not set.
        32768 * 4 => 'OVERFLOW_2', //  Reserved for internal use.  Do not set.

    ];

    /**
     * ROOM CONSTANTS
     */
    const sectorTypes = [
        'Inside',// Indoors (small number of move points needed).
        'City',// The streets of a city.
        'Field', // An open field.
        'Forest',// A dense forest.
        'Hills',// Low foothills.
        'Mountains',// Steep mountain regions.
        'Water Swim',// Water (swimmable).
        'Water NoSwim',// Unswimmable water - boat required for passage.
        'Air',
        'Underwater',
        'Desert',
        'Tree',
        'Dark City',
        0 => 'INSIDE', // Indoors (small number of move points needed).
        1 => 'CITY', // The streets of a city.
        2 => 'FIELD', // An open field.
        3 => 'FOREST', // A dense forest.
        4 => 'HILLS', // Low foothills.
        5 => 'MOUNTAIN', // Steep mountain regions.
        6 => 'WATER_SWIM', // Water (swimmable).
        7 => 'WATER_NOSWIM', // Unswimmable water - boat required for passage.
        8 => 'UNDERWATER', // Underwater.
        9 => 'FLYING' // Wheee!
    ];

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
        'ACT_ARCHER' => (1 << 31)
    ];

    const actFlagsReverse = [
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
    ];

    const affectionFlagReverse = [
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

    const loadPosition = [
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

}