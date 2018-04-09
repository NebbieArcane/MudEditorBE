<?php
/**
 * Created by PhpStorm.
 * User: enricomoretti
 * Date: 16/03/18
 * Time: 18:01
 */

namespace app\models;

use app\exceptions\ZoneListException;
use Illuminate\Database\Eloquent\Model;
use Monolog\Logger;

class ZoneList extends Model {
    /**
     * @var Logger
     */
    public $log;
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

    /**
     * @param Logger $log
     */
    public function setLog($log) {
        $this->log = $log;
    }

    /**
     * @param string $path
     * @return null
     * @throws ZoneListException
     */
    public function findByPath(string $path) {
        $retVal = false;
        try {
            $zone = $this::where('path', $path)->get();
            if ($zone->count() > 0) {
                $this->log->debug('Fount [' . $zone->name . '] for path [' . $path . ']');
                $retVal = true;
            }
        } catch (\Exception $e) {
            $this->log->debug($e->getCode() . " " . $e->getMessage());
            throw new ZoneListException('Error fetching zone from Database', 1500);
        }
        return $retVal;
    }
}