<?php
/**
 * Created by PhpStorm.
 * User: enricomoretti
 * Date: 30/03/18
 * Time: 16:16
 */

namespace app\models;


use Illuminate\Database\Eloquent\Model;

class ZoneIdProvider extends Model {
    public $timestamps = false;
    protected $table = 'zoneIdProvider';
    protected $fillable = ['id'];
}