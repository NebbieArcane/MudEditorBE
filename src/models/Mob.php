<?php
/**
 * Created by PhpStorm.
 * User: enricomoretti
 * Date: 19/03/18
 * Time: 15:28
 */

namespace app\models;


use Illuminate\Database\Eloquent\Model;

class Mob extends Model {
    protected $table = 'mobs';
    protected $fillable = [
        'vnum',
        'aliasList',
        'shortDescritpion',
        'longDescription',
        'detailedDescription',
        'actionBitvector',
        'affectionBitvector',
        'aligment',
        'typeFlag',
        'numAttack',
        'level',
        'thac0',
        'ac',
        'maxHitPoints',
        'bareHandDamage',
        'gold',
        'xpBonus',
        'race',
        'loadPosition',
        'defaultPosition',
        'sex',
        'sameroomSound',
        'adiacentRoomSound',
        'specialId'
    ];

    public function findAll(){
        return $this->get()->toArray();
    }
}