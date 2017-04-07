<?php
class My_Cache{
	public $CI;
	public function __construct(){
		$this->CI =& get_instance();
	}	
	public function loadCache(){
		$frontendOptions = array('lifetime' => 3600,
                  'automatic_serialization' => true);
        $backendOptions = array('cache_dir' => APPPATH . 'cache');
        $this->CI->zend->load('Zend_Cache');
        return Zend_Cache::factory('Core', 'File', $frontendOptions, $backendOptions);
	}
}