<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Mdata_model extends CI_Model {

    function __construct() {
        parent::__construct();
        set_time_limit(0);
    }

    function create() {
        $this->db->query("TRUNCATE TABLE qidx_adjcls");
        $this->db->query("OPTIMIZE TABLE qidx_adjcls");
        $this->db->query("LOAD DATA LOCAL INFILE '//LOCAL/IFRCVN/VNDB/ADJCLS/QIDX_MDATA.txt' INTO TABLE qidx_adjcls FIELDS TERMINATED BY  '\t' LINES TERMINATED BY  '\r\n' IGNORE 1 LINES (ticker,market,cur,yyyymmdd,date,shares,close,divtr)");
        $this->db->query("UPDATE qidx_adjcls SET adjcoeff = 1");
        $this->db->query("TRUNCATE TABLE vndb_adjcoeff_final");
        $this->db->query("LOAD DATA LOCAL INFILE '//LOCAL/IFRCVN/VNDB/ADJCLS/VNDB_ADJCOEFF.txt' INTO TABLE vndb_adjcoeff_final FIELDS TERMINATED BY  '\t' LINES TERMINATED BY  '\r\n' IGNORE 1 LINES (ticker, date, dividend, adjcoeff)");
        $this->db->query("UPDATE qidx_adjcls as a,vndb_adjcoeff_final as b SET a.adjcoeff = b.adjcoeff WHERE a.ticker = b.ticker AND a.date = b.date AND b.adjcoeff <>0 ");
        /*$this->db->query("UPDATE qidx_adjcls as a,vndb_event2_cph as b SET a.adjcoeff = b.adjcoeff WHERE a.ticker = b.ticker AND a.date = b.date_ex and b.adjcoeff is not null");
        $this->db->query("UPDATE qidx_adjcls as a,vndb_event3_cph as b SET a.adjcoeff = b.adjcoeff WHERE a.ticker = b.ticker AND a.date = b.date_ex and b.adjcoeff is not null");*/
        $this->db->query("UPDATE qidx_adjcls SET adjcoeffc = adjcoeff, adjclose = close");
    }

    function calculation() {
        $this->db->query("TRUNCATE TABLE qidx_adjcls_bk");
        $this->db->query("OPTIMIZE TABLE qidx_adjcls_bk");
        $this->db->query("INSERT INTO qidx_adjcls_bk (TICKER, DATE, SHARES, CLOSE, DIVTR, ADJCOEFF) SELECT TICKER, DATE, SHARES, CLOSE, DIVTR, ADJCOEFF FROM qidx_adjcls");
        $this->db->query("UPDATE qidx_adjcls_bk A, BKH_CALENDAR B SET A.NEXT_DATE = B.NXT_DATE, A.PREV_DATE = B.PRV_DATE WHERE A.DATE = B.DATE");
        $this->db->query("UPDATE qidx_adjcls_bk set adjcoeffc = adjcoeff, adjclose = close, divtr = if(divtr is null,0,divtr)");
        $this->db->query("UPDATE qidx_adjcls_bk A, qidx_adjcls_bk B SET A.PREV_CLOSE = B.CLOSE, A.PREV_ADJCOEFF = B.ADJCOEFF WHERE A.PREV_DATE = B.DATE AND A.TICKER = B.TICKER");
        $this->db->query("UPDATE qidx_adjcls_bk SET RT = (CLOSE / (PREV_CLOSE * ADJCOEFF))-1, RTD = ((CLOSE+(DIVTR*ADJCOEFF))/(PREV_CLOSE*ADJCOEFF))-1 WHERE PREV_CLOSE IS NOT NULL");
        $this->db->query("DROP TABLE IF EXISTS TMP_MINH");
        $this->db->query("CREATE TABLE TMP_MINH SELECT * FROM qidx_adjcls_bk ORDER BY TICKER, DATE DESC");
        $this->db->query("DROP TABLE IF EXISTS TMP_MINH1");
		$this->db->query("SET @source:= null");
		$this->db->query("SET @total:=  1");
		$this->db->query("SELECT 
a.ticker, a.prev_date, 
(@source := a.prev_adjcoeff ) source, 
IF(@plr = a.ticker, @total =  @total, @total := a.prev_adjcoeff) AS tmp, 
IF(@plr = a.ticker, if(@source is null, @total := 1, @total :=  @total * @source), @total := a.prev_adjcoeff) AS total, 
@plr := a.ticker AS dummy 
FROM TMP_MINH a");
		$this->db->query("SET @source:= null");
		$this->db->query("SET @total:=  1");
        $this->db->query("CREATE TABLE TMP_MINH1
SELECT 
a.ticker, a.prev_date, 
(@source := a.prev_adjcoeff ) source, 
IF(@plr = a.ticker, @total =  @total, @total := a.prev_adjcoeff) AS tmp, 
IF(@plr = a.ticker, if(@source is null, @total := 1, @total :=  @total * @source), @total := a.prev_adjcoeff) AS total, 
@plr := a.ticker AS dummy 
FROM TMP_MINH a");
        $this->db->query("UPDATE qidx_adjcls_bk A, TMP_MINH1 B SET A.ADJCOEFFC = B.TOTAL WHERE A.TICKER = B.TICKER AND A.DATE = B.PREV_DATE");
        $this->db->query("UPDATE qidx_adjcls_bk A, qidx_adjcls_bk B SET A.NEXT_ADJCOEFFC = B.ADJCOEFFC WHERE A.NEXT_DATE = B.DATE AND A.TICKER = B.TICKER");
        $this->db->query("UPDATE qidx_adjcls_bk SET ADJCLOSE = if(next_date is not NULL,ROUND(CLOSE * NEXT_ADJCOEFFC,12),close)");
        $this->db->query("UPDATE qidx_adjcls a, qidx_adjcls_bk b SET a.adjcoeffc = b.adjcoeffc, a.adjclose = b.adjclose, a.rt = b.rt, a.rtd = b.rtd 
WHERE a.ticker = b.ticker and a.date = b.date");
        $this->db->query("UPDATE qidx_adjcls set adjclose=close where adjclose is NULL");
    }

    function update_dividend() {
        $this->db->query("INSERT INTO vndb_dividends_history (ticker,market, date,yyyymmdd,dividend) (SELECT ticker,market, date_ex, DATE_FORMAT(`date_ex`,'%Y%m%d') as yyyymmdd,
 dividend FROM vndb_dividends_final WHERE concat(ticker,date_ex) NOT IN (SELECT concat(ticker,date) FROM vndb_dividends_history))");
     $this->db->query("INSERT INTO vndb_dividends_history (ticker,market, date,yyyymmdd,dividend) (SELECT ticker,market, date, DATE_FORMAT(`date`,'%Y%m%d') as yyyymmdd,
 amount FROM vndb_dividends_missing WHERE concat(ticker,date) NOT IN (SELECT concat(ticker,date) FROM vndb_dividends_history))");
        $this->db->query("UPDATE vndb_prices_history as a, vndb_dividends_history as b SET a.dividend = b.dividend where a.ticker = b.ticker AND a.date = b.date");
        /*Update thêm t? table vndb_adjcoeff_final */
        $this->db->query("UPDATE vndb_prices_history as a, vndb_adjcoeff_final as b SET a.dividend = b.dividend where a.ticker = b.ticker AND a.date = b.date");
    }

    function update() {
        $this->db->query("UPDATE vndb_prices_history SET adj_pcls = NULL");
        $this->db->query("UPDATE vndb_prices_history a, qidx_adjcls b SET a.adj_pcls = b.adjclose, a.adj_coeff = b.adjcoeff, a.rt = b.rt, a.rtd = b.rtd WHERE a.ticker = b.ticker AND a.date = b.date AND a.market = b.market");
    }

    function export($path, $file) {
        ini_set('memory_limit', '1024M');
        $header = 'STK_CODE'."\t".'MARKET'."\t".'YYYYMMDD'."\t".'DATE'."\t".'STK_SHARES'."\t".'CLOSE'."\t".'DIVTR'."\t".'DIVNR'."\t".'ADJCOEFF'."\t".'ADJCOEFFC'."\t".'ADJCLOSE'."\t".'RT'."\t".'RTD';
        //$header .= "\r\n";
        $file_name = $path.$file;
        $create = fopen($file_name, "w");
        fwrite($create, $header);
        fclose($create);
        $total_row = $this->db->count_all('qidx_adjcls');
        for($i=0; $i<=$total_row; $i = $i+10000){  
            $start = $i;
            $end = 10000;
            $data_result = $this->db->query("select ticker, market, yyyymmdd, DATE_FORMAT(date, '%Y/%m/%d') as `date`, if(shares is NULL,'',shares), if(close is NULL,'',close), if(divtr is NULL,'',divtr), if(divnr is NULL,'',divnr), if(adjcoeff is NULL,'',adjcoeff), if(adjcoeffc is NULL,'',adjcoeffc), if(adjclose is NULL,'',adjclose), if(rt is NULL,'',rt), if(rtd is NULL,'',rtd) from qidx_adjcls  where close<>0  limit $start, $end")->result_array();
            $data_dr = array();
            //$data_import = implode("\r\n");
            $create = fopen($file_name, "a");
            fwrite($create,"\r\n");
            foreach($data_result as $dr){
                $data_dr[] = implode("\t",$dr);
            }
            $data_import = implode("\r\n",$data_dr);
            fwrite($create, $data_import);
            fclose($create);
        }
        
    }

    function export_qidx($path = "", $file_name = "") {
		ini_set('memory_limit', '1024M');
        $sql = "select `ticker`,`market`,'' as cur,`yyyymmdd`, DATE_FORMAT(`date`, '%Y/%m/%d') AS `date`,if(date>='2010-12-31',shou,shli) as shares,if(last is NULL,NULL,last) as `close`,if(dividend is NULL,NULL,dividend) as divtr
                from vndb_prices_history 
                where LENGTH(ticker) = 3 and market <> 'UPC';";
        $arr = $this->db->query($sql)->result_array();
        if (is_array($arr) && count($arr) > 0) {
            /* export qidx_mdata */
			$headers = array('ticker', 'market', 'cur', 'yyyymmdd', 'date', 'shares', 'close', 'divtr');
            export_file($path, $file_name, $headers, $arr);
            /* end export qidx_mdata */
        }
    }

    function update_date_diff() {
        $this->db->query("INSERT INTO VNDB_PRICES_HISTORY (SOURCE, TICKER, MARKET, DATE, YYYYMMDD, SHLI, SHOU, SHFN, PREF, PCEI, PFLR, POPN, PHGH, PLOW, PBASE, PAVG, PCLS, VLM, TRN, LAST) (SELECT A.SOURCE, A.TICKER, A.MARKET, A.DATE, A.YYYYMMDD, A.SHLI, A.SHOU, A.SHFN, A.PREF, A.PCEI, A.PFLR, A.POPN, A.PHGH, A.PLOW, A.PBASE, A.PAVG, A.PCLS, A.VLM, A.TRN, A.LAST FROM VNDB_DAILY A WHERE NOT EXISTS(SELECT 1 FROM VNDB_PRICES_HISTORY B WHERE B.DATE = A.DATE))");
        $this->db->query("UPDATE VNDB_PRICES_HISTORY A, VNDB_DAILY B SET A.SHLI = B.SHLI, A.SHOU = B.SHOU WHERE A.TICKER = B.TICKER AND A.DATE = B.DATE AND A.MARKET = B.MARKET AND ( A.SHOU is null OR A.SHLI is null OR A.SHLI = 0 OR A.SHOU = 0)");
    }

    function get_setting() {
        $this->db->where('key', 'price_history');
        return $this->db->get('setting')->row_array();
    }

    function update_event() {
        $this->db->query("TRUNCATE TABLE vndb_event2_cph");
        $this->db->query("LOAD DATA LOCAL INFILE '//LOCAL/IFRCVN/VNDB/ADJCLS/CPH/CPH_EVENTS2.TXT' INTO TABLE vndb_event2_cph FIELDS TERMINATED BY  '\t' IGNORE 1 LINES (source,evtname,ticker,date_ex,date_eff,ratio,sharesbef,sharesadd,sharesaft,pref,oldns,newns)");
        $this->db->query("UPDATE vndb_event2_cph SET adjcoeff= oldns/(oldns+newns)");
        $this->db->query("UPDATE vndb_event2_cph SET prv_date = NULL");
        $this->db->query("UPDATE vndb_event2_cph as a, bkh_calendar as b set a.prv_date=b.prv_date where a.date_ex=b.date and b.prv_date is not Null");
        $this->db->query("UPDATE vndb_event2_cph as a, vndb_prices_history as b set a.prv_close = b.last where a.prv_date=b.date and a.ticker=b.ticker");
        $this->db->query("UPDATE vndb_event2_cph SET adjclose=prv_close* adjcoeff");
        $this->db->query("UPDATE vndb_event2_cph SET `right`= prv_close-adjclose");

        $this->db->query("TRUNCATE TABLE vndb_event3_cph");
        $this->db->query("LOAD DATA LOCAL INFILE '//LOCAL/IFRCVN/VNDB/ADJCLS/CPH/CPH_EVENTS3.TXT' INTO TABLE VNDB_EVENT3_CPH FIELDS TERMINATED BY  '\t'  LINES TERMINATED BY  '\n' IGNORE 1 LINES (source,evtname,ticker,date_ex,date_eff,ratio,sharesbef,sharesadd,sharesaft,pref,oldns,newns,eprice)");
        $this->db->query("UPDATE VNDB_EVENT3_CPH set prv_date = NULL");
        $this->db->query("UPDATE VNDB_EVENT3_CPH as a, bkh_calendar as b SET a.prv_date=b.prv_date where a.date_ex=b.date and b.prv_date is not Null");
        $this->db->query("UPDATE VNDB_EVENT3_CPH as a, vndb_prices_history as b SET a.prv_close = b.last where a.prv_date=b.date and a.ticker=b.ticker");
        $this->db->query("UPDATE VNDB_EVENT3_CPH SET `RIGHT` = IF(PRV_CLOSE <> '',IF((PRV_CLOSE-EPRICE)>0,(PRV_CLOSE-EPRICE)*NEWNS/(NEWNS+OLDNS),NULL),NULL), ADJCLOSE = IF(`RIGHT` REGEXP '[[:digit:]]' AND `RIGHT`<>0, PRV_CLOSE-`RIGHT`, NULL), ADJCOEFF = IF(`RIGHT` REGEXP '[[:digit:]]' AND `RIGHT`<>0, ADJCLOSE/PRV_CLOSE, NULL)");
    }

}
