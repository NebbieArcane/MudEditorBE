<?php
/**
 * Created by PhpStorm.
 * User: enricomoretti
 * Date: 01/04/18
 * Time: 11:42
 */

namespace app\exceptions;


use Throwable;

/**
 * Class ZoneListException
 * @package app\exceptions
 *
 * Error map:
 * 1000: path already exist
 * 1500: error fetching data
 */
class ZoneListException extends \Exception {

    function __construct(string $message, int $code = 0, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }

    function __toString() {
        return __CLASS__ - ": [{$this->code}]: {$this->message}\n";
    }
}