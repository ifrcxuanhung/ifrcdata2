<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class Runbat extends Admin {
    protected $data;
    private $cal_dates;
    protected $_option;

    public function __construct() {
        parent::__construct();
        set_time_limit(0);
        $this->load->library('curl');
       // $this->load->library('simple_html_dom');
    }
	public function index(){
		//exec('\\\LOCAL\IFRCVN\VNDB\VNDBZIP_DAY.bat');
		system('\\\LOCAL\IFRCVN\VNDB\VNDBZIP_DAY.bat');
	/*function replaceFile($content, $rContent, $path){
        if(!is_file($path))
            return FALSE;
        $files = file_get_contents($path);
        $files = str_replace($content, $rContent, $files);
        $f = file_put_contents($path, $files);
        return TRUE;


    }*/
	//$data=replaceFile('20130110','20130111','\\\LOCAL\IFRCVN\VNDB\test.txt');
	
	
	
	}	
}
