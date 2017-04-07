<?php

/* * ******************************************************************************************************************* *
 * 	 Author: Minh Đẹp Trai			 																					 *
 * * ******************************************************************************************************************* */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Reference extends Admin {

    protected $data;
    private $cal_dates;
    protected $_option;

    public function __construct() {
        parent::__construct();
        set_time_limit(0);
        $this->load->library('curl');
        $this->load->library('simple_html_dom');
    }

    public function reference_compare(){
        $this->template->write_view('content', 'reference/reference_compare', $this->data);
        $this->template->write('title', 'Reference Compare');
        $this->template->render();
    }

    public function process_reference_compare(){
        if ($this->input->is_ajax_request()) {
            set_time_limit(0);
            $from = microtime(true);

            $dataCompare = $this->db->query("SELECT `ticker`, `name`, `date`, `market`, `yyyymmdd`, `ipo`, `ftrd`, `shli`, `shou`, 'Intro' as `option` FROM `vndb_reference_day` WHERE `ticker` NOT IN (
    SELECT `ticker` FROM `vndb_reference_daily` 
        WHERE `date` = (
            SELECT MAX(`date`) as `date` FROM `vndb_reference_daily`
        )
    )
UNION ALL
SELECT a.`ticker`, a.`name`, a.`date`, `market`, `yyyymmdd`, `ipo`, `ftrd`, `shli`, `shou`, 'Remove' as `option` FROM `vndb_reference_daily` a WHERE a.`ticker` NOT IN (
    SELECT b.`ticker` FROM vndb_reference_day b 
        WHERE a.`ticker` = b.`ticker`
    ) AND a.`date` = (
        SELECT MAX(`date`) as `date` FROM `vndb_reference_daily`
    )
")->result_array();
            $response[0]['report'] = array();
            $numCompare = count($dataCompare);
            if($numCompare != 0){
                $dataInsert = array();
                foreach($dataCompare as $compare){
                    $response[0]['report'][] = 'TICKER '.$compare['ticker'].' '.$compare['option'];
                    $numCheck = $this->db->query("SELECT * FROM vndb_reference_compare WHERE ticker = '{$compare['ticker']}' AND date = '{$compare['date']}'")->num_rows();
                    if($numCheck == 0){
                        $dataInsert[] = array(
                            'ticker'    => $compare['ticker'],
                            'name'      => $compare['name'],
                            'date'      => $compare['date'],
                            'market'    => $compare['market'],
                            'yyyymmdd'  => $compare['yyyymmdd'],
                            'ipo'       => $compare['ipo'],
                            'ftrd'      => $compare['ftrd'],
                            'shli'      => $compare['shli'],
                            'shou'      => $compare['shou'],
                            'comment'   => $compare['option']
                        );
                    }
                }
                if(count($dataInsert) > 0){
                    $this->db->insert_batch('vndb_reference_compare',$dataInsert);
                }
            }

            $total = microtime(true) - $from;
            $response[0]['time'] = round($total, 2);
            $response[0]['task'] = 'Time';
            echo json_encode($response);
        }
    }

    public function reference_old() {
        if (!isset($data['err']) || count($data['err']) == 0) {
            $this->db->query("TRUNCATE TABLE vndb_reference_day");
            $start = 0;  // Download d? li?u t? ngày
            $end = 400;
            $date = date('d/m/Y');
            $content = 'source,ticker,name,market,date,yyyymmdd,ipo,ipo_shli,ipo_shou,ftrd,ftrd_cls,shli,shou,shfn,capi,capi_fora,capi_forn,capi_stat' . PHP_EOL;
            ;
            $content = str_replace(',', chr(9), $content);
            while ($start <= $end) {
                $curl = new curl( );
                $datahtml = $curl->makeRequest('post', 'http://www.hnx.vn/en/web/guest/dang-niem-yet?p_p_id=hsnydkgdjson_WAR_HnxIndexportlet&p_p_lifecycle=2&p_p_state=exclusive&p_p_mode=view&p_p_cacheability=cacheLevelPage&_hsnydkgdjson_WAR_HnxIndexportlet_json=1&_hsnydkgdjson_WAR_HnxIndexportlet_viewType=ny&_hsnydkgdjson_WAR_HnxIndexportlet_viewType=ny', array('iColumns=10','iDisplayLength=100','iDisplayStart='.$start,'iSortCol_0=1',
				
		   'iSortingCols=1','loaick=','mDataProp_0=0','mDataProp_1=1','mDataProp_2=2',
			'nganh=','p_p_cacheability=cacheLevelPage','p_p_col_id=column-1','p_p_id=hsnydkgdjson_WAR_HnxIndexportlet',
			'p_p_lifecycle=2','quymony=','sColumns=','sEcho=5'));
			//echo "<pre>";print_r($datahtml);exit; 
                $datahtml1 = substr($datahtml, strpos($datahtml, '{'));
                //print_r($datahtml);
                $datahtml = json_decode($datahtml1, 1);
                //print_r($datahtml);
                foreach ($datahtml['aaData'] as $item) {
                    $source = 'EXC';
                    $market = 'HNX';
                    $date1 = date('Y/m/d');
                    $content .= $source . chr(9);
                    $ticker = substr($item[1], strpos($item[1], '>') + 1);
                    $ticker = str_replace('</a>', '', $ticker);
                    $content .= $ticker . chr(9);
                    $name = substr($item[2], strpos($item[2], '>') + 1);
                    $name = str_replace('</a>', '', $name);
                    $content .= $name . chr(9);
                    $content .= $market . chr(9);
                    $content .= $date1 . chr(9);
                    //$date = explode('/', $date);
                    //$date=$date[2] . $date[1] . $date[0];
                    $content .= str_replace('/', '', $date1) . chr(9);
                    $lcdate1 = explode('/', $item[3]);
                    $lcdate = $lcdate1[2] . '/' . $lcdate1[1] . '/' . $lcdate1[0];
                    $content .=chr(9);
                    $content .=chr(9);
                    $content .=chr(9);
                    $content .= $lcdate . chr(9);
                    $content .=chr(9);
                    $content .= str_replace(',', '', $item[4]) . chr(9);
                    $content .=chr(9);
                    $content .=chr(9);
                    $content .= str_replace(',', '', $item[6]);
                    //$content .= 1000*str_replace('.', '', $item[7]) . chr(9);
                    $content .=chr(9);
                    $content .=chr(9);
                    $content .=chr(9);
                    $content .= PHP_EOL;
                }
                //print_r($datahtml);
                //pre($datahtml);die();
                $html = str_get_html($datahtml1);
                $date2 = explode('/', $date);
                $date3 = $date2[2] . $date2[1] . $date2[0];
                $start = $start + 100;
            }
            $base = '\\\LOCAL\IFRCVN\VNDB\METASTOCK\REFERENCE\EXC\REF_HNX_' . $date3 . '.txt';
            $file = fopen($base, 'w');
            fwrite($file, $content);
            $base_url = str_replace("\\", "\\\\", $base);
            //$base_url = $base;
            $arr_header = array(
                'source','ticker','name','market','date','yyyymmdd','ipo','ipo_shli','ipo_shou','ftrd','ftrd_cls','shli','shou','shfn','capi','capi_fora','capi_forn','capi_stat'
            );
            load_data_mysql('\\\LOCAL\IFRCVN\VNDB\METASTOCK\REFERENCE\EXC\\', 'REF_HNX_' . $date3 . '.txt', 'vndb_reference_day', $arr_header);
            //$this->db->query("LOAD DATA LOCAL INFILE '" . $base_url . "' INTO TABLE vndb_reference_day FIELDS TERMINATED BY '\t' IGNORE 1 LINES (source,ticker,name,market,date	,yyyymmdd,ipo,ipo_shli,ipo_shou,ftrd,ftrd_cls,shli,shou,shfn,capi,capi_fora,capi_forn,capi_stat)");
            $start = 0;  // Download d? li?u t? ngày
            $end = 400;
            $date = date('d/m/Y');
            $content = 'source,ticker,name,market,date,yyyymmdd,ipo,ipo_shli,ipo_shou,ftrd,ftrd_cls,shli,shou,shfn,capi,capi_fora,capi_forn,capi_stat' . PHP_EOL;
            ;
            $content = str_replace(',', chr(9), $content);
			
			
			
			
            while ($start <= $end) {
                $curl = new curl( );
                $datahtml = $curl->makeRequest('post','http://www.hnx.vn/en/web/guest/143?p_p_id=hsnydkgdjson_WAR_HnxIndexportlet&p_p_lifecycle=2&p_p_state=exclusive&p_p_mode=view&p_p_cacheability=cacheLevelPage&_hsnydkgdjson_WAR_HnxIndexportlet_json=1&_hsnydkgdjson_WAR_HnxIndexportlet_viewType=ny&_hsnydkgdjson_WAR_HnxIndexportlet_viewType=ny',array('iColumns=10','iDisplayLength=100','iDisplayStart='.$start,'iSortCol_0=1','iSortingCols=1','loaick=','mDataProp_0=0','mDataProp_1=1','mDataProp_2=2',
				
			'nganh=','p_p_cacheability=cacheLevelPage','p_p_col_id=column-1','p_p_id=hsnydkgdjson_WAR_HnxIndexportlet',
			'p_p_lifecycle=2','quymony=','sColumns=','sEcho=5'));
                $datahtml1 = substr($datahtml, strpos($datahtml, '{'));
                //print_r($datahtml);
                $datahtml = json_decode($datahtml1, 1);
                //print_r($datahtml);
                foreach ($datahtml['aaData'] as $item) {
                    $source = 'EXC';
                    $market = 'UPC';
                    $date1 = date('Y/m/d');
                    $content .= $source . chr(9);
                    $ticker = substr($item[1], strpos($item[1], '>') + 1);
                    $ticker = str_replace('</a>', '', $ticker);
                    $content .= $ticker . chr(9);
                    $name = substr($item[2], strpos($item[2], '>') + 1);
                    $name = str_replace('</a>', '', $name);
                    $content .= $name . chr(9);
                    $content .= $market . chr(9);
                    $content .= $date1 . chr(9);
                    //$date = explode('/', $date);
                    //$date=$date[2] . $date[1] . $date[0];
                    $content .= str_replace('/', '', $date1) . chr(9);
                    $lcdate1 = explode('/', $item[3]);
                    $lcdate = $lcdate1[2] . '/' . $lcdate1[1] . '/' . $lcdate1[0];
                    $content .=chr(9);
                    $content .=chr(9);
                    $content .=chr(9);
                    $content .= $lcdate . chr(9);
                    $content .=chr(9);
                    $content .= str_replace(',', '',str_replace('.', '', $item[4])) . chr(9);
                    $content .= chr(9);
                    $content .=chr(9);
					$content .= str_replace(',', '',$item[6]);
                    //$content .= str_replace(',', '',str_replace('.', '', $item[6]));
                    //$content .= 1000*str_replace('.', '', $item[7]) . chr(9);
                    $content .=chr(9);
                    $content .=chr(9);
                    $content .=chr(9);
                    $content .= PHP_EOL;
                }
                //print_r($datahtml);
                //pre($datahtml);die();
                $html = str_get_html($datahtml1);
                $date2 = explode('/', $date);
                $date3 = $date2[2] . $date2[1] . $date2[0];
                $start = $start + 100;
            }
            $base = '\\\LOCAL\IFRCVN\VNDB\METASTOCK\REFERENCE\EXC\REF_UPC_' . $date3 . '.txt';
            $file = fopen($base, 'w');
            fwrite($file, $content);
            $base_url = str_replace("\\", "\\\\", $base);
            load_data_mysql('\\\LOCAL\IFRCVN\VNDB\METASTOCK\REFERENCE\EXC\\', 'REF_UPC_' . $date3 . '.txt', 'vndb_reference_day', $arr_header);
            //$this->db->query("LOAD DATA LOCAL INFILE '" . $base_url . "' INTO TABLE vndb_reference_day FIELDS TERMINATED BY '\t' IGNORE 1 LINES (source,ticker,name,market,date	,yyyymmdd,ipo,ipo_shli,ipo_shou,ftrd,ftrd_cls,shli,shou,shfn,capi,capi_fora,capi_forn,capi_stat)");
			
			
			
			
            $this->db->query("TRUNCATE TABLE vndb_reference_day_hsx");
            $dir_hsx = '\\\LOCAL\IFRCVN\VNDB\METASTOCK\REFERENCE\EXC\MANUAL\\';
            $files_hsx = glob($dir_hsx . '*.txt');
            foreach ($files_hsx as $base_hsx) {
                $filename_hsx = pathinfo($base_hsx, PATHINFO_FILENAME);
                $arr = explode('_', $filename_hsx);
                if ($arr[2] == $date3 && $arr[1] == 'HSX') {
                    $arr_hsx = explode('_', $filename_hsx);
                    $year_hsx = substr($arr_hsx[2], 0, 4);
                    $month_hsx = substr($arr_hsx[2], 4, 2);
                    $day_hsx = substr($arr_hsx[2], 6, 2);
                    $date_hsx = $year_hsx . '-' . $month_hsx . '-' . $day_hsx;
                    $yyyymmdd_hsx = $year_hsx . $month_hsx . $day_hsx;
                    $file_name_hsx = basename($base_hsx, ".txt");
                    $base_url_hsx = str_replace("\\", "\\\\", $base_hsx);
                    $arr_header = array(
                        'ticker','name','shli','shou','ipo'
                    );
                    load_data_mysql($dir_hsx, $file_name_hsx.'.txt', 'vndb_reference_day_hsx' , $arr_header);
                    //$this->db->query("LOAD DATA LOCAL INFILE '" . $base_url_hsx . "' INTO TABLE vndb_reference_day_hsx FIELDS TERMINATED BY '\t' IGNORE 1 LINES (ticker,name,shli,shou,ipo) SET market = 'HSX', date = '" . $date_hsx . "', yyyymmdd = '" . $yyyymmdd_hsx . "'");
                    $this->db->query("update vndb_reference_day_hsx set market = 'HSX', date = '" . $date_hsx . "', yyyymmdd = '" . $yyyymmdd_hsx . "'");
                    $this->db->query("update vndb_reference_day_hsx set ticker = REPLACE(ticker,' ',''),shli = REPLACE(shli,' ',''), shli = REPLACE(shli,'.',''),shli = REPLACE(shli,',',''),shou = REPLACE(shou,' ',''),shou = REPLACE(shou,'.',''),shou = REPLACE(shou,',',''), ipo = REPLACE(ipo,'/',','), ipo = STR_TO_DATE(ipo,'%d,%m,%Y')");
                }
            }
        }
        redirect(admin_url());
    }

    public function reference_all() {
        $this->template->write_view('content', 'reference/reference_all', $this->data);
        $this->template->write('title', 'Reference All');
        $this->template->render();
    }

    public function process_reference_all() {
        if ($this->input->is_ajax_request()) {
            set_time_limit(0);
            $from = microtime(true);
            /*             * *** * **
             *    Old	*
             * * * *** * */
            if (!isset($data['err']) || count($data['err']) == 0) {
                $this->db->query("TRUNCATE TABLE vndb_reference_day");
                $start = 0;  // Download d? li?u t? ngày
                $end = 400;
                $date = date('d/m/Y');
                $content = 'source,ticker,name,market,date,yyyymmdd,ipo,ipo_shli,ipo_shou,ftrd,ftrd_cls,shli,shou,shfn,capi,capi_fora,capi_forn,capi_stat' . PHP_EOL;
                $content = str_replace(',', chr(9), $content);
                while ($start <= $end) {
                    $curl = new curl( );
                    $datahtml = $curl->makeRequest('post', 'http://hnx.vn/en/web/guest/dang-niem-yet', array('iColumns=10','iDisplayLength=100','iDisplayStart='.$start,'iSortCol_0=1',
		   'iSortingCols=1','loaick=','mDataProp_0=0','mDataProp_1=1','mDataProp_2=2',
			'nganh=','p_p_cacheability=cacheLevelPage','p_p_col_id=column-1','p_p_id=hsnydkgdjson_WAR_HnxIndexportlet',
			'p_p_lifecycle=2','quymony=','sColumns=','sEcho=5'));
                    $datahtml1 = substr($datahtml, strpos($datahtml, '{'));
                    //print_r($datahtml);
                    $datahtml = json_decode($datahtml1, 1);
                    //print_r($datahtml);
                    foreach ($datahtml['aaData'] as $item) {
                        $source = 'EXC';
                        $market = 'HNX';
                        $date1 = date('Y/m/d');
                        $content .= $source . chr(9);
                        $ticker = substr($item[1], strpos($item[1], '>') + 1);
                        $ticker = str_replace('</a>', '', $ticker);
                        $content .= $ticker . chr(9);
                        $name = substr($item[2], strpos($item[2], '>') + 1);
                        $name = str_replace('</a>', '', $name);
                        $content .= $name . chr(9);
                        $content .= $market . chr(9);
                        $content .= $date1 . chr(9);
                        //$date = explode('/', $date);
                        //$date=$date[2] . $date[1] . $date[0];
                        $content .= str_replace('/', '', $date1) . chr(9);
                        $lcdate1 = explode('/', $item[3]);
                        $lcdate = $lcdate1[2] . '/' . $lcdate1[1] . '/' . $lcdate1[0];
                        $content .=chr(9);
                        $content .=chr(9);
                        $content .=chr(9);
                        $content .= $lcdate . chr(9);
                        $content .=chr(9);
                        $content .= str_replace(',', '', $item[4]) . chr(9);
                        $content .=chr(9);
                        $content .=chr(9);
                        $content .= str_replace(',', '', $item[6]);
                        //$content .= 1000*str_replace('.', '', $item[7]) . chr(9);
                        $content .=chr(9);
                        $content .=chr(9);
                        $content .=chr(9);
                        $content .= PHP_EOL;
                    }
                    //print_r($datahtml);
                    //pre($datahtml);die();
                    $html = str_get_html($datahtml1);
                    $date2 = explode('/', $date);
                    $date3 = $date2[2] . $date2[1] . $date2[0];
                    $start = $start + 100;
                }
                $base = '\\\LOCAL\IFRCVN\VNDB\METASTOCK\REFERENCE\EXC\REF_HNX_' . $date3 . '.txt';
                $file = fopen($base, 'w');
                fwrite($file, $content);
                $base_url = str_replace("\\", "\\\\", $base);
                $this->db->query("LOAD DATA LOCAL INFILE '" . $base_url . "' INTO TABLE vndb_reference_day FIELDS TERMINATED BY '\t' IGNORE 1 LINES (source,ticker,name,market,date	,yyyymmdd,ipo,ipo_shli,ipo_shou,ftrd,ftrd_cls,shli,shou,shfn,capi,capi_fora,capi_forn,capi_stat)");
                $start = 0;  // Download d? li?u t? ngày
                $end = 400;
                $date = date('d/m/Y');
                $content = 'source,ticker,name,market,date,yyyymmdd,ipo,ipo_shli,ipo_shou,ftrd,ftrd_cls,shli,shou,shfn,capi,capi_fora,capi_forn,capi_stat' . PHP_EOL;
                $content = str_replace(',', chr(9), $content);
                while ($start <= $end) {
                    $curl = new curl( );
                    $datahtml = $curl->makeRequest('post', 'http://www.hnx.vn/en/web/guest/143', array('iColumns=10','iDisplayLength=100','iDisplayStart='.$start,'iSortCol_0=1','iSortingCols=1','loaick=','mDataProp_0=0','mDataProp_1=1','mDataProp_2=2',
			'nganh=','p_p_cacheability=cacheLevelPage','p_p_col_id=column-1','p_p_id=hsnydkgdjson_WAR_HnxIndexportlet',
			'p_p_lifecycle=2','quymony=','sColumns=','sEcho=5'));
                    $datahtml1 = substr($datahtml, strpos($datahtml, '{'));
                    //print_r($datahtml);
                    $datahtml = json_decode($datahtml1, 1);
                    //print_r($datahtml);
                    foreach ($datahtml['aaData'] as $item) {
                        $source = 'EXC';
                        $market = 'UPC';
                        $date1 = date('Y/m/d');
                        $content .= $source . chr(9);
                        $ticker = substr($item[1], strpos($item[1], '>') + 1);
                        $ticker = str_replace('</a>', '', $ticker);
                        $content .= $ticker . chr(9);
                        $name = substr($item[2], strpos($item[2], '>') + 1);
                        $name = str_replace('</a>', '', $name);
                        $content .= $name . chr(9);
                        $content .= $market . chr(9);
                        $content .= $date1 . chr(9);
                        //$date = explode('/', $date);
                        //$date=$date[2] . $date[1] . $date[0];
                        $content .= str_replace('/', '', $date1) . chr(9);
                        $lcdate1 = explode('/', $item[3]);
                        $lcdate = $lcdate1[2] . '/' . $lcdate1[1] . '/' . $lcdate1[0];
                        $content .=chr(9);
                        $content .=chr(9);
                        $content .=chr(9);
                        $content .= $lcdate . chr(9);
                        $content .=chr(9);
                        $content .= str_replace(',', '', $item[4]) . chr(9);
                        $content .= chr(9);
                        $content .=chr(9);
                        $content .= str_replace(',', '', $item[6]);
                        //$content .= 1000*str_replace('.', '', $item[7]) . chr(9);
                        $content .=chr(9);
                        $content .=chr(9);
                        $content .=chr(9);
                        $content .= PHP_EOL;
                    }
                    //print_r($datahtml);
                    //pre($datahtml);die();
                    $html = str_get_html($datahtml1);
                    $date2 = explode('/', $date);
                    $date3 = $date2[2] . $date2[1] . $date2[0];
                    $start = $start + 100;
                }
                $base = '\\\LOCAL\IFRCVN\VNDB\METASTOCK\REFERENCE\EXC\REF_UPC_' . $date3 . '.txt';
                $file = fopen($base, 'w');
                fwrite($file, $content);
                $base_url = str_replace("\\", "\\\\", $base);
                $this->db->query("LOAD DATA LOCAL INFILE '" . $base_url . "' INTO TABLE vndb_reference_day FIELDS TERMINATED BY '\t' IGNORE 1 LINES (source,ticker,name,market,date	,yyyymmdd,ipo,ipo_shli,ipo_shou,ftrd,ftrd_cls,shli,shou,shfn,capi,capi_fora,capi_forn,capi_stat)");

                $this->db->query("TRUNCATE TABLE vndb_reference_day_hsx");
                $dir_hsx = '\\\LOCAL\IFRCVN\VNDB\METASTOCK\REFERENCE\EXC\MANUAL\\';
                $files_hsx = glob($dir_hsx . '*.txt');
                foreach ($files_hsx as $base_hsx) {
                    $filename_hsx = pathinfo($base_hsx, PATHINFO_FILENAME);
                    $arr = explode('_', $filename_hsx);
                    if ($arr[2] == $date3 && $arr[1] == 'HSX') {
                        $arr_hsx = explode('_', $filename_hsx);
                        $year_hsx = substr($arr_hsx[2], 0, 4);
                        $month_hsx = substr($arr_hsx[2], 4, 2);
                        $day_hsx = substr($arr_hsx[2], 6, 2);
                        $date_hsx = $year_hsx . '-' . $month_hsx . '-' . $day_hsx;
                        $yyyymmdd_hsx = $year_hsx . $month_hsx . $day_hsx;
                        $file_name_hsx = basename($base_hsx, ".txt");
                        $base_url_hsx = str_replace("\\", "\\\\", $base_hsx);
                        $this->db->query("LOAD DATA LOCAL INFILE '" . $base_url_hsx . "' INTO TABLE vndb_reference_day_hsx FIELDS TERMINATED BY '\t' IGNORE 1 LINES (ticker,name,shli,shou,ipo) SET market = 'HSX', date = '" . $date_hsx . "', yyyymmdd = '" . $yyyymmdd_hsx . "'");
                        $this->db->query("update vndb_reference_day_hsx set ticker = REPLACE(ticker,' ',''),shli = REPLACE(shli,' ',''), shli = REPLACE(shli,'.',''),shli = REPLACE(shli,',',''),shou = REPLACE(shou,' ',''),shou = REPLACE(shou,'.',''),shou = REPLACE(shou,',',''), ipo = REPLACE(ipo,'/',','), ipo = STR_TO_DATE(ipo,'%d,%m,%Y')");
                    }
                }
            }
            /** *** * **
             *   New   *
             ** * *** **/
            $this->get_shares_hnx();
            $this->get_shares_upc();
            /** ****** **
             *  Merges  *
             ** ****** **/
            $this->db->query("DELETE FROM vndb_reference_day where market = 'HSX' and source = 'EXC'");
            $this->db->query("INSERT INTO vndb_reference_day (source,ticker,market,name,date,yyyymmdd,ipo,shli,shou) SELECT 'EXC' as source, ticker, market, name, date, yyyymmdd, ipo, shli, shou FROM vndb_reference_day_hsx");
            $this->db->query("CREATE TABLE vndb_tmp (SELECT * FROM vndb_reference_day ORDER BY ticker,date)");
            $this->db->query("UPDATE vndb_tmp set id = NULL");
            $this->db->query("TRUNCATE TABLE vndb_reference_day");
            $this->db->query("INSERT INTO vndb_reference_day (SELECT * FROM vndb_tmp)");
            $this->db->query("DROP TABLE IF EXISTS vndb_tmp");
            $this->db->query("UPDATE vndb_reference_day a, vndb_reference_day_hnxupc b set a.shou = b.shou where a.ticker = b.ticker and a.date = b.date and a.market = b.market");
            /* * ****** * **
             *    Export   *
             * * * ****** **/
            $data = $this->db->query("select source,ticker,name,market,date,yyyymmdd,ipo,ipo_shli,ipo_shou,ftrd,ftrd_cls,shli,shou,shfn,capi,capi_fora,capi_forn,capi_stat from vndb_reference_day")->result_array();
            $implode = array();
            foreach ($data as $item) {
                $header = array_keys($item);
                $date = $item['yyyymmdd'];
                $item['ticker'] = trim($item['ticker']);
                $item['date'] = str_replace('-', '/', $item['date']);
                $item['ftrd'] = str_replace('-', '/', $item['ftrd']);
                $item['ipo'] = str_replace('-', '/', $item['ipo']);
                $implode[] = implode("\t", $item);
            }
            $header = implode("\t", $header);
            $implode = implode("\r\n", $implode);
            $file = $header . "\r\n";
            $file .= $implode;
            $filename = '\\\LOCAL\IFRCVN\VNDB\METASTOCK\REFERENCE\EXC\ALL\\REF_ALL_' . $date . '.txt';
            $create = fopen($filename, "w");
            $write = fwrite($create, $file);
            fclose($create);
			/* * ****** * **
             *    Report   *
             * * * ****** **/
			$this->_change();
        	$this->_anomalies();
			/* * ****** * **
             *    Switch   *
             * * * ****** **/
            $total = microtime(true) - $from;
            $response[0]['time'] = round($total, 2);
            $response[0]['task'] = 'Reference All';
            echo json_encode($response);
        }
    }

    public function reference_merges() {
        $this->db->query("DELETE FROM vndb_reference_day where market = 'HSX' and source = 'EXC'");
        $this->db->query("INSERT INTO vndb_reference_day (source,ticker,market,name,date,yyyymmdd,ipo,shli,shou) SELECT 'EXC' as source, TRIM(ticker), 'HSX' as market, name, date, yyyymmdd, ipo, shli, shou FROM vndb_reference_day_hsx");
        $this->db->query("CREATE TABLE vndb_tmp (SELECT * FROM vndb_reference_day ORDER BY ticker,date)");
        $this->db->query("UPDATE vndb_tmp set id = NULL");
        $this->db->query("TRUNCATE TABLE vndb_reference_day");
        $this->db->query("INSERT INTO vndb_reference_day (SELECT * FROM vndb_tmp)");
        $this->db->query("DROP TABLE IF EXISTS vndb_tmp");
        $this->db->query("UPDATE vndb_reference_day a, vndb_reference_day_hnxupc b set a.shou = b.shou where a.ticker = b.ticker and a.date = b.date and a.market = b.market");
		$data = $this->db->query("select source,ticker,name,market,date,yyyymmdd,ipo,ipo_shli,ipo_shou,ftrd,ftrd_cls,shli,shou,shfn	capi,capi_fora,capi_forn,capi_stat from vndb_reference_day where market = 'HSX'")->result_array();
        $implode = array();
        foreach ($data as $item) {
            $header = array_keys($item);
            $date = $item['yyyymmdd'];
			$item['ticker'] = trim($item['ticker']);
            $item['date'] = str_replace('-', '/', $item['date']);
            $item['ftrd'] = str_replace('-', '/', $item['ftrd']);
            $item['ipo'] = str_replace('-', '/', $item['ipo']);
            $implode[] = implode("\t", $item);
        }
        $header = implode("\t", $header);
        $implode = implode("\r\n", $implode);
        $file = $header . "\r\n";
        $file .= $implode;
        $filename = '\\\LOCAL\IFRCVN\VNDB\METASTOCK\REFERENCE\EXC\\REF_HSX_' . $date . '.txt';
        $create = fopen($filename, "w");
        $write = fwrite($create, $file);
        fclose($create);
        redirect(admin_url());
    }

    public function reference_switch() {
        $this->template->write_view('content', 'reference/reference_switch', $this->data);
        $this->template->write('title', 'Reference Switch');
        $this->template->render();
    }

    public function process_check_date() {
        if ($this->input->is_ajax_request()) {
            set_time_limit(0);
            $respone['date'] = $this->input->post('date');
            $date = str_replace('-', '', $respone['date']);
			$data_day = $this->db->query("select * from vndb_reference_day where yyyymmdd = '" . $date . "'")->num_rows();
            $data_daily = $this->db->query("select * from vndb_reference_daily where yyyymmdd = '" . $date . "'")->num_rows();
            $data_history = $this->db->query("select * from vndb_reference_history where yyyymmdd = '" . $date . "'")->num_rows();
			if($data_day != 0){
				if ($data_daily > 0 or $data_history > 0) {
					$respone['day'] = 'Have Data';
					$respone['key'] = 'Yes';
					if ($data_daily == 0) {
						$respone['daily'] = 'No Data';
					} else {
						$respone['daily'] = 'Have Data';
					}
					if ($data_history == 0) {
						$respone['history'] = 'No Data';
					} else {
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

    public function process_reference_switch() {
        if ($this->input->is_ajax_request()) {
            $from = microtime(true);
            set_time_limit(0);
            $date = $this->input->post('date');
            $key = $this->input->post('key');
            $date = str_replace('-', '', $date);
            if ($key == 'Yes') {
                $this->db->query("DELETE FROM vndb_reference_daily where yyyymmdd = '" . $date . "'");
                $this->db->query("DELETE FROM vndb_reference_history where yyyymmdd = '" . $date . "'");
                $this->db->query("INSERT INTO vndb_reference_daily (source,ticker,name,market,date,yyyymmdd,ipo,ipo_shli,ipo_shou,ftrd,ftrd_cls,shli,shou,shfn,capi,capi_fora, capi_forn, capi_stat) SELECT source,ticker,name,market,date,yyyymmdd,ipo,ipo_shli,ipo_shou,ftrd,ftrd_cls,shli,shou,shfn,capi,capi_fora, capi_forn, capi_stat FROM vndb_reference_day where yyyymmdd = '" . $date . "'");
                $this->db->query("INSERT INTO vndb_reference_history(SELECT source,ticker,name,market,date,yyyymmdd,ipo,ipo_shli,ipo_shou,ftrd,ftrd_cls,shli,shou,shfn,capi,capi_fora, capi_forn, capi_stat FROM vndb_reference_day where yyyymmdd = '" . $date . "')");
            } else {
                $this->db->query("INSERT INTO vndb_reference_daily (source,ticker,name,market,date,yyyymmdd,ipo,ipo_shli,ipo_shou,ftrd,ftrd_cls,shli,shou,shfn,capi,capi_fora, capi_forn, capi_stat) SELECT source,ticker,name,market,date,yyyymmdd,ipo,ipo_shli,ipo_shou,ftrd,ftrd_cls,shli,shou,shfn,capi,capi_fora, capi_forn, capi_stat FROM vndb_reference_day where yyyymmdd = '" . $date . "'");
                $this->db->query("INSERT INTO vndb_reference_history(SELECT source,ticker,name,market,date,yyyymmdd,ipo,ipo_shli,ipo_shou,ftrd,ftrd_cls,shli,shou,shfn,capi,capi_fora, capi_forn, capi_stat FROM vndb_reference_day where yyyymmdd = '" . $date . "')");
            }
            $total = microtime(true) - $from;
            $response[0]['time'] = round($total, 2);
            $response[0]['task'] = 'Reference Switch';
            echo json_encode($response);
        }
    }

    public function reference_export() {
        $data = $this->db->query("select source,ticker,name,market,date,yyyymmdd,ipo,ipo_shli,ipo_shou,ftrd,ftrd_cls,shli,shou,shfn,capi,capi_fora,capi_forn,capi_stat from vndb_reference_day")->result_array();
        $implode = array();
        foreach ($data as $item) {
            $header = array_keys($item);
            $date = $item['yyyymmdd'];
            $item['ticker'] = trim($item['ticker']);
            $item['date'] = str_replace('-', '/', $item['date']);
            $item['ftrd'] = str_replace('-', '/', $item['ftrd']);
            $item['ipo'] = str_replace('-', '/', $item['ipo']);
            $implode[] = implode("\t", $item);
        }
        $header = implode("\t", $header);
        $implode = implode("\r\n", $implode);
        $file = $header . "\r\n";
        $file .= $implode;
        $filename = '\\\LOCAL\IFRCVN\VNDB\METASTOCK\REFERENCE\EXC\ALL\\REF_ALL_' . $date . '.txt';
        $create = fopen($filename, "w");
        $write = fwrite($create, $file);
        fclose($create);
        redirect(admin_url() . 'daily_action');
    }

    public function reference_anomalies() {
        if ($this->input->is_ajax_request()) {
           $response = json_encode($this->_anomalies());
           echo json_encode($response);
        }
    }

	public function update_calendar() {
        $this->template->write_view('content', 'reference/update_calendar', $this->data);
        $this->template->write('title', 'Update Calendar');
        $this->template->render();
    }

	public function process_update_calendar(){
		 if ($this->input->is_ajax_request()) {
			$from = microtime(true);
            set_time_limit(0);
			$date = $this->input->post('date');
			$yyyymmdd = str_replace('-','',$date);

			$num_b1 = $this->db->query("select * from vndb_calendar")->num_rows();
            if ($num_b1 != 0) {
                $this->db->query("INSERT INTO vndb_calendar (date, dtyyyymmdd, vni, hnx, upc)(Select a.date, a.dtyyyymmdd, a.vni, a.hnx, a.upc from vndb_indexes_history AS a left join vndb_calendar AS b on a.date = b.date where b.date is null GROUP BY a.date);");
            } else {
                $this->db->query("INSERT INTO vndb_calendar (date, dtyyyymmdd, vni, hnx, upc)(Select a.date, a.dtyyyymmdd, a.vni, a.hnx, a.upc from vndb_indexes_history AS a GROUP BY a.date);");
            }

			$data = $this->db->query('SELECT * FROM VNDB_CALENDAR WHERE date = \''.$date.'\'')->num_rows();
			if($data == 0){
				$this->db->query('INSERT INTO VNDB_CALENDAR (date, dtyyyymmdd) values (\''.$date.'\',\''.$yyyymmdd.'\')');
				$response['message'] = 'No Data & Insert';

			}else{
				$response['message'] = 'Have Data';
			}
			$response['task'] = 'Message';
            $total = microtime(true) - $from;
            $response['time'] = round($total, 2);
            $response['title'] = 'Update Calendar';
            echo json_encode($response);
        }
	}

    function report_all(){
        // $this->_change();
        // $this->_anomalies();
        $now = date('Y-m-d');
        $this->db->query("UPDATE vndb_changes_day a, vndb_reference_history b SET a.pticker=b.ticker WHERE a.pdate=b.date AND a.ticker=b.ticker");
        $this->db->query("UPDATE vndb_changes_day SET changeevt='shou' WHERE shou>0");
        $this->db->query("UPDATE vndb_changes_day SET changeevt='shli' WHERE shli>0");
        $changes = $this->db->query("SELECT * FROM vndb_changes_day WHERE date = '$now'")->result_array();
        $anomalies = $this->db->query("SELECT * FROM vndb_anomalies_daily WHERE date = '$now'")->result_array();


        $this->data->changes = $changes;
        $this->data->anomalies = $anomalies;
        $this->data->title = "Report";
        $this->template->write_view('content', 'ref_report/index', $this->data);
        $this->template->write('title', 'Report');
        $this->template->render();
    }

    function report_part_1(){
        set_time_limit(0);
        $this->_change();
    }

    function report_part_2(){
        set_time_limit(0);
        $this->_anomalies();
    }

    function report_part_3(){
        set_time_limit(0);
        $now = date('Y-m-d');
        $this->db->query("UPDATE vndb_changes_day a, vndb_reference_history b SET a.pticker=b.ticker WHERE a.pdate=b.date AND a.ticker=b.ticker");
        $this->db->query("UPDATE vndb_changes_day SET changeevt='shou' WHERE shou>0");
        $this->db->query("UPDATE vndb_changes_day SET changeevt='shli' WHERE shli>0");
        $changes = $this->db->query("SELECT * FROM vndb_changes_day WHERE date = '$now'")->result_array();
        $anomalies = $this->db->query("SELECT * FROM vndb_anomalies_daily WHERE date = '$now'")->result_array();


        $this->data->changes = $changes;
        $this->data->anomalies = $anomalies;
        $this->data->title = "Report";
        $this->template->write_view('content', 'ref_report/index', $this->data);
        $this->template->write('title', 'Report');
        $this->template->render();
    }

    protected function _change() {
        $sql = "DROP TABLE IF EXISTS `vndb_reference_daily_tmp`;
            CREATE TABLE `vndb_reference_daily_tmp` (SELECT * FROM vndb_reference_day );
            TRUNCATE `vndb_reference_daily_tmp`;
            ALTER TABLE vndb_reference_daily_tmp ADD column pticker char(12) COLLATE utf8_general_ci;
            CREATE INDEX date ON vndb_reference_daily_tmp (date) USING BTREE;
            CREATE INDEX pdate ON vndb_reference_daily_tmp (pdate) USING BTREE;";

        $sql = explode(';', $sql);
        array_pop($sql);
        foreach ($sql as $item) {
            $this->db->query($item);
        }

        $path = '\\\LOCAL\IFRCVN\VNDB\METASTOCK\REFERENCE\EXC\ALL\\';
        $files = glob($path . 'REF_ALL_*.txt');
        $data_load = '';
        $i = 1;
        foreach ($files as $key => $item) {
            $filename = basename($item);
            load_data_mysql($path,$filename,'vndb_reference_daily_tmp');
        }

        $sql = "DROP TABLE IF EXISTS vndb_reference_tmp_calendar;
            CREATE TABLE vndb_reference_tmp_calendar (SELECT date, date AS pdate FROM vndb_reference_daily_tmp GROUP BY date ORDER BY date ASC);
            ALTER TABLE vndb_reference_tmp_calendar ADD column `id` int(10) unsigned primary KEY AUTO_INCREMENT;

            CREATE INDEX date ON vndb_reference_tmp_calendar (date) USING BTREE;
            CREATE INDEX pdate ON vndb_reference_tmp_calendar (pdate) USING BTREE;

            UPDATE vndb_reference_tmp_calendar SET pdate = NULL;

            UPDATE `vndb_reference_tmp_calendar`, `vndb_reference_tmp_calendar` AS `temp` SET `vndb_reference_tmp_calendar`.`pdate` = `temp`.`date` WHERE `vndb_reference_tmp_calendar`.`id` = `temp`.`id` + 1;

            UPDATE vndb_reference_daily_tmp a, vndb_reference_tmp_calendar b SET a.pdate = b.pdate WHERE a.date = b.date;

            UPDATE vndb_reference_daily_tmp a, vndb_reference_daily_tmp b SET a.pticker = b.pticker, a.pshli = b.shli, a.pshou = b.shou, a.pmarket = b.market WHERE a.ticker = b.ticker and a.pdate = b.date;

            DROP TABLE IF EXISTS vndb_changes_day_tmp;
            CREATE TABLE vndb_changes_day_tmp (select date,pdate,ticker,pticker,market,pmarket,shli,pshli,shou,pshou from vndb_reference_daily_tmp where ticker <> pticker or shli <> pshli or market <> pmarket or shou <> pshou or (shli = 0 and pshli = 0) or (shou = 0 and pshou = 0));
            TRUNCATE TABLE vndb_changes_day_tmp;

            INSERT INTO vndb_changes_day_tmp (date,pdate,ticker,market,pmarket)(SELECT date,pdate,ticker,market,pmarket from vndb_reference_daily_tmp where market <> pmarket and LENGTH(ticker) = 3);
            INSERT INTO vndb_changes_day_tmp (date,pdate,ticker,market,shli,pshli)(SELECT date,pdate,ticker,market,shli,pshli from vndb_reference_daily_tmp where shli <> pshli or (shli = 0 and pshli = 0) or (shli = 0) and LENGTH(ticker) = 3);
            INSERT INTO vndb_changes_day_tmp (date,pdate,ticker,market,shou,pshou)(SELECT date,pdate,ticker,market,shou,pshou from vndb_reference_daily_tmp where shou <> pshou or (shou = 0 and pshou = 0) or (shou = 0) and LENGTH(ticker) = 3);
            INSERT INTO vndb_changes_day_tmp (date,pdate,ticker,market,pticker)(SELECT date,pdate,ticker,market,pticker from vndb_reference_daily_tmp where ticker <> pticker and LENGTH(ticker) <= 3);

            DROP TABLE IF EXISTS vndb_changes_day;
            CREATE TABLE vndb_changes_day (SELECT * FROM vndb_changes_day_tmp WHERE LENGTH(ticker) <= 3 ORDER BY date DESC, ticker ASC);
            ALTER TABLE vndb_changes_day ADD COLUMN `id` INT(10) UNSIGNED PRIMARY KEY AUTO_INCREMENT;
            ALTER TABLE vndb_changes_day ADD COLUMN `changeevt` char(10) ;

            DROP TABLE IF EXISTS vndb_changes_day_tmp;";
        $sql = explode(';', $sql);
        array_pop($sql);
        foreach ($sql as $item) {
            $this->db->query($item);
        }
        return TRUE;
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

    public function change() {
        if ($this->input->is_ajax_request()) {
            $now = time();
            if($this->_change()){
                $total = time() - $now;
                $response[0]['time'] = $total;
                $response[0]['task'] = 'Total';
                $response = json_encode($response);
                $this->output->set_output($response);
            }
        } else {
            $this->data->title = "Reference Change";
            $this->template->write_view('content', 'reference/reference_change', $this->data);
            $this->template->write('title', 'Reference Change');
            $this->template->render();
        }
    }

    public function get_shares_hnx() {
        $now = time();
        $table = 'vndb_reference_day_hnxupc';
        $market = 'HNX';
        $this->db->where('date', date('Y-m-d', $now));
        $this->db->where('market', $market);
        $this->db->delete($table);
        $source = 'EXC';
        $this->load->library('curl');
        $this->load->Model('exchange_model', 'mexchange');
        $tickers = $this->mexchange->getTicker2($market);
        $curl = new curl;
        $url = 'http://hnx.vn/web/guest/tong-quan/';
        $f = fopen('\\\LOCAL\IFRCVN\VNDB\METASTOCK\REFERENCE\EXC\SHOU\SHOU_HNX_' . date('Ymd', $now) . '.txt', 'w');
        $data[0] = array('sources', 'ticker', 'name', 'market', 'date', 'yyyymmdd', 'ipo', 'ipo_shli', 'ipo_shou', 'ftrd', 'ftrd_cls', 'shli', 'shou', 'shfn', 'capi', 'capi_fora', 'capi_forn', 'capi_stat');
        $content = implode(chr(9), end($data)) . PHP_EOL;
        fwrite($f, $content);
        $html = '';
        foreach ($tickers as $ticker) {
            $ticker = $ticker['ticker'];
            $post = array('maCk=' . $ticker, '_hstongquan_WAR_HnxIndexportlet_anchor=optionSelected', 'p_p_col_count=1', 'p_p_col_id=column-9', 'p_p_id=hstongquan_WAR_HnxIndexportlet', 'p_p_lifecycle=1', 'p_p_mode=view', 'p_p_state=exclusive');
            $start = '<a style="cursor: pointer;" onclick="loadPage';
            $end = '</a>';
            $start = preg_quote($start, '/');
            $end = preg_quote($end, '/');
            $rule = "/$start.*>(.*)$end/msU";
            while(1){
                $html = $curl->makeRequest('post', $url, $post, 3);
                preg_match_all($rule, $html, $result);
                if(!empty($result[1])){
                    if(isset($result[1][3]) || isset($result[1][4])){
                        break;
                    }
                }
            }

            if(!empty($result[1])){
                $shli = '';
                $shou = '';
                if(isset($result[1][3])){
                    $shou = trim(str_replace('.', '', $result[1][3]));
                    $shou = trim(str_replace(',', '', $shou)) * 1;
                }
                if(isset($result[1][4])){
                    $shli = trim(str_replace('.', '', $result[1][4]));
                    $shli = trim(str_replace(',', '', $shli)) * 1;
                }

                $data[] = array(
                    'sources' => $source,
                    'ticker' => $ticker,
                    'name' => '',
                    'market' => $market,
                    'date' => date('Y/m/d', $now),
                    'yyyymmdd' => str_replace('/', '', date('Y/m/d', $now)),
                    'ipo' => '',
                    'ipo_shli' => '',
                    'ipo_shou' => '',
                    'ftrd' => '',
                    'ftrd_cls' => '',
                    'shli' => $shli,
                    'shou' => $shou,
                    'shfn' => '',
                    'capi' => '',
                    'capi_fora' => '',
                    'capi_forn' => '',
                    'capi_stat' => ''
                );
                $content = implode(chr(9), end($data)) . PHP_EOL;
                fwrite($f, $content);
            }
        }
        array_shift($data);
        $this->db->insert_batch($table, $data);
        fclose($f);
    }

    public function get_shares_upc() {
        $now = time();
        $market = 'UPC';
        $table = 'vndb_reference_day_hnxupc';
        $this->db->where('date', date('Y-m-d', $now));
        $this->db->where('market', $market);
        $this->db->delete($table);
        $source = 'EXC';
        $this->load->library('curl');
        $this->load->Model('exchange_model', 'mexchange');
        $tickers = $this->mexchange->getTicker2($market);
        $curl = new curl;
        $url = 'http://hnx.vn/web/guest/tong-quan1/';
        $f = fopen('\\\LOCAL\IFRCVN\VNDB\METASTOCK\REFERENCE\EXC\SHOU\SHOU_UPC_' . date('Ymd', $now) . '.txt', 'w');
        $data[0] = array('sources', 'ticker', 'name', 'market', 'date', 'yyyymmdd', 'ipo', 'ipo_shli', 'ipo_shou', 'ftrd', 'ftrd_cls', 'shli', 'shou', 'shfn', 'capi', 'capi_fora', 'capi_forn', 'capi_stat');
        $content = implode(chr(9), end($data)) . PHP_EOL;
        fwrite($f, $content);
        foreach ($tickers as $ticker) {
            $ticker = $ticker['ticker'];

            //$ticker = 'ABI';

            $post = array('maCk=' . $ticker, '_hstongquan_WAR_HnxIndexportlet_anchor=optionSelected', 'p_p_col_count=1', 'p_p_col_id=column-9', 'p_p_id=hstongquan_WAR_HnxIndexportlet', 'p_p_lifecycle=1', 'p_p_mode=view', 'p_p_state=exclusive');
            $start = '<a style="cursor: pointer;" onclick="loadPage';
            $end = '</a>';
            $start = preg_quote($start, '/');
            $end = preg_quote($end, '/');
            $rule = "/$start.*>(.*)$end/msU";
            $html = '';
            while(1){
                $html = $curl->makeRequest('post', $url, $post, 3);
                preg_match_all($rule, $html, $result);
                if(!empty($result[1])){
                    if(isset($result[1][3]) || isset($result[1][4])){
                        break;
                    }
                }
            }

            if(!empty($result[1])){
                $shli = '';
                $shou = '';
                if(isset($result[1][3])){
                    $shou = trim(str_replace('.', '', $result[1][3]));
                    $shou = trim(str_replace(',', '', $shou)) * 1;
                }
                if(isset($result[1][4])){
                    $shli = trim(str_replace('.', '', $result[1][4]));
                    $shli = trim(str_replace(',', '', $shli)) * 1;
                }

                $data[] = array(
                    'sources' => $source,
                    'ticker' => $ticker,
                    'name' => '',
                    'market' => $market,
                    'date' => date('Y/m/d', $now),
                    'yyyymmdd' => str_replace('/', '', date('Y/m/d', $now)),
                    'ipo' => '',
                    'ipo_shli' => '',
                    'ipo_shou' => '',
                    'ftrd' => '',
                    'ftrd_cls' => '',
                    'shli' => $shli,
                    'shou' => $shou,
                    'shfn' => '',
                    'capi' => '',
                    'capi_fora' => '',
                    'capi_forn' => '',
                    'capi_stat' => ''
                );
                $content = implode(chr(9), end($data)) . PHP_EOL;
                fwrite($f, $content);
            } else {
                $fails[] = $ticker;
            }
        }
        array_shift($data);
        $this->db->insert_batch($table, $data);
        fclose($f);
    }

    function test() {
        $file1 = array(
            'W:\DOWNLOADS\VNDB\METASTOCK\PRICES\EXC\ALL\EXC_ALL_20130102.txt',
            'W:\DOWNLOADS\VNDB\METASTOCK\PRICES\EXC\ALL\EXC_ALL_20130103.txt',
            'W:\DOWNLOADS\VNDB\METASTOCK\PRICES\EXC\ALL\EXC_ALL_20130104.txt',
            'W:\DOWNLOADS\VNDB\METASTOCK\PRICES\EXC\ALL\EXC_ALL_20130107.txt',
            'W:\DOWNLOADS\VNDB\METASTOCK\PRICES\EXC\ALL\EXC_ALL_20130108.txt',
            'W:\DOWNLOADS\VNDB\METASTOCK\PRICES\EXC\ALL\EXC_ALL_20130109.txt',
            'W:\DOWNLOADS\VNDB\METASTOCK\PRICES\EXC\ALL\EXC_ALL_20130110.txt',
            'W:\DOWNLOADS\VNDB\METASTOCK\PRICES\EXC\ALL\EXC_ALL_20130111.txt',
            'W:\DOWNLOADS\VNDB\METASTOCK\PRICES\EXC\ALL\EXC_ALL_20130114.txt',
            'W:\DOWNLOADS\VNDB\METASTOCK\PRICES\EXC\ALL\EXC_ALL_20130115.txt',
            'W:\DOWNLOADS\VNDB\METASTOCK\PRICES\EXC\ALL\EXC_ALL_20130116.txt',
            'W:\DOWNLOADS\VNDB\METASTOCK\PRICES\EXC\ALL\EXC_ALL_20130117.txt',
            'W:\DOWNLOADS\VNDB\METASTOCK\PRICES\EXC\ALL\EXC_ALL_20130118.txt',
            'W:\DOWNLOADS\VNDB\METASTOCK\PRICES\EXC\ALL\EXC_ALL_20130121.txt',
            'W:\DOWNLOADS\VNDB\METASTOCK\PRICES\EXC\ALL\EXC_ALL_20130122.txt',
            'W:\DOWNLOADS\VNDB\METASTOCK\DAILY\ALL\DAILY_ALL_20130102.txt',
            'W:\DOWNLOADS\VNDB\METASTOCK\DAILY\ALL\DAILY_ALL_20130103.txt',
            'W:\DOWNLOADS\VNDB\METASTOCK\DAILY\ALL\DAILY_ALL_20130104.txt',
            'W:\DOWNLOADS\VNDB\METASTOCK\DAILY\ALL\DAILY_ALL_20130107.txt',
            'W:\DOWNLOADS\VNDB\METASTOCK\DAILY\ALL\DAILY_ALL_20130108.txt',
            'W:\DOWNLOADS\VNDB\METASTOCK\DAILY\ALL\DAILY_ALL_20130109.txt',
            'W:\DOWNLOADS\VNDB\METASTOCK\DAILY\ALL\DAILY_ALL_20130110.txt',
            'W:\DOWNLOADS\VNDB\METASTOCK\DAILY\ALL\DAILY_ALL_20130111.txt',
            'W:\DOWNLOADS\VNDB\METASTOCK\DAILY\ALL\DAILY_ALL_20130114.txt',
            'W:\DOWNLOADS\VNDB\METASTOCK\DAILY\ALL\DAILY_ALL_20130115.txt',
            'W:\DOWNLOADS\VNDB\METASTOCK\DAILY\ALL\DAILY_ALL_20130116.txt',
            'W:\DOWNLOADS\VNDB\METASTOCK\DAILY\ALL\DAILY_ALL_20130117.txt',
            'W:\DOWNLOADS\VNDB\METASTOCK\DAILY\ALL\DAILY_ALL_20130118.txt',
            'W:\DOWNLOADS\VNDB\METASTOCK\DAILY\ALL\DAILY_ALL_20130121.txt',
            'W:\DOWNLOADS\VNDB\METASTOCK\DAILY\ALL\DAILY_ALL_20130122.txt',


            );
        $file2 = array(
            'W:\DOWNLOADS\VNDB\TESTS\EXC_ALL_20130117.txt',
            'W:\DOWNLOADS\VNDB\TESTS\EXC_ALL_20130118.txt',
            'W:\DOWNLOADS\VNDB\TESTS\EXC_ALL_20130121.txt',
            'W:\DOWNLOADS\VNDB\TESTS\EXC_ALL_20130122.txt',
            'W:\DOWNLOADS\VNDB\TESTS\DAILY_ALL_20130102.txt',
            'W:\DOWNLOADS\VNDB\TESTS\DAILY_ALL_20130103.txt',
            'W:\DOWNLOADS\VNDB\TESTS\DAILY_ALL_20130104.txt',
            'W:\DOWNLOADS\VNDB\TESTS\DAILY_ALL_20130107.txt',
            'W:\DOWNLOADS\VNDB\TESTS\DAILY_ALL_20130108.txt',
            'W:\DOWNLOADS\VNDB\TESTS\DAILY_ALL_20130109.txt',
            'W:\DOWNLOADS\VNDB\TESTS\DAILY_ALL_20130110.txt',
            'W:\DOWNLOADS\VNDB\TESTS\DAILY_ALL_20130111.txt',
            'W:\DOWNLOADS\VNDB\TESTS\DAILY_ALL_20130114.txt',
            'W:\DOWNLOADS\VNDB\TESTS\DAILY_ALL_20130115.txt',
            'W:\DOWNLOADS\VNDB\TESTS\DAILY_ALL_20130116.txt',
            'W:\DOWNLOADS\VNDB\TESTS\DAILY_ALL_20130117.txt',
            'W:\DOWNLOADS\VNDB\TESTS\DAILY_ALL_20130118.txt',
            'W:\DOWNLOADS\VNDB\TESTS\DAILY_ALL_20130121.txt',
            'W:\DOWNLOADS\VNDB\TESTS\DAILY_ALL_20130122.txt',



            );
        foreach($file1 as $k => $f){
             $data = compare_files('prices',$f, $file2[$k], array('capi', 'name', 'shfn', 'capi_fora', 'capi_forn', 'capi_stat', 'id', 'pdate', 'pshli', 'pshou', 'pmarket'));
            //  if(is_array($data) && !empty($data)){
            //     echo $f;
            //     echo '<br />';
            //     echo $file2[$k];

            //     pre($data);echo '<br />';break;
            // }
             if(is_array($data) && !empty($data)){
             // if($data == -1 || !empty($data)){
                echo $f . ' AND ' . $file2[$k];
                pre($data);
                echo '<br />';
             }
        }
    //pre(compare_files('reference', 'W:\DOWNLOADS\VNDB\METASTOCK\PRICES\EXC\ALL\EXC_ALL_20130108.txt', 'W:\DOWNLOADS\VNDB\TESTS\EXC_ALL_20130108.txt'));

    }

}