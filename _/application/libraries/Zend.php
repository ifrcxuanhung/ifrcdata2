<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * Zend Framework Loader
 *
 * Put the 'Zend' folder (unpacked from the Zend Framework package, under 'Library')
 * in CI installation's 'application/libraries' folder
 * You can put it elsewhere but remember to alter the script accordingly
 *
 * Usage:
 *       $this->load->library('zend');
 *      $this->zend->load('Zend_Package_Name',$param);
 *      $this->Zend_Package_Name->abc();

 */
class CI_Zend {

    /**
     * Constructor
     *
     * @param	string $class class name
     */
    function __construct() {
        // include path for Zend Framework
        // alter it accordingly if you have put the 'Zend' folder elsewhere
        ini_set('include_path', ini_get('include_path') . PATH_SEPARATOR . APPPATH . 'libraries');
        log_message('debug', "Zend Class Initialized");
    }

    /**
     * Zend Class Loader
     *
     * @param	string $class class name
     */
    function load($class) {
        $path = str_replace('_', DIRECTORY_SEPARATOR, $class);
        require_once (string) $path . EXT;
        log_message('debug', "Zend Class $class Loaded");
    }

}

?>