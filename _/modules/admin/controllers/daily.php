<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/* * ******************************************************************************************************************* *
 * 	 Author: Minh Đẹp Trai			 																					 *
 * * ******************************************************************************************************************* */

class Daily extends Admin {

    public function __construct() {
        parent::__construct();
    }
	public function daily_all() {
        $this->template->write_view('content', 'daily/daily_all', $this->data);
        $this->template->write('title', 'Daily All');
        $this->template->render();
    }
	public function process_daily_all(){
		if ($this->input->is_ajax_request()) {
			set_time_limit(0);
			$tb_hsx = 'VNDB_REFERENCE_TMP_HSX';
			$tb_hnxupc = 'VNDB_REFERENCE_TMP_HNXUPC';
			$tb_shou = 'VNDB_REFERENCE_SHOU_HNXUPC';
			$tb_ref = 'TEST_REFERENCE_DAILY';
			$tb_pri = 'TEST_PRICES_DAILY';
			$tb_daily = 'TEST_DAILY';
			$extension = '*.txt';
			$option = '\'\t\'';
			//$dir_ref = '\\\LOCAL\IFRCVN\VNDB\METASTOCK\REFERENCE\EXC\\';
			$dir_ref = '\\\LOCAL\IFRCVN\VNDB\TESTS\SOURCE\REF\\';
			$dir_ref_all = '\\\LOCAL\IFRCVN\VNDB\METASTOCK\REFERENCE\EXC\ALL\\';
			//$dir_shou = '\\\LOCAL\IFRCVN\VNDB\METASTOCK\REFERENCE\EXC\SHOU\\';
			$dir_shou = '\\\LOCAL\IFRCVN\VNDB\TESTS\SOURCE\SHOU\\';
			$column_ref = 'source,ticker,name,market,date,yyyymmdd,ipo,ipo_shli,ipo_shou,ftrd,ftrd_cls,shli,shou,shfn,capi,capi_fora,capi_forn,capi_stat';
			//$dir_pri = '\\\LOCAL\IFRCVN\VNDB\METASTOCK\PRICES\EXC\\';
			$dir_pri = '\\\LOCAL\IFRCVN\VNDB\TESTS\SOURCE\PRI\\';
			$dir_pri_all = '\\\LOCAL\IFRCVN\VNDB\METASTOCK\PRICES\EXC\ALL\\';
			$column_pri = 'source,ticker,market,date,yyyymmdd,shli,shou,shfn,pref,pcei,pflr,popn,phgh,plow,pbase,pavg,pcls,vlm,trn,adj_pcls,adj_coeff';
			$column_pri2 = 'source,ticker,market,date,yyyymmdd,shli,shou,shfn,pref,pcei,pflr,popn,phgh,plow,pbase,pavg,pcls,vlm,trn,last';
			$start_date = $this->input->post('startdate');
			$end_date = $this->input->post('enddate');
			$start_date = str_replace('-','',$start_date);
			$end_date = str_replace('-','',$end_date);
			$process = $this->input->post('process');
			$check = $this->input->post('check');
			if($start_date == '20130102' && $end_date == date('Ymd',time())){
				$this->db->query("TRUNCATE TABLE $tb_ref");
				$this->db->query("TRUNCATE TABLE $tb_pri");
				$this->db->query("TRUNCATE TABLE $tb_hsx");
				$this->db->query("TRUNCATE TABLE $tb_hnxupc");
				$this->db->query("TRUNCATE TABLE $tb_shou");
				$this->db->query("TRUNCATE TABLE $tb_daily");
			}else{
				$this->db->query("DELETE FROM $tb_ref WHERE yyyymmdd BETWEEN $start_date and $end_date");
				$this->db->query("DELETE FROM $tb_pri WHERE yyyymmdd BETWEEN $start_date and $end_date");
				$this->db->query("DELETE FROM $tb_hsx WHERE yyyymmdd BETWEEN $start_date and $end_date");
				$this->db->query("DELETE FROM $tb_hnxupc WHERE yyyymmdd BETWEEN $start_date and $end_date");
				$this->db->query("DELETE FROM $tb_shou WHERE yyyymmdd BETWEEN $start_date and $end_date");
				$this->db->query("DELETE FROM $tb_daily WHERE yyyymmdd BETWEEN $start_date and $end_date");
			}
			if($process == 1){ // FROM EXCHANGE FILES
				/* * *********** * *
				 *    REFERENCE    *
				 * * *********** * */
				$files_ref = glob($dir_ref . $extension);
				foreach ($files_ref as $base_ref) {
					$filename_ref = pathinfo($base_ref, PATHINFO_FILENAME);
					$arr_ref = explode('_', $filename_ref);
					$base_url_ref = str_replace("\\", "\\\\", $base_ref);
					if($arr_ref[2] >= $start_date && $arr_ref[2] <= $end_date){
						if($arr_ref[1] == 'HSX'){
							$this->db->query("LOAD DATA INFILE '" . $base_url_ref . "' INTO TABLE $tb_hsx FIELDS TERMINATED BY $option IGNORE 1 LINES ($column_ref)");
						}else{
							$this->db->query("LOAD DATA INFILE '" . $base_url_ref . "' INTO TABLE $tb_hnxupc FIELDS TERMINATED BY $option IGNORE 1 LINES ($column_ref)");
						}
					}
				}
				/* * ****** * *
				 *    SHOU    *
				 * * ****** * */
				$files_shou = glob($dir_shou . $extension);
				foreach ($files_shou as $base_shou) {
					$filename_shou = pathinfo($base_shou, PATHINFO_FILENAME);
					$arr_shou = explode('_', $filename_shou);
					$base_url_shou = str_replace("\\", "\\\\", $base_shou);
					if($arr_shou[2] >= $start_date && $arr_shou[2] <= $end_date){
						$this->db->query("LOAD DATA INFILE '" . $base_url_shou . "' INTO TABLE $tb_shou FIELDS TERMINATED BY $option IGNORE 1 LINES ($column_ref)");
					}
				}
				//$conditions = array('market' => "== 'HSX'", 'start_date' => ''.$start_date.'', 'end_date' => ''.$end_date.'');
				//load_data($dir_ref, $extension, $tb_hsx, $option, $column_ref, $conditions);
				//$conditions = array('market' => "!= 'HSX'", 'start_date' => ''.$start_date.'', 'end_date' => ''.$end_date.'');
				//load_data($dir_ref, $extension, $tb_hnxupc, $option, $column_ref, $conditions);
				/* * ****************** * *
				 *    MERGES REFERENCE    *
				 * * ****************** * */
				$this->db->query("INSERT INTO $tb_ref ($column_ref) (SELECT $column_ref FROM $tb_hsx WHERE yyyymmdd BETWEEN $start_date and $end_date)");
				$this->db->query("INSERT INTO $tb_ref ($column_ref) (SELECT $column_ref FROM $tb_hnxupc WHERE yyyymmdd BETWEEN $start_date and $end_date)");
				$this->db->query("UPDATE $tb_ref a, $tb_shou b set a.shou = b.shou WHERE a.ticker = b.ticker AND a.market = b.market AND a.date = b.date AND a.yyyymmdd BETWEEN $start_date AND $end_date");
				/* * ******** * *
				 *    PRICES    *
				 * * ******** * */
				$files_pri = glob($dir_pri . $extension);
				foreach ($files_pri as $base_pri) {
					$filename_pri = pathinfo($base_pri, PATHINFO_FILENAME);
					$arr_pri = explode('_', $filename_pri);
					if($arr_pri[2] >= $start_date && $arr_pri[2] <= $end_date){
						$base_url_pri = str_replace("\\", "\\\\", $base_pri);
						$this->db->query("LOAD DATA INFILE '" . $base_url_pri . "' INTO TABLE $tb_pri FIELDS TERMINATED BY $option IGNORE 1 LINES ($column_pri2)");
					}
				}
				//load_data($dir_pri, $extension, $tb_pri, $option, $column_pri, $conditions);
				/* * ************ * *
				 *    EXPORT ALL    *
				 * * ************ * */
				if($check == 1){
					/* * **************** * *
					 *    EXPORT REF ALL    *
					 * * **************** * */
					$data_ref = $this->db->query("SELECT DISTINCT yyyymmdd FROM $tb_ref WHERE yyyymmdd BETWEEN $start_date and $end_date")->result_array();
					foreach($data_ref as $item_ref){
						$data = $this->db->query("SELECT $column_ref FROM $tb_ref WHERE yyyymmdd = '".$item_ref['yyyymmdd']."' order by ticker")->result_array();
						$implode = array();
						foreach($data as $item_ref2){
							$header = array_keys($item_ref2);
							$date = $item_ref2['yyyymmdd'];
							$item_ref2['date'] = str_replace('-','/',$item_ref2['date']);
							$item_ref2['ipo'] = str_replace('-','/',$item_ref2['ipo']);
							$item_ref2['ftrd'] = str_replace('-','/',$item_ref2['ftrd']);
							$implode[] = implode("\t",$item_ref2);
						}
						$header = implode("\t",$header);
						$implode = implode("\r\n",$implode);
						$file = $header."\r\n";
						$file .= $implode;
						//$filename = '\\\LOCAL\IFRCVN\VNDB\METASTOCK\REFERENCE\ALL\REF_ALL_'.$item['yyyymmdd'].'.txt';
						$filename = '\\\LOCAL\IFRCVN\VNDB\TESTS\REF_ALL_'.$date.'.txt';
						$create = fopen($filename, "w");
						$write = fwrite($create, $file);
						fclose($create);
					}
					/* * **************** * *
					 *    EXPORT PRI ALL    *
					 * * **************** * */
					$data_pri = $this->db->query("SELECT DISTINCT yyyymmdd FROM $tb_pri WHERE yyyymmdd BETWEEN $start_date and $end_date")->result_array();
					foreach($data_pri as $item_pri){
						$data = $this->db->query("SELECT $column_pri2 FROM $tb_pri WHERE yyyymmdd = '".$item_pri['yyyymmdd']."' order by ticker")->result_array();
						$implode = array();
						foreach($data as $item_pri2){
							$header = array_keys($item_pri2);
							$date = $item_pri2['yyyymmdd'];
							$item_pri2['date'] = str_replace('-','/',$item_pri2['date']);
							$implode[] = implode("\t",$item_pri2);
						}
						$header = implode("\t",$header);
						$implode = implode("\r\n",$implode);
						$file = $header."\r\n";
						$file .= $implode;
						//$filename = '\\\LOCAL\IFRCVN\VNDB\METASTOCK\REFERENCE\ALL\REF_ALL_'.$item['yyyymmdd'].'.txt';
						$filename = '\\\LOCAL\IFRCVN\VNDB\TESTS\PRI_ALL_'.$date.'.txt';
						$create = fopen($filename, "w");
						$write = fwrite($create, $file);
						fclose($create);
					}	 
				}else{
					/* * **************** * *
					 *    EXPORT REF ALL    *
					 * * **************** * */
					//$dir = '\\\LOCAL\IFRCVN\VNDB\METASTOCK\DAILY\ALL\\';
					$dir = '\\\LOCAL\IFRCVN\VNDB\TESTS\\';
					$data_ref = $this->db->query("SELECT DISTINCT yyyymmdd FROM $tb_ref WHERE yyyymmdd BETWEEN $start_date and $end_date")->result_array();
					foreach($data_ref as $item_ref){
						$filename = $dir.'REF_ALL_'.$item_ref['yyyymmdd'].'.txt';
						$files = glob($dir. '*.txt');
						if (!in_array($filename, $files)) {
							$data = $this->db->query("SELECT $column_ref FROM $tb_ref WHERE yyyymmdd = '".$item_ref['yyyymmdd']."' order by ticker")->result_array();	
							$implode = array();
							foreach($data as $item_ref2){
								$header = array_keys($item_ref2);
								$item_ref2['date'] = str_replace('-','/',$item_ref2['date']);
								$item_ref2['ipo'] = str_replace('-','/',$item_ref2['ipo']);
								$item_ref2['ftrd'] = str_replace('-','/',$item_ref2['ftrd']);
								$implode[] = implode("\t",$item_ref2);
							}
							$header = implode("\t",$header);
							$implode = implode("\r\n",$implode);
							$file = $header."\r\n";
							$file .= $implode;
							$create = fopen($filename, "w");
							$write = fwrite($create, $file);
							fclose($create);
						}
					}
					/* * **************** * *
					 *    EXPORT PRI ALL    *
					 * * **************** * */			
					$data_pri = $this->db->query("SELECT DISTINCT yyyymmdd FROM $tb_pri WHERE yyyymmdd BETWEEN $start_date and $end_date")->result_array();
					foreach($data_pri as $item_pri){
						$filename = $dir.'EXC_ALL_'.$item_pri['yyyymmdd'].'.txt';
						$files = glob($dir. '*.txt');
						if (!in_array($filename, $files)) {
							$data = $this->db->query("SELECT $column_pri2 FROM $tb_pri WHERE yyyymmdd = '".$item_pri['yyyymmdd']."' order by ticker")->result_array();	
							$implode = array();
							foreach($data as $item_pri2){
								$header = array_keys($item_pri2);
								$item_pri2['date'] = str_replace('-','/',$item_pri2['date']);
								$implode[] = implode("\t",$item_pri2);
							}
							$header = implode("\t",$header);
							$implode = implode("\r\n",$implode);
							$file = $header."\r\n";
							$file .= $implode;
							$create = fopen($filename, "w");
							$write = fwrite($create, $file);
							fclose($create);
						}
					}
				}
			}else{ // FROM FINALS REF, UPC
				$files_ref = glob($dir_ref_all . $extension);
				foreach ($files_ref as $base_ref) {
					$filename_ref = pathinfo($base_ref, PATHINFO_FILENAME);
					$arr_ref = explode('_', $filename_ref);
					if($arr_ref[2] >= $start_date && $arr_ref[2] <= $end_date){
						$base_url_ref = str_replace("\\", "\\\\", $base_ref);
						$this->db->query("LOAD DATA INFILE '" . $base_url_ref . "' INTO TABLE $tb_ref FIELDS TERMINATED BY $option IGNORE 1 LINES ($column_ref)");
					}
				}
				$files_pri = glob($dir_pri_all . $extension);
				foreach ($files_pri as $base_pri) {
					$filename_pri = pathinfo($base_pri, PATHINFO_FILENAME);
					$arr_pri = explode('_', $filename_pri);
					if($arr_pri[2] >= $start_date && $arr_pri[2] <= $end_date){
						$base_url_pri = str_replace("\\", "\\\\", $base_pri);
						$this->db->query("LOAD DATA INFILE '" . $base_url_pri . "' INTO TABLE $tb_pri FIELDS TERMINATED BY $option IGNORE 1 LINES ($column_pri2)");
					}
				}
				//$conditions = "'start_date' => $start_date , 'end_date' => $end_date";
				//load_data($dir_ref, $extension, $tb_ref, $option, $column_ref, $conditions);
				//load_data($dir_pri, $extension, $tb_pri, $option, $column_pri, $conditions);
			}
			/* * ****************** * *
			 *    ORDERS REFERENCE    *
			 * * ****************** * */
			$this->db->query("DROP TABLE IF EXISTS vndb_tmp");
			$this->db->query("CREATE TABLE vndb_tmp (SELECT * FROM $tb_ref ORDER BY ticker,date)");
			$this->db->query("UPDATE vndb_tmp set id = NULL"); 
			$this->db->query("TRUNCATE TABLE $tb_ref");
			$this->db->query("INSERT INTO $tb_ref (SELECT * FROM vndb_tmp)");
			$this->db->query("DROP TABLE IF EXISTS vndb_tmp");
			/* * *************** * *
			 *    ORDERS PRICES    *
			 * * *************** * */
			$this->db->query("DROP TABLE IF EXISTS vndb_tmp");
			$this->db->query("CREATE TABLE vndb_tmp (SELECT * FROM $tb_pri ORDER BY ticker,date)");
			$this->db->query("UPDATE vndb_tmp set id = NULL"); 
			$this->db->query("TRUNCATE TABLE $tb_pri");
			$this->db->query("INSERT INTO $tb_pri (SELECT * FROM vndb_tmp)");
			$this->db->query("DROP TABLE IF EXISTS vndb_tmp");
			/* * ************** * *
			 *    MERGES DAILY    *
			 * * ************** * */
			$this->db->query("INSERT INTO $tb_daily ($column_pri2)
			(SELECT $column_pri2 FROM $tb_pri WHERE yyyymmdd BETWEEN $start_date and $end_date)");
			$this->db->query("DROP TABLE IF EXISTS vndb_tmp");
			$this->db->query("CREATE TABLE vndb_tmp (SELECT * FROM $tb_daily ORDER BY ticker,date)");
			$this->db->query("UPDATE vndb_tmp set id = NULL"); 
			$this->db->query("TRUNCATE TABLE $tb_daily");
			$this->db->query("INSERT INTO $tb_daily (SELECT * FROM vndb_tmp)");
			$this->db->query("DROP TABLE IF EXISTS vndb_tmp");
			$this->db->query("update $tb_daily as a, $tb_ref as b set a.shou = b.shou,a.shli = b.shli where a.ticker = b.ticker AND a.market = b.market AND a.date = b.date AND a.yyyymmdd BETWEEN $start_date and $end_date ");
			$this->db->query("update $tb_daily set last = if(pcls = 0, pref, pcls) where yyyymmdd BETWEEN $start_date and $end_date");
			/* * ******** * *
			 *    REPORT    *
			 * * ******** * */
			$where = "LENGTH(ticker) = 3 and yyyymmdd BETWEEN $start_date and $end_date";
			$report = $this->db->query("select * from 
(select a.hsx,b.hnx,c.upc, a.hsx+b.hnx+c.upc as sum from 
(select count(*) as hsx from $tb_ref where market = 'HSX' and $where) a, 
(select count(*) as hnx from $tb_ref where market = 'HNX' and $where) b, 
(select count(*) as upc from $tb_ref where market = 'UPC' and $where) c) as ref
UNION ALL 
select * from 
(select a.hsx,b.hnx,c.upc, d.sum_date as sum from 
(select count(DISTINCT yyyymmdd) as hsx from $tb_ref where market = 'HSX' and $where) a, 
(select count(DISTINCT yyyymmdd) as hnx from $tb_ref where market = 'HNX' and $where) b, 
(select count(DISTINCT yyyymmdd) as upc from $tb_ref where market = 'UPC' and $where) c, 
(select count(DISTINCT yyyymmdd) as sum_date from $tb_ref where $where) d) as date_ref
UNION ALL 
select * from 
(select a.hsx,b.hnx,c.upc, a.hsx+b.hnx+c.upc as sum from 
(select count(*) as hsx from $tb_pri where market = 'HSX' and $where) a, 
(select count(*) as hnx from $tb_pri where market = 'HNX' and $where) b, 
(select count(*) as upc from $tb_pri where market = 'UPC' and $where) c) as pri
UNION ALL 
select * from 
(select a.hsx,b.hnx,c.upc, d.sum_date as sum from 
(select count(DISTINCT yyyymmdd) as hsx from $tb_pri where market = 'HSX' and $where) a, 
(select count(DISTINCT yyyymmdd) as hnx from $tb_pri where market = 'HNX' and $where) b, 
(select count(DISTINCT yyyymmdd) as upc from $tb_pri where market = 'UPC' and $where) c, 
(select count(DISTINCT yyyymmdd) as sum_date from $tb_pri where $where) d) as date_pri
UNION ALL 
select * from 
(select a.hsx,b.hnx,c.upc, a.hsx+b.hnx+c.upc as sum from 
(select count(*) as hsx from $tb_daily where market = 'HSX' and $where) a, 
(select count(*) as hnx from $tb_daily where market = 'HNX' and $where) b, 
(select count(*) as upc from $tb_daily where market = 'UPC' and $where) c) as daily
UNION ALL 
select * from 
(select a.hsx,b.hnx,c.upc, d.sum_date as sum from 
(select count(DISTINCT yyyymmdd) as hsx from $tb_daily where market = 'HSX' and $where) a, 
(select count(DISTINCT yyyymmdd) as hnx from $tb_daily where market = 'HNX' and $where) b, 
(select count(DISTINCT yyyymmdd) as upc from $tb_daily where market = 'UPC' and $where) c, 
(select count(DISTINCT yyyymmdd) as sum_date from $tb_daily where $where) d) as date_upc
UNION ALL 
select * from 
(select a.hsx,b.hnx,c.upc, a.hsx+b.hnx+c.upc as sum from 
(select count(*) as hsx from $tb_daily where market = 'HSX' and shli = 0 and $where) a, 
(select count(*) as hnx from $tb_daily where market = 'HNX' and shli = 0 and $where) b, 
(select count(*) as upc from $tb_daily where market = 'UPC' and shli = 0 and $where) c) as shli
UNION ALL 
select * from 
(select a.hsx,b.hnx,c.upc, a.hsx+b.hnx+c.upc as sum from 
(select count(*) as hsx from $tb_daily where market = 'HSX' and shou = 0 and $where) a, 
(select count(*) as hnx from $tb_daily where market = 'HNX' and shou = 0 and $where) b, 
(select count(*) as upc from $tb_daily where market = 'UPC' and shou = 0 and $where) c) as shou
UNION ALL 
select * from 
(select a.hsx,b.hnx,c.upc, a.hsx+b.hnx+c.upc as sum from 
(select count(*) as hsx from $tb_daily where market = 'HSX' and last = 0 and $where) a, 
(select count(*) as hnx from $tb_daily where market = 'HNX' and last = 0 and $where) b, 
(select count(*) as upc from $tb_daily where market = 'UPC' and last = 0 and $where) c) as last")->result_array();
			foreach($report as $item){
				$data_report['hsx'][] = $item['hsx'];
				$data_report['hnx'][] = $item['hnx'];
				$data_report['upc'][] = $item['upc'];
				$data_report['sum'][] = $item['sum'];
			}
			$date_gd = $this->db->query("select count(DISTINCT date) as date_gd from vndb_calendar where date BETWEEN STR_TO_DATE('".$start_date."','%Y%m%d') and STR_TO_DATE('".$end_date."','%Y%m%d')")->row_array();
			$response['date_gd'] = $date_gd['date_gd'];
			$response['report'] = $data_report;
			$response['check'] = $check;
            echo json_encode($response);
		}	
	}
	public function process_export_daily(){
		if ($this->input->is_ajax_request()) {
			$from = microtime(true);
			set_time_limit(0);
			$tb_daily = 'TEST_DAILY';
			$start_date = $this->input->post('startdate');
			$end_date = $this->input->post('enddate');
			$start_date = str_replace('-','',$start_date);
			$end_date = str_replace('-','',$end_date);
			$check = $this->input->post('check');
			$column = 'source,ticker,market,date,yyyymmdd,shli,shou,shfn,pref,pcei,pflr,popn,phgh,plow,pbase,pavg,pcls,vlm,trn,last';
			//$dir = '\\\LOCAL\IFRCVN\VNDB\METASTOCK\DAILY\ALL\\';
			$dir = '\\\LOCAL\IFRCVN\VNDB\TESTS\\';
			if($check == 1){
				$data = $this->db->query("SELECT DISTINCT yyyymmdd FROM $tb_daily WHERE yyyymmdd BETWEEN $start_date and $end_date")->result_array();
				foreach($data as $item){
					$filename = $dir.'DAILY_ALL_'.$item['yyyymmdd'].'.txt';
					$files = glob($dir. '*.txt');
					$data = $this->db->query("SELECT $column FROM $tb_daily WHERE yyyymmdd = '".$item['yyyymmdd']."'")->result_array();	
					$implode = array();
					foreach($data as $item2){
						$header = array_keys($item2);
						$item2['date'] = str_replace('-','/',$item2['date']);
						$implode[] = implode("\t",$item2);
					}
					$header = implode("\t",$header);
					$implode = implode("\r\n",$implode);
					$file = $header."\r\n";
					$file .= $implode;
					$create = fopen($filename, "w");
					$write = fwrite($create, $file);
					fclose($create);
				}
			}else{
				$data = $this->db->query("SELECT DISTINCT yyyymmdd FROM $tb_daily WHERE yyyymmdd BETWEEN $start_date and $end_date")->result_array();
				foreach($data as $item){
					$filename = $dir.'DAILY_ALL_'.$item['yyyymmdd'].'.txt';
					$files = glob($dir. '*.txt');
					if (!in_array($filename, $files)) {
						$data = $this->db->query("SELECT $column FROM $tb_daily WHERE yyyymmdd = '".$item['yyyymmdd']."'")->result_array();	
						$implode = array();
						foreach($data as $item2){
							$header = array_keys($item2);
							$item2['date'] = str_replace('-','/',$item2['date']);
							$implode[] = implode("\t",$item2);
						}
						$header = implode("\t",$header);
						$implode = implode("\r\n",$implode);
						$file = $header."\r\n";
						$file .= $implode;
						$create = fopen($filename, "w");
						$write = fwrite($create, $file);
						fclose($create);
					}
				}
			}
			$total = microtime(true) - $from;
            $response[0]['time'] = round($total, 2);
            $response[0]['task'] = 'Export Daily';
            echo json_encode($response);
		}
	}
	public function merges_daily(){
		$this->template->write_view('content', 'daily/merges_daily', $this->data);
        $this->template->write('title', 'Daily All');
        $this->template->render();
	}
	public function process_check_merges(){
		if ($this->input->is_ajax_request()) {
			set_time_limit(0);
			//$check_day = $this->db->query("select count(*) as count from vndb_prices_day where date = (select DISTINCT date from vndb_reference_day);")->row_array();
			$check_day = $this->db->query('select a.date_ref, b.date_pri, if(a.date_ref = b.date_pri, 1, 0) as result from (select date as date_ref from vndb_reference_day GROUP BY date) a, (select date as date_pri from vndb_prices_day GROUP BY date) b')->row_array();
			$check_line_ref = $this->db->query("select if(sum(a.ticker) = b.final,1,0) as final from (select count(ticker) as ticker from vndb_reference_day GROUP BY ticker HAVING count(ticker) >= 1) a, (select count(*) as final from vndb_reference_day) b")->row_array();
			$check_line_pri = $this->db->query("select if(sum(a.ticker) = b.final,1,0) as final from (select count(ticker) as ticker from vndb_prices_day GROUP BY ticker HAVING count(ticker) >= 1) a, (select count(*) as final from vndb_prices_day) b")->row_array();
			$check_ticker = $this->db->query('select a.c_ref_ticker, b.c_pri_ticker, if(a.c_ref_ticker = b.c_pri_ticker,1,0) as result from (select count(DISTINCT ticker) as c_ref_ticker from vndb_reference_day where LENGTH(ticker) = 3) a, (select count(DISTINCT ticker) as c_pri_ticker from vndb_prices_day where LENGTH(ticker) = 3) b')->row_array();
			if($check_day['result'] == 0){
				$response['message_day'] = 'NOT OK';
			}else{
				$response['message_day'] = 'OK';	
			}
			if(($check_line_ref['final'] != $check_line_pri['final']) or ($check_ticker['result'] == 0)){
				$response['message_line'] = 'NOT OK';	
			}else{
				$response['message_line'] = 'OK';
			}
			$response['date_ref'] = $check_day['date_ref'];
			$response['date_pri'] = $check_day['date_pri'];
			$response['ticker_ref'] = $check_ticker['c_ref_ticker'];
			$response['ticker_pri'] = $check_ticker['c_pri_ticker'];
			echo json_encode($response);
		}
	}
	public function process_merges_daily(){
		if ($this->input->is_ajax_request()) {
			$from = microtime(true);
			set_time_limit(0);
			$this->db->query("TRUNCATE TABLE vndb_day");
	
			$this->db->query("INSERT INTO vndb_day (source, ticker, market, date, yyyymmdd, shli, shou, shfn, pref, pcei, pflr, popn, phgh, plow, pbase, pavg, pcls, vlm, trn)(select source, ticker, market, date, yyyymmdd, shli, shou, shfn, pref, pcei, pflr, popn, phgh, plow, pbase, pavg, pcls, vlm, trn from vndb_prices_day_stp)");
			
			$this->db->query("DROP TABLE IF EXISTS vndb_tmp");
			$this->db->query("CREATE TABLE vndb_tmp (SELECT * FROM vndb_day ORDER BY ticker,date)");
			$this->db->query("UPDATE vndb_tmp set id = NULL"); 
			$this->db->query("TRUNCATE TABLE vndb_day");
			$this->db->query("INSERT INTO vndb_day (SELECT * FROM vndb_tmp)");
			$this->db->query("DROP TABLE IF EXISTS vndb_tmp");
			
			$this->db->query("update vndb_day as a, vndb_reference_day as b set a.shou=b.shou,a.shli=b.shli  where a.ticker=b.ticker and a.market=b.market and a.yyyymmdd=b.yyyymmdd");
			
			$this->db->query("update vndb_day set last=if(pcls=0,pref,pcls)");
			
			$data = $this->db->query("select source, ticker, market, date, yyyymmdd, shli, shou, shfn, pref, pcei, pflr, popn, phgh, plow, pbase, pavg, pcls, vlm, trn, last from vndb_day")->result_array();	
			$implode = array();
			foreach($data as $item){
				$header = array_keys($item);
				$date = $item['yyyymmdd'];
				$item['date'] = str_replace('-','/',$item['date']);
				$implode[] = implode("\t",$item);
			}
			$header = implode("\t",$header);
			$implode = implode("\r\n",$implode);
			$file = $header."\r\n";
			$file .= $implode;
			$filename = '\\\LOCAL\IFRCVN\VNDB\METASTOCK\DAILY\ALL\DAILY_ALL_'.$date.'.txt';
			$create = fopen($filename, "w");
			$write = fwrite($create, $file);
			fclose($create);
			$final = $this->db->query("select * from vndb_day where LENGTH(ticker) =3 and shli*shou*last = 0")->num_rows();
			$total = microtime(true) - $from;
			$response[0]['report'] = $final;
            $response[0]['time'] = round($total, 2);
            $response[0]['task'] = 'Daily Merges';
            echo json_encode($response);
		}	
	}
	public function daily_switch(){
		$this->template->write_view('content', 'daily/daily_switch', $this->data);
        $this->template->write('title', 'Daily Switch');
        $this->template->render();	
	}
	
 	public function process_check_date(){
		if ($this->input->is_ajax_request()) {
			set_time_limit(0);	
			$respone['date'] = $this->input->post('date');
			$date = str_replace('-', '', $respone['date']);
			$data_day = $this->db->query("select * from vndb_day where yyyymmdd = '".$date."'")->num_rows();
			$data_daily = $this->db->query("select * from vndb_daily where yyyymmdd = '".$date."'")->num_rows();
			$data_history = $this->db->query("select * from vndb_history where yyyymmdd = '".$date."'")->num_rows();
			if($data_day != 0){
				if ($data_daily > 0 or $data_history > 0) {
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
	public function process_daily_switch(){
		if ($this->input->is_ajax_request()) {
			$from = microtime(true);
			set_time_limit(0);
			$date = $this->input->post('date');
			$key = $this->input->post('key');
			$date = str_replace('-','',$date);
			if($key == 'Yes'){
				$this->db->query("DELETE FROM vndb_daily where yyyymmdd = '".$date."'");
				$this->db->query("DELETE FROM vndb_history where yyyymmdd = '".$date."'");
				$this->db->query("INSERT INTO vndb_daily (source, ticker, market, date, yyyymmdd, shli, shou, shfn, pref, pcei, pflr, popn, phgh, plow, pbase, pavg, pcls, vlm, trn, last) SELECT source,ticker, market, date, yyyymmdd, shli, shou, shfn, pref, pcei, pflr, popn, phgh, plow, pbase, pavg, pcls, vlm, trn, last FROM vndb_day where yyyymmdd = '".$date."'");
				$this->db->query("INSERT INTO vndb_history(source,ticker, market, date, yyyymmdd, shli, shou, shfn, pref, pcei, pflr, popn, phgh, plow, pbase, pavg, pcls, vlm, trn, last)(SELECT source,ticker, market, date, yyyymmdd, shli, shou, shfn, pref, pcei, pflr, popn, phgh, plow, pbase, pavg, pcls, vlm, trn, last FROM vndb_day where yyyymmdd = '".$date."')");	 
			}else{
				$this->db->query("INSERT INTO vndb_daily(source,ticker, market, date, yyyymmdd, shli, shou, shfn, pref, pcei, pflr, popn, phgh, plow, pbase, pavg, pcls, vlm, trn, last) SELECT source,ticker, market, date, yyyymmdd, shli, shou, shfn, pref, pcei, pflr, popn, phgh, plow, pbase, pavg, pcls, vlm, trn, last FROM vndb_day where yyyymmdd = '".$date."'");
				$this->db->query("INSERT INTO vndb_history(source,ticker, market, date, yyyymmdd, shli, shou, shfn, pref, pcei, pflr, popn, phgh, plow, pbase, pavg, pcls, vlm, trn, last)(SELECT source,ticker, market, date, yyyymmdd, shli, shou, shfn, pref, pcei, pflr, popn, phgh, plow, pbase, pavg, pcls, vlm, trn, last FROM vndb_day where yyyymmdd = '".$date."')");	 
			}
			$this->db->query("CREATE TABLE vndb_tmp (SELECT source,ticker, market, date, yyyymmdd, shli, shou, shfn, pref, pcei, pflr, popn, phgh, plow, pbase, pavg, pcls, vlm, trn, last FROM vndb_daily ORDER BY ticker,date asc)");
			$this->db->query("TRUNCATE TABLE vndb_daily");
			$this->db->query("INSERT INTO vndb_daily (source,ticker, market, date, yyyymmdd, shli, shou, shfn, pref, pcei, pflr, popn, phgh, plow, pbase, pavg, pcls, vlm, trn, last) (SELECT source,ticker, market, date, yyyymmdd, shli, shou, shfn, pref, pcei, pflr, popn, phgh, plow, pbase, pavg, pcls, vlm, trn, last FROM vndb_tmp)");
			$this->db->query("DROP TABLE IF EXISTS vndb_tmp");
			$this->db->query("CREATE TABLE vndb_tmp (SELECT source,ticker, market, date, yyyymmdd, shli, shou, shfn, pref, pcei, pflr, popn, phgh, plow, pbase, pavg, pcls, vlm, trn, last FROM vndb_history ORDER BY ticker,date asc)");
			$this->db->query("TRUNCATE TABLE vndb_history");
			$this->db->query("INSERT INTO vndb_history (source,ticker, market, date, yyyymmdd, shli, shou, shfn, pref, pcei, pflr, popn, phgh, plow, pbase, pavg, pcls, vlm, trn, last) (SELECT source,ticker, market, date, yyyymmdd, shli, shou, shfn, pref, pcei, pflr, popn, phgh, plow, pbase, pavg, pcls, vlm, trn, last FROM vndb_tmp)");
			$this->db->query("DROP TABLE IF EXISTS vndb_tmp");
			$total = microtime(true) - $from;
            $response[0]['time'] = round($total, 2);
            $response[0]['task'] = 'Daily Switch';
            echo json_encode($response);
		}	
	}
	public function both_merges_switch(){
		$this->template->write_view('content', 'daily/both_merges_switch', $this->data);
        $this->template->write('title', 'Daily Switch');
        $this->template->render();		
	}
	public function process_both_merges_switch(){
		if ($this->input->is_ajax_request()) {
			$from = microtime(true);
			set_time_limit(0);
			$this->db->query("TRUNCATE TABLE vndb_day");
	
			$this->db->query("INSERT INTO vndb_day (source, ticker, market, date, yyyymmdd, shli, shou, shfn, pref, pcei, pflr, popn, phgh, plow, pbase, pavg, pcls, vlm, trn)(select source, ticker, market, date, yyyymmdd, shli, shou, shfn, pref, pcei, pflr, popn, phgh, plow, pbase, pavg, pcls, vlm, trn from vndb_prices_day)");
			
			$this->db->query("DROP TABLE IF EXISTS vndb_tmp");
			$this->db->query("CREATE TABLE vndb_tmp (SELECT * FROM vndb_day ORDER BY ticker,date)");
			$this->db->query("UPDATE vndb_tmp set id = NULL"); 
			$this->db->query("TRUNCATE TABLE vndb_day");
			$this->db->query("INSERT INTO vndb_day (SELECT * FROM vndb_tmp)");
			$this->db->query("DROP TABLE IF EXISTS vndb_tmp");
			
			$this->db->query("update vndb_day as a, vndb_reference_day as b set a.shou=b.shou,a.shli=b.shli  where a.ticker=b.ticker and a.market=b.market and a.yyyymmdd=b.yyyymmdd");
			
			$this->db->query("update vndb_day set last=if(pcls=0,pref,pcls)");
			
			$data = $this->db->query("select source, ticker, market, date, yyyymmdd, shli, shou, shfn, pref, pcei, pflr, popn, phgh, plow, pbase, pavg, pcls, vlm, trn, last from vndb_day")->result_array();	
			$implode = array();
			foreach($data as $item){
				$header = array_keys($item);
				$date = $item['yyyymmdd'];
				$item['date'] = str_replace('-','/',$item['date']);
				$implode[] = implode("\t",$item);
			}
			$header = implode("\t",$header);
			$implode = implode("\r\n",$implode);
			$file = $header."\r\n";
			$file .= $implode;
			$filename = '\\\LOCAL\IFRCVN\VNDB\METASTOCK\DAILY\ALL\DAILY_ALL_'.$date.'.txt';
			$create = fopen($filename, "w");
			$write = fwrite($create, $file);
			fclose($create);
			$final = $this->db->query("select * from vndb_day where LENGTH(ticker) =3 and shli*shou*last = 0")->num_rows();
			
			$date = $this->input->post('date');
			$key = $this->input->post('key');
			$date = str_replace('-','',$date);
			if($key == 'Yes'){
				$this->db->query("DELETE FROM vndb_daily where yyyymmdd = '".$date."'");
				$this->db->query("DELETE FROM vndb_history where yyyymmdd = '".$date."'");
				$this->db->query("INSERT INTO vndb_daily (source, ticker, market, date, yyyymmdd, shli, shou, shfn, pref, pcei, pflr, popn, phgh, plow, pbase, pavg, pcls, vlm, trn) SELECT source,ticker, market, date, yyyymmdd, shli, shou, shfn, pref, pcei, pflr, popn, phgh, plow, pbase, pavg, pcls, vlm, trn FROM vndb_day where yyyymmdd = '".$date."'");
				$this->db->query("INSERT INTO vndb_history(source,ticker, market, date, yyyymmdd, shli, shou, shfn, pref, pcei, pflr, popn, phgh, plow, pbase, pavg, pcls, vlm, trn)(SELECT source,ticker, market, date, yyyymmdd, shli, shou, shfn, pref, pcei, pflr, popn, phgh, plow, pbase, pavg, pcls, vlm, trn FROM vndb_day where yyyymmdd = '".$date."')");	 
			}else{
				$this->db->query("INSERT INTO vndb_daily(source,ticker, market, date, yyyymmdd, shli, shou, shfn, pref, pcei, pflr, popn, phgh, plow, pbase, pavg, pcls, vlm, trn) SELECT source,ticker, market, date, yyyymmdd, shli, shou, shfn, pref, pcei, pflr, popn, phgh, plow, pbase, pavg, pcls, vlm, trn FROM vndb_day where yyyymmdd = '".$date."'");
				$this->db->query("INSERT INTO vndb_history(source,ticker, market, date, yyyymmdd, shli, shou, shfn, pref, pcei, pflr, popn, phgh, plow, pbase, pavg, pcls, vlm, trn)(SELECT source,ticker, market, date, yyyymmdd, shli, shou, shfn, pref, pcei, pflr, popn, phgh, plow, pbase, pavg, pcls, vlm, trn FROM vndb_day where yyyymmdd = '".$date."')");	 
			}
			$total = microtime(true) - $from;
            $response[0]['time'] = round($total, 2);
            $response[0]['task'] = 'Daily Both Merges & Switch';
			$response[0]['report'] = $final;
            echo json_encode($response);
		}
	}
}