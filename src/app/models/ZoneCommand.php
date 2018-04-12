<?php
/**
 * Created by PhpStorm.
 * User: enricomoretti
 * Date: 19/03/18
 * Time: 12:46
 */

namespace app\models;


use Illuminate\Database\Eloquent\Model;

class ZoneCommand extends Model {
    protected $table = 'zoneCommands';
    protected $fillable = [
        'zoneCmd',
        'zoneId',
        'vnum',
        'cap',
        'room',
        'slot',
        'slotDesc',
        'intoObj',
        'exits',
        'state'
    ];

    public function findAll(){
        return $this->get()->toArray();
    }
}