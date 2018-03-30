<?php
/**
 * Created by PhpStorm.
 * User: enricomoretti
 * Date: 19/03/18
 * Time: 12:40
 */

namespace app\models;


use Illuminate\Database\Eloquent\Model;

class Zone extends Model {
    /**
     * @var string
     */
    protected $table = 'zones';
    /**
     * @var array
     */
    protected $fillable = [
        'vnum',
        'zoneId',
        'name',
        'lifeSpan',
        'resetMode'
    ];

    /**
     * @return Zone
     */
    static function findAll(){
        return Zone::get()->toArray();
    }

    /**
     * @param int $pk
     * @return Zone
     */
    static function findOne(int $pk) {
        return Zone::get($pk)->toArray();
    }
}