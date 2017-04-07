<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class Runbat_test extends Admin {
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
    	$date=date('Ymd',time());
    	$link='\\\LOCAL\IFRCVN\VNDB\VNDBZIP.bat';
    	if(!is_file($link)){
            echo 'Fil not found';
            exit();
        }
    	$file = file_get_contents($link);
        $rule = "/(?<=\[).*(?=\])/";
        preg_match($rule, $file, $result);
    	$file= str_replace($result[0], $date, $file);
    	$complete = file_put_contents($link, $file);
		
		
		$link1='\\\LOCAL\IFRCVN\VNDB\VNDBZIP_DAY.bat';
    	if(!is_file($link1)){
            echo 'Fil not found';
            exit();
        }
    	$file1 = file_get_contents($link1);
        $rule1 = "/(?<=\[).*(?=\])/";
        preg_match($rule1, $file1, $result1);
    	$file1= str_replace($result1[0], $date, $file1);
    	$complete1 = file_put_contents($link1, $file1);
		//exec('\\\LOCAL\IFRCVN\VNDB\VNDBZIP.bat');
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
