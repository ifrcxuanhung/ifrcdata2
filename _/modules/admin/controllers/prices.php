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

class Prices extends Admin {

    protected $data;
    private $cal_dates;
    protected $_option;

    public function __construct() {
        parent::__construct();
        set_time_limit(0);
        $this->load->library('curl');
        $this->load->library('simple_html_dom');
        $this->load->Model('exchange_model', 'mexchange');
        date_default_timezone_set('Asia/Ho_Chi_Minh');
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
        if($this->input->is_ajax_request()){
            $now = time();
            $response = array(
                'report' => ''
            );
            $date = date('Ymd');
            $this->db->truncate('vndb_prices_day');
            $this->mexchange->delByDate(date('Ymd'));
            $curl = new curl( );
            $urls = array(
                'HNX' => 'http://www.hnx.vn/web/guest/ket-qua?p_p_id=gdct_WAR_HnxIndexportlet&p_p_lifecycle=2&p_p_state=normal&p_p_mode=view&p_p_cacheability=cacheLevelPage&p_p_col_id=column-1&p_p_col_count=1&_gdct_WAR_HnxIndexportlet_json=1',
                'HSX' => 'http://www.hsx.vn/hsx/Modules/Giaodich/Live3Price.aspx',
                'UPC' => 'http://www.hnx.vn/web/guest/128?p_p_id=gdct_WAR_HnxIndexportlet&p_p_lifecycle=2&p_p_state=normal&p_p_mode=view&p_p_cacheability=cacheLevelPage&p_p_col_id=column-1&p_p_col_count=1&_gdct_WAR_HnxIndexportlet_json=1'
            );
            $now = time();
            $options = array(
                'UPC' => array(
                    'tungay=' . date('d/m/Y'),
                    'denngay=' . date('d/m/Y'),
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
                    'loaiindex=UPCOM_INDEX',
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
                ),
                'HNX' => array(
                    'tungay=' . date('d/m/Y'),
                    'denngay=' . date('d/m/Y'),
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
                ),
                'HSX' => array('ctl00%24mainContent%24Live3Price1_NEW%24wdcDate%24dateInput=2012-10-17%2011%3A51%3A23','ctl00%24mainContent%24Live3Price1_NEW%24wdcDate%24dateInput_TextBox=17%2F10%2F2012','ctl00_mainContent_Live3Price1_NEW_RadAjaxPanel1PostDataValue=','ctl00_mainContent_Live3Price1_NEW_wdcDate=2012-10-17','httprequest=true')
            );
            $this->load->Model('exchange_model', 'mexchange');
            $format = $this->mexchange->getMetaFormat('PRICES');
            $headers = array_keys($format);            
            $content = array(
                'HNX' => 'ticker,pref,pcei,pflr,popn,pcls,phgh,plow,pbase,vlm,trn,capi',
                'UPC' => 'ticker,pref,pcei,pflr,popn,pcls,phgh,plow,pavg,vlm,trn,capi',
                'HSX' => 'ticker,pref,popn,pcls,plow,phgh,pavg,vlm,trn'
            );
            $markets = array('HNX', 'HSX', 'UPC');
            // $markets = array('HSX');
            foreach($markets as $market){
                $contents = implode(chr(9), $headers) . PHP_EOL;
                $data = array();
                $data[0] = explode(',', $content[$market]);
                $s = 0;
                $e = 400;
                if($market == 'HSX'){
                    $datahtml = $curl->makeRequest('post', $urls[$market], $options[$market]);
                    $file1 = fopen('\\\LOCAL\IFRCVN\VNDB\HTM\PRICES\EXC\HSX\HSX_' . date('Ymd') . '.htm', 'w');
                    fwrite($file1, $datahtml);
                    $datahtml = substr($datahtml, strpos($datahtml,'7pt">(ngàn vnd)</span>'));
                    $rule = "/\<table.*\>.*\<tr.*\>(.*)\<\/tr\>.*\<\/table\>/msU";
                    preg_match_all($rule, $datahtml, $tr);
                    array_shift($tr);
                    $tr = $tr[0];
                    array_pop($tr);
                    foreach($tr as $item){
                        $item = explode('</td>', $item);
                        $temp = array();
                        foreach($item as $k => $v){
                            if(!in_array($k, array(4, 5))){
                                $temp[] = trim(strip_tags($v));
                            }
                        }
                        array_pop($temp);
                        $data[] = $temp;
                    }
                }else{
                    while($s <= $e){
                        //$data = array();
                        //$data[0] = $headers;
                        $options[$market][2] = 'iDisplayStart=' . $s;                        
                        $datahtml = $curl->makeRequest('post', $urls[$market], $options[$market]);
                        $datahtml = substr($datahtml, strpos($datahtml, '{'));
                        $datahtml = json_decode($datahtml, 1);
                        if(is_array($datahtml['aaData'])){
                            foreach($datahtml['aaData'] as $item){
                                $temp = array();
                                foreach($item as $k => $v){
                                    if(!in_array($k, array(0, 2, 11, 12, 15, 16, 17, 18, 19, 20, 22))){
                                        $temp[] = $v;
                                    }
                                }
                                $data[] = $temp;
                            }
                        }
                        $s += 100;
                    }
                }
                if(count($data) > 1){
                    $data = convertMetastock2($data, 'PRICES', date('Ymd'), $market, 'EXC');
                    if(count(end($data)) == 1){
                        array_pop($data);
                    }
                    $check = FALSE;
                    foreach($data as $k => $item){
                        $i = 0;
                        $values = '';
                        foreach($item as $key => $value){
                            if($value != ''){
                                $value = str_replace('.', '', $value);
                                $value = str_replace(',', '.', $value);
                                if(in_array($key, array('pref','pcei','pflr','popn','pcls','phgh','plow','pavg','pbase','trn'))){
                                    $value *= 1000;
                                }
                                $check = TRUE;
                                
                            }
                            // $values[] = $value;
                            $data[$k][$key] = $value;
                            $i++;
                        }                        
                        $contents .= implode(chr(9), $data[$k]);
                        $contents .= PHP_EOL;
                    }
                    if($check == TRUE){
                        $this->mexchange->addData($data);
                    }
                }
                $file = fopen('\\\LOCAL\IFRCVN\VNDB\METASTOCK\PRICES\EXC\EXC_' . $market . '_' . $date . '.txt', 'w');
                fwrite($file, $contents);
                $this->db->query("LOAD DATA LOCAL INFILE '\\\\\\\\LOCAL\\\IFRCVN\\\VNDB\\\METASTOCK\\\PRICES\\\EXC\\\EXC_" . $market . "_" .$date. ".txt' INTO TABLE vndb_prices_day FIELDS TERMINATED BY  '\\t'  IGNORE 1 LINES");
                //LOAD DATA INFILE 'D:\\IFRCVNS\\VNDB\\METASTOCK\\code_stoxplus.txt'INTO TABLE code_stoxplus FIELDS TERMINATED BY  '\t'  IGNORE 1 LINES

            }
            $sql = "UPDATE vndb_prices_day SET last=IF(pcls <> 0, pcls, pref);";                
            $this->db->query($sql);
            $rows = $this->db->get('vndb_prices_day')->result_array();
            $f = fopen('\\\LOCAL\IFRCVN\VNDB\METASTOCK\PRICES\EXC\ALL\EXC_ALL_' . $date . '.txt', 'w');
            $headers = array_keys($rows[0]);
            foreach($headers as $key => $header){
                if($header == 'id'){
                    unset($headers[$key]);
                }
            }
            $content = implode(chr(9), $headers) . PHP_EOL;
            foreach($rows as $row){
                unset($row['id']);
                $row['date'] = str_replace('-', '/', $row['date']);
                $content .= implode(chr(9), $row) . PHP_EOL;
            }
            fwrite($f, $content);
            fclose($f);

            $this->_get_exc();
            $response['report'][0]['task'] = 'Download';
            $response['report'][0]['time'] = time() - $now;
            $this->output->set_output(json_encode($response));
        }
        //}
    }  

    public function get_price_from_hnx_page(){
        $url = 'http://hnx.vn/web/guest/ket-qua-giao-dich?p_p_id=gdtkkqgd_WAR_HnxIndexportlet&p_p_lifecycle=2&p_p_state=exclusive&p_p_mode=view&p_p_cacheability=cacheLevelPage&_gdtkkqgd_WAR_HnxIndexportlet_anchor=queryAction&_gdtkkqgd_WAR_HnxIndexportlet_anchor=loadContent&_gdtkkqgd_WAR_HnxIndexportlet_ROUTER_PAGE=%2Fhtml%2Fgiaodich%2Fgdtkkqgd%2Fresult%2Fcontent_day.jsp&sEcho=1&iColumns=15&sColumns=&iDisplayStart=0&iDisplayLength=10&mDataProp_0=0&mDataProp_1=1&mDataProp_2=2&mDataProp_3=3&mDataProp_4=4&mDataProp_5=5&mDataProp_6=6&mDataProp_7=7&mDataProp_8=8&mDataProp_9=9&mDataProp_10=10&mDataProp_11=11&mDataProp_12=12&mDataProp_13=13&mDataProp_14=14&_gdtkkqgd_WAR_HnxIndexportlet_par_tk_type=0&_gdtkkqgd_WAR_HnxIndexportlet_par_ad_view=&_gdtkkqgd_WAR_HnxIndexportlet_par_idx=HNX_INDEX';
        $curl = new curl(FALSE);
        $data = $curl->makeRequest('get', $url, NULL);
        $data = json_decode($data, 1);
        return $data['aaData'];
    }

    protected function _get_exc(){
        $this->load->Model('download_model', 'mdownload');

        $now = time();
        $table = 'vndb_stats_daily';
        // get hsx
        $market = 'HSX';
        $this->load->library('curl');
        $curl = new curl;
        $url = 'http://www.hsx.vn/hsx/Modules/Giaodich/KQGDCN.aspx';
        $method = 'post';
        $post = NULL;
        $start = 'Cổ Phiếu / Stocks</span></td>';
        $end = '</td>';
        $value = download_exc($market, $url, $start, $end, $method, $post);

        $start = 'Chứng Chỉ Quỹ / IFCs</span></td>';
        $value2 = download_exc($market, $url, $start, $end, $method, $post);
    
        $svlm_exc = 0;
        $strn_exc = 0;
        $svlmkl_exc = 0;
        $strnkl_exc = 0;

        if($value != ''){
            $value = $value[0];
            if(isset($value[0])){
                $svlm_exc = convertNumber2Us(trim(strip_tags(str_replace('""', '"', $value[0]))), 'vn') * 1;
                if($value2 != ''){
                    if(isset($value2[0][0])){
                        $svlmkl_exc = convertNumber2Us(trim(strip_tags(str_replace('""', '"', $value2[0][0]))), 'vn') * 1;
                        $svlmkl_exc += $svlm_exc;
                    }
                }
            }
            if(isset($value[1])){
                $strn_exc = convertNumber2Us(trim(strip_tags(str_replace('""', '"', $value[1]))), 'vn') * 1000;
                if($value2 != ''){
                    if(isset($value2[0][1])){
                        $strnkl_exc = convertNumber2Us(trim(strip_tags(str_replace('""', '"', $value2[0][1]))), 'vn') * 1000;
                        $strnkl_exc += $strn_exc;
                    }
                }
            }
            
            
            $data = array(
                'market' => $market,
                'date' => date('Y/m/d', $now),                    
                'yyyymmdd' => date('Ymd', $now),
                'yyyymm' => date('Ym', $now),
                'yyyy' => date('Y', $now),
                'svlm_exc' => $svlm_exc,
                'strn_exc' => $strn_exc,
                'svlmkl_exc' => $svlmkl_exc,
                'strnkl_exc' => $strnkl_exc
            );
            $where = array(
                'market' => $data['market'],
                'yyyymmdd' => $data['yyyymmdd']
            );
            $this->mdownload->update_exc($table, $data, $where);

            $file = '\\\LOCAL\IFRCVN\VNDB\METASTOCK\STATISTICS\DAY\STATS_HSX_' . $data['yyyymmdd'] . '.txt';
            $headers = array_keys($data);
            $content = implode(chr(9), $headers) . PHP_EOL;
            $content .= implode(chr(9), $data) . PHP_EOL;
            $f = fopen($file, 'w');
            fwrite($f, $content);
            fclose($f);
            
        }

        // get hnx
        $market = 'HNX';
        $this->load->library('curl');
        $curl = new curl;
        // $post = 'p_p_id=gdtkkqgd_WAR_HnxIndexportlet&p_p_lifecycle=1&p_p_state=exclusive&p_p_mode=view&p_p_col_id=column-1&p_p_col_count=2&_gdtkkqgd_WAR_HnxIndexportlet_anchor=queryAction&_gdtkkqgd_WAR_HnxIndexportlet_cmd=&_gdtkkqgd_WAR_HnxIndexportlet_par_tk_type=0&_gdtkkqgd_WAR_HnxIndexportlet_par_ad_view=&_gdtkkqgd_WAR_HnxIndexportlet_par_idx=HNX_INDEX&_gdtkkqgd_WAR_HnxIndexportlet_par_chart_kl=&_gdtkkqgd_WAR_HnxIndexportlet_par_chart_gt=&_gdtkkqgd_WAR_HnxIndexportlet_rd_par_tk_type=0&_gdtkkqgd_WAR_HnxIndexportlet_rd_par_tk_type=1&_gdtkkqgd_WAR_HnxIndexportlet_rd_par_tk_type=2&_gdtkkqgd_WAR_HnxIndexportlet_rd_par_idx=HNX_INDEX&_gdtkkqgd_WAR_HnxIndexportlet_rd_par_idx=HNX30&as_fid=rlMD6I7rsttLF64BTe/G';
        // $post = explode('&', $post);
        // $method = 'post';
        $url = 'http://hnx.vn/web/guest/ket-qua-giao-dich?p_p_id=gdtkkqgd_WAR_HnxIndexportlet&p_p_lifecycle=2&p_p_state=exclusive&p_p_mode=view&p_p_cacheability=cacheLevelPage&_gdtkkqgd_WAR_HnxIndexportlet_anchor=queryAction&_gdtkkqgd_WAR_HnxIndexportlet_anchor=loadContent&_gdtkkqgd_WAR_HnxIndexportlet_ROUTER_PAGE=%2Fhtml%2Fgiaodich%2Fgdtkkqgd%2Fresult%2Fcontent_day.jsp&sEcho=1&iColumns=15&sColumns=&iDisplayStart=0&iDisplayLength=10&mDataProp_0=0&mDataProp_1=1&mDataProp_2=2&mDataProp_3=3&mDataProp_4=4&mDataProp_5=5&mDataProp_6=6&mDataProp_7=7&mDataProp_8=8&mDataProp_9=9&mDataProp_10=10&mDataProp_11=11&mDataProp_12=12&mDataProp_13=13&mDataProp_14=14&_gdtkkqgd_WAR_HnxIndexportlet_par_tk_type=0&_gdtkkqgd_WAR_HnxIndexportlet_par_ad_view=&_gdtkkqgd_WAR_HnxIndexportlet_par_idx=HNX_INDEX';

        // $start = '<tr class=';
        // $end = '<td>';
        // $value = download_exc($market, $url, $start, $end, $method, $post);
        $value = download_exc($market, $url);
        $svlm_exc = 0;
        $strn_exc = 0;
        $svlmtt_exc = 0;
        $strntt_exc = 0;
        $method = 'w';
        if(!empty($value)){
            foreach($value as $item){
                $content = '';
                $data = '';
                if(isset($item[0])){
                    $date = trim(strip_tags($item[0]));
                    $date = explode('/', $date);
                    $date = $date[2] . '-' . $date[1] . '-' . $date[0];
                    $date = strtotime($date);
                    $date = date('Y-m-d', $date);
                }
                if(isset($item[1])){
                    $svlm_exc = convertNumber2Us(trim(strip_tags(str_replace('""', '"', $item[1]))), 'vn') * 1;
                }
                if(isset($item[2])){
                    $strn_exc = convertNumber2Us(trim(strip_tags(str_replace('""', '"', $item[2]))), 'vn') * 1000;
                }  
                if(isset($item[5])){
                    $svlmtt_exc = convertNumber2Us(trim(strip_tags(str_replace('""', '"', $item[5]))), 'vn') * 1;
                }
                if(isset($item[6])){
                    $strntt_exc = convertNumber2Us(trim(strip_tags(str_replace('""', '"', $item[6]))), 'vn') * 1000;
                }
                $data = array(
                    'market' => $market,
                    'date' => date('Y/m/d', $now),                    
                    'yyyymmdd' => date('Ymd', strtotime($date)),
                    'yyyymm' => date('Ym', strtotime($date)),
                    'yyyy' => date('Y', strtotime($date)),
                    'svlm_exc' => $svlm_exc,
                    'strn_exc' => $strn_exc,
                    'svlmkl_exc' => $svlm_exc,
                    'strnkl_exc' => $strn_exc,
                    'svlmtt_exc' => $svlmtt_exc,
                    'strntt_exc' => $strntt_exc
                );
                $where = array(
                    'market' => $data['market'],
                    'yyyymmdd' => $data['yyyymmdd']
                );
                $this->mdownload->update_exc($table, $data, $where);
                
                $file = '\\\LOCAL\IFRCVN\VNDB\METASTOCK\STATISTICS\DAY\STATS_HNX_' . str_replace('/', '', $data['date']) . '.txt';
                if($method == 'w'){
                    $headers = array_keys($data);
                    $content .= implode(chr(9), $headers) . PHP_EOL;
                }
                $content .= implode(chr(9), $data) . PHP_EOL;
                $f = fopen($file, $method);
                fwrite($f, $content);
                fclose($f);
                $method = 'a';
            }
        }

        //get upc
        $market = 'UPC';
        $this->load->library('curl');
        $curl = new curl;
        // $post = 'sEcho=1&iColumns=23&sColumns=&iDisplayStart=0&iDisplayLength=10&mDataProp_0=0&mDataProp_1=1&mDataProp_2=2&mDataProp_3=3&mDataProp_4=4&mDataProp_5=5&mDataProp_6=6&mDataProp_7=7&mDataProp_8=8&mDataProp_9=9&mDataProp_10=10&mDataProp_11=11&mDataProp_12=12&mDataProp_13=13&mDataProp_14=14&mDataProp_15=15&mDataProp_16=16&mDataProp_17=17&mDataProp_18=18&mDataProp_19=19&mDataProp_20=20&mDataProp_21=21&mDataProp_22=22&iSortCol_0=0&sSortDir_0=asc&iSortingCols=1&bSortable_0=true&bSortable_1=true&bSortable_2=true&bSortable_3=true&bSortable_4=true&bSortable_5=true&bSortable_6=true&bSortable_7=true&bSortable_8=true&bSortable_9=true&bSortable_10=true&bSortable_11=true&bSortable_12=true&bSortable_13=true&bSortable_14=true&bSortable_15=true&bSortable_16=true&bSortable_17=true&bSortable_18=true&bSortable_19=true&bSortable_20=true&bSortable_21=true&bSortable_22=true&loaick=&nyuc=uc&loaiindex=UPCOM_INDEX&tungay=27%2F05%2F2013&denngay=27%2F05%2F2013&nganh=&mack=&url=http%3A%2F%2Fhnx.vn%2Fweb%2Fguest%2F128%3Fp_p_id%3Dgdct_WAR_HnxIndexportlet%26p_p_lifecycle%3D0%26p_p_state%3Dnormal%26p_p_mode%3Dview%26p_p_col_id%3Dcolumn-1%26p_p_col_count%3D1%26_gdct_WAR_HnxIndexportlet_anchor%3DtoMck';
        // $post = explode('&', $post);
        // $method = 'post';
        $url = 'http://hnx.vn/web/guest/ket-qua-giao-dich2?p_p_id=gdtkkqgd_WAR_HnxIndexportlet&p_p_lifecycle=2&p_p_state=exclusive&p_p_mode=view&p_p_cacheability=cacheLevelPage&_gdtkkqgd_WAR_HnxIndexportlet_anchor=queryAction&_gdtkkqgd_WAR_HnxIndexportlet_anchor=loadContent&_gdtkkqgd_WAR_HnxIndexportlet_ROUTER_PAGE=%2Fhtml%2Fgiaodich%2Fgdtkkqgd%2Fresult%2Fcontent_day.jsp&sEcho=1&iColumns=15&sColumns=&iDisplayStart=0&iDisplayLength=10&mDataProp_0=0&mDataProp_1=1&mDataProp_2=2&mDataProp_3=3&mDataProp_4=4&mDataProp_5=5&mDataProp_6=6&mDataProp_7=7&mDataProp_8=8&mDataProp_9=9&mDataProp_10=10&mDataProp_11=11&mDataProp_12=12&mDataProp_13=13&mDataProp_14=14&_gdtkkqgd_WAR_HnxIndexportlet_par_tk_type=0&_gdtkkqgd_WAR_HnxIndexportlet_par_ad_view=&_gdtkkqgd_WAR_HnxIndexportlet_par_idx=UPCOM_INDEX';

        // $start = '';
        // $end = '';
        // $value = download_exc($market, $url, $start, $end, $method, $post);
        $value = download_exc($market, $url);
        $svlm_exc = 0;
        $strn_exc = 0;
        $svlmtt_exc = 0;
        $strntt_exc = 0;
        $method = 'w';
        if($value != ''){
            foreach($value as $item){
                $content = '';
                $data = '';
                if(isset($item[0])){
                    $date = trim(strip_tags($item[0]));
                    $date = explode('/', $date);
                    $date = $date[2] . '-' . $date[1] . '-' . $date[0];
                    $date = strtotime($date);
                    $date = date('Y-m-d', $date);
                }
                if(isset($item[1])){
                    $svlm_exc = convertNumber2Us(trim(strip_tags(str_replace('""', '"', $item[1]))), 'vn') * 1;
                }
                if(isset($item[2])){
                    $strn_exc = convertNumber2Us(trim(strip_tags(str_replace('""', '"', $item[2]))), 'vn') * 1000;
                }
                if(isset($item[5])){
                    $svlmtt_exc = convertNumber2Us(trim(strip_tags(str_replace('""', '"', $item[5]))), 'vn') * 1;
                }
                if(isset($item[6])){
                    $strntt_exc = convertNumber2Us(trim(strip_tags(str_replace('""', '"', $item[6]))), 'vn') * 1000;
                }
                $data = array(
                    'market' => $market,
                    'date' => date('Y/m/d', $now),                    
                    'yyyymmdd' => date('Ymd', strtotime($date)),
                    'yyyymm' => date('Ym', strtotime($date)),
                    'yyyy' => date('Y', strtotime($date)),
                    'svlm_exc' => $svlm_exc,
                    'strn_exc' => $strn_exc,
                    'svlmkl_exc' => $svlm_exc,
                    'strnkl_exc' => $strn_exc,
                    'svlmtt_exc' => $svlmtt_exc,
                    'strntt_exc' => $strntt_exc
                );
                $where = array(
                    'market' => $data['market'],
                    'yyyymmdd' => $data['yyyymmdd']
                );
                $this->mdownload->update_exc($table, $data, $where);

                $file = '\\\LOCAL\IFRCVN\VNDB\METASTOCK\STATISTICS\DAY\STATS_UPC_' . str_replace('/', '', $data['date']) . '.txt';
                if($method == 'w'){
                    $headers = array_keys($data);
                    $content .= implode(chr(9), $headers) . PHP_EOL;
                }
                $content .= implode(chr(9), $data) . PHP_EOL;
                $f = fopen($file, $method);
                fwrite($f, $content);
                fclose($f);
                $method = 'a';
            }

        }
        $this->mdownload->order_table('vndb_stats_daily', array('yyyymmdd'=>'DESC', 'market'=>'ASC'));
    }

    protected function _statistics(){
        $sql = "UPDATE vndb_stats_daily A, (SELECT yyyymmdd, market, SUM(vlm) AS svlm_vndb, SUM(trn) AS strn_vndb, COUNT(ticker) AS nb FROM vndb_prices_day WHERE LENGTH(ticker) = 3 GROUP BY yyyymmdd, market) B SET A.svlm_vndb = B.svlm_vndb, A.strn_vndb = B.strn_vndb, A.nb = B.nb WHERE A.yyyymmdd = B.yyyymmdd AND A.market = B.market;
                UPDATE vndb_stats_daily A, (SELECT yyyymmdd, market, SUM(vlm) AS svlm_vndb, SUM(trn) AS strn_vndb, COUNT(ticker) AS nb FROM vndb_daily WHERE LENGTH(ticker) = 3 GROUP BY yyyymmdd, market) B SET A.svlm_vndb = B.svlm_vndb, A.strn_vndb = B.strn_vndb, A.nb = B.nb WHERE A.yyyymmdd = B.yyyymmdd AND A.market = B.market;
                UPDATE vndb_stats_daily SET correct_vlm = (IF(svlm_exc = svlm_vndb, 1, 0)), correct_trn = (IF(strn_exc = strn_vndb, 1, 0));
                UPDATE vndb_stats_daily SET correct = (IF(correct_vlm = 1 AND correct_trn = 1, 1, 0));
                UPDATE vndb_stats_daily SET svlm_diff = svlm_exc - svlm_vndb, strn_diff = strn_exc - strn_vndb";
        $sql = explode(';', $sql);
        foreach($sql as $item){
            $this->db->query($item);
        }
        unset($sql);
        $sql = "UPDATE vndb_stats_monthly SET correct_vlm = (IF(svlm_exc = svlm_vndb, 1, 0)), correct_trn = (IF(strn_exc = strn_vndb, 1, 0));
                UPDATE vndb_stats_monthly SET correct = (IF(correct_vlm = 1 AND correct_trn = 1, 1, 0))";
                // UPDATE vndb_stats_monthly SET svlm_diff = svlm_exc - svlm_vndb, strn_diff = strn_exc - strn_vndb";
        $sql = explode(';', $sql);
        foreach($sql as $item){
            $this->db->query($item);
        }
        unset($sql);
        $sql = "UPDATE vndb_stats_yearly SET correct_vlm = (IF(svlm_exc = svlm_vndb, 1, 0)), correct_trn = (IF(strn_exc = strn_vndb, 1, 0));
                UPDATE vndb_stats_yearly SET correct = (IF(correct_vlm = 1 AND correct_trn = 1, 1, 0))";
                // UPDATE vndb_stats_yearly SET svlm_diff = svlm_exc - svlm_vndb, strn_diff = strn_exc - strn_vndb";
        $sql = explode(';', $sql);
        foreach($sql as $item){
            $this->db->query($item);
        }
        return TRUE;
    }


/* * ******************************************************************************************************************* *
 * 	 Author: Minh Đẹp Trai			 																					 *
 * * ******************************************************************************************************************* */
 	public function prices_all(){
		$this->template->write_view('content', 'prices/prices_all', $this->data);
        $this->template->write('title', 'Prices All');
        $this->template->render();		
	}
	public function process_prices_all(){
		if ($this->input->is_ajax_request()) {
			$from = microtime(true);
			set_time_limit(0);
			$total = microtime(true) - $from;
			$date = date('Ymd');
            $this->db->truncate('vndb_prices_day');
            $this->mexchange->delByDate(date('Ymd'));
            $curl = new curl( );
            $urls = array(
                'HNX' => 'http://www.hnx.vn/web/guest/ket-qua?p_p_id=gdct_WAR_HnxIndexportlet&p_p_lifecycle=2&p_p_state=normal&p_p_mode=view&p_p_cacheability=cacheLevelPage&p_p_col_id=column-1&p_p_col_count=1&_gdct_WAR_HnxIndexportlet_json=1',
                'HSX' => 'http://www.hsx.vn/hsx/Modules/Giaodich/Live3Price.aspx',
                'UPC' => 'http://www.hnx.vn/web/guest/128?p_p_id=gdct_WAR_HnxIndexportlet&p_p_lifecycle=2&p_p_state=normal&p_p_mode=view&p_p_cacheability=cacheLevelPage&p_p_col_id=column-1&p_p_col_count=1&_gdct_WAR_HnxIndexportlet_json=1'
            );
            $now = time();
            $options = array(
                'UPC' => array(
                    'tungay=' . date('d/m/Y'),
                    'denngay=' . date('d/m/Y'),
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
                    'loaiindex=UPCOM_INDEX',
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
                ),
                'HNX' => array(
                    'tungay=' . date('d/m/Y'),
                    'denngay=' . date('d/m/Y'),
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
                ),
                'HSX' => array('ctl00%24mainContent%24Live3Price1_NEW%24wdcDate%24dateInput=2012-10-17%2011%3A51%3A23','ctl00%24mainContent%24Live3Price1_NEW%24wdcDate%24dateInput_TextBox=17%2F10%2F2012','ctl00_mainContent_Live3Price1_NEW_RadAjaxPanel1PostDataValue=','ctl00_mainContent_Live3Price1_NEW_wdcDate=2012-10-17','httprequest=true')
            );
            $this->load->Model('exchange_model', 'mexchange');
            $format = $this->mexchange->getMetaFormat('PRICES');
            $headers = array_keys($format);            
            $content = array(
                'HNX' => 'ticker,pref,pcei,pflr,popn,pcls,phgh,plow,pbase,vlm,trn,capi',
                'UPC' => 'ticker,pref,pcei,pflr,popn,pcls,phgh,plow,pavg,vlm,trn,capi',
                'HSX' => 'ticker,pref,popn,pcls,plow,phgh,pavg,vlm,trn'
            );
            $markets = array('HNX', 'HSX', 'UPC');
            // $markets = array('HSX');
            foreach($markets as $market){
                $contents = implode(chr(9), $headers) . PHP_EOL;
                $data = array();
                $data[0] = explode(',', $content[$market]);
                $s = 0;
                $e = 400;
                if($market == 'HSX'){
                    $datahtml = $curl->makeRequest('post', $urls[$market], $options[$market]);
                    $file1 = fopen('\\\LOCAL\IFRCVN\VNDB\HTM\PRICES\EXC\HSX\HSX_' . date('Ymd') . '.htm', 'w');
                    fwrite($file1, $datahtml);
                    $datahtml = substr($datahtml, strpos($datahtml,'7pt">(ngàn vnd)</span>'));
                    $rule = "/\<table.*\>.*\<tr.*\>(.*)\<\/tr\>.*\<\/table\>/msU";
                    preg_match_all($rule, $datahtml, $tr);
                    array_shift($tr);
                    $tr = $tr[0];
                    array_pop($tr);
                    foreach($tr as $item){
                        $item = explode('</td>', $item);
                        $temp = array();
                        foreach($item as $k => $v){
                            if(!in_array($k, array(4, 5))){
                                $temp[] = trim(strip_tags($v));
                            }
                        }
                        array_pop($temp);
                        $data[] = $temp;
                    }
                }else{
                    while($s <= $e){
                        //$data = array();
                        //$data[0] = $headers;
                        $options[$market][2] = 'iDisplayStart=' . $s;                        
                        $datahtml = $curl->makeRequest('post', $urls[$market], $options[$market]);
                        $datahtml = substr($datahtml, strpos($datahtml, '{'));
                        $datahtml = json_decode($datahtml, 1);
                        if(is_array($datahtml['aaData'])){
                            foreach($datahtml['aaData'] as $item){
                                $temp = array();
                                foreach($item as $k => $v){
                                    if(!in_array($k, array(0, 2, 11, 12, 15, 16, 17, 18, 19, 20, 22))){
                                        $temp[] = $v;
                                    }
                                }
                                $data[] = $temp;
                            }
                        }
                        $s += 100;
                    }
                }
                if(count($data) > 1){
                    $data = convertMetastock2($data, 'PRICES', date('Ymd'), $market, 'EXC');
                    if(count(end($data)) == 1){
                        array_pop($data);
                    }
                    $check = FALSE;
                    foreach($data as $k => $item){
                        $i = 0;
                        $values = '';
                        foreach($item as $key => $value){
                            if($value != ''){
                                $value = str_replace('.', '', $value);
                                $value = str_replace(',', '.', $value);
                                if(in_array($key, array('pref','pcei','pflr','popn','pcls','phgh','plow','pavg','pbase','trn'))){
                                    $value *= 1000;
                                }
                                $check = TRUE;
                                
                            }
                            // $values[] = $value;
                            $data[$k][$key] = $value;
                            $i++;
                        }                        
                        $contents .= implode(chr(9), $data[$k]);
                        $contents .= PHP_EOL;
                    }
                    if($check == TRUE){
                        $this->mexchange->addData($data);
                    }
                }
                $file = fopen('\\\LOCAL\IFRCVN\VNDB\METASTOCK\PRICES\EXC\EXC_' . $market . '_' . $date . '.txt', 'w');
                fwrite($file, $contents);
                $this->db->query("LOAD DATA LOCAL INFILE '\\\\\\\\LOCAL\\\IFRCVN\\\VNDB\\\METASTOCK\\\PRICES\\\EXC\\\EXC_" . $market . "_" .$date. ".txt' INTO TABLE vndb_prices_day FIELDS TERMINATED BY  '\\t'  IGNORE 1 LINES");
                //LOAD DATA INFILE 'D:\\IFRCVNS\\VNDB\\METASTOCK\\code_stoxplus.txt'INTO TABLE code_stoxplus FIELDS TERMINATED BY  '\t'  IGNORE 1 LINES

            }
            $sql = "UPDATE vndb_prices_day SET last=IF(pcls <> 0, pcls, pref);";                
            $this->db->query($sql);
            $rows = $this->db->get('vndb_prices_day')->result_array();
            $f = fopen('\\\LOCAL\IFRCVN\VNDB\METASTOCK\PRICES\EXC\ALL\EXC_ALL_' . $date . '.txt', 'w');
            $headers = array_keys($rows[0]);
            foreach($headers as $key => $header){
                if($header == 'id'){
                    unset($headers[$key]);
                }
            }
            $content = implode(chr(9), $headers) . PHP_EOL;
            foreach($rows as $row){
                unset($row['id']);
                $row['date'] = str_replace('-', '/', $row['date']);
                $content .= implode(chr(9), $row) . PHP_EOL;
            }
            fwrite($f, $content);
            fclose($f);
			/*$date_s = $this->input->post('date');
			$key = $this->input->post('key');
			$date_s = str_replace('-','',$date_s);
			if($key == 'Yes'){
				$this->db->query("DELETE FROM vndb_prices_daily where yyyymmdd = '".$date_s."'");
				$this->db->query("DELETE FROM vndb_prices_history where yyyymmdd = '".$date_s."'");
				$this->db->query("INSERT INTO vndb_prices_daily (source, ticker, market, date, yyyymmdd, shli, shou, shfn, pref, pcei, pflr, popn, phgh, plow, pbase, pavg, pcls, vlm, trn, last) SELECT source, ticker, market, date, yyyymmdd, shli, shou, shfn, pref, pcei, pflr, popn, phgh, plow, pbase, pavg, pcls, vlm, trn, last FROM vndb_prices_day where yyyymmdd = '".$date_s."'");
				$this->db->query("INSERT INTO vndb_prices_history(source, ticker, market, date, yyyymmdd, shli, shou, shfn, pref, pcei, pflr, popn, phgh, plow, pbase, pavg, pcls, vlm, trn, last)(SELECT source, ticker, market, date, yyyymmdd, shli, shou, shfn, pref, pcei, pflr, popn, phgh, plow, pbase, pavg, pcls, vlm, trn, last FROM vndb_prices_day where yyyymmdd = '".$date_s."')");	 
			}else{
				$this->db->query("INSERT INTO vndb_prices_daily(source, ticker, market, date, yyyymmdd, shli, shou, shfn, pref, pcei, pflr, popn, phgh, plow, pbase, pavg, pcls, vlm, trn, last) SELECT source, ticker, market, date, yyyymmdd, shli, shou, shfn, pref, pcei, pflr, popn, phgh, plow, pbase, pavg, pcls, vlm, trn, last FROM vndb_prices_day where yyyymmdd = '".$date_s."'");
				$this->db->query("INSERT INTO vndb_prices_history(source, ticker, market, date, yyyymmdd, shli, shou, shfn, pref, pcei, pflr, popn, phgh, plow, pbase, pavg, pcls, vlm, trn, last)(SELECT source, ticker, market, date, yyyymmdd, shli, shou, shfn, pref, pcei, pflr, popn, phgh, plow, pbase, pavg, pcls, vlm, trn, last FROM vndb_prices_day where yyyymmdd = '".$date_s."')");	 
			}*/
			/* * ****** * **
             *    Report   *
             * * * ****** **/
			$this->_change();
        	$this->_anomalies();
            $response[0]['time'] = round($total, 2);
            $response[0]['task'] = 'Prices All';
            echo json_encode($response);

		}
	}
 	public function prices_switch(){
		$this->template->write_view('content', 'prices/prices_switch', $this->data);
        $this->template->write('title', 'Prices Switch');
        $this->template->render();	
	}
 	public function process_check_date(){
		if ($this->input->is_ajax_request()) {
			set_time_limit(0);	
			$respone['date'] = $this->input->post('date');
			$date = str_replace('-', '', $respone['date']);
			$data_day = $this->db->query("select * from vndb_prices_day_stp where yyyymmdd = '".$date."'")->num_rows();
			$data_daily = $this->db->query("select * from vndb_prices_daily where yyyymmdd = '".$date."'")->num_rows();
			$data_history = $this->db->query("select * from vndb_prices_history where yyyymmdd = '".$date."'")->num_rows();
			if($data_day != 0){
				if ($data_daily > 0 || $data_history > 0) {
					$respone['day'] = 'Have Data';
					$respone['key'] = 'Yes';
					if($data_daily == 0){
						 $respone['daily'] = 'No Data';
					}else{
						$respone['daily'] = 'Have Data';
					}
					if($data_history == 0){
						$respone['history'] = 'No Data';
					}else{
						$respone['history'] = 'Have Data';
					}
				} else {
					$respone['key'] = 'No';
				}
			}else{
				$respone['key'] = '';
				$respone['day'] = 'No Data';
				$respone['daily'] = '';
				$respone['history'] ='';
			}
 			echo json_encode($respone);
		}
	}
	public function process_prices_switch(){
		if ($this->input->is_ajax_request()) {
			$from = microtime(true);
			set_time_limit(0);
			$date = $this->input->post('date');
			$key = $this->input->post('key');
			$date = str_replace('-','',$date);
			if($key == 'Yes'){
				$this->db->query("DELETE FROM vndb_prices_daily where yyyymmdd = '".$date."'");
				$this->db->query("DELETE FROM vndb_prices_history where yyyymmdd = '".$date."'");
				$this->db->query("INSERT INTO vndb_prices_daily (source, ticker, market, date, yyyymmdd, shli, shou, shfn, pref, pcei, pflr, popn, phgh, plow, pbase, pavg, pcls, vlm, trn, last) SELECT source, ticker, market, date, yyyymmdd, shli, shou, shfn, pref, pcei, pflr, popn, phgh, plow, pbase, pavg, pcls, vlm, trn, last FROM vndb_prices_day_stp where yyyymmdd = '".$date."'");
				$this->db->query("INSERT INTO vndb_prices_history(source, ticker, market, date, yyyymmdd, shli, shou, shfn, pref, pcei, pflr, popn, phgh, plow, pbase, pavg, pcls, vlm, trn, last)(SELECT source, ticker, market, date, yyyymmdd, shli, shou, shfn, pref, pcei, pflr, popn, phgh, plow, pbase, pavg, pcls, vlm, trn, last FROM vndb_prices_day_stp where yyyymmdd = '".$date."')");	 
			}else{
				$this->db->query("INSERT INTO vndb_prices_daily(source, ticker, market, date, yyyymmdd, shli, shou, shfn, pref, pcei, pflr, popn, phgh, plow, pbase, pavg, pcls, vlm, trn, last) SELECT source, ticker, market, date, yyyymmdd, shli, shou, shfn, pref, pcei, pflr, popn, phgh, plow, pbase, pavg, pcls, vlm, trn, last FROM vndb_prices_day_stp where yyyymmdd = '".$date."'");
				$this->db->query("INSERT INTO vndb_prices_history(source, ticker, market, date, yyyymmdd, shli, shou, shfn, pref, pcei, pflr, popn, phgh, plow, pbase, pavg, pcls, vlm, trn, last)(SELECT source, ticker, market, date, yyyymmdd, shli, shou, shfn, pref, pcei, pflr, popn, phgh, plow, pbase, pavg, pcls, vlm, trn, last FROM vndb_prices_day_stp where yyyymmdd = '".$date."')");	 
			}
			$total = microtime(true) - $from;
            $response[0]['time'] = round($total, 2);
            $response[0]['task'] = 'Prices Switch';
            echo json_encode($response);
		}	
	}

    function report_all(){
        $this->_statistics();
        $this->_anomalies();
        $now = date('Y-m-d');
        $yyyymmdd = date('Ymd');
        $changes = $this->db->query("SELECT * FROM vndb_stats_daily WHERE yyyymmdd = $yyyymmdd")->result_array();
        $anomalies = $this->db->query("SELECT * FROM vndb_anomalies_daily WHERE date = '$now'")->result_array();


        $this->data->changes = $changes;
        $this->data->anomalies = $anomalies;
        $this->data->title = "Report";
        $this->template->write_view('content', 'prices/report', $this->data);
        $this->template->write('title', 'Report');
        $this->template->render();
    }

    protected function _anomalies(){
        //drop table vndb_anomalies_daily
            $this->db->simple_query('DROP TABLE IF EXISTS `vndb_anomalies_daily`');
            //create table vndb_anomalies_daily
            $sql = "CREATE TABLE IF NOT EXISTS `vndb_anomalies_daily` (
                    `id` int(15) NOT NULL AUTO_INCREMENT,
                    `date` date DEFAULT NULL,
                    `yyyymmdd` int(8) DEFAULT NULL,
                    `market` varchar(3) DEFAULT NULL,
                    `correct` char(5),
                    `txtrefhsx` int(6) DEFAULT NULL,
                    `txtrefhnx` int(6) DEFAULT NULL,
                    `txtrefupc` int(6) DEFAULT NULL,
                    `txtrefall` int(6) DEFAULT NULL,
                    `txtrefsum` int(6) DEFAULT NULL,
                    `txtprchsx` int(6) DEFAULT NULL,
                    `txtprchnx` int(6) DEFAULT NULL,
                    `txtprcupc` int(6) DEFAULT NULL,
                    `txtprcall` int(6) DEFAULT NULL,
                    `txtprcsum` int(6) DEFAULT NULL,
                    `tblref` int(6) DEFAULT NULL,
                    `tblprc` int(6) DEFAULT NULL,
                    `last0` int(6) DEFAULT NULL,
                    `shli0` int(6) DEFAULT NULL,
                    `shou0` int(6) DEFAULT NULL,

                    PRIMARY KEY (`id`)
                    ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0 ;";

            $this->db->simple_query($sql);

            $this->db->simple_query('DROP TABLE IF EXISTS `vndb_daily_anomalies`');
            $sql = "CREATE TABLE IF NOT EXISTS `vndb_daily_anomalies` (
                    `id` int(11) NOT NULL AUTO_INCREMENT,
                    `source` varchar(5) DEFAULT NULL,
                    `market` varchar(5) DEFAULT NULL,
                    `ticker` varchar(6) DEFAULT NULL,
                    `date` date DEFAULT NULL,
                    `yyyymmdd` varchar(8) DEFAULT NULL,
                    `shli` double DEFAULT NULL,
                    `shou` double DEFAULT NULL,
                    `shfn` double DEFAULT NULL,
                    `pref` double DEFAULT NULL,
                    `pcei` double DEFAULT NULL,
                    `pflr` double DEFAULT NULL,
                    `popn` double DEFAULT NULL,
                    `phgh` double DEFAULT NULL,
                    `plow` double DEFAULT NULL,
                    `pbase` double DEFAULT NULL,
                    `pavg` double DEFAULT NULL,
                    `pcls` double DEFAULT NULL,
                    `vlm` double DEFAULT NULL,
                    `trn` double DEFAULT NULL,
                    `last` double DEFAULT NULL,
                    PRIMARY KEY (`id`)
                    ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0;";
            $this->db->simple_query($sql);

            $this->db->simple_query('DROP TABLE IF EXISTS `vndb_reference_daily_anomalies`');
            $sql = "CREATE TABLE IF NOT EXISTS `vndb_reference_daily_anomalies` (
                    `source` varchar(5) DEFAULT NULL,
                    `ticker` varchar(6) DEFAULT NULL,
                    `name` varchar(255) DEFAULT NULL,
                    `market` varchar(5) DEFAULT NULL,
                    `date` date DEFAULT NULL,
                    `yyyymmdd` varchar(10) DEFAULT NULL,
                    `ipo` date DEFAULT NULL,
                    `ipo_shli` double DEFAULT NULL,
                    `ipo_shou` double DEFAULT NULL,
                    `ftrd` date DEFAULT NULL,
                    `ftrd_cls` double NOT NULL,
                    `shli` double NOT NULL,
                    `shou` double NOT NULL,
                    `shfn` double NOT NULL,
                    `capi` double NOT NULL,
                    `capi_fora` double NOT NULL,
                    `capi_forn` double NOT NULL,
                    `capi_stat` double NOT NULL,
                    `id` int(11) NOT NULL AUTO_INCREMENT,
                    PRIMARY KEY (`id`)
                    ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0;";
            $this->db->simple_query($sql);

            $this->db->simple_query('DROP TABLE IF EXISTS `vndb_prices_daily_anomalies`');

            $sql = "CREATE TABLE IF NOT EXISTS `vndb_prices_daily_anomalies` (
                    `source` varchar(5) DEFAULT NULL,
                    `ticker` varchar(6) DEFAULT NULL,
                    `market` varchar(5) DEFAULT NULL,
                    `date` date DEFAULT NULL,
                    `yyyymmdd` varchar(8) DEFAULT NULL,
                    `shli` double DEFAULT NULL,
                    `shou` double DEFAULT NULL,
                    `shfn` double DEFAULT NULL,
                    `pref` double DEFAULT NULL,
                    `pcei` double DEFAULT NULL,
                    `pflr` double DEFAULT NULL,
                    `popn` double DEFAULT NULL,
                    `phgh` double DEFAULT NULL,
                    `plow` double DEFAULT NULL,
                    `pbase` double DEFAULT NULL,
                    `pavg` double DEFAULT NULL,
                    `pcls` double DEFAULT NULL,
                    `vlm` double DEFAULT NULL,
                    `trn` double DEFAULT NULL,
                    `last` double DEFAULT NULL,
                    `id` int(11) NOT NULL AUTO_INCREMENT,
                    PRIMARY KEY (`id`)
                    ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0;";

            $this->db->simple_query($sql);

            

            /* caculation */
            $path = '\\\LOCAL\IFRCVN\VNDB\METASTOCK\\';
            $list = list_file_vndb($path . 'REFERENCE\EXC\ALL');
            $data = array();
            if (is_array($list)) {
                foreach ($list as $key => $value) {
                    $temp = explode('/', $value);
                    $temp = explode('.', end($temp));
                    $temp = explode('_', $temp[0]);
                    $date = end($temp);
                    $date_org = $date;
                    $date = substr($date, 0, 4) . '-' . substr($date, 4, 2) . '-' . substr($date, -2);

                    insert_from_file($value, 'vndb_reference_daily_anomalies');
                    insert_from_file($path . "DAILY\ALL\DAILY_ALL_{$date_org}.txt", 'vndb_daily_anomalies');
                    insert_from_file($path . "PRICES\EXC\ALL\EXC_ALL_{$date_org}.txt", 'vndb_prices_daily_anomalies');

                    $data[$key]['date'] = $date;
                    $data[$key]['yyyymmdd'] = $date_org;
                    $data[$key]['txtrefhnx'] = line_of_file($path . "REFERENCE\EXC\REF_HNX_{$date_org}.txt", TRUE);
                    $data[$key]['txtrefhsx'] = line_of_file($path . "REFERENCE\EXC\REF_HSX_{$date_org}.txt", TRUE);
                    $data[$key]['txtrefupc'] = line_of_file($path . "REFERENCE\EXC\REF_UPC_{$date_org}.txt", TRUE);
                    $data[$key]['txtrefall'] = line_of_file($value, TRUE);
                    $data[$key]['txtrefsum'] = $data[$key]['txtrefhnx'] + $data[$key]['txtrefhsx'] + $data[$key]['txtrefupc'];
                    $data[$key]['txtprchnx'] = line_of_file($path . "PRICES\EXC\EXC_HNX_{$date_org}.txt", TRUE);
                    $data[$key]['txtprchsx'] = line_of_file($path . "PRICES\EXC\EXC_HSX_{$date_org}.txt", TRUE);
                    $data[$key]['txtprcupc'] = line_of_file($path . "PRICES\EXC\EXC_UPC_{$date_org}.txt", TRUE);
                    $data[$key]['txtprcall'] = line_of_file($path . "PRICES\EXC\ALL\EXC_ALL_{$date_org}.txt", TRUE);
                    $data[$key]['txtprcsum'] = $data[$key]['txtprchnx'] + $data[$key]['txtprchsx'] + $data[$key]['txtprcupc'];

                    $data[$key]['tblref'] = $this->db->query("select count('id') as counts from vndb_reference_daily_anomalies where `date`='{$date}'")->row()->counts;
                    $data[$key]['tblprc'] = $this->db->query("select count('id') as counts from vndb_prices_daily_anomalies where `date`='{$date}'")->row()->counts;
                    $data[$key]['last0'] = $this->db->query("select count('id') as counts from vndb_daily_anomalies where `date`='{$date}' and `last`=0 and length(ticker)<=3")->row()->counts;
                    $data[$key]['shli0'] = $this->db->query("select count('id') as counts from vndb_daily_anomalies where `date`='{$date}' and `shli`=0 and length(ticker)<=3")->row()->counts;
                    $data[$key]['shou0'] = $this->db->query("select count('id') as counts from vndb_daily_anomalies where `date`='{$date}' and `shou`=0 and length(ticker)<=3")->row()->counts;
                }
                $this->db->insert_batch('vndb_anomalies_daily', $data);
                $sql = "UPDATE vndb_anomalies_daily SET correct = IF(txtrefall = txtrefsum AND txtrefall = tblref AND txtprcall = txtprcsum AND txtprcall = tblprc, 'ok', IF(txtrefall = txtrefsum AND txtrefall = tblref, 'prc', IF(txtprcall = txtprcsum AND txtprcall = tblprc, 'ref', 'bad')))";
                $this->db->simple_query($sql);
                return 'done';
            } else {
                return 'folder is empty';
            }
    }
    
    
  function prices_stoxfeed(){
    
        define('_USERNAME', 'IFRC');
        define('_PASSWORD', 'IFRCF$$D2014');
        $markets = array();
        array_push($markets, array('market' => 'hose', 'indexType' => 0));
        array_push($markets, array('market' => 'hnx', 'indexType' => 1));
        array_push($markets, array('market' => 'upcom', 'indexType' => 3));
        $startdate=date('Y-m-d');
        $enddate=date('Y-m-d');
      // $startdate = '2014-10-20';
      // $enddate = '2014-10-20';
        //$enddate = date('Y-m-d', strtotime($_POST['date']));
        $date = date('Ymd');
        
        /* output message */
        $message = 'DONE!';
        
        
        foreach ($markets as $key => $value) {
            
            $urlPageCount = "http://datafeed.stox.vn/DataFeed.asmx/GetEODStockByEXPageCount";
            $postPageCount = "username="._USERNAME."&password="._PASSWORD."&exCode=".$value['market']."&date=".$enddate;
            
            $ch = curl_init($urlPageCount);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postPageCount);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $xml_data = curl_exec($ch);
            $PageCount = simplexml_load_string($xml_data);

            for($page = 0; $page < $PageCount; $page++)
            {
                
                /* parameters */
                switch ($value['market']) {
                    case 'hose':
                        $header = array('StockSymbol','DateReport','PriorClosePrice', 'Ceiling', 'Floor', 'OpenPrice', 'Highest', 'Lowest','Last', 'Totalshare','TotalValue');
                        $tagget = 'stox_tb_HOSE_Trading';
                        break;
                    case 'hnx':
                        $header = array('Code', 'Trading_date','Basic_price', 'Ceiling_price', 'Floor_price', 'Open_price', 'Highest_price', 'Lowest_price','average_price', 'Close_price', 'Nm_total_traded_qtty','Nm_total_traded_value');
                        $tagget = 'stox_tb_StocksInfo';
                        break;
                    case 'upcom':
                        $header = array('Code', 'Trading_date','Basic_price', 'Ceiling_price', 'Floor_price', 'Open_price', 'Highest_price', 'Lowest_price','average_price', 'Close_price', 'Nm_total_traded_qtty','Nm_total_traded_value');
                        $tagget = 'stox_tb_upcom_StocksInfo';
                        break;
                }
            
                $url = 'http://datafeed.stox.vn/DataFeed.asmx/GetEODStockByEX';
                $post = "username="._USERNAME."&password="._PASSWORD."&exCode={$value['market']}&date={$enddate}&page=".$page;
                $base64 = true;

                $dataxml = $this->getdataxml($url, $post, $tagget, $header, $base64);

                $path="\\\LOCAL\IFRCVN\VNDB\METASTOCK\PRICES\STP\\";
                //$path = "D:/IFRCDATA/VNSDB/EXCHANGES/HNX/";
                if (!is_dir($path)) {
                    mkdir($path);
                }

                $filename = "STP_{$value['market']}_{$date}.txt";
                $header = array_map('strtolower', $header);
                $file = $path . $filename;
                if(is_file($file))
                {
                    $this->exportfile_append($path, $filename, $header, $dataxml);
                }
                else
                {
                   
                    $this->exportfile($path, $filename, $header, $dataxml);
                }
        
                /* result output json */
        
                /*if (is_file("{$path}{$filename}")) {
                    $message .= "{$path}{$filename} success \n";
                } else {
                    $message .= "{$path}{$filename} error \n";
                }
                unset($markets[$key]);*/
            }
        }
       echo $message;
        exit();

        }
    
  function getdataxml($url = '', $post = '', $tagget = '', $header = array(), $base64 = true) {
        $result = array();
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $xml_data = curl_exec($ch);
        if ($xml_data) {
            $parser = simplexml_load_string($xml_data);
            $doc = new DOMDocument();
            if ($base64 == true) {
                $doc->loadXML(base64_decode($parser));
            } else {
                $doc->loadXML($parser);
            }
            $key = 0;

            foreach ($doc->getElementsByTagName($tagget) as $item) {
                foreach ($header as $col) {
                    $value = @$item->getElementsByTagName($col)->item(0)->nodeValue;
                    $result[$key][strtolower($col)] = isset($value) ? trim($value) : '';
                }
                $key++;
            }
        }
        return $result;
    }

    function exportfile($path = '', $file_name = '', $headers = array(), $arr = array()) {
        $file = "{$path}{$file_name}";
        if (is_file($file)) {
            unlink($file);
        }
        $temp = '';
        $data = array();
        foreach ($headers as $k => $value) {
            $temp .= $value . chr(9);
        }
        $data[0] = trim($temp) . PHP_EOL;
        foreach ($arr as $k => $item) {
            $temp = '';
            $count = 1;
            foreach ($headers as $value) {
                if ($count < count($headers)) {
                    if (isset($item[$value])) {
                        $temp .= $item[$value] . chr(9);
                    } else {
                        $temp .= chr(9);
                    }
                } else {
                    $temp .= $item[$value];
                }
                $count++;
            }
            $data[] = $temp . PHP_EOL;
        }
        file_put_contents($file, $data);
        unset($data);
    }

    function exportfile_append($path = '', $file_name = '', $headers = array(), $arr = array()) {
        $file = "{$path}/{$file_name}";
        
        $temp = '';
        $data = array();
        foreach ($headers as $k => $value) {
            $temp .= $value . chr(9);
        }
        $data[0] = trim($temp) . PHP_EOL;
        foreach ($arr as $k => $item) {
            $temp = '';
            $count = 1;
            foreach ($headers as $value) {
                if ($count < count($headers)) {
                    if (isset($item[$value])) {
                        $temp .= $item[$value] . chr(9);
                    } else {
                        $temp .= chr(9);
                    }
                } else {
                    $temp .= $item[$value];
                }
                $count++;
            }
            $data[] = $temp . PHP_EOL;
            unset($data['0']);
        }
        file_put_contents($file, $data, FILE_APPEND);
        unset($data);
    }  

     public function upload_stp_prices()
    {
        
            $date = date('Ymd');
            set_time_limit(0);
            ini_set('memory_limit', '5000M');
            $from = microtime(true);
            $this->db->query('TRUNCATE TABLE `vndb_prices_day_stp`');
            $this->db->query('LOAD DATA LOCAL INFILE "//LOCAL/IFRCVN/VNDB/METASTOCK/PRICES/STP/STP_hose_'.$date.'.txt" INTO TABLE vndb_prices_day_stp
CHARACTER SET UTF8 FIELDS TERMINATED BY  "\t"  LINES TERMINATED BY  "\r\n" IGNORE 1 LINES (ticker,date,pref,pcei,pflr,popn,phgh,plow,pcls,vlm,trn)');
            $this->db->query('update `vndb_prices_day_stp` set market="HSX" where market is null;');
            
             $this->db->query('LOAD DATA LOCAL INFILE "//LOCAL/IFRCVN/VNDB/METASTOCK/PRICES/STP/STP_hnx_'.$date.'.txt" INTO TABLE vndb_prices_day_stp
CHARACTER SET UTF8 FIELDS TERMINATED BY  "\t"  LINES TERMINATED BY  "\r\n" IGNORE 1 LINES (ticker,date,pref,pcei,pflr,popn,phgh,plow,pavg,pcls,vlm,trn)');
            $this->db->query('update `vndb_prices_day_stp` set market="HNX" where market is null;');
            
 $this->db->query('LOAD DATA LOCAL INFILE "//LOCAL/IFRCVN/VNDB/METASTOCK/PRICES/STP/STP_upcom_'.$date.'.txt" INTO TABLE vndb_prices_day_stp
CHARACTER SET UTF8 FIELDS TERMINATED BY  "\t"  LINES TERMINATED BY  "\r\n" IGNORE 1 LINES (ticker,date,pref,pcei,pflr,popn,phgh,plow,pavg,pcls,vlm,trn)');
            $this->db->query('update `vndb_prices_day_stp` set market="UPC" where market is null;');

            $this->db->query('update `vndb_prices_day_stp` set source="EXC", yyyymmdd=concat(left(date,4),SUBSTR(date FROM 6 FOR 2),right(date,2));');
            $this->db->query('update `vndb_prices_day_stp` set last=if(pcls<>0,pcls,pref);');
            
            $total = microtime(true) - $from;
            $result[0]['time'] = round($total, 2);
            $result[0]['task'] = 'Finish';
            
            echo json_encode($result);
    }
    function compare_prices(){
       // $this->_statistics();
       // $this->_anomalies();
       // $now = date('Y-m-d');
       // $yyyymmdd = date('Ymd');
        $difference = $this->db->query("select * from (select a.ticker,a.date,a.market,b.vlm, (a.vlm-b.vlm) as vlm_diff, (a.pcls-b.pcls) as pcls_diff, (a.last-b.last) as last_diff  
from vndb_prices_day a, vndb_prices_day_stp b 
where a.ticker=b.ticker and a.date=b.date) as compare
where vlm_diff>0 OR pcls_diff >0 OR last_diff>0;")->result_array();
        //$anomalies = $this->db->query("SELECT * FROM vndb_anomalies_daily WHERE date = '$now'")->result_array();
      //  $this->data->changes = $changes;
        $this->data->difference = $difference;
        $this->data->title = "Compare prices";
        $this->template->write_view('content', 'prices/compare_prices', $this->data);
        $this->template->write('title', 'Compare prices');
        $this->template->render();
    }
    function download_hsx_newwebsite(){
        if (!isset($data['err']) || count($data['err']) == 0) {
	//	if ($_SESSION['ltw_level'] > 1) {
			$date = date('d.m.Y');
			if(isset($_GET['date'])){
				$date = $_GET['date'];
			}
			echo $date;
			$curl = new curl( );
		//	$content = 'ticker	isin	yyyymmdd	exc_pref	exc_pcei	exc_pflr	exc_popn	exc_pcls	exc_phgh	exc_plow	exc_avg	exc_pvlm	exc_ptrn	exc_capi' . PHP_EOL;
            $content = 'source	ticker	market	date	yyyymmdd	shli	shou	shfn	pref	pcei	pflr	popn	phgh	plow	pbase	pavg	pcls	vlm	trn	adj_pcls	adj_coeff' . PHP_EOL;
        	$datahtml = $curl->makeRequest('GET','http://www.hsx.vn/Modules/Rsde/Report/QuoteReport?pageFieldName1=Date&pageFieldValue1='.$date.'&pageFieldOperator1=eq&pageFieldName2=KeyWord&pageFieldValue2=&pageFieldOperator2=&pageFieldName3=IndexType&pageFieldValue3=0&pageFieldOperator3=&pageCriteriaLength=3&_search=false&nd=1423196420736&rows=2147483647&page=1&sidx=id&sord=desc', array(''));
			$datahtml1 = $curl->makeRequest('GET','http://www.hsx.vn/Modules/Rsde/Report/QuoteReport?pageFieldName1=Date&pageFieldValue1='.$date.'&pageFieldOperator1=eq&pageFieldName2=KeyWord&pageFieldValue2=&pageFieldOperator2=&pageFieldName3=IndexType&pageFieldValue3=0&pageFieldOperator3=&pageCriteriaLength=3&_search=false&nd=1423196420736&rows=2147483647&page=1&sidx=id&sord=desc', array(''));
            $datahtml = substr($datahtml, strpos($datahtml,'{'));
			$datahtml = json_decode($datahtml, 1);
			foreach($datahtml['rows'] as $item){
                $content .='EXC'. chr(9);//source
				$ticker = $item['cell'][0];
				$content .= $ticker . chr(9);//ticker
                $content .='HSX' .chr(9);//market
				//$isin =  $item['cell'][2];
				//$content .= $isin . chr(9);//isin
                $content .=date('Y/m/d').chr(9);//date
				$lcdate   = explode('.',$date);
				$yyyymmdd = $lcdate[2] . $lcdate[1] . $lcdate[0];
				$content .= $yyyymmdd . chr(9);//yyyymmdd
                $content .=''.chr(9);//shli
                $content .=''.chr(9);//shou
                $content .=''.chr(9);//shfn
				$content .= 1000*str_replace(',', '.', $item['cell'][6]) . chr(9);//exc_pref
				$content .= 1000*str_replace(',', '.', $item['cell'][4]) . chr(9);//exc_pcei
                $content .= 1000*str_replace(',', '.', $item['cell'][5]) . chr(9);//exc_pflr
				$content .= 1000*str_replace(',', '.', $item['cell'][7]) . chr(9);//exc_popn
                $content .= 1000*str_replace(',', '.', $item['cell'][12]) . chr(9);//exc_phgh
                $content .= 1000*str_replace(',', '.', $item['cell'][11]) . chr(9);//exc_plow
                $content .= ''.chr(9);//pbase
                $content .= 1000*str_replace(',', '.', $item['cell'][13]) . chr(9);//exc_avg
				$content .= 1000*str_replace(',', '.', $item['cell'][8]) . chr(9);//exc_pcls
				$content .= 10*str_replace(',', '.',str_replace('.', '', $item['cell'][14])) . chr(9);//exc_pvlm
				$content .= 1000000*str_replace(',', '.', str_replace('.', '', $item['cell'][15])) . chr(9);//exc_ptrn
                $content .=''.chr(9);//adj_pcls
               // $content .=''.chr(9);//adj_coeff
				$content .= PHP_EOL;
			}
			$date2 = explode('.', $date);
			$date3 = $date2[2] . $date2[1] . $date2[0];
			$file = fopen('\\\LOCAL\IFRCVN\VNDB\METASTOCK\PRICES\EXC\EXC_HSX_' .$date3. '.txt', 'w');
            fwrite($file, $content);
			$file = fopen('\\\LOCAL\IFRCVN\VNDB\HTM\PRICES\EXC\HSX\HSX_' .$date3. '.htm', 'w');
            fwrite($file, $datahtml1);
            $this->db->query("LOAD DATA LOCAL INFILE '\\\\\\\\LOCAL\\\IFRCVN\\\VNDB\\\METASTOCK\\\PRICES\\\EXC\\\EXC_HSX_" .$date3. ".txt' INTO TABLE vndb_prices_day FIELDS TERMINATED BY  '\\t'  IGNORE 1 LINES");
                //LOAD DATA INFILE 'D:\\IFRCVNS\\VNDB\\METASTOCK\\code_stoxplus.txt'INTO TABLE code_stoxplus FIELDS TERMINATED BY  '\t'  IGNORE 1 LINE;
            
		//	 }
        }
            $sql = "UPDATE vndb_prices_day SET last=IF(pcls <> 0, pcls, pref);";                
            $this->db->query($sql);
}
    
    
        
}