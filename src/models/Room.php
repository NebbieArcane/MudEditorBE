<?php
/**
 * Created by PhpStorm.
 * User: enricomoretti
 * Date: 19/03/18
 * Time: 14:22
 */

namespace app\models;


use Illuminate\Database\Eloquent\Model;

class Room extends Model {
    protected $table = 'rooms';
    protected $fillable = [
        'zoneId',
        'vnum',
        'roomName',
        'roomDescription',
        'roomBitvector',
        'sectorType',
        'exits',
        'objInRoom',
        'mobInRoom',
        'specialId'
    ];

    public function findAll() {
        return $this->get()->toArray();
    }
}