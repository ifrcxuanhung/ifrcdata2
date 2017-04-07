<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Registry
 *
 * Gives registry functionality (similar to the Zend
 * Registry).  Allows you to save code 'globally'.
 *
 * @author Simon Emms
 */
class Registry {
    /* Where everything is stored */

    private static $_arrRegistry = array();

    /**
     * Set Magic Method
     *
     * Sets data to the registry
     *
     * @param string $name
     * @param mixed $value
     */
    final public function __set($name, $value) {
        self::set($name, $value);
    }

    /**
     * Get Magic Method
     *
     * Gets from the registry
     *
     * @param string $name
     * @return mixed
     */
    final public function __get($name) {
        if (array_key_exists($name, self::$_arrRegistry)) {
            return self::$_arrRegistry[$name];
        } else {
            return false;
        }
    }

    /**
     * Set
     *
     * Sets to the registry
     *
     * @param string $name
     * @param mixed $value
     */
    final public static function set($name, $value = NULL) {
        if (isset($name) == TRUE && $name != '') {
            if (is_array($name) == TRUE) {
                foreach ($name as $key => $val) {
                    self::$_arrRegistry[$key] = $val;
                }
            } else {
                self::$_arrRegistry[$name] = $value;
            }
        }
    }

    /**
     * Get
     *
     * Gets from the registry
     *
     * @param string $name
     * @return mixed
     */
    final public static function get($name) {
        if (array_key_exists($name, self::$_arrRegistry)) {
            return self::$_arrRegistry[$name];
        } else {
            return false;
        }
    }

    /**
     * Get All
     *
     * Gets everything set to the registry. Mainly
     * a debugging thing more than anything
     *
     * @return array
     */
    final public static function getAll() {
        return self::$_arrRegistry;
    }

    /*
      check if a registry registered
     */

    final public static function isRegistered($name) {
        $name_arr = array_keys(self::$_arrRegistry);
        if (in_array($name, $name_arr)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

}

?>