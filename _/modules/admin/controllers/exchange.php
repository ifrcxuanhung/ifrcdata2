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

class Exchange extends Admin {

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
            $dir = '\\\LOCAL\IFRCVN\VNDB\HTM\PRICES\EXC\\';
            $folders = glob($dir . '*');
            foreach($folders as $folder){
                $code = pathinfo($folder, PATHINFO_FILENAME);
                $dir = '\\\LOCAL\IFRCVN\VNDB\HTM\PRICES\EXC\\' . $code . '\\';
                $files = glob($dir . '*.htm');
                $code_info = $this->mexchange->getCodeInfo($code, 'EXC');
                if($code_info != FALSE){
                    $options = $this->mexchange->getOption();
                    $format = $this->mexchange->getMetaFormat($code_info['output']);
                    $left = $code_info['left'];
                    $right = $code_info['right'];
                    //$test_i = 0;


                    foreach($files as $info){
                        $data = '';
                        $filename = pathinfo($info, PATHINFO_FILENAME);
                        //$code = substr($filename, 0, strpos($filename, '_'));
                        $date = str_replace($code . '_', '', pathinfo($info, PATHINFO_FILENAME));
                        $start = substr($date, 0, 4) . '/' . substr($date, 4, 2) . '/' . substr($date, 6);
                        $start = strtotime($start);
                        if(!in_array(date('D', $start), array('Sat', 'Sun'))){
                            $name = 'EXC_' . $code . '_' . $date . '.txt';
                            //if(!is_file('\\\LOCAL\IFRCVN\VNDB\METASTOCK\PRICES\EXC\\' . $code . '\\' . $name)){
    						if(!is_file('\\\LOCAL\IFRCVN\VNDB\METASTOCK\PRICES\EXC\\' . $name)){
                                $datahtml = $curl->makeRequest('post', 'file:' . $info, array('ctl00%24mainContent%24Live3Price1_NEW%24wdcDate%24dateInput=2012-10-17%2011%3A51%3A23','ctl00%24mainContent%24Live3Price1_NEW%24wdcDate%24dateInput_TextBox=17%2F10%2F2012','ctl00_mainContent_Live3Price1_NEW_RadAjaxPanel1PostDataValue=','ctl00_mainContent_Live3Price1_NEW_wdcDate=2012-10-17','httprequest=true'));

                                $headers = array_keys($format);
                                $contents = strtoupper(implode(chr(9) ,$headers)) . PHP_EOL;

                                $from = strpos($datahtml, $code_info['left']);
                                $len = strpos($datahtml, $code_info['right'], $from) - ($from + strlen($code_info['left']));
                                $datahtml = substr($datahtml, $from, $len);
                                $datahtml = str_replace($code_info['left'], '', $datahtml);
                                $datahtml = str_replace($code_info['right'], '', $datahtml);

                                if($code_info['left2'] != ''){
                                    $from = strpos($datahtml, $code_info['left2']);
                                    if($code_info['right2'] != ''){
                                        $len = strpos($code_info['right2'], $from) - ($from + strlen($code_info['left2']));
                                    }else{
                                        $len = NULL;
                                    }
                                    if($len == NULL){
                                        $datahtml = substr($datahtml, $from);
                                    }else{
                                        $datahtml = substr($datahtml, $from, $len);
                                    }
                                    $datahtml = str_replace($code_info['left2'], '', $datahtml);
                                    $datahtml = str_replace($code_info['right2'], '', $datahtml);
                                }
                                if($code_info['del_bllef'] == ''){
                                    $code_info['del_bllef'] = '<tr';
                                }
                                $result[0] = explode($code_info['del_bllef'], $datahtml);

                                // $from = strpos($datahtml, $left);
                                // $len = strpos($datahtml, $right, $from) - $from;
                                // $datahtml = substr($datahtml, $from, $len);
                                // if($right == '</table>'){
                                //     $datahtml .= '</table>';
                                // }
                                // $rule = "/\<tr.*\>(.*)\<\/tr\>/msU";
                                // preg_match_all($rule, $datahtml, $result);
                                // pre($result);die();

                                if(!empty($result[0])){
                                    array_shift($result[0]);
                                    $data = convertMetastock($result[0], $format, $options, $code_info, $date);
									if(is_array($data)){
										foreach($data as $item){
											$values = '';
											foreach($item as $key => $value){
												$values[] = $value;
											}
											$contents .= implode(chr(9), $values);
											$contents .= PHP_EOL;
										}
									}
                                }                                 
                                $file = fopen('\\\LOCAL\IFRCVN\VNDB\METASTOCK\PRICES\EXC\\' . $name, 'w');
    							fwrite($file, $contents);
                            }
                        }
                        // $test_i++;
                        // if($test_i == 4) break;
                        
                    }
                }
            }
			redirect(admin_url());
            $this->data->title = "Exchange";
            $this->template->write_view('content', 'hnx/hnx', $this->data);
            $this->template->write('title', 'Exchange');
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