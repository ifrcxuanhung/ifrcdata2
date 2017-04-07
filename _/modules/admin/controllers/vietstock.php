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

class Vietstock extends Admin {

    protected $data;
    private $cal_dates;
    protected $_option;

    public function __construct() {
        parent::__construct();
        set_time_limit(0);
        $this->load->library('curl');
        $this->load->library('simple_html_dom');
        $this->load->Model('exchange_model', 'mexchange');
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

    public function index(){
        //if($this->input->is_ajax_request()){
            $curl = new curl( );
            $dir1 = '\\\LOCAL\IFRCDATA\DOWNLOADS\VNDB\HTM\PRICES\VST\\';
            $folders = glob($dir1 . '*');
            foreach($folders as $folder){
                $code = pathinfo($folder, PATHINFO_FILENAME);                
                $dir = $dir1 . $code . '\\';
                $files = glob($dir . '*.htm');
                $code_info = $this->mexchange->getCodeInfo($code, 'VST');
                if($code_info != FALSE){
                    $options = $this->mexchange->getOption();
                    $format = $this->mexchange->getMetaFormat($code_info['output']);
                    $left = $code_info['left'];
                    $right = $code_info['right'];
                    $startdates = $code_info['startdates'];
                    $enddates = ($code_info['enddates'] != '') ? $code_info['enddates'] : date('Ymd');


                    foreach($files as $info){
                        $data = '';
                        $filename = pathinfo($info, PATHINFO_FILENAME);
                        //$code = substr($filename, 0, strpos($filename, '_'));
                        $date = str_replace('VST_' . $code . '_', '', pathinfo($info, PATHINFO_FILENAME));
						
                        if($date >= $startdates && $date <= $enddates){
						
                            $start = substr($date, 0, 4) . '/' . substr($date, 4, 2) . '/' . substr($date, 6);
                            $start = strtotime($start);
                            if(!in_array(date('D', $start), array('Sat', 'Sun'))){
                                $name = 'VST_' . $code . '_' . $date . '.txt';
                                //if(!is_file('\\\LOCAL\IFRCDATA\DOWNLOADS\VNDB\METASTOCK\PRICES\VST\\' . $code . '\\' . $name)){
							
        						if(!is_file('\\\LOCAL\IFRCDATA\DOWNLOADS\VNDB\METASTOCK\PRICES\VST\\' . $name)){
								
                                    $datahtml = $curl->makeRequest('post', 'file:' . $info, array('ctl00%24mainContent%24Live3Price1_NEW%24wdcDate%24dateInput=2012-10-17%2011%3A51%3A23','ctl00%24mainContent%24Live3Price1_NEW%24wdcDate%24dateInput_TextBox=17%2F10%2F2012','ctl00_mainContent_Live3Price1_NEW_RadAjaxPanel1PostDataValue=','ctl00_mainContent_Live3Price1_NEW_wdcDate=2012-10-17','httprequest=true'));

                                    $headers = array_keys($format);
                                    $contents = strtoupper(implode(chr(9) ,$headers)) . PHP_EOL;

                                    $from = strpos($datahtml, $left);
                                    $len = strpos($datahtml, $right, $from) - $from;
                                    $datahtml = substr($datahtml, $from, $len);
                                    if($right == '</table>'){
                                        $datahtml .= '</table>';
                                    }
                                    $rule = "/\<tr.*\>(.*)\<\/tr\>/msU";
                                    preg_match_all($rule, $datahtml, $result);
                                    if(!empty($result[0])){
                                        if($code == 'HNX'){
                                            array_shift($result[0]);
                                            array_shift($result[0]);
                                            array_shift($result[0]);
                                        }
                                        $data = convertMetastock($result[0], $format, $options, $code_info, $date);
                                        foreach($data as $item){
                                            $values = '';
                                            foreach($item as $key => $value){
                                                if($key == 'source'){
                                                    $values[] = 'VSTH';
                                                }else{
                                                    $values[] = $value;
                                                }                                                
                                            }
                                            $contents .= implode(chr(9), $values);
                                            $contents .= PHP_EOL;
                                        }
                                    }
                                    $file = fopen('\\\LOCAL\IFRCDATA\DOWNLOADS\VNDB\METASTOCK\PRICES\VST\\' . $name, 'w');
        							fwrite($file, $contents);
                                }
                            }
                        }
                        
                    }
                }
            }
            $this->data->title = "Vietstock";
            $this->template->write_view('content', 'hnx/hnx_index', $this->data);
            $this->template->write('title', 'Vietstock');
            $this->template->render();
        //}
    }
    public function test(){
        $data = array(
            0 => array('ticker','pref','popn','pcls'),
            1 => array('abc',1,2,3),
            2 => array('aec',1,2,3)
        );
        $test = convertMetastock2($data, 'PRICES');
        print_r($test);
    }

}