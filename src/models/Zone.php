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
    protected $table = 'zones';
    protected $fillable = [
        'vnum',
        'zoneId',
        'name',
        'lifeSpan',
        'resetMode'
    ];

    public function findAll(){
        return $this->get()->toArray();
    }
}