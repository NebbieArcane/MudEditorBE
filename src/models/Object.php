<?php
/**
 * Created by PhpStorm.
 * User: enricomoretti
 * Date: 19/03/18
 * Time: 14:48
 */

namespace app\models;


use Illuminate\Database\Eloquent\Model;

class Object extends Model {
    protected $table = 'objects';
    protected $fillable = [
        'vnum',
        'aliasList',
        'shortDescription',
        'longDescription',
        'actionDescription',
        'typeFlag',
        'extraAffect',
        'wear',
        'value',
        'weigth',
        'cost',
        'rent',
        'extraDescription',
        'affectFields',
        'specialId'
    ];

    public function findAll(){
        return $this->get()->toArray();
    }
}