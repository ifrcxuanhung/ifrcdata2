<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/* * *******************************************************************************************************************
 * Client  Name ：  IFRC
 * ---------------------------------------------------------------------------------------------------------------------
 * Project Name ：  IMS v3.0
 * ---------------------------------------------------------------------------------------------------------------------
 * Program Name ：  hnx.php
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

class Hnx extends Admin {

    protected $data;
    private $cal_dates;
    protected $_option;

    public function __construct() {
        parent::__construct();
        set_time_limit(0);
        $this->load->library('curl');
        $this->load->library('simple_html_dom');
        $this->_option = array(
                        'tungay=',
                        'denngay=',
                        'iDisplayStart=',
                        'sEcho=2',
                        'bSortable_0=true',
                        'bSortable_1=true',
                        'bSortable_10=true',
                        'bSortable_11=true',
                        'bSortable_12=true',
                        'bSortable_13=true',
                        'bSortable_14=true',
                        'bSortable_15=true',
                        'bSortable_16=true',
                        'bSortable_17=true',
                        'bSortable_18=true',
                        'bSortable_19=true',
                        'bSortable_2=true',
                        'bSortable_20=true',
                        'bSortable_21=true',
                        'bSortable_22=true',
                        'bSortable_3=true',
                        'bSortable_4=true',
                        'bSortable_5=true',
                        'bSortable_6=true',
                        'bSortable_7=true',
                        'bSortable_8=true',
                        'bSortable_9=true',
                        'iColumns=23',
                        'iDisplayLength=100',
                        'iSortCol_0=0',
                        'iSortingCols=1',
                        'loaick=',
                        'loaiindex=HNX_INDEX',
                        'mDataProp_0=0',
                        'mDataProp_1=1',
                        'mDataProp_10=10',
                        'mDataProp_11=11',
                        'mDataProp_12=12',
                        'mDataProp_13=13',
                        'mDataProp_14=14',
                        'mDataProp_15=15',
                        'mDataProp_16=16',
                        'mDataProp_17=17',
                        'mDataProp_18=18',
                        'mDataProp_19=19',
                        'mDataProp_2=2',
                        'mDataProp_20=20',
                        'mDataProp_21=21',
                        'mDataProp_22=22',
                        'mDataProp_3=3',
                        'mDataProp_4=4',
                        'mDataProp_5=5',
                        'mDataProp_6=6',
                        'mDataProp_7=7',
                        'mDataProp_8=8',
                        'mDataProp_9=9',
                        'mack=','nganh=',
                        'nyuc=ny','sColumns=',
                        'sSortDir_0=asc',
                        'url=http://www.hnx.vn/web/guest/ket-qua?p_p_id=gdct_WAR_HnxIndexportlet&p_p_lifecycle=0&p_p_state=normal&p_p_mode=view&p_p_col_id=column-1&p_p_col_count=1&_gdct_WAR_HnxIndexportlet_anchor=toMck',
                        
                    );

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
        //$this->load->Model('midx_model', 'midx');
        if (!isset($data['err']) || count($data['err']) == 0) {
            $start = 0;  // Download d? li?u t? ngày
            $end = 400;
            $date = date('d/m/Y');
            if(isset($_GET['date'])){
                $date = $_GET['date'];
            }
                //$date=$_GET['date'];
            //$content = 'ticker,yyyymmdd,exc_pref,exc_pcei,exc_pflr,exc_popn,exc_pcls,exc_phgh,exc_plow,exc_pbase,exc_pvlm,exc_ptrn,exc_capi' . PHP_EOL;
			$content ='source,ticker,market,date,yyyymmdd,shli,shou,shfn,pref,pcei,pflr,popn,phgh,plow,pbase,pavg,pcls,vlm,trn,adj_pcls,adj_coeff' . PHP_EOL;
            $content = str_replace(',', chr(9), $content);
            $curl = new curl();
            while ($start <= $end) {
                    $this->_option[1] = 'denngay=' . $date;
                    $this->_option[0] = 'tungay=' . $date;
                    $this->_option[2] = 'iDisplayStart=' . $start;
                    $datahtml = $curl->makeRequest('post', 'http://www.hnx.vn/web/guest/ket-qua?p_p_id=gdct_WAR_HnxIndexportlet&p_p_lifecycle=2&p_p_state=normal&p_p_mode=view&p_p_cacheability=cacheLevelPage&p_p_col_id=column-1&p_p_col_count=1&_gdct_WAR_HnxIndexportlet_json=1', $this->_option);
                    $datahtml = substr($datahtml, strpos($datahtml, '{'));
                    $datahtml = json_decode($datahtml, 1);
                    foreach($datahtml['aaData'] as $item){
					$source='EXC';
					$market='HNX';
					$lcdate1=date('Y/m/d');
						$content .=$source .chr(9);
                        $ticker = substr($item[1], strpos($item[1], '>') + 1);
                        $ticker = str_replace('</a>', '', $ticker);
                        $content .= $ticker . chr(9);
						$content .= $market . chr(9);
						$content .= $lcdate1 . chr(9);
                        $lcdate = explode('/', $item[2]);
                        $lcdate = $lcdate[2] . $lcdate[1] . $lcdate[0];
                        $content .= $lcdate . chr(9);
						$content .=  chr(9);
						$content .= chr(9);
						$content .= chr(9);
                        $content .= 1000*str_replace(',', '.', $item[3]) . chr(9);//pref
                        $content .= 1000*str_replace(',', '.', $item[4]) . chr(9);//pcei
                        $content .= 1000*str_replace(',', '.', $item[5]) . chr(9);//pflr
                        $content .= 1000*str_replace(',', '.', $item[6]) . chr(9);//popn
						$content .= 1000*str_replace(',', '.', $item[8]) . chr(9);//phgh
                        $content .= 1000*str_replace(',', '.', $item[9]) . chr(9);//plow
                        $content .= 1000*str_replace(',', '.', $item[10]) . chr(9);//pbase
						$content .= chr(9);
                        $content .= 1000*str_replace(',', '.', $item[7]) . chr(9);//pcls
                        $content .= str_replace('.', '', $item[13]) . chr(9);//pvlm
                        $content .= 1000*str_replace('.', '', $item[14]) . chr(9);//ptrn
						$content .=  chr(9);
                        //$content .= 1000*str_replace(',','.',str_replace('.', '', $item[21])); //capi
                        $content .= PHP_EOL;
                    }
                    $date2 = explode('/', $date);
                    $date3 = $date2[2] . $date2[1] . $date2[0];
                    $start = $start +100;
            }
           // $file = fopen($_SERVER['DOCUMENT_ROOT'] . '/IFRCDATA/assets/download/hnx/HNX_' .$date3. '.txt', 'w');
		   $file = fopen('\\\LOCAL\IFRCVN\VNDB\METASTOCK\PRICES\EXC\EXC_HNX_' .$date3. '.txt', 'w');
            fwrite($file, $content);
            echo " Complete!";
        }
        $this->data->title = "HNX";
        $this->template->write_view('content', 'hnx/hnx_index', $this->data);
        $this->template->write('title', 'HNX');
        $this->template->render();
    }

    public function custom_hnx(){
        $this->load->Model('midx_model', 'midx');
        
        if($this->input->is_ajax_request()){
            $e = 400;
            $start = strtotime($this->input->post('start'));
            $end = strtotime($this->input->post('end'));
            $date = date('d/m/Y');
            if($start != ''){
                $curl = new curl();
                while ($start <= $end) {
                    if(!in_array(date('D', $start), array('Sat', 'Sun'))){
                        $date = date('d/m/Y', $start);
                        $date2 = explode('/', $date);
                        $date3 = $date2[2] . $date2[1] . $date2[0];
                        $name = 'EXC_HNX_' .$date3. '.txt';
                        if(!is_file('\\\LOCAL\IFRCVN\VNDB\METASTOCK\PRICES\EXC\\' . $name)){
                            $this->_option[1] = 'denngay=' . $date;
                            $this->_option[0] = 'tungay=' . $date;
                            $s = 0;
                            $this->load->Model('exchange_model', 'mexchange');
                            $format = $this->mexchange->getMetaFormat('PRICES');
                            $headers = array_keys($format);
                            $contents = implode(chr(9), $headers) . PHP_EOL;
                            $content = 'ticker,pref,pcei,pflr,popn,pcls,phgh,plow,pbase,vlm,trn,capi';
                            $content = str_replace(',', chr(9), $content);                            
                            while($s <= $e){
                                $data = array();
                                $data[0] = explode(chr(9), $content);
                                $this->_option[2] = 'iDisplayStart=' . $s;
                                $datahtml = $curl->makeRequest('post', 'http://www.hnx.vn/web/guest/ket-qua?p_p_id=gdct_WAR_HnxIndexportlet&p_p_lifecycle=2&p_p_state=normal&p_p_mode=view&p_p_cacheability=cacheLevelPage&p_p_col_id=column-1&p_p_col_count=1&_gdct_WAR_HnxIndexportlet_json=1', $this->_option);
                                $datahtml = substr($datahtml, strpos($datahtml, '{'));
                                $datahtml = json_decode($datahtml, 1);
                                foreach($datahtml['aaData'] as $item){
                                    $temp = array();
                                    foreach($item as $k => $v){
                                        if(!in_array($k, array(0, 2, 11, 12, 15, 16, 17, 18, 19, 20, 22))){
                                            $temp[] = $v;
                                        }
                                    }
                                    $data[] = $temp;
                                }
                               // print_r($data);
                                $data = convertMetastock2($data, 'PRICES', date('Ymd', $start), 'HNX', 'EXC');
                                foreach($data as $item){
                                    $i = 0;
                                    $values = '';
                                    foreach($item as $key => $value){
                                        if($value != ''){
                                            $value = str_replace('.', '', $value);
                                            $value = str_replace(',', '.', $value);
                                            if(in_array($key, array('pref','pcei','pflr','popn','pcls','phgh','plow','pbase','trn'))){
                                                $value *= 1000;
                                            }
                                        }
                                        $values[] = $value;
                                        $i++;
                                    }
                                    $contents .= implode(chr(9), $values);
                                    $contents .= PHP_EOL;
                                }
                                $s += 100;
                            }
							
                            $date2 = explode('/', $date);
                            $date3 = $date2[2] . $date2[1] . $date2[0];
                            $name = 'EXC_HNX_' .$date3. '.txt';
    						$file = fopen('\\\LOCAL\IFRCVN\VNDB\METASTOCK\PRICES\EXC\\' . $name, 'w');
                            fwrite($file, $contents);
                        }
                    }
                    $start = strtotime("+1 day", $start);                    
                }
            }
            echo 'done';
        }else{
            $this->data->title = "HNX";
            $this->template->write_view('content', 'hnx/index', $this->data);
            $this->template->write('title', 'HNX');
            $this->template->render();
        }
    }

}