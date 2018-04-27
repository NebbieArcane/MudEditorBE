<?php
/**
 * Created by PhpStorm.
 * User: enricomoretti
 * Date: 16/04/18
 * Time: 07:17
 */

namespace app\services;


class Constants extends MobConstants {

    /**
     * SHOP
     */
    const temper = [
        0 => 'PUKE',
        1 => 'SMOKE',
        'PUKE' => 0,
        'SMOKE' => 1
    ];

    const shopBV = [
        1 => 'WILL_START_FIGTH',
        2 => 'WILL_BANK_MONEY',
        'WILL_START_FIGTH' => 1,
        'WILL_BANK_MONEY' => 2
    ];

    const withWho = [
        0 => 'ALL',
        1 => 'NOGOOD',
        2 => 'NOEVIL',
        4 => 'NONEUTRAL',
        8 => 'NOMAGIC_USER',
        16 => 'NOCLERIC',
        32 => 'NOTHIEF',
        64 => 'NOWARRIOR',
        'ALL' => 0,
        'NOGOOD' => 1,
        'NOEVIL' => 2,
        'NONEUTRAL' => 4,
        'NOMAGIC_USER' => 8,
        'NOCLERIC' => 16,
        'NOTHIEF' => 32,
        'NOWARRIOR' => 64
    ];
}