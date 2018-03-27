<?php
/**
 * Created by PhpStorm.
 * User: enricomoretti
 * Date: 16/03/18
 * Time: 18:01
 */

namespace app\models;

use Illuminate\Database\Eloquent\Model;
use Monolog\Logger;

class ZoneList extends Model {
    public $timestamps = false;
    protected $table = 'zoneList';
    protected $fillable = [
        'id',
        'userId',
        'name',
        'path',
        'start',
        'end',
        'status'
    ];

    static function findAll(){
        return ZoneList::get()->toArray();
    }

    static function findOne($primaryKey) {
        return ZoneList::get($primaryKey)->toArray();
    }

    static function findByUserId($userId, $log){
        try {
            $result =  ZoneList::where('userId', $userId)->get()->toArray();

            $log->info(print_r($result, true));
        } catch (\Exception $e) {
            $log->error($e->getMessage());
        }


        return $result;
    }
}