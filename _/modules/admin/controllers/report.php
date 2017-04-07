<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/* * ******************************************************************************************************************* *
 * 	 																													 *
 *																														 *
 *																														 *
 *													Author: Minh Đẹp Trai			 									 *
 *																														 *
 *																														 *
 *																														 *
 * * ******************************************************************************************************************* */
class Report extends Admin {
    public function __construct() {
        parent::__construct();
    }
	public function report_month() {
        $this->template->write_view('content', 'report/report_month', $this->data);
        $this->template->write('title', 'Report Month');
        $this->template->render();
    }

	public function process_report_month(){
		if($this->input->is_ajax_request()){
			set_time_limit(0);
			$month = $this->input->post('month');
			if($month < 10){
				$month = '0'.$month;
			}
            $year = $this->input->post('year');
			$last_year = $year - 1;
			$date_first = $last_year.'/'.$month.'/31';
			$date_last = $year.'/'.$month.'/31';

			// if($date_first >= '2011/12/31'){
			// 	$filter = 'SHOU';
			// }else{
			// 	$filter = 'SHLI';
			// }

			$this->db->query('SET @day_first = "'.$date_first.'"');
			$this->db->query('SET @day_last = "'.$date_last.'"');

			$this->db->query("DROP TABLE IF EXISTS VNDB_MONTHLY_MDATA");
			$this->db->query("CREATE TABLE VNDB_MONTHLY_MDATA  (
			SELECT TICKER, MARKET, YYYYMMDD, DATE, IF(DATE>='2011/12/31',SHOU,SHLI) as SHLI , VLM, TRN, LAST AS PREF, LAST AS PCLS, LAST, SHLI AS CAPI
			FROM vndb_prices_history
			WHERE DATE > @day_first and date <= @day_last and LENGTH(ticker) = 3 
			ORDER BY DATE DESC)");
			$this->db->query("UPDATE VNDB_MONTHLY_MDATA SET CAPI = SHLI*LAST");

			$this->db->query("DROP TABLE IF EXISTS VNDB_MONTHLY_STATS");
			$this->db->query("CREATE TABLE VNDB_MONTHLY_STATS  (
			SELECT TICKER, MARKET, LEFT(YYYYMMDD,6) as YYYYMM, DATE, COUNT(DATE) as NB, SUM(VLM) AS SVLM, SUM(TRN) AS STRN, 100*(SUM(VLM/SHLI)) AS SVELO,
			SHLI, PREF, LAST AS PCLS, CAPI AS LASTCAPI, SUM(IF(VLM>0,1,0)) AS NBT
			FROM VNDB_MONTHLY_MDATA
			GROUP BY TICKER, YYYYMM
			ORDER BY YYYYMM DESC, TICKER
			)");

			$this->db->query("DROP TABLE IF EXISTS VNDB_MONTHLY_FINAL");
			$this->db->query("CREATE TABLE VNDB_MONTHLY_FINAL  (
			SELECT TICKER, MARKET, YYYYMM, DATE, SVLM AS SVOLUME, STRN AS STURNOVER, SVELO, NB, NBT, SHLI AS SHARES_LIS, PCLS AS PR_CLS,
			SUM(NB) AS SSNB, SUM(NBT) AS SSNBT, SUM(STRN) AS SSTURNOVER, SUM(SVLM) AS SSVOLUME, SUM(SVELO) AS SSVELO
			FROM VNDB_MONTHLY_STATS
			GROUP BY TICKER
			ORDER BY TICKER
			)");

			$data = $this->db->query("SELECT * FROM VNDB_MONTHLY_FINAL")->result_array();

			foreach($data as $item){
				$header = array_keys($item);
				$item['DATE'] = str_replace('-','/',$item['DATE']);
				$content[] = implode(',',$item);
			}
			$header = implode(',',$header);
			$content = implode("\r\n",$content);
			$file = $header."\r\n";
			$file .= $content."\r\n";
			$filename = '\\\LOCAL\IFRCVN\VNDB\MONTHLY\UP\rmonth_'.$year.$month.'.csv';
			$create = fopen($filename, "w");
			$write = fwrite($create, $file);
			$close = fclose($create);
			$respone['task'] = 'Month: '.$month.' | Year: '.$year;
			$respone['result'] = $this->db->query("SELECT * FROM VNDB_MONTHLY_MDATA WHERE LAST*SHLI = 0")->num_rows().' Record (LAST * SHLI = 0)';
			echo json_encode($respone);
		}

	}

	public function report_daily(){
		set_time_limit(0);
		/*****************************
		*    INSERT & UPDATE DATA    *
		******************************/
		$this->db->query("TRUNCATE TABLE vndb_stats_vndb");
		$this->db->query("TRUNCATE TABLE vndb_stats_exc");
		$dir = '\\\LOCAL\IFRCVN\VNDB\METASTOCK\DAILY\FINAL\\';
		$files = glob($dir. '*.txt');
		foreach($files as $base){
			$base_url = str_replace("\\","\\\\",$base);
			$this->db->query("LOAD DATA INFILE '".$base_url."' INTO TABLE `vndb_stats_vndb` FIELDS TERMINATED BY '\t' IGNORE 1 LINES (source,ticker,market,date,yyyymmdd,shli,shou,shfn,pref,pcei,pflr,popn,phgh,plow,pbase,pavg,pcls,vlm,trn)");

		}
		$dir1 = '\\\LOCAL\IFRCVN\VNDB\METASTOCK\DAILY\STATS\\';
		$files1 = glob($dir1. '*.txt');
		foreach($files1 as $base1){
			$base_url1 = str_replace("\\","\\\\",$base1);
			$this->db->query("LOAD DATA INFILE '".$base_url1."' INTO TABLE `vndb_stats_exc` FIELDS TERMINATED BY '\t' IGNORE 1 LINES (MARKET,DATE,YYYYMMDD,SVLM_EXC,SVLMTT_EXC,STRN_EXC,STRNTT_EXC)");
		}
		//Insert date exc -> daily
		$this->db->query("TRUNCATE TABLE vndb_stats_daily");
		//INSERT DAY & DATA EXC
		$this->db->query("INSERT INTO vndb_stats_daily (market, date, yyyymmdd,svlm_exc,svlmtt_exc,strn_exc,strntt_exc)(select market,date,yyyymmdd,svlm_exc,svlmtt_exc,strn_exc,strntt_exc from vndb_stats_exc)");

		/**************
		*    DAILY    *
		***************/

		$query3a = $this->db->query("select DISTINCT date, market from vndb_stats_daily ORDER BY market,date asc")->result_array();
		//Update data VNDB
		foreach($query3a as $date){
			$query4a = $this->db->query("select sum(vlm) as svlm_vndb,sum(trn) as strn_vndb, count(ticker) as nb from vndb_stats_vndb where market = '".$date['market']."' AND date = '".$date['date']."' AND LENGTH(ticker) = 3")->result_array();
			$this->db->update('vndb_stats_daily',$query4a[0],array('market' => $date['market'],'date' => $date['date']));
			$this->db->query("update vndb_stats_daily set correct = IF(svlm_vndb = svlm_exc AND strn_vndb = strn_exc,1,0), correct_vlm = IF(svlm_vndb = svlm_exc ,1,0), correct_trn = IF(strn_vndb = strn_exc,1,0) where date = '".$date['date']."' and market = '".$date['market']."'");
		}
		/*********************
		*    REPORT DAILY    *
		**********************/
		$now_date = date('Ymd',time());
		$year = substr($now_date,0,4);
		$month = substr($now_date,4,2);
		if($month == '01'){
			$last_year = $year - 1;
		}else{
			$last_year = $year;
		}
		$current_date = $last_year.'/'.$month.'/01';
		$last_date = $year.'/'.$month.'/31';
		
		
		$query5 = $this->db->query("select DISTINCT ticker from vndb_stats_vndb where length(ticker)<5 and market != 'UPC' and date BETWEEN '".$current_date."' AND '".$last_date."' order by ticker")->result_array();
		foreach($query5 as $tk){
			$query6 = $this->db->query("select ticker, market, EXTRACT(YEAR_MONTH FROM max(date)) as yyyymm, MAX(date) as date, sum(vlm) as svolume,sum(trn) as sturnover,100*sum(vlm)/shli as svelo, count(date) as nb ,count(ticker) as nbt,'' as shares_lis, '' as pr_cls, '' as ssnb, '' as	ssnbt, '' as	ssturnover, '' as	ssvolume, '' as	ssvelo from vndb_meta_prices where ticker = '".$tk['ticker']."' and date BETWEEN '".$current_date."' AND '".$last_date."' ORDER BY ticker,date asc;")->row_array();
			$content[] = implode(chr(9),$query6);
		}
		$header = array_keys($query6);
		$header = implode(chr(9),$header);
		$content = implode("\r\n",$content);
		$file = $header."\r\n";
		$file .= $content;
		$filename = '\\\LOCAL\IFRCVN\VNDB\METASTOCK\DAILY\REPORT\DAILY_REPORT_'.$now_date.'.txt';
		$create = fopen($filename, "w");
		$write = fwrite($create, $file);
		$close = fclose($create);

		/**************************************************
		*    UPDATE VNDB_META_PRICE -> VNDB_STATS_DAILY   *
		***************************************************/
		$start_date = '2012/11/01';
		$end_date = '2012/12/31';
		$query7 = $this->db->query("select date,market,svlm_vndb,strn_vndb,nb from vndb_stats_daily where date BETWEEN '".$start_date."' and '".$end_date."' order by date,market")->result_array();
		foreach($query7 as $item){
			if($item['svlm_vndb'] == '' && $item['strn_vndb'] == '' && $item['nb'] == '0'){
				$this->db->query("UPDATE vndb_stats_daily SET svlm_vndb = (SELECT SUM(b.vlm) FROM vndb_meta_prices AS b WHERE b.market = '".$item['market']."' AND b.date='".$item['date']."' AND LENGTH(b.ticker) = 3),strn_vndb = (SELECT SUM(b.trn) FROM vndb_meta_prices AS b WHERE b.market = '".$item['market']."' AND b.date='".$item['date']."' AND LENGTH(b.ticker) < 4),nb = (SELECT count(b.ticker) FROM vndb_meta_prices AS b WHERE b.market = '".$item['market']."' AND b.date='".$item['date']."') WHERE date = '".$item['date']."' and market = '".$item['market']."'");
			}
			$this->db->query("update vndb_stats_daily set correct = IF(svlm_vndb = svlm_exc AND strn_vndb = strn_exc,1,0), correct_vlm = IF(svlm_vndb = svlm_exc ,1,0), correct_trn = IF(strn_vndb = strn_exc,1,0) where date = '".$item['date']."' and market = '".$item['market']."'");
		}
		redirect(admin_url() . 'daily_action');
	}

	public function export_report(){
		error_reporting(E_ALL ^E_NOTICE);
		set_time_limit(0);
		//TRUNCATE 3 TABLE (DAY,MONTH,YEAR)
		$this->db->query('TRUNCATE TABLE VNDB_STATS_EXC_DAY');
		$this->db->query('TRUNCATE TABLE VNDB_STATS_EXC_MONTH');
		$this->db->query('TRUNCATE TABLE VNDB_STATS_EXC_YEAR');
		$this->db->query('TRUNCATE TABLE VNDB_STATS_DAILY2');
		$this->db->query('TRUNCATE TABLE VNDB_STATS_MONTHLY');
		$this->db->query('TRUNCATE TABLE VNDB_STATS_YEARLY');
	   	$dir = '\\\LOCAL\IFRCVN\VNDB\METASTOCK\DAILY\STATS\BACKUP\\';
		$column = 'MARKET,DATE,YYYYMMDD,YYYYMM,YYYY,SVLM_EXC,STRN_EXC,SVLMKL_EXC,STRNKL_EXC,SVLMTT_EXC,STRNTT_EXC';
		$files = glob($dir . '*.txt');
		foreach ($files as $base) {
			$file_name = basename($base, ".txt");
			$base_url = str_replace("\\", "\\\\", $base);
			switch($file_name)
			{
			//LOAD DATA IN VNDB_STATS_EXC_DAY
			case 'VNDB_STATS_EXC_DAY':
				$this->db->query("LOAD DATA INFILE '".$base_url."' INTO TABLE `VNDB_STATS_EXC_DAY` FIELDS TERMINATED BY '\t' IGNORE 1 LINES ({$column})");
				break;
			//LOAD DATA IN VNDB_STATS_EXC_MONTH
			case 'VNDB_STATS_EXC_MONTH':
				$this->db->query("LOAD DATA INFILE '".$base_url."' INTO TABLE `VNDB_STATS_EXC_MONTH` FIELDS TERMINATED BY '\t' IGNORE 1 LINES ({$column})");
				break;
			//LOAD DATA IN VNDB_STATS_EXC_YEAR
			case 'VNDB_STATS_EXC_YEAR':
				$this->db->query("LOAD DATA INFILE '".$base_url."' INTO TABLE `VNDB_STATS_EXC_YEAR` FIELDS TERMINATED BY '\t' IGNORE 1 LINES ({$column})");
				break;
			}
		}

		/**************
		*    DAILY    *
		***************/
		//INSERT MARKET,YYYYMMDD,YYYYMM,YYYY VNDB_PRICES_UNI -> VNDB_STATS_DAILY
		$this->db->query("INSERT INTO vndb_stats_daily2 (YYYYMMDD,YYYYMM,YYYY,MARKET,SVLM_VNDB,STRN_VNDB) (SELECT  YYYYMMDD, EXTRACT(YEAR_MONTH FROM date) AS YYYYMM, EXTRACT(YEAR FROM date) AS YYYY, market, SUM(VLM) as svlm_vndb, SUM(TRN) as strn_vndb FROM vndb_prices_uni where LENGTH(ticker) = 3 GROUP BY market, date)");
		//GET YYYY, MARKET VNDB_STATS_YEARLY
		$query1c = $this->db->query("SELECT MARKET,YYYYMMDD FROM VNDB_STATS_DAILY2")->result_array();
		foreach($query1c as $ymd){
			//SUM VLM & TRN AND UPDATE OF EXC
			$query2a = $this->db->query("UPDATE vndb_stats_daily2 a, (select svlm_exc, strn_exc from vndb_stats_exc_day where yyyymmdd = '".$ymd['YYYYMMDD']."' and market = '".$ymd['MARKET']."') b
			set 
			a.svlm_exc = b.svlm_exc,
			a.strn_exc = b.strn_exc
			");
		}
		//UPDATE CORRECT VNDB_STATS_DAILY
		$query2aa = $this->db->query("SELECT id,IF(svlm_vndb = svlm_exc AND strn_vndb = strn_exc,1,0) as correct, IF(svlm_vndb = svlm_exc ,1,0) as correct_vlm, IF(strn_vndb = strn_exc,1,0) as correct_trn FROM vndb_stats_daily2")->result_array();
		$this->db->update_batch('vndb_stats_daily2', $query2aa,'id');
		/****************
		*    MONTHLY    *
		*****************/
		//INSERT MARKET,YYYYMM,YYYY VNDB_PRICES_UNI -> VNDB_STATS_MONTHLY
		$this->db->query("INSERT INTO vndb_stats_monthly (YYYYMM,YYYY,MARKET) (SELECT DISTINCT EXTRACT(YEAR_MONTH FROM date) AS YYYYMM, EXTRACT(YEAR FROM date) AS YYYY, market FROM vndb_prices_uni GROUP BY market, date)");
		//GET YYYYMM & MARKET VNDB_STATS_MONTHLY
		$query1a = $this->db->query("SELECT MARKET,YYYYMM FROM VNDB_STATS_MONTHLY")->result_array();
		foreach($query1a as $ym){
			//SUM VLM & TRN AND UPDATE OF EXC
			$query2b = $this->db->query("UPDATE vndb_stats_monthly a, (select sum(vlm) as svlm_vndb, sum(trn) as strn_vndb,  from vndb_prices_uni where EXTRACT(YEAR_MONTH FROM date) = '".$ym['YYYYMM']."' and market = '".$ym['MARKET']."' and LENGTH(ticker) = 3) b, (select svlm_exc, strn_exc from vndb_stats_exc_month where yyyymm = '".$ym['YYYYMM']."' and market = '".$ym['MARKET']."') c
			set
			a.svlm_vndb = b.svlm_vndb,
			a.strn_vndb = b.strn_vndb,
			a.svlm_exc = c.svlm_exc,
			a.strn_exc = c.strn_exc");
		}
		//UPDATE CORRECT VNDB_STATS_MONTHLY
		$query2bb = $this->db->query("SELECT id,IF(svlm_vndb = svlm_exc AND strn_vndb = strn_exc,1,0) as correct, IF(svlm_vndb = svlm_exc ,1,0) as correct_vlm, IF(strn_vndb = strn_exc,1,0) as correct_trn FROM vndb_stats_monthly")->result_array();
		$this->db->update_batch('vndb_stats_monthly', $query2bb,'id');
		/***************
		*    YEARLY    *
		****************/
		//INSERT MARKET,YYYY VNDB_PRICES_UNI -> VNDB_STATS_YEARLY
		$this->db->query("INSERT INTO vndb_stats_yearly (YYYY,MARKET) (SELECT DISTINCT EXTRACT(YEAR FROM date) AS YYYY, market FROM vndb_prices_uni GROUP BY market, date)");
		//GET YYYY, MARKET VNDB_STATS_YEARLY
		$query1b = $this->db->query("SELECT MARKET,YYYY FROM VNDB_STATS_YEARLY")->result_array();
		foreach($query1b as $y){
			//SUM VLM & TRN AND UPDATE OF EXC
			$query2c = $this->db->query("UPDATE vndb_stats_yearly as a, (select sum(vlm) as svlm_vndb, sum(trn) as strn_vndb from vndb_prices_uni where EXTRACT(YEAR FROM date) = '".$y['YYYY']."' and market = '".$y['MARKET']."' and LENGTH(ticker) = 3) b, (select svlm_exc, strn_exc from vndb_stats_exc_year where yyyy = '".$y['YYYY']."' and market = '".$y['MARKET']."') c
			set
			a.svlm_vndb = b.svlm_vndb,
			a.strn_vndb = b.strn_vndb,
			a.svlm_exc = c.svlm_exc,
			a.strn_exc = c.strn_exc");
		}
		//UPDATE CORRECT VNDB_STATS_YEARLY
		$query2cc = $this->db->query("SELECT id,IF(svlm_vndb = svlm_exc AND strn_vndb = strn_exc,1,0) as correct, IF(svlm_vndb = svlm_exc ,1,0) as correct_vlm, IF(strn_vndb = strn_exc,1,0) as correct_trn FROM vndb_stats_yearly")->result_array();
		$this->db->update_batch('vndb_stats_yearly', $query2cc,'id');
		redirect(admin_url() . 'daily_action');
	}

	public function export_vnx_monthly(){
        $this->template->write_view('content', 'report/export_vnx_monthly',$this->data);
        $this->template->write('title', 'Export VNX Monthly');
        $this->template->render();
	}

	public function export_vnx_yearly(){
        $this->template->write_view('content', 'report/export_vnx_yearly',$this->data);
        $this->template->write('title', 'Export VNX Yearly');
        $this->template->render();
	}

	public function process_export_vnx_monthly(){
		if ($this->input->is_ajax_request()) {
        	$from = microtime(true);

        	$month = $this->input->post('month');
			if($month < 10){
				$month = '0'.$month;
			}
        	$year = $this->input->post('year');

        	$this->db->query("SET @runtot:= NULL");
        	$this->db->query("SET @runtot1:= NULL");
        	$this->db->query("SET @plr:= NULL");
        	$this->db->query("SET @plr1:= NULL");
        	$dataExport = $this->db->query("SELECT `date`, `open`, `high`, `low`, `close`, `volume`, `adjclose`, `code`, 
IF(@plr = code,(@runtot :=  ((a.close/ @runtot)-1)*100),null) AS perform,
'IFRC' as `provider`,
(@runtot := a.close ) ne,
@plr := code AS dummy
FROM    
 (SELECT REPLACE(`date`,'-','/') as `date`, `idx_open` as `open`, MAX(`idx_close`) as `high`, MIN(`idx_close`) as `low`,`idx_close` as `close`, NULL as `volume`, `idx_close` as `adjclose`, `idx_code` as `code`, NULL as `perform`, NULL as `provider` 
	FROM `ims_production`.`idx_stats_histoday` 
	WHERE `date` IN (SELECT MAX(`date`) 
						FROM `ims_production`.`idx_stats_histoday` 
						WHERE CONCAT(year (date), month(date))<={$year}{$month}
							GROUP BY YEAR(`date`), MONTH(`date`)
					)
	GROUP BY idx_code, YEAR(`date`), MONTH(`date`)
	) a
-- WHERE MONTH(a.date) <= {$month} AND YEAR(a.date) <= {$year}
ORDER BY a.`code`, a.`date`")->result_array();
        	$fp = fopen('//LOCAL/IFRCVN/IOBS/UPLOAD/vnx_month.csv', 'w');
        	$i=0;
			foreach ($dataExport as $fields) {
				unset($fields['ne'],$fields['dummy']);
				if($i==0){
					$dataHeader = array();
					foreach($fields as $key => $value){
						$dataHeader[] = $key;
					}
					fputcsv($fp, $dataHeader);
				}
				fputcsv($fp, $fields);
			    $i++;
			}

			fclose($fp);

        	$total = microtime(true) - $from;
            $response[0]['time'] = round($total, 2);
            $response[0]['task'] = 'Time';
        	echo json_encode($response);
        }
	}

	public function process_export_vnx_yearly(){
		if ($this->input->is_ajax_request()) {
        	$from = microtime(true);

        	$month = $this->input->post('month');
			if($month < 10){
				$month = '0'.$month;
			}
        	$year = $this->input->post('year');

        	$this->db->query("SET @runtot:= NULL");
        	$this->db->query("SET @runtot1:= NULL");
        	$this->db->query("SET @plr:= NULL");
        	$this->db->query("SET @plr1:= NULL");
        	$dataExport = $this->db->query("SELECT `date`, `open`, `high`, `low`, `close`, `volume`, `adjclose`, `code`, 
IF(@plr = code,(@runtot :=  ((a.close/ @runtot)-1)*100),null) AS perform, volat, 
'IFRC' as `provider`,
(@runtot := a.close ) ne,
@plr := code AS dummy
FROM    
 (SELECT REPLACE(`date`,'-','/') as `date`, `idx_open` as `open`, MAX(`idx_close`) as `high`, MIN(`idx_close`) as `low`,`idx_close` as `close`, NULL as `volume`, `idx_close` as `adjclose`, `idx_code` as `code`, NULL as `perform`, NULL as `volat`, NULL as `provider` 
	FROM `ims_production`.`idx_stats_histoday` 
	WHERE `date` IN (SELECT MAX(`date`) 
						FROM `ims_production`.`idx_stats_histoday` 
						WHERE CONCAT(year (date), month(date))<={$year}{$month}
							GROUP BY YEAR(`date`)
					)
	GROUP BY idx_code, YEAR(`date`)
	) a
-- WHERE YEAR(a.date) <= {$year}        	
ORDER BY a.`code`, a.`date`")->result_array();


        	$fp = fopen('//LOCAL/IFRCVN/IOBS/UPLOAD/vnx_year.csv', 'w');
        	$i=0;
			foreach ($dataExport as $fields) {
				unset($fields['ne'],$fields['dummy']);
				if($i==0){
					$dataHeader = array();
					foreach($fields as $key => $value){
						$dataHeader[] = $key;
					}
					fputcsv($fp, $dataHeader);
				}
				fputcsv($fp, $fields);
			    $i++;
			}

			fclose($fp);

        	$total = microtime(true) - $from;
            $response[0]['time'] = round($total, 2);
            $response[0]['task'] = 'Time';
        	echo json_encode($response);
        }
	}
}