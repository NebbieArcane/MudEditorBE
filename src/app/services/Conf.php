<?php

namespace app\services;

/**
 *
 * @author giovanni
 *
 */
class Conf {

    /**
     */
    private $conf;

    public function __construct($conf) {
        $this->conf = new \ArrayObject(array_change_key_case($conf, CASE_LOWER), \ArrayObject::STD_PROP_LIST);
    }

    public function __get($var) {
        return $this->conf[strtolower($var)];
    }

    public function __set($name, $value) {
        $this->conf[strtolower($name)] = $value;
    }

    /**
     * @param $name
     * @return bool
     */
    public function __isset($name) {
        /**
         * @var bool
         */
        $retVal = false;
        if (isset($this->conf[$name])) {
            $retVal = true;
        }
        return $retVal;
    }
}

