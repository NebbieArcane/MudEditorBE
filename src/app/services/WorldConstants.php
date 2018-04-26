<?php
/**
 * Created by PhpStorm.
 * User: enricomoretti
 * Date: 26/04/18
 * Time: 11:35
 */

namespace app\services;


class WorldConstants {
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
}