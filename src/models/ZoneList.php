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
    function findByPath(string $path) {
        $zone = null;
        try {
            $zone = ZoneList::where('path', $path)->findOrFail(1);
        } catch (\Exception $e) {
            $this->log->debug("Path [$path] not found in DB");
        }
        $this->log->debug("Returning zone [$zone]");
        return $zone;
    }
}