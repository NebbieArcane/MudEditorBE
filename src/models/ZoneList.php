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
}