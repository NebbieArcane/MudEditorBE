<?php
/**
 * Created by PhpStorm.
 * User: enricomoretti
 * Date: 16/03/18
 * Time: 18:01
 */

namespace app\models;

use Illuminate\Database\Eloquent\Model;

class ZoneList extends Model {
    protected $table = 'zoneList';
    protected $fillable = [
        'id',
        'name',
        'path',
        'start',
        'end',
        'status'
    ];

    public function findAll(){
        return $this->get()->toArray();
    }
}