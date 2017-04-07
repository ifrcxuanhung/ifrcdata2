<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class Hsx_daily extends Admin {

    protected $data;
    private $cal_dates;
    protected $_option;

    public function __construct() {
        parent::__construct();
        set_time_limit(0);
        $this->load->library('curl');
        $this->load->library('simple_html_dom');
    }

    /*     * ***********************************************************************************************************
     * Name         ： index
     * -----------------------------------------------------------------------------------------------------------------
     * Description  ：
     * -----------------------------------------------------------------------------------------------------------------
     * Params       ：
     * -----------------------------------------------------------------------------------------------------------------
     * Return       ：
     * -----------------------------------------------------------------------------------------------------------------
     * Warning      ：
     * -----------------------------------------------------------------------------------------------------------------
     * Copyright    ： IFRC
     * -----------------------------------------------------------------------------------------------------------------
     * M001         ： New  2012.10.16 (Tung)
     * *************************************************************************************************************** */

   /* public function index() {
        
        $this->data->title = "HSX DAILY";
        $this->template->write_view('content', 'hnx/hnx_index', $this->data);
        $this->template->write('title', 'HNX');
        $this->template->render();
    }*/
    public function index(){
        //if($this->input->is_ajax_request()){
			$date = date('d/m/Y');
			$curl = new curl( );
			$datahtml = $curl->makeRequest('post','http://www.hsx.vn/hsx/Modules/Giaodich/Live3Price.aspx', array('ctl00%24mainContent%24Live3Price1_NEW%24wdcDate%24dateInput=2012-11-20%2011%3A51%3A23','ctl00%24mainContent%24Live3Price1_NEW%24wdcDate%24dateInput_TextBox=20%2F11%2F2012','ctl00_mainContent_Live3Price1_NEW_RadAjaxPanel1PostDataValue=','ctl00_mainContent_Live3Price1_NEW_wdcDate=2012-10-17','httprequest=true'));
			$datahtml1 = substr($datahtml, strpos($datahtml,'<html'));
			$datahtml = substr($datahtml, strpos($datahtml,'#4A3C8C;background-color:#D2EBFF;">'));
			//$html = str_get_html($datahtml);
			 $rule = "/\<table.*\>.*\<tr.*\>(.*)\<\/tr\>.*\<\/table\>/msU";
			 preg_match_all($rule, $datahtml, $tr);
			array_shift($tr);

            $tr = $tr[0];
            array_pop($tr);
            foreach($tr as $item){
                $item = explode('</td>', $item);
                unset($item[4]);
                unset($item[5]);
                foreach($item as $key => $value){
                    $item[$key] = trim(strip_tags($value));
                }
                $data[] = $item;
            }
			//$contents = 'TICKER,PREF,POPN,PCLS,PLOW,PHGH,PAVG,VLM,TRN' . PHP_EOL;
			$contents = 'SOURCE,TICKER,MARKET,DATE,YYYYMMDD,SHLI,SHOU,SHFN,PREF,PCEI,PFLR,POPN,PHGH,PLOW,PBASE,PAVG,PCLS,VLM,TRN,ADJ_PCLS,ADJ_COEFF' . PHP_EOL;

            $contents = str_replace(',', chr(9), $contents);
            foreach($data as $item){
			$source='EXC';
			$market='HSX';
			$lcdate1=date('Y/m/d');
			   $contents .= $source . chr(9);
			   $contents .= $item[0] . chr(9);
			   $contents .= $market . chr(9);
			   $contents .= $lcdate1 . chr(9);
			   $lcdate1 = explode('/', $lcdate1);
               $lcdate1 = $lcdate1[0] . $lcdate1[1] . $lcdate1[2];
               $contents .= $lcdate1 . chr(9);
			   $contents .= chr(9);
			   $contents .= chr(9);
			   $contents .= chr(9);
			   $contents .= 1000*str_replace(',', '.',$item[1]) . chr(9); //PREF
			   $contents .= chr(9);
			   $contents .= chr(9);
			   $contents .= 1000*str_replace(',', '.',$item[2]). chr(9);//POPN
			   $contents .= 1000*str_replace(',', '.',$item[7]). chr(9);//PHGH
			   $contents .= 1000*str_replace(',', '.',$item[6]). chr(9);//PLOW
			   $contents .= chr(9);
			   $contents .= 1000*str_replace(',', '.',$item[8]). chr(9);//PAVG
			   $contents .= 1000*str_replace(',', '.',$item[3]) . chr(9);//PCLS
			   $contents .= str_replace('.', '',$item[9]) . chr(9);//VLM
			   $contents .= 1000*str_replace('.', '',$item[10]);//TRN
			   $contents .= chr(9);
			   $contents .= chr(9);
               
               $contents .= PHP_EOL;
            }
    		$date2 = explode('/', $date);
    		$date3 = $date2[2] . $date2[1] . $date2[0];
    		$file = fopen('\\\LOCAL\IFRCVN\VNDB\METASTOCK\PRICES\EXC\EXC_HSX_' .$date3. '.txt', 'w');
            fwrite($file, $contents);
    		//$file1 = fopen('\\\LOCAL\IFRCVN\VNDB\HTM\PRICES\EXC\HSX\HSX_' .$date3. '.htm', 'w');
    		//fwrite($file1, $datahtml1);
            $this->data->title = "HSX";
            $this->template->write_view('content', 'hsx_daily/index', $this->data);
            $this->template->write('title', 'HSX');
            $this->template->render();
        }else{
            redirect(admin_url());
        }
	}	
}


