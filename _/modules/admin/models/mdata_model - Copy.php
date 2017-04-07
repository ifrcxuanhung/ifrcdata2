<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Mdata_model extends CI_Model {

    function __construct() {
        parent::__construct();
        set_time_limit(0);
    }
    
    function create(){
        $this->db->query("TRUNCATE TABLE qidx_adjcls");
        $this->db->query("OPTIMIZE TABLE qidx_adjcls");
        $this->db->query("LOAD DATA INFILE '////LOCAL//IFRCDATA//VNDB//ADJCLS//QIDX_MDATA.txt' INTO TABLE qidx_adjcls FIELDS TERMINATED BY  '\t' LINES TERMINATED BY  '\r\n' IGNORE 1 LINES (ticker,market,cur,yyyymmdd,date,shares,close,divtr)");
        $this->db->query("UPDATE qidx_adjcls SET adjcoeff = 1");
        $this->db->query("UPDATE qidx_adjcls as a,vndb_event2_cph as b SET a.adjcoeff = b.adjcoeff WHERE a.ticker = b.ticker AND a.date = b.date_ex");
        $this->db->query("UPDATE qidx_adjcls as a,vndb_event3_cph as b SET a.adjcoeff = b.adjcoeff WHERE a.ticker = b.ticker AND a.date = b.date_ex");
        $this->db->query("UPDATE qidx_adjcls SET adjcoeffc = adjcoeff, adjclose = close");
    }
    
    function calculation(){
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
        $this->db->query("SELECT 
a.ticker, a.prev_date, 
(@source := a.prev_adjcoeff ) source, 
IF(@plr = a.ticker, @total :=  @total, @total := a.prev_adjcoeff) AS tmp, 
IF(@plr = a.ticker, if(@source is null, @total := 1, @total :=  @total * @source), @total := a.prev_adjcoeff) AS total, 
@plr := a.ticker AS dummy 
FROM TMP_MINH a, (SELECT @source:='0', @total:= '1')b");
        $this->db->query("CREATE TABLE TMP_MINH1
SELECT 
a.ticker, a.prev_date, 
(@source := a.prev_adjcoeff ) source, 
IF(@plr = a.ticker, @total :=  @total, @total := a.prev_adjcoeff) AS tmp, 
IF(@plr = a.ticker, if(@source is null, @total := 1, @total :=  @total * @source), @total := a.prev_adjcoeff) AS total, 
@plr := a.ticker AS dummy 
FROM TMP_MINH a, (SELECT @source:='0', @total:= '1')b");
        $this->db->query("UPDATE qidx_adjcls_bk A, TMP_MINH1 B SET A.ADJCOEFFC = B.TOTAL WHERE A.TICKER = B.TICKER AND A.DATE = B.PREV_DATE");
        $this->db->query("UPDATE qidx_adjcls_bk A, qidx_adjcls_bk B SET A.NEXT_ADJCOEFFC = B.ADJCOEFFC WHERE A.NEXT_DATE = B.DATE AND A.TICKER = B.TICKER"); 
        $this->db->query("UPDATE qidx_adjcls_bk SET ADJCLOSE = ROUND(CLOSE * NEXT_ADJCOEFFC,12)");
    }
    
    function update_dividend(){
        $this->db->query("INSERT INTO vndb_dividends_history (ticker,market, date,yyyymmdd,dividend) (SELECT ticker,market, date_ex, DATE_FORMAT(`date_ex`,'%Y%m%d') as yyyymmdd,
 dividend FROM vndb_dividends_final WHERE concat(ticker,date_ex) NOT IN (SELECT concat(ticker,date) FROM vndb_dividends_history))");
        $this->db->query("UPDATE vndb_prices_history as a, vndb_dividends_history as b SET a.dividend = b.dividend where a.ticker = b.ticker AND a.market = b.market AND a.date = b.date");
    }
    
    function update(){
        $this->db->query("UPDATE qidx_adjcls a, qidx_adjcls_bk b 
SET a.adjcoeffc = b.adjcoeffc, a.adjclose = b.adjclose, a.rt = b.rt, a.rtd = b.rtd 
WHERE a.ticker = b.ticker and a.date = b.date");
        $this->db->query("UPDATE vndb_prices_history SET adj_pcls = NULL");
        $this->db->query("UPDATE vndb_prices_history a, qidx_adjcls b SET a.adj_pcls = b.adjclose, a.adj_coeff = b.adjcoeff, a.rt = b.rt, a.rtd = b.rtd WHERE a.ticker = b.ticker AND a.date = b.date AND a.market = b.market");
    }
    
    function export($path){
        $base_url = str_replace('/', '//', $path);
        $this->db->query("SELECT 'ticker' AS `STK_CODE`, 'market' AS `MARKET`, 'yyyymmdd' AS `YYYYMMDD`, 'date' AS `DATE`, 'shares' AS `SHARES`, 'close' AS `CLOSE`, 'divtr' AS `DIVTR`, 'divnr' AS `DIVNR`, 'adjcoeff' AS `ADJCOEFF`, 'adjcoeffc' AS `ADJCOEFFC`, 'adjclose' AS `ADJCLOSE`, 'rt' AS `RT`, 'rtd' AS `RTD`
UNION
select ticker, market, yyyymmdd, DATE_FORMAT(date, '%Y/%m/%d'), if(shares is NULL,'',shares), if(close is NULL,'',close), if(divtr is NULL,'',divtr), if(divnr is NULL,'',divnr), if(adjcoeff is NULL,'',adjcoeff), if(adjcoeffc is NULL,'',adjcoeffc), if(adjclose is NULL,'',adjclose), if(rt is NULL,'',rt), if(rtd is NULL,'',rtd)
from qidx_adjcls 
INTO OUTFILE '{$base_url}'
FIELDS TERMINATED BY '\t'
LINES TERMINATED BY '\r\n'");
    }
    
    function export_qidx($path){
        $base_url = str_replace('/', '//', $path);
        $this->db->query("SELECT 'ticker' AS `ticker`, 'market' AS `market`,'cur' AS `cur`, 'yyyymmdd' AS `yyyymmdd`, 'date' AS `date`, 'shares' AS `shares`,
'close' AS `close`,'divtr' as `divtr`
UNION
select `ticker`,`market`,'' as cur,`yyyymmdd`, DATE_FORMAT(`date`, '%Y/%m/%d') AS `date`,if(shli is NULL,'',shli) as shares,if(last is NULL,'',last) as `close`,if(dividend is NULL,'',dividend) as divtr from vndb_prices_history 
where date>='2008-12-31' and LENGTH(ticker) = 3 and market <> 'UPC'
INTO OUTFILE '{$base_url}'
FIELDS TERMINATED BY '\t'
LINES TERMINATED BY '\r\n'");
    }
    
    function update_date_diff(){
        $this->db->query("INSERT INTO VNDB_PRICES_HISTORY (SOURCE, TICKER, MARKET, DATE, YYYYMMDD, SHLI, SHOU, SHFN, PREF, PCEI, PFLR, POPN, PHGH, PLOW, PBASE, PAVG, PCLS, VLM, TRN, LAST) (SELECT A.SOURCE, A.TICKER, A.MARKET, A.DATE, A.YYYYMMDD, A.SHLI, A.SHOU, A.SHFN, A.PREF, A.PCEI, A.PFLR, A.POPN, A.PHGH, A.PLOW, A.PBASE, A.PAVG, A.PCLS, A.VLM, A.TRN, A.LAST FROM VNDB_DAILY A WHERE NOT EXISTS(SELECT 1 FROM VNDB_PRICES_HISTORY B WHERE B.DATE = A.DATE))");
        $this->db->query("UPDATE VNDB_PRICES_HISTORY A, VNDB_DAILY B SET A.SHLI = B.SHLI, A.SHOU = B.SHOU WHERE A.TICKER = B.TICKER AND A.DATE = B.DATE AND A.MARKET = B.MARKET AND (A.SHLI = 0 OR A.SHOU = 0)");
    }
    
    function get_setting(){
        $this->db->where('key','price_history');
        return $this->db->get('setting')->row_array();
    }
    
    function update_event(){
        $this->db->query("TRUNCATE TABLE vndb_event2_cph");
        $this->db->query("LOAD DATA INFILE '////LOCAL//IFRCDATA//VNDB//EVENTS//CPH_EVENTS2.TXT' INTO TABLE vndb_event2_cph FIELDS TERMINATED BY  '\t' IGNORE 1 LINES (source,evtname,ticker,date_ex,date_eff,ratio,sharesbef,sharesadd,sharesaft,pref,oldns,newns)");
        $this->db->query("UPDATE vndb_event2_cph SET adjcoeff= oldns/(oldns+newns)");
        $this->db->query("UPDATE vndb_event2_cph SET prv_date = NULL");
        $this->db->query("UPDATE vndb_event2_cph as a, bkh_calendar as b set a.prv_date=b.prv_date where a.date_ex=b.date and b.prv_date is not Null");
        $this->db->query("UPDATE vndb_event2_cph as a, qidx_mdata_day as b set a.prv_close = b.`close` where a.prv_date=b.date and a.ticker=b.ticker");
        $this->db->query("UPDATE vndb_event2_cph SET adjclose=prv_close* adjcoeff");
        $this->db->query("UPDATE vndb_event2_cph SET `right`= prv_close-adjclose");

        $this->db->query("TRUNCATE TABLE vndb_event3_cph");
        $this->db->query("LOAD DATA INFILE '////LOCAL//IFRCDATA//VNDB//EVENTS//CPH_EVENTS3.TXT' INTO TABLE VNDB_EVENT3_CPH FIELDS TERMINATED BY  '\t'  LINES TERMINATED BY  '\n' IGNORE 1 LINES (source,evtname,ticker,date_ex,date_eff,ratio,sharesbef,sharesadd,sharesaft,pref,oldns,newns,eprice)");
        $this->db->query("UPDATE VNDB_EVENT3_CPH set prv_date = NULL");
        $this->db->query("UPDATE VNDB_EVENT3_CPH as a, bkh_calendar as b SET a.prv_date=b.prv_date where a.date_ex=b.date and b.prv_date is not Null");
        $this->db->query("UPDATE VNDB_EVENT3_CPH as a, qidx_mdata_day as b SET a.prv_close = b.`close` where a.prv_date=b.date and a.ticker=b.ticker");
        $this->db->query("UPDATE VNDB_EVENT3_CPH SET `RIGHT` = IF(PRV_CLOSE <> '',IF((PRV_CLOSE-EPRICE)>0,(PRV_CLOSE-EPRICE)*NEWNS/(NEWNS+OLDNS),NULL),NULL), ADJCLOSE = IF(`RIGHT` REGEXP '[[:digit:]]' AND `RIGHT`<>0, PRV_CLOSE-`RIGHT`, NULL), ADJCOEFF = IF(`RIGHT` REGEXP '[[:digit:]]' AND `RIGHT`<>0, ADJCLOSE/PRV_CLOSE, NULL)");
    }
}
