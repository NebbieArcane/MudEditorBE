<?php
/**
 * Created by PhpStorm.
 * User: enricomoretti
 * Date: 19/03/18
 * Time: 12:52
 */

namespace app\models;


use Illuminate\Database\Eloquent\Model;

class Special extends Model {
    protected $table = 'specials';
    protected $fillable = [
        'specType',
        'extra'
    ];

    public function findAll(){
        return $this->get()->toArray();
    }
}