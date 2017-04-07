<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/* * ******************************************************************************************************************* *
 *   Author: Minh Đẹp Trai                                                                                               *
 * * ******************************************************************************************************************* */

class Vnx extends Admin {

    public function __construct() {
        parent::__construct();
    }

    public function hight_dividend() {
        $this->template->write_view('content', 'vnx/hight_dividend', $this->data);
        $this->template->write('title', 'Hight Dividend');
        $this->template->render();
    }
	
	public function low_volalitily() {
        $this->template->write_view('content', 'vnx/low_volalitily', $this->data);
        $this->template->write('title', 'Low Volalitily');
        $this->template->render();
    }
	
	public function hight_volalitily() {
        $this->template->write_view('content', 'vnx/hight_volalitily', $this->data);
        $this->template->write('title', 'Hight Volalitily');
        $this->template->render();
    }
	
	public function equal_weighted_50() {
        $this->template->write_view('content', 'vnx/equal_weighted_50', $this->data);
        $this->template->write('title', 'Equal Weighted 50');
        $this->template->render();
    }
	
	public function equal_weighted_25() {
        $this->template->write_view('content', 'vnx/equal_weighted_25', $this->data);
        $this->template->write('title', 'Equal Weighted 25');
        $this->template->render();
    }
	
	public function process_hight_dividend() {
		if($this->input->is_ajax_request()){
			header('Content-Type: text/html; charset=utf-8');
			$from = microtime(true);
            set_time_limit(0);
			$this->db->query('DROP TABLE IF EXISTS VNDB_PRICES_HISTORY_DIV');

			$this->db->query('CREATE TABLE VNDB_PRICES_HISTORY_DIV SELECT TICKER, DATE, YYYYMMDD AS YYYY, DIVIDEND, LAST, LAST AS YIELD FROM VNDB_PRICES_HISTORY WHERE dividend <> 0');
			
			$this->db->query('UPDATE VNDB_PRICES_HISTORY_DIV SET YIELD = 100*(DIVIDEND/LAST), YYYY = LEFT(DATE,4)');
			
			$this->db->query('DROP TABLE IF EXISTS VNDB_PRICES_HISTORY_DIV_YEAR');
			
			$this->db->query('CREATE TABLE VNDB_PRICES_HISTORY_DIV_YEAR SELECT TICKER,YYYY,SUM(YIELD) AS YIELD FROM VNDB_PRICES_HISTORY_DIV GROUP BY TICKER, YYYY');
			
			$this->import_tmp();
			
			$this->db->query('UPDATE TMP A, VNDB_PRICES_HISTORY B SET A.PCLS = B.LAST WHERE A.STK_CODE = B.TICKER AND A.START_DATE = B.DATE AND (A.PCLS = 0 OR A.PCLS IS NULL)');
			
			$this->db->query('DROP TABLE IF EXISTS TMP_1');
			
			$this->db->query('CREATE TABLE TMP_1 SELECT yyyymm, stk_code, STK_NAME, stk_curr, stk_shares, pcls, capi, stk_float, idx_code, start_date, end_date, dividend, yield, sum_yield, ratio, sum_capi, 
			stk_capp, wgt FROM TMP WHERE IDX_CODE = "VNX50VND"');
			
			$this->db->query('UPDATE TMP_1 SET YYYYMM = CONCAT(YEAR(start_date),MONTH(start_date))');
			
			$this->db->query('UPDATE TMP_1 A, VNDB_PRICES_HISTORY B SET A.DIVIDEND = B.DIVIDEND WHERE A.STK_CODE = B.TICKER AND A.START_DATE = B.DATE');
			
			$this->db->query('UPDATE TMP_1 SET CAPI = STK_SHARES * PCLS');
			
			$this->db->query('UPDATE TMP_1 A, VNDB_PRICES_HISTORY_DIV_YEAR B SET A.YIELD = B.YIELD WHERE A.STK_CODE = B.TICKER AND LEFT(A.YYYYMM,4) = B.YYYY');
			
			$this->db->query('DROP TABLE IF EXISTS TMP_2');

			$this->db->query('SET @num = 0');

			$this->db->query('SET @plr = NULL');
			
			$this->db->query('CREATE TABLE TMP_2 
			
			SELECT * FROM
			(
			SELECT *,
			@num := IF(@plr=yyyymm, @num + 1, 1) AS row_number,
			@plr := yyyymm AS dummy
			FROM TMP_1
			ORDER BY yyyymm, yield DESC
			) a
			WHERE row_number <=25');
			
			$this->db->query('DROP TABLE IF EXISTS TMP_3');
			
			$this->db->query('CREATE TABLE TMP_3 SELECT LEFT(YYYYMM,4) AS `YEAR`, SUM(YIELD) AS SUM_YIELD, SUM(CAPI) AS SUM_CAPI from tmp_2 GROUP BY LEFT(YYYYMM,4)');
			
			$this->db->query('UPDATE TMP_2 A, TMP_3 B SET A.SUM_YIELD = B.SUM_YIELD, A.SUM_CAPI = B.SUM_CAPI WHERE LEFT(A.YYYYMM,4) = B.`YEAR`');
			
			$this->db->query('UPDATE TMP_2 SET RATIO = YIELD/SUM_YIELD, STK_CAPP = (RATIO*SUM_CAPI)/CAPI, WGT = ((CAPI*STK_CAPP)/SUM_CAPI)*100, IDX_CODE = "VNX25HD", STK_FLOAT = 100');
			
			$this->db->query('DROP TABLE IF EXISTS QUIDX_COMPO_YIELD');
			
			$this->db->query('CREATE TABLE QUIDX_COMPO_YIELD SELECT stk_code, stk_name, stk_curr, stk_shares, stk_float, stk_capp, idx_code, start_date, end_date FROM TMP_2');
			
			$this->export('QUIDX_COMPO_YIELD','QUIDX_COMPO_YIELD');
			
			$total = microtime(true) - $from;
            $response[0]['time'] = round($total, 2);
            $response[0]['task'] = 'High Dividend';
            echo json_encode($response);
		}
    }
	
	public function process_low_volalitily() {
		if($this->input->is_ajax_request()){
			header('Content-Type: text/html; charset=utf-8');
			$from = microtime(true);
            set_time_limit(0);
			$this->db->query('TRUNCATE TABLE VNDB_VOLATILITY_YEAR');
			$dir = '//LOCAL/IFRCVN/VNDB/DATA/';
			$files = glob($dir . "*.csv");
			foreach ($files as $base) {
				$filename = basename($base, ".csv");
				if ($filename == 'VNDB_VOLATILITY_YEAR') {
					//$base_url = str_replace("\\", "\\\\", $base);
					$this->db->query("LOAD DATA LOCAL INFILE '".$base."' INTO TABLE VNDB_VOLATILITY_YEAR FIELDS TERMINATED BY '\t' IGNORE 1 LINES (STK_CODE, `YEAR`, VOLATILITY)");
				}
			}
			
			$this->db->query('UPDATE VNDB_VOLATILITY_YEAR SET STK_CODE = REPLACE(STK_CODE,"\"","")');
			
			$this->import_tmp();
			
			$this->db->query('UPDATE TMP A, VNDB_PRICES_HISTORY B SET A.PCLS = B.LAST WHERE A.STK_CODE = B.TICKER AND A.START_DATE = B.DATE AND (A.PCLS = 0 OR A.PCLS IS NULL)');
			
			$this->db->query('UPDATE TMP SET RATIO = 999999');
			
			$this->db->query('UPDATE TMP A, VNDB_VOLATILITY_YEAR B SET A.RATIO = (1/B.VOLATILITY) WHERE A.STK_CODE = B.STK_CODE AND LEFT(A.YYYYMM,4) = B.`YEAR`');
			
			$this->db->query('DROP TABLE IF EXISTS TMP_1');
			
			$this->db->query('CREATE TABLE TMP_1 SELECT yyyymm, stk_code, STK_NAME, stk_curr, stk_shares, pcls, capi, stk_float, idx_code, start_date, end_date, dividend, yield, sum_yield, ratio, sum_capi, 
			stk_capp, wgt FROM TMP WHERE IDX_CODE = "VNX50VND"');
			
			$this->db->query('UPDATE TMP_1 SET YYYYMM = CONCAT(YEAR(start_date),MONTH(start_date))');
			
			$this->db->query('UPDATE TMP_1 SET CAPI = STK_SHARES * PCLS');
			
			$this->db->query('DROP TABLE IF EXISTS TMP_2');

			$this->db->query('SET @num = 0');

			$this->db->query('SET @plr = NULL');
			
			$this->db->query('CREATE TABLE TMP_2 
			
			SELECT * FROM
			(
			SELECT *,
			@num := IF(@plr=yyyymm, @num + 1, 1) AS row_number,
			@plr := yyyymm AS dummy
			FROM TMP_1
			WHERE RATIO <> 0
			ORDER BY yyyymm, RATIO ASC
			) a
			WHERE row_number <=25');
			
			$this->db->query('DROP TABLE IF EXISTS TMP_3');
			
			$this->db->query('CREATE TABLE TMP_3 SELECT LEFT(YYYYMM,4) AS `YEAR`, SUM(RATIO) AS SUM_YIELD, SUM(CAPI) AS SUM_CAPI from tmp_2 GROUP BY LEFT(YYYYMM,4)');
			
			$this->db->query('UPDATE TMP_2 A, TMP_3 B SET A.SUM_YIELD = B.SUM_YIELD, A.SUM_CAPI = B.SUM_CAPI WHERE LEFT(A.YYYYMM,4) = B.`YEAR`');
			
			$this->db->query('UPDATE TMP_2 SET STK_FLOAT = 100, WGT = (RATIO/SUM_YIELD)*100, IDX_CODE = "VNX25LV"');
			
			$this->db->query('UPDATE TMP_2 SET STK_CAPP = (WGT*SUM_CAPI)/CAPI');
			
			$this->db->query('SELECT YYYYMM, SUM(WGT) from TMP_2 GROUP BY YYYYMM');
			
			$this->db->query('DROP TABLE IF EXISTS QUIDX_COMPO_VOLAT');
			
			$this->db->query('CREATE TABLE QUIDX_COMPO_VOLAT SELECT stk_code, stk_name, stk_curr, stk_shares, stk_float, stk_capp, idx_code, start_date, end_date FROM TMP_2');
			
			$this->export('QUIDX_COMPO_VOLAT','QUIDX_COMPO_LOW_VOLAT');
			  
			$total = microtime(true) - $from;
            $response[0]['time'] = round($total, 2);
            $response[0]['task'] = 'Low Volalitily';
            echo json_encode($response);
		}
    }
	
	public function process_hight_volalitily() {
		if($this->input->is_ajax_request()){
			$from = microtime(true);
            set_time_limit(0);
			$this->db->query('TRUNCATE TABLE VNDB_VOLATILITY_YEAR');
			$dir = '//LOCAL/IFRCVN/VNDB/DATA/';
			$files = glob($dir . '*csv');
			foreach ($files as $base) {
				$filename = basename($base, ".csv");
				if ($filename == 'VNDB_VOLATILITY_YEAR') {
					//$base_url = str_replace("\\", "\\\\", $base);
					$this->db->query("LOAD DATA LOCAL INFILE '".$base."' INTO TABLE VNDB_VOLATILITY_YEAR FIELDS TERMINATED BY '\t' IGNORE 1 LINES 	(STK_CODE, `YEAR`, VOLATILITY)");
				}
			}
			
			$this->db->query('UPDATE VNDB_VOLATILITY_YEAR SET STK_CODE = REPLACE(STK_CODE,"\"","")');

			$this->import_tmp();
			
			$this->db->query('UPDATE TMP A, VNDB_PRICES_HISTORY B SET A.PCLS = B.LAST WHERE A.STK_CODE = B.TICKER AND A.START_DATE = B.DATE AND (A.PCLS = 0 OR A.PCLS IS NULL)');
			
			$this->db->query('UPDATE TMP SET RATIO = 999999');
			
			$this->db->query('UPDATE TMP A, VNDB_VOLATILITY_YEAR B SET A.RATIO = 1/(B.VOLATILITY) WHERE A.STK_CODE = B.STK_CODE AND LEFT(A.YYYYMM,4) = B.`YEAR`');
			
			$this->db->query('DROP TABLE IF EXISTS TMP_1');
			
			$this->db->query('CREATE TABLE TMP_1 SELECT yyyymm, stk_code, STK_NAME, stk_curr, stk_shares, pcls, capi, stk_float, idx_code, start_date, end_date, dividend, yield, sum_yield, ratio, sum_capi, 
			stk_capp, wgt FROM TMP WHERE IDX_CODE = "VNX50VND"');
			
			$this->db->query('UPDATE TMP_1 SET YYYYMM = CONCAT(YEAR(start_date),MONTH(start_date))');
			
			$this->db->query('UPDATE TMP_1 SET CAPI = STK_SHARES * PCLS');
			
			$this->db->query('DROP TABLE IF EXISTS TMP_2');

			$this->db->query('SET @num = 0');
			
			$this->db->query('SET @plr = NULL');
			
			$this->db->query('CREATE TABLE TMP_2 
			
			SELECT * FROM
			(
			SELECT *,
			@num := IF(@plr=yyyymm, @num + 1, 1) AS row_number,
			@plr := yyyymm AS dummy
			FROM TMP_1
			WHERE RATIO <> 0
			ORDER BY yyyymm, RATIO DESC
			) a
			WHERE row_number <=25');
			
			$this->db->query('DROP TABLE IF EXISTS TMP_3');
			
			$this->db->query('CREATE TABLE TMP_3 SELECT LEFT(YYYYMM,4) AS `YEAR`, SUM(RATIO) AS SUM_YIELD, SUM(CAPI) AS SUM_CAPI from tmp_2 GROUP BY LEFT(YYYYMM,4)');
			
			$this->db->query('UPDATE TMP_2 A, TMP_3 B SET A.SUM_YIELD = B.SUM_YIELD, A.SUM_CAPI = B.SUM_CAPI WHERE LEFT(A.YYYYMM,4) = B.`YEAR`');
			
			$this->db->query('UPDATE TMP_2 SET STK_FLOAT = 100, WGT = (RATIO/SUM_YIELD)*100, IDX_CODE = "VNX25HV"');
			
			$this->db->query('UPDATE TMP_2 SET STK_CAPP = (WGT*SUM_CAPI)/CAPI');
			
			$this->db->query('SELECT YYYYMM, SUM(WGT) from TMP_2 GROUP BY YYYYMM');
			
			$this->db->query('DROP TABLE IF EXISTS QUIDX_COMPO_VOLAT');
			
			$this->db->query('CREATE TABLE QUIDX_COMPO_VOLAT SELECT stk_code, stk_name, stk_curr, stk_shares, stk_float, stk_capp, idx_code, start_date, end_date FROM TMP_2');
			
			$this->export('QUIDX_COMPO_VOLAT','QUIDX_COMPO_HIGHT_VOLAT');
			  
			$total = microtime(true) - $from;
            $response[0]['time'] = round($total, 2);
            $response[0]['task'] = 'High Volalitily';
            echo json_encode($response);
		}
    }
	
	public function process_equal_weighted_50() {
		if($this->input->is_ajax_request()){
			$from = microtime(true);
            set_time_limit(0);
			$this->import_tmp();
			
			$this->db->query('UPDATE TMP A, VNDB_PRICES_HISTORY B SET A.PCLS = B.LAST WHERE A.STK_CODE = B.TICKER AND A.START_DATE = B.DATE AND (A.PCLS = 0 OR A.PCLS IS NULL)');
			
			$this->db->query('DROP TABLE IF EXISTS TMP_1');
			
			$this->db->query('CREATE TABLE TMP_1 SELECT yyyymm, stk_code, STK_NAME, stk_curr, stk_shares, pcls, capi, stk_float, idx_code, start_date, end_date, dividend, yield, sum_yield, ratio, sum_capi, 
			stk_capp, wgt FROM TMP WHERE IDX_CODE = "VNX50VND"');
			
			$this->db->query('UPDATE TMP_1 SET YYYYMM = CONCAT(YEAR(start_date),MONTH(start_date))');
			
			$this->db->query('UPDATE TMP_1 A, VNDB_PRICES_HISTORY B SET A.DIVIDEND = B.DIVIDEND WHERE A.STK_CODE = B.TICKER AND A.START_DATE = B.DATE');
			
			$this->db->query('UPDATE TMP_1 SET CAPI = STK_SHARES * PCLS');
			
			$this->db->query('DROP TABLE IF EXISTS TMP_2');
			
			$this->db->query('CREATE TABLE TMP_2 SELECT LEFT(YYYYMM,4) AS `YEAR`, SUM(CAPI) AS SUM_CAPI from tmp_1 GROUP BY LEFT(YYYYMM,4)');
			
			$this->db->query('UPDATE TMP_1 A, TMP_2 B SET A.SUM_CAPI = B.SUM_CAPI WHERE LEFT(A.YYYYMM,4) = B.`YEAR`');
			
			$this->db->query('UPDATE TMP_1 SET STK_CAPP = SUM_CAPI/(50*CAPI), WGT = ((CAPI*STK_CAPP)/SUM_CAPI)*100, IDX_CODE = "VNX50EQVND", STK_FLOAT = 100');
			
			$this->db->query('DROP TABLE IF EXISTS QUIDX_COMPO_EQ');
			
			$this->db->query('CREATE TABLE QUIDX_COMPO_EQ SELECT stk_code, stk_name, stk_curr, stk_shares, stk_float, stk_capp, idx_code, start_date, end_date FROM TMP_1');
			
			$this->export('QUIDX_COMPO_EQ','QUIDX_COMPO_EQ_50');
			
			$total = microtime(true) - $from;
            $response[0]['time'] = round($total, 2);
            $response[0]['task'] = 'Equal Weighted 50';
            echo json_encode($response);
			
		}
    }
	
	public function process_equal_weighted_25() {
		if($this->input->is_ajax_request()){
			$from = microtime(true);
            set_time_limit(0);
			$this->import_tmp();
			
			$this->db->query('UPDATE TMP A, VNDB_PRICES_HISTORY B SET A.PCLS = B.LAST WHERE A.STK_CODE = B.TICKER AND A.START_DATE = B.DATE AND (A.PCLS = 0 OR A.PCLS IS NULL)');
			
			$this->db->query('DROP TABLE IF EXISTS TMP_1');
			
			$this->db->query('CREATE TABLE TMP_1 SELECT yyyymm, stk_code, STK_NAME, stk_curr, stk_shares, pcls, capi, stk_float, idx_code, start_date, end_date, dividend, yield, sum_yield, ratio, sum_capi, 
			stk_capp, wgt FROM TMP WHERE IDX_CODE = "VNX25VND"');
			
			$this->db->query('UPDATE TMP_1 SET YYYYMM = CONCAT(YEAR(start_date),MONTH(start_date))');
			
			$this->db->query('UPDATE TMP_1 A, VNDB_PRICES_HISTORY B SET A.DIVIDEND = B.DIVIDEND WHERE A.STK_CODE = B.TICKER AND A.START_DATE = B.DATE');
			
			$this->db->query('UPDATE TMP_1 SET CAPI = STK_SHARES * PCLS');
			
			$this->db->query('DROP TABLE IF EXISTS TMP_2');
			
			$this->db->query('CREATE TABLE TMP_2 SELECT LEFT(YYYYMM,4) AS `YEAR`, SUM(CAPI) AS SUM_CAPI from tmp_1 GROUP BY LEFT(YYYYMM,4)');
			
			$this->db->query('UPDATE TMP_1 A, TMP_2 B SET A.SUM_CAPI = B.SUM_CAPI WHERE LEFT(A.YYYYMM,4) = B.`YEAR`');
			
			$this->db->query('UPDATE TMP_1 SET STK_CAPP = SUM_CAPI/(50*CAPI), WGT = ((CAPI*STK_CAPP)/SUM_CAPI)*100, IDX_CODE = "VNX25EQVND", STK_FLOAT = 100');
			
			$this->db->query('DROP TABLE IF EXISTS QUIDX_COMPO_EQ');
			
			$this->db->query('CREATE TABLE QUIDX_COMPO_EQ SELECT stk_code, stk_name, stk_curr, stk_shares, stk_float, stk_capp, idx_code, start_date, end_date FROM TMP_1');
			
			$this->export('QUIDX_COMPO_EQ','QUIDX_COMPO_EQ_25');
			
			$total = microtime(true) - $from;
            $response[0]['time'] = round($total, 2);
            $response[0]['task'] = 'Equal Weighted 25';
            echo json_encode($response);
			
		}
    }

    public function import_tmp(){
		$this->db->query('DROP TABLE IF EXISTS TMP');

		$this->db->query('CREATE TABLE TMP SELECT stk_code, stk_name, "AAAAAAAAAAAAAAAAAA" as stk_curr, stk_shares_idx as stk_shares, stk_float_idx as stk_float, stk_capp_idx as stk_capp, 
idx_code, dates as start_date, "0000-00-00" as end_date, 999999999999999 as pcls, 999999999999999 as capi, 999999999999999 as yield, 999999999999999 as ratio, 
999999999999999 as sum_capi, 999999999999999 as sum_yield, 999999.99999999 AS wgt, 999999999999999 as dividend, 999999 as yyyymm FROM VIDX_COMPO');
		
		$this->db->query('TRUNCATE TABLE TMP');
		
		$this->db->query('ALTER TABLE TMP MODIFY pcls DOUBLE');
		$this->db->query('ALTER TABLE TMP MODIFY capi DOUBLE');
		$this->db->query('ALTER TABLE TMP MODIFY yield DOUBLE');
		$this->db->query('ALTER TABLE TMP MODIFY ratio DOUBLE');
		$this->db->query('ALTER TABLE TMP MODIFY sum_capi DOUBLE');
		$this->db->query('ALTER TABLE TMP MODIFY sum_yield DOUBLE');
		$this->db->query('ALTER TABLE TMP MODIFY dividend DOUBLE');
		$this->db->query('ALTER TABLE TMP MODIFY end_date DATE');
		
		$this->db->query('CREATE INDEX TICKERDATE ON TMP (STK_CODE,START_DATE) USING BTREE');
		
		$dir = '//LOCAL/IFRCVN/VNDB/HISTORY/';
        $files = glob($dir . '*.txt');
        foreach ($files as $base) {
            $filename = basename($base, ".txt");
            if ($filename == 'QIDX_COMPO_INPUT') {
                //$base_url = str_replace("\\", "\\\\", $base);
                $this->db->query("LOAD DATA LOCAL INFILE '".$base."' INTO TABLE TMP FIELDS TERMINATED BY '\t' IGNORE 1 LINES");
            }
        }
        $this->db->query("UPDATE `tmp` a, (SELECT ticker, end_date, date FROM 
			(SELECT stk_code, end_date, YEAR(`end_date`) - 1 as prev_year FROM `tmp`)A,
			(SELECT ticker,MAX(date) as date FROM vndb_prices_history c WHERE 
			YEAR(date) IN (
			SELECT YEAR(`end_date`) - 1 as prev_year FROM `tmp` d GROUP BY YEAR(`end_date`)
			) GROUP BY ticker, YEAR(`date`))B
			WHERE A.stk_code = B.ticker AND A.prev_year = YEAR(B.date)) b SET a.start_date = b.date 
			WHERE a.stk_code = b.ticker AND a.end_date = b.end_date AND YEAR(a.start_date) <> 2008");

        $this->db->query('UPDATE TMP SET YYYYMM = CONCAT(YEAR(start_date),MONTH(start_date))');
	}
	
	public function export($table,$name){
		$dir = '\\\LOCAL\IFRCVN\VNDB\HISTORY\\';
		$data = $this->db->query("SELECT * FROM ".$table)->result_array();
		$implode = array();
		foreach ($data as $item) {
			$header = array_keys($item);
			$implode[] = implode("\t", $item);
		}
		$header = implode("\t", $header);
		$implode = implode("\n", $implode);
		$file = $header . "\r\n";
		$file .= $implode;
		$filename = $dir.$name.".txt";
		$create = fopen($filename, "w");
		$write = fwrite($create, $file);
		fclose($create);
	}
}