<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/* * *******************************************************************************************************************
 * Client  Name ：  IFRC
 * ---------------------------------------------------------------------------------------------------------------------
 * Project Name ：  IMS v3.0
 * ---------------------------------------------------------------------------------------------------------------------
 * Program Name ：  hsx.php
 * ---------------------------------------------------------------------------------------------------------------------
 * Entry Server ：
 * ---------------------------------------------------------------------------------------------------------------------
 * Called By    ：  System
 * ---------------------------------------------------------------------------------------------------------------------
 * Notice       ：
 * ---------------------------------------------------------------------------------------------------------------------
 * Copyright    ：  IFRC
 * ---------------------------------------------------------------------------------------------------------------------
 * Comment      ：
 * ---------------------------------------------------------------------------------------------------------------------
 * History      ：
 * ---------------------------------------------------------------------------------------------------------------------
 * Version V001 ：  2012.09.20 (Tung)        New Create
 * ******************************************************************************************************************* */

class Hsx extends Admin {

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

    public function index() {
        
        $this->data->title = "HNX";
        $this->template->write_view('content', 'hnx/hnx_index', $this->data);
        $this->template->write('title', 'HNX');
        $this->template->render();
    }

    public function custom_hsx(){
        if($this->input->is_ajax_request()){
            $curl = new curl( );
            $dir = '\\\LOCAL\IFRCVN\VNDB\HTM\PRICES\EXC\HSX\\';
            $files = glob($dir . '*.htm');
            $start = $this->input->post('start');
            $end = $this->input->post('end');
            $start = date('Ymd', strtotime($start));
            $end = date('Ymd', strtotime($end));
            foreach($files as $info){
                $data = array();
                $data[0] = array('ticker','pref','popn','pcls','','','plow','phgh','pavg','vlm','trn');
                unset($data[0][4]);
                unset($data[0][5]);
                $date = str_replace('HSX_', '', pathinfo($info, PATHINFO_FILENAME));
                $name = 'EXC_HSX_' . $date . '.txt';
                if(!is_file('\\\LOCAL\IFRCVN\VNDB\METASTOCK\PRICES\EXC\\' . $name)){
                    if($date >= $start && $date <= $end){            
                        $datahtml = $curl->makeRequest('post', 'file:' . $info, array('ctl00%24mainContent%24Live3Price1_NEW%24wdcDate%24dateInput=2012-10-17%2011%3A51%3A23','ctl00%24mainContent%24Live3Price1_NEW%24wdcDate%24dateInput_TextBox=17%2F10%2F2012','ctl00_mainContent_Live3Price1_NEW_RadAjaxPanel1PostDataValue=','ctl00_mainContent_Live3Price1_NEW_wdcDate=2012-10-17','httprequest=true'));
                        $datahtml = substr($datahtml, strpos($datahtml,'7pt">(ngàn vnd)</span>'));
                        $rule = "/\<table.*\>.*\<tr.*\>(.*)\<\/tr\>.*\<\/table\>/msU";
                        preg_match_all($rule, $datahtml, $tr);
                        array_shift($tr);
                        $tr = $tr[0];
                        foreach($tr as $item){
                            $item = explode('</td>', $item);
                            if(count($item) > 1){
                                unset($item[4]);
                                unset($item[5]);
                                foreach($item as $key => $value){                                
                                    $item[$key] = trim(strip_tags($value));                                
                                }
                                array_pop($item);
                                $data[] = $item;
                            }                        
                        }
                        if(count(end($data)) == 1){
                            array_pop($data);
                        }
                        $data = convertMetastock2($data, 'PRICES', $date, 'HSX', 'EXC');
                        //$contents = 'TICKER,PREF,POPN,PCLS,PLOW,PHGH,PAVG,VLM,TRN' . PHP_EOL;
                        $headers = array_keys($data[0]);
                        $contents = strtoupper(implode(chr(9) ,$headers)) . PHP_EOL;
                        //$contents = str_replace(',', chr(9), $contents);
                        foreach($data as $item){
                            $i = 0;
                            $values = '';
                            foreach($item as $key => $value){
                                if($value != ''){
                                    $value = str_replace('.', '', $value);
                                    $value = str_replace(',', '.', $value);
                                    if(!in_array($key, array('market', 'source', 'ticker', 'vlm', 'date', 'yyyymmdd'))){
                                        $value *= 1000;
                                    }
                                }
                                $values[] = $value;
                                $i++;
                            }
                            $contents .= implode(chr(9), $values);
                            $contents .= PHP_EOL;
                        }
                        //$name = 'EXC_HSX_' . $date . '.txt';
                        $file = fopen('\\\LOCAL\IFRCVN\VNDB\METASTOCK\PRICES\EXC\\' . $name, 'w');
                        fwrite($file, $contents);
                    }
                }
            }
        }else{
            $this->data->title = "HSX";
            $this->template->write_view('content', 'hsx/index', $this->data);
            $this->template->write('title', 'HSX');
            $this->template->render();
        }
    }

}