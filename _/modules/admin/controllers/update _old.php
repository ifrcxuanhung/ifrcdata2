<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/* * ******************************************************************************************************************* *
 *   Author: Minh                                                                                                        *
 * * ******************************************************************************************************************* */

class Update extends Admin {

    public function __construct()
    {
        parent::__construct();
    }

    public function compo()
    {
        $this->template->write_view('content', 'update/compo', $this->data);
        $this->template->write('title', 'Compo');
        $this->template->render();
    }

    public function specs()
    {
        $this->template->write_view('content', 'update/specs', $this->data);
        $this->template->write('title', 'Specs');
        $this->template->render();
    }

    public function vnx()
    {
        $this->template->write_view('content', 'update/vnx', $this->data);
        $this->template->write('title', 'Vnx');
        $this->template->render();
    }

    public function ifrclab()
    {
        $this->template->write_view('content', 'update/ifrclab', $this->data);
        $this->template->write('title', 'IFRC LAB');
        $this->template->render();
    }

    public function back_data()
    {
        $this->template->write_view('content', 'update/back_data', $this->data);
        $this->template->write('title', 'Back Data');
        $this->template->render();
    }

    public function world_indexes()
    {
        $this->template->write_view('content', 'update/world_indexes', $this->data);
        $this->template->write('title', 'World Indexes');
        $this->template->render();
    }

    public function get_hnx()
    {
        $this->template->write_view('content', 'update/get_hnx', $this->data);
        $this->template->write('title', 'Get HNX');
        $this->template->render();
    }

    public function get_hsx()
    {
        $this->template->write_view('content', 'update/get_hsx', $this->data);
        $this->template->write('title', 'Get HSX');
        $this->template->render();
    }

    public function update_idx_compo()
    {
        if ($this->input->is_ajax_request())
        {
            $from = microtime(true);

            $date = $this->input->post('date');

            $this->db->query("SET @date='" . $date . "'");

            // ims_production

            $this->db->query("DROP TABLE IF EXISTS `ifrcdata_db`.`tmp`");

            $this->db->query("create TEMPORARY table  `ifrcdata_db`.`tmp` select * from ims_production.idx_composition_histoday where date= @date");


            $this->db->query("insert into `ifrcdata_db`.`tmp` (select * from ims_production.idx_composition where date= @date)");

            // pvn
            $this->db->query("insert into `ifrcdata_db`.`tmp` (select * from pvn.idx_composition_histoday where date= @date)");
            $this->db->query("insert into `ifrcdata_db`.`tmp` (select * from pvn.idx_composition where date= @date)");

            //

            $this->db->query("delete from `ifrcdata_db`.`idx_compo` where left(code,3) IN ('VNX','PVN')");

            $this->db->query("insert into `ifrcdata_db`.`idx_compo`(`code`,isin, `name`,curr, shares, `floats`, capping, last, date, weight)
 (select idx_code, stk_code, UPPER(stk_name) as stk_name,idx_curr,stk_shares_idx,stk_float_idx,stk_capp_idx,stk_price,date,stk_wgt
 from ifrcdata_db.tmp group by date, idx_code, stk_code)");
 $this->db->query("UPDATE `ifrcdata_db`.`idx_compo` as A, `vnxindex_data`.`idx_sample` as B SET A.`PROVIDER`=B.`PROVIDER` 
WHERE A.`code`=B.`code`");

            $total = microtime(true) - $from;
            $result[0]['time'] = round($total, 2);
            $result[0]['task'] = 'Finish';
            echo json_encode($result);
        }
    }

    public function update_idx_specs()
    {
        if ($this->input->is_ajax_request())
        {
            $from = microtime(true);

            $date = $this->input->post('date');

            $this->db->query("SET @date='" . $date . "'");

            $this->db->query("DROP TABLE IF EXISTS ifrcdata_db.tmp");

            $this->db->query("create TEMPORARY table ifrcdata_db.tmp select * from ims_production.idx_specs_histoday where date= @date");

            $this->db->query("insert into ifrcdata_db.tmp (select * from ims_production.idx_specs where date= @date)");

            $this->db->query("insert into ifrcdata_db.tmp (select * from pvn.idx_specs where date= @date)");

            $this->db->query("insert into ifrcdata_db.tmp (select * from pvn.idx_specs_histoday where date= @date)");

            $this->db->query("UPDATE ifrcdata_db.tmp SET id = NULL");

            $this->db->query("truncate table ifrcdata_db.idx_specs");

            $this->db->query("insert into ifrcdata_db.idx_specs select * from ifrcdata_db.tmp group by date, idx_code");

            $this->update_idx_month_chart();

            $total = microtime(true) - $from;
            $result[0]['time'] = round($total, 2);
            $result[0]['task'] = 'Finish';
            echo json_encode($result);
        }
    }
    
    public function update_all()
    {	
        $from = microtime(true);
            $this->db->query("drop table if EXISTS vnxindex_data.obs_year");
            $this->db->query("create table vnxindex_data.obs_year like vnxindex_data.idx_year");
            $this->db->query("insert into vnxindex_data.obs_year(date,open,high,low,close,volume,adjclose,code,perform,volat,provider)
select date,open,high,low,close,volume,adjclose,code,perform,volat,provider from vnxindex_data.idx_year;");
            $this->db->query("drop table if EXISTS vnxindex_data.tmp;");
            $this->db->query("create table vnxindex_data.tmp select * from vnxindex_data.obs_year group by code, date order by code, date;");
            $this->db->query("drop table if EXISTS vnxindex_data.obs_year;");
            $this->db->query("create table vnxindex_data.obs_year select id,date,open,high,low,close,volume,adjclose,code,perform,volat,provider from vnxindex_data.tmp;");
//========================OBS_YEAR =====================================================
            $this->db->query("TRUNCATE TABLE vnxindex_data.idx_month_chart;");
			$this->db->query("insert into vnxindex_data.idx_month_chart ( date, open,high, low, `close`,code, perform, provider)
select REPLACE(date,'-','/') as date, open,high, low, `close`,code, perform, provider from vnxindex_data.idx_month where provider in ('IFRC', 'IFRCLAB', 'PVN', 'VNX','HOSE','HNX','IFRCGWC','PROVINCIAL') order by date desc;");
//========================================
            $this->db->query("drop table if EXISTS vnxindex_data.tmp");
            $this->db->query("create table vnxindex_data.tmp select * from vnxindex_data.idx_month_chart group by date, code;");
            $this->db->query("TRUNCATE table vnxindex_data.idx_month_chart;");
            $this->db->query("insert into vnxindex_data.idx_month_chart select * from vnxindex_data.tmp;");
            $this->db->query("insert into vnxindex_data.idx_month_chart (date, open, high, low, close, code, perform, provider) 
(select REPLACE(a.date,'-','/') as date, a.open, a.high, a.low,a.close,a.code, a.perform,a.provider from vnxindex_data.idx_month as a, vnxindex_data.idx_sample as b 
where a.code=b.code and b.place='Vietnam' and a.provider not in('VNX','IFRC','IFRCLAB','PVN','HOSE','HNX'))");
            $this->db->query("insert into vnxindex_data.idx_month_chart (date, open, high, low, close, adjclose,code, perform, provider) 
(select REPLACE(a.date,'-','/') as date, a.open, a.high, a.low,a.close,a.adjclose,a.code, a.perform,a.provider 
from index_ifrc.idx_month as a where code in ('IFRCAWASEAN','IFRCTASEAN40','IFRCBLWITHA','IFRCBLACMAL','IFRCBLACPHL','IFRCBLACSGP'));");
// ========================OBS_HOME ==========================================
            $this->db->query("drop table if EXISTS vnxindex_data.tmp;");
            $this->db->query("create table vnxindex_data.tmp
select a.date, a.open, a.high, a.low,a.close,a.code, a.perform,a.provider from vnxindex_data.idx_day as a, vnxindex_data.idx_sample as b where a.code=b.code and( b.place='Vietnam' OR b.PROVIDER='IFRCGWC') 
and (a.provider not in('VNX','IFRC','IFRCLAB','PVN','HOSE','HNX','PROVINCIAL') OR (a.code not in (select DISTINCT code from vnxindex_data.obs_home))) order by a.date desc;
");
            $this->db->query("delete from vnxindex_data.obs_home where provider not in('VNX','IFRC','IFRCLAB','PVN','HOSE','HNX','PROVINCIAL');");
            $this->db->query("insert vnxindex_data.obs_home(close,dvar, code, date, yyyymm,yyyy, provider )
select close,perform as dvar,code, max(date) as date,concat(substr(max(date),1,4), substr(max(date),6,2)) as yyyymm, substr(max(date),1,4) as yyyy, provider 
from vnxindex_data.tmp group by code;");
            $this->db->query("update vnxindex_data.obs_home a, vnxindex_data.idx_month b set a.varmonth=b.perform where a.code=b.code and a.date=b.date and a.varmonth=0;");
            $this->db->query("update vnxindex_data.obs_home a, vnxindex_data.idx_year b set a.varyear=b.perform where a.code=b.code and a.date=b.date and a.varyear=0;");
            $this->db->query("update vnxindex_data.obs_home a, vnxindex_data.idx_sample b set a.provider=b.provider where a.code=b.code ;");
            $this->db->query('DROP TABLE IF EXISTS `vnxindex_data`.`idx_sector_weight_daily`');
            $this->db->query('create table `vnxindex_data`.`idx_sector_weight_daily` (select a.code as idx_code ,sum(a.WEIGHT) as weight, b.stk_sector as sector , a.date
from `vnxindex_data`.`idx_compo` as a, (select * from `vnxindex_data`.`stk_ref` group by stk_code) as b 
where a.ISIN=b.ticker and a.PROVIDER in("VNX","IFRC","PVN","IFRCLAB","PROVINCIAL")
group by a.code,stk_sector order by a.code,weight desc)');
          /*  $this->db->query("update vnxindex_data.obs_home set yyyymm='20144' where yyyymm='201404';");
            $this->db->query("");
            $this->db->query("");
            $this->db->query("");*/
            
        $total = microtime(true) - $from;
        $result[0]['time'] = round($total, 2);
        $result[0]['task'] = 'Finish';
        echo json_encode($result);
    
    }
public function update_stk_perf()
    {	
        $from = microtime(true);
           /* $this->db->query("TRUNCATE TABLE vnxindex_data.stk_perf;");
            $this->db->query("insert into vnxindex_data.stk_perf (ticker, year, eoy)
								select ticker, year(date) as year, max(date) as eoy
								from ifrcdata_db.vndb_prices_history where market<>'UPC' and date >='2008-12-31' and length(ticker)=3 group by ticker, year order by ticker,year asc;");
             // Import file for Womenceo_ asaen ---PHUONG fix 20150417
            $this->db->query("LOAD DATA LOCAL INFILE '//Ifrccloud/works/IFRCDATA/IMS/IMSTXT/stk_year.txt' INTO TABLE vnxindex_data.stk_perf
CHARACTER SET UTF8 FIELDS TERMINATED BY  '\t'  LINES TERMINATED BY  '\r\n' (`ticker`,eoy,`year`,`close`,perf);");*/

            $this->db->query("update vnxindex_data.stk_perf as a, ifrcdata_db.vndb_prices_history as b set a.close=b.last, a.adjclose=b.adj_pcls
								where a.ticker=b.ticker and a.eoy= b.date;");
			$this->db->query("update vnxindex_data.stk_perf set adjclose= close where adjclose is null;");
            $this->db->query("SET @runtot = 0;");
            $this->db->query("SET @runtot1 = 0;");
            $this->db->query("SET @plr = NULL;");
            $this->db->query("SET @plr1 = NULL;");
            $this->db->query("SET @year = 0;");
            $this->db->query("SET @year1 = 0;");
            $this->db->query("DROP TABLE IF EXISTS vnxindex_data.tmp;");
			$this->db->query("CREATE TABLE vnxindex_data.tmp
			SELECT ticker, eoy,adjclose,
			IF(@plr = ticker and year-@year1 =1,(@runtot :=  ((a.adjclose/ @runtot)-1)*100),null) AS perf,
			(@runtot := a.adjclose ) p, (@runtot1 := a.adjclose ) p1,
			@plr := ticker AS dummy,
			@plr1 := ticker AS dummy1,
            @year := year as moi,
            @year1 := year as moi1
			FROM vnxindex_data.stk_perf a,(SELECT @runtot:=0, @runtot1:=0) c ;");
			$this->db->query("ALTER TABLE vnxindex_data.tmp ADD  INDEX codedate USING BTREE (ticker, eoy);");
            $this->db->query("UPDATE vnxindex_data.stk_perf A, vnxindex_data.tmp B SET A.perf = B.perf WHERE A.ticker = B.ticker AND A.eoy = B.eoy");
            // update vnxindex_data.stk_month_chart
            $this->db->query("TRUNCATE TABLE  vnxindex_data.stk_month_chart");
            $this->db->query("insert into  vnxindex_data.stk_month_chart (ticker, year, date,yyyymm)
select ticker, year(date) as year, max(date) as date, concat(year(date),SUBSTR(date,6,2)) as yyyymm
from ifrcdata_db.vndb_prices_history 
where market<>'UPC' and date >='2008-12-31' 
and length(ticker)=3 and ticker in (select DISTINCT ticker from ifrcdata_db.vndb_reference_day)group by ticker, yyyymm order by ticker,yyyymm asc");
            $this->db->query("update vnxindex_data.stk_month_chart as a, ifrcdata_db.vndb_prices_history as b set a.close=b.last, a.adjclose=b.adj_pcls
where a.ticker=b.ticker and a.date= b.date");
            $this->db->query("update  vnxindex_data.stk_month_chart set adjclose= close where adjclose is null");
            // Import file for Womenceo_ asaen ---PHUONG fix 20150417
            /*$this->db->query("LOAD DATA LOCAL INFILE '//Ifrccloud/works/IFRCDATA/IMS/IMSTXT/stk_month.txt' INTO TABLE vnxindex_data.stk_month_chart
CHARACTER SET UTF8 FIELDS TERMINATED BY  '\t'  LINES TERMINATED BY  '\r\n' (`ticker`,date,yyyymm,`adjclose`,perform);");*/

            $this->db->query("update vnxindex_data.stk_month_chart a, vnxindex_data.stk_ref b set a.stk_sector=b.stk_sector, a.market=b.stk_market
where a.ticker=b.stk_code and b.enddate='2099-12-31' ");
            $this->db->query("SET @runtot = 0");
            $this->db->query("SET @runtot1 = 0");
            $this->db->query("SET @plr = NULL");
            $this->db->query("SET @plr1 = NULL");
            $this->db->query("DROP TABLE IF EXISTS vnxindex_data.tmp");
            $this->db->query("CREATE TABLE vnxindex_data.tmp SELECT  ticker, date,adjclose, IF(@plr = ticker,(@runtot :=  ((a.adjclose/ @runtot)-1)*100),null) AS perform,
(@runtot := a.adjclose ) p, (@runtot1 := a.adjclose ) p1, @plr := ticker AS dummy, @plr1 := ticker AS dummy1
FROM vnxindex_data.stk_month_chart a,(SELECT @runtot:=0, @runtot1:=0) c ");
            $this->db->query("ALTER TABLE vnxindex_data.tmp ADD  INDEX codedate USING BTREE (ticker, date)");
            $this->db->query("UPDATE vnxindex_data.stk_month_chart A, vnxindex_data.tmp B SET A.perform = B.perform WHERE A.ticker = B.ticker AND A.date = B.date");
            // ===========================IDX_MONTH_CHART==================================
            $this->db->query("TRUNCATE TABLE vnxindex_data.idx_month_chart;");
            $this->db->query("insert into vnxindex_data.idx_month_chart ( date, open,high, low, `close`,code, perform, provider)
            select REPLACE(date,'-','/') as date, open,high, low, `close`,code, perform, provider 
            from vnxindex_data.idx_month 
            where provider in ('IFRC', 'IFRCLAB', 'PVN', 'VNX','HOSE','HNX','IFRCGWC','PROVINCIAL') order by date desc;");

            $this->db->query("drop table if EXISTS vnxindex_data.tmp;");
            $this->db->query("create table vnxindex_data.tmp select * from vnxindex_data.idx_month_chart group by date, code;");
            $this->db->query("TRUNCATE table vnxindex_data.idx_month_chart;");
            $this->db->query("insert into vnxindex_data.idx_month_chart select * from vnxindex_data.tmp;");
            
            $this->db->query("insert into vnxindex_data.idx_month_chart (date, open, high, low, close, code, perform, provider) 
            (select REPLACE(a.date,'-','/') as date, a.open, a.high, a.low,a.close,a.code, a.perform,a.provider 
            from vnxindex_data.idx_month as a, vnxindex_data.idx_sample as b 
            where a.code=b.code and b.place='Vietnam' and a.provider not in('VNX','IFRC','IFRCLAB','PVN','HOSE','HNX'));");
            
            $this->db->query("insert into vnxindex_data.idx_month_chart (date, open, high, low, close, adjclose,code, perform, provider) 
            (select REPLACE(a.date,'-','/') as date, a.open, a.high, a.low,a.close,a.adjclose,a.code, a.perform,a.provider 
            from index_ifrc.idx_month as a where code in ('IFRCAWASEAN','IFRCTASEAN40','IFRCBLWITHA','IFRCBLACMAL','IFRCBLACPHL','IFRCBLACSGP'));");
            $total = microtime(true) - $from;
            $result[0]['time'] = round($total, 2);
            $result[0]['task'] = 'Finish';
            echo json_encode($result);
    
    }
    public function update_idx_month_chart()
    {
        $date_chart = $this->db->query('SELECT CONCAT(YEAR(MAX(`date`)),MONTH(MAX(`date`))) as `date` FROM idx_month_chart')->row_array();
        $date_specs = $this->db->query('SELECT CONCAT(YEAR(`date`),MONTH(`date`)) as `date` FROM idx_specs')->row_array();
        if ($date_chart['date'] != $date_specs['date'])
        {
            if ($date_chart['date'] != '' && $date_specs['date'] != '')
            {
                $year = substr($date_specs['date'], 0, 4);
                $month = substr($date_specs['date'], 4);
                $num_rows = $this->db->query('SELECT * FROM idx_month_chart WHERE YEAR(`date`) = ' . $year . ' AND MONTH(`date`) = ' . $month)->num_rows();
                if ($num_rows != 0)
                {
                    $this->db->query('DELETE FROM idx_month_chart WHERE YEAR(`date`) = ' . $year . ' AND MONTH(`date`) = ' . $month . ' AND LEFT(CODE,3) = "VNX"');
                    //$delete_affected = $this->db->affected_rows();
                    //print_r('Delete have '.$delete_affected.' row affected and ');
                }
                $this->db->query('INSERT INTO idx_month_chart (`CODE`, `DATE`, `CLOSE`) SELECT idx_code, REPLACE(`date`, "-", "/") as `date`, idx_last FROM idx_specs WHERE `date` = (SELECT MAX(`DATE`) as `date` FROM idx_specs)');
                //$row_affected = $this->db->affected_rows();
                //print_r('Run have '.$row_affected.' row affected');
            }
            else
            {
                //print_r('Date of idx_month_chart and date of idx_specs are not value');
            }
        }
        else
        {
            $year = substr($date_chart['date'], 0, 4);
            $month = substr($date_chart['date'], 4);
            $this->db->query('DELETE FROM idx_month_chart WHERE YEAR(`date`) = ' . $year . ' AND MONTH(`date`) = ' . $month . ' AND LEFT(CODE,3) = "VNX"');
            $this->db->query('DELETE FROM idx_month_chart WHERE YEAR(`date`) = ' . $year . ' AND MONTH(`date`) = ' . $month . ' AND LEFT(CODE,3) = "PVN"');
            //$delete_affected = $this->db->affected_rows();
            $this->db->query('INSERT INTO idx_month_chart (`CODE`, `DATE`, `CLOSE`) SELECT idx_code, REPLACE(`date`, "-", "/") as `date`, idx_last FROM idx_specs WHERE `date` = (SELECT MAX(`DATE`) as `date` FROM idx_specs)');
            //$row_affected = $this->db->affected_rows();
            //print_r('Delete have '.$delete_affected.' row affected and Run have '.$row_affected.' row affected');
            //print_r('Date of idx_month_chart: '.$date_chart['date'].' and date of idx_specs: '.$date_specs['date'].' are equal');
        }
        $this->update_idx_month_chart_2();
        $this->db->query('UPDATE idx_month_chart SET PROVIDER = "IFRC" WHERE PROVIDER IS NULL AND LEFT(CODE,3) = "VNX"');
    }

    public function update_idx_month_chart_2()
    {
        $data_dates_chart = $this->db->query('SELECT CONCAT(YEAR(`date`),MONTH(`date`)) as `date` FROM idx_month_chart WHERE LEFT(CODE,4) = "IFRC" GROUP BY YEAR(`date`), MONTH(`date`)')->result_array();
        $dates_chart = array();
        foreach ($data_dates_chart as $item_chart)
        {
            $dates_chart[] = $item_chart['date'];
        }

        $data_dates_metafile = $this->db->query('SELECT CONCAT(YEAR(`date`),MONTH(`date`)) as `date` FROM vndb_metafile GROUP BY YEAR(`date`), MONTH(`date`)')->result_array();
        $dates_metafile = array();
        foreach ($data_dates_metafile as $item_metafile)
        {
            $dates_metafile[] = $item_metafile['date'];
        }

        $c = array_unique(array_merge($dates_chart, $dates_metafile));
        $d = array_intersect($dates_chart, $dates_metafile);

        $result = array_diff($c, $d);
        if (count($result) > 0)
        {
            foreach ($result as $date)
            {
                $year = substr($date, 0, 4);
                $month = substr($date, 4);
                $this->db->query('INSERT INTO idx_month_chart (`CODE`, `DATE`, `OPEN`, `HIGH`, `LOW`, `CLOSE`, `VOLUME`) SELECT CASE ticker WHEN "^HASTC" THEN "IFRCHNX" WHEN "^VNINDEX" THEN "IFRCVNI" END AS `code`, REPLACE(`date`, "-", "/") as `date`, `open`, `high`, `low`, `close`, `volume` FROM vndb_metafile where date = (select MAX(date) from vndb_metafile where YEAR(`date`) = ' . $year . ' and MONTH(`date`) = ' . $month . ')');
            }
        }
        $this->db->query('UPDATE idx_month_chart SET PROVIDER = "EXCH" WHERE PROVIDER IS NULL AND LEFT(CODE,4) = "IFRC"');
    }

    public function update_idx_vnx()
    {
        if ($this->input->is_ajax_request())
        {
            $from = microtime(true);

            $date = $this->input->post('date');

            $rowCheck = $this->db->query('SELECT * FROM ifrcdata_db.idx_specs WHERE date = "' . $date . '"')->num_rows();

            if ($rowCheck == 0)
            {

                $this->db->query("SET @date='" . $date . "'");

                $this->db->query("DROP TABLE IF EXISTS ifrcdata_db.tmp");

                $this->db->query("create TEMPORARY table ifrcdata_db.tmp select * from ims_production.idx_specs_histoday where date= @date");

                $this->db->query("insert into ifrcdata_db.tmp (select * from ims_production.idx_specs where date= @date)");

                $this->db->query("insert into ifrcdata_db.tmp (select * from pvn.idx_specs where date= @date)");

                $this->db->query("insert into ifrcdata_db.tmp (select * from pvn.idx_specs_histoday where date= @date)");

                // $this->db->query("insert into ifrcdata_db.tmp (select * from vndb_hnx_index where date= @date)");

                $this->db->query("UPDATE ifrcdata_db.tmp SET id = NULL");

                $this->db->query("truncate table ifrcdata_db.idx_specs");

                $this->db->query("insert into ifrcdata_db.idx_specs select * from ifrcdata_db.tmp group by date, idx_code");
            }

            $this->update_idx($date);

            $total = microtime(true) - $from;
            $result[0]['time'] = round($total, 2);
            $result[0]['task'] = 'Finish';
            echo json_encode($result);
        }
    }

    public function update_idx($dateChoice)
    {
        $this->idx_day($dateChoice);
        $this->idx_month();
        $this->idx_year();

        $dataCodeDay = $this->db->query('SELECT DISTINCT(code) FROM `vnxindex_data`.`idx_day` WHERE provider IN ("VNX","PVN","IFRC","IFRCLAB")')->result_array();
        foreach ($dataCodeDay as $code)
        {
            $checkOBS = $this->db->query('SELECT * FROM `vnxindex_data`.`obs_home` WHERE code = "' . $code['code'] . '"')->num_rows();
            if ($checkOBS == 0)
            {
                $this->db->query('INSERT INTO `vnxindex_data`.`obs_home` (`code`) VALUES ("' . $code['code'] . '")');
            }
        }

        $this->db->query('UPDATE `vnxindex_data`.`obs_home` A, (SELECT A.*, B.perform as varmonth, C.perform as varyear, 
D.idx_dvar as dvar FROM
(SELECT code, date, CONCAT(YEAR(date),MONTH(date)) as yyyymm, YEAR(date) as `year`, close 
FROM `vnxindex_data`.`idx_day` WHERE date = (SELECT MAX(date) as date FROM `vnxindex_data`.`idx_day`)) A
LEFT JOIN (SELECT code, date, perform FROM `vnxindex_data`.`idx_month`) B ON A.date = B.date AND A.code = B.code
LEFT JOIN (SELECT code, date, perform FROM `vnxindex_data`.`idx_year`) C ON A.date = C.date AND A.code = C.code
LEFT JOIN (SELECT idx_code, date, idx_dvar FROM `ifrcdata_db`.`idx_specs`) D ON A.date = D.date AND A.code = D.idx_code) B 
SET A.date = B.date, A.yyyymm = B.yyyymm, A.yyyy = B.year, A.close = B.close, A.varmonth = B.varmonth, A.varyear = B.varyear, 
A.dvar = B.dvar
WHERE A.code = B.code');
        $this->db->query('UPDATE `vnxindex_data`.`obs_home` as A, `vnxindex_data`.`idx_day` as B SET A.`dvar`=B.`perform` 
WHERE A.`code`=B.`code` AND A.`date`=B.`date` AND A.`dvar` = 0');

        // }
    }

    public function idx_day($dateChoice)
    {
      
        $this->db->query('DELETE FROM `vnxindex_data`.`idx_day` WHERE 
                `date` >= "' . $dateChoice . '" AND LEFT(CODE,3) IN ("VNX","PVN","IFRC")');

        $this->db->query('INSERT INTO `vnxindex_data`.`idx_day` (`CODE`, `DATE`, `CLOSE`) SELECT idx_code, 
                REPLACE(`date`, "-", "/") as `date`, idx_last FROM idx_specs
                WHERE `date` = "' . $dateChoice . '"');

        $dataHNX = $this->db->query('SELECT idx_code as code, date, open, high, low, close FROM vndb_hnx_index WHERE date= "' . $dateChoice . '"')->result_array();
        foreach ($dataHNX as $itemHNX)
        {
            $check = $this->db->query('SELECT * FROM `vnxindex_data`.`idx_day` WHERE code = "' . $itemHNX['code'] . '" and date = "' . $itemHNX['date'] . '"')->num_rows();
            if ($check == 0)
            {
                $this->db->insert('`vnxindex_data`.`idx_day`', $itemHNX);
            }
            else
            {
                $this->db->where('code', $itemHNX['code']);
                $this->db->where('date', $itemHNX['date']);
                $this->db->update('`vnxindex_data`.`idx_day`', $itemHNX);
            }
        }
        $dataHSX = $this->db->query('SELECT idx_code as code, date, open, high, low, close FROM vndb_hsx_index WHERE date= "' . $dateChoice . '"')->result_array();
        foreach ($dataHSX as $itemHSX)
        {
            $check = $this->db->query('SELECT * FROM `vnxindex_data`.`idx_day` WHERE code = "' . $itemHSX['code'] . '" and date = "' . $itemHSX['date'] . '"')->num_rows();
            if ($check == 0)
            {
                $this->db->insert('`vnxindex_data`.`idx_day`', $itemHSX);
            }
            else
            {
                $this->db->where('code', $itemHSX['code']);
                $this->db->where('date', $itemHSX['date']);
                $this->db->update('`vnxindex_data`.`idx_day`', $itemHSX);
            }
        }
        $this->db->query('insert into `vnxindex_data`.`idx_day` (code, open, high,low, close, date, provider)
                select if(ticker="^HASTC","IFRCHNX", "IFRCVNI") as ticker, open, high, low, close,date, "EXC" as provider
                 from ifrcdata_db.vndb_metafile WHERE `date` = "' . $dateChoice . '" group by ticker, date order by date desc');
        // PVN //
        // if($dateChoice == $dateSpe){
        //     $this->db->query('INSERT INTO `vnxindex_data`.`idx_day` (`CODE`, `DATE`, `CLOSE`) SELECT idx_code,
        //  REPLACE(`date`, "-", "/") as `date`, idx_last FROM `pvn`.`idx_specs` 
        //     WHERE `date` = (SELECT MAX(`DATE`) as `date` FROM `pvn`.`idx_specs`)');
        // }else{
        //     $this->db->query('INSERT INTO `vnxindex_data`.`idx_day` (`CODE`, `DATE`, `CLOSE`) SELECT idx_code,
        //  REPLACE(`date`, "-", "/") as `date`, idx_last FROM `pvn`.`idx_specs_histoday` 
        //     WHERE `date` = "'.$dateChoice.'"');
        // }
        // }
        // CALCULATE PERFORM
        $this->db->query('SET @runtot = 0');
        $this->db->query('SET @runtot1 = 0');
        $this->db->query('SET @plr = NULL');
        $this->db->query('SET @plr1 = NULL');
        $this->db->query('DROP TABLE IF EXISTS tmp');
        $this->db->query('CREATE TEMPORARY TABLE tmp SELECT * FROM `vnxindex_data`.`idx_day` ORDER BY code, date');
        $this->db->query('DROP TABLE IF EXISTS tmp2');
        $this->db->query('CREATE TEMPORARY TABLE tmp2
        SELECT code, date, close,
        IF(@plr = code,(@runtot := ((a.close/ @runtot)-1)*100),null) AS perform,
        (@runtot := a.close ) p, (@runtot1 := a.close ) p1, @plr := code AS dummy, @plr1 := code AS dummy1 
        FROM tmp a');
        //$this->db->query('ALTER TABLE tmp2 ADD INDEX codedate USING BTREE (code, date)');
        $this->db->query('UPDATE `vnxindex_data`.`idx_day` A, tmp2 B SET A.PERFORM = B.perform 
            WHERE A.code = B.code AND A.date = B.date;');

        $this->db->query('UPDATE `vnxindex_data`.`idx_day` A, `vnxindex_data`.`idx_sample` B 
            SET A.provider = B.PROVIDER, high = close, low = close WHERE A.code = B.code');

        $cnn = mysql_connect('pvnindex.vn', 'pvn', 'pvn@)!!');
        if ($cnn)
        {
            mysql_select_db('pvnindex_release', $cnn);
            mysql_query("SET NAMES utf8");

            $this->db->query('DROP TABLE IF EXISTS tmp');
            $this->db->query('CREATE TEMPORARY TABLE tmp SELECT * FROM pvn_idx_last LIMIT 0');

            $sql = 'SELECT * FROM pvn_idx_last';
            $query = mysql_query($sql, $cnn);
            $dataPVN = array();
            while ($rows = mysql_fetch_assoc($query))
            {
                $dataPVN[] = $rows;
            }
            $this->db->insert_batch('tmp', $dataPVN);

            $this->db->query('UPDATE `vnxindex_data`.`idx_day` A, tmp B SET A.open = B.open, A.high = B.high, A.low = B.low WHERE A.code = B.idx_code AND A.date = B.dates');
        }
    }

    public function idx_month()
    {
        // // MONTH //
        // $date_specs = $this->db->query('SELECT CONCAT(YEAR(`date`),MONTH(`date`)) as `date` FROM idx_specs')->row_array();
        // $date_idx = $this->db->query('SELECT CONCAT(YEAR(MAX(`date`)),MONTH(MAX(`date`))) as `date` FROM  `vnxindex_data`.`idx_month`')->row_array();
        // if($date_idx['date'] != $date_specs['date']){
        //     if($date_idx['date'] != '' && $date_specs['date'] != ''){
        //         $year = substr($date_specs['date'], 0,4);
        //         $month = substr($date_specs['date'], 4);
        //         $num_rows = $this->db->query('SELECT * FROM  `vnxindex_data`.`idx_month` WHERE YEAR(`date`) = '.$year.' AND MONTH(`date`) = '.$month)->num_rows();
        //         if($num_rows != 0){
        //             $this->db->query('DELETE FROM  `vnxindex_data`.`idx_month` WHERE YEAR(`date`) = '.$year.' AND MONTH(`date`) = '.$month.' AND LEFT(CODE,3) = "VNX"');
        //         }
        //         $this->db->query('INSERT INTO  `vnxindex_data`.`idx_month` (`CODE`, `DATE`, `CLOSE`) SELECT idx_code,
        //             REPLACE(`date`, "-", "/") as `date`, idx_last FROM idx_specs 
        //             WHERE `date` = (SELECT MAX(`DATE`) as `date` FROM idx_specs)');
        //         // PVN //
        //         if($dateChoice == $dateSpe){
        //             $this->db->query('INSERT INTO  `vnxindex_data`.`idx_month` (`CODE`, `DATE`, `CLOSE`) SELECT idx_code,
        //             REPLACE(`date`, "-", "/") as `date`, idx_last FROM `pvn`.`idx_specs` 
        //             WHERE `date` = (SELECT MAX(`DATE`) as `date` FROM `pvn`.`idx_specs`)');
        //         }else{
        //             $this->db->query('INSERT INTO `vnxindex_data`.`idx_month` (`CODE`, `DATE`, `CLOSE`) SELECT idx_code,
        //          REPLACE(`date`, "-", "/") as `date`, idx_last FROM `pvn`.`idx_specs_histoday` 
        //             WHERE `date` = "'.$dateChoice.'"');
        //         }
        //     }
        // }else{
        //     $year = substr($date_idx['date'], 0,4);
        //     $month = substr($date_idx['date'], 4);
        //     $this->db->query('DELETE FROM  `vnxindex_data`.`idx_month` WHERE YEAR(`date`) = '.$year.' AND MONTH(`date`) = '.$month.' AND LEFT(CODE,3) IN ("VNX","PVN")');
        //     // $delete_affected = $this->db->affected_rows();
        //     $this->db->query('INSERT INTO  `vnxindex_data`.`idx_month` (`CODE`, `DATE`, `CLOSE`) SELECT idx_code, 
        //         REPLACE(`date`, "-", "/") as `date`, idx_last FROM idx_specs
        //         WHERE `date` = (SELECT MAX(`DATE`) as `date` FROM idx_specs)');
        //     // PVN //
        //     if($dateChoice == $dateSpe){
        //         $this->db->query('INSERT INTO  `vnxindex_data`.`idx_month` (`CODE`, `DATE`, `CLOSE`) SELECT idx_code,
        //             REPLACE(`date`, "-", "/") as `date`, idx_last FROM `pvn`.`idx_specs` 
        //             WHERE `date` = (SELECT MAX(`DATE`) as `date` FROM `pvn`.`idx_specs`)');
        //     }else{
        //         $this->db->query('INSERT INTO `vnxindex_data`.`idx_month` (`CODE`, `DATE`, `CLOSE`) SELECT idx_code,
        //      REPLACE(`date`, "-", "/") as `date`, idx_last FROM `pvn`.`idx_specs_histoday` 
        //         WHERE `date` = "'.$dateChoice.'"');
        //     }
        // }
        // $this->db->query('UPDATE `vnxindex_data`.`idx_month` SET provider = LEFT(CODE,3)');

        $this->db->query('TRUNCATE TABLE `vnxindex_data`.`idx_month`');

        // $this->db->query('INSERT INTO `vnxindex_data`.`idx_month` (`date`,`high`,`low`,`close`,`code`,`provider`) 
        //     SELECT MAX(date) as date, MAX(close) as high, MIN(close) as low, close, code, provider FROM `vnxindex_data`.`idx_day` GROUP BY YEAR(`date`), MONTH(`date`), code');

        $this->db->query('INSERT INTO `vnxindex_data`.`idx_month` (`date`,`high`,`low`,`code`,`provider`,`close`) 
            SELECT A.*, B.close FROM
                (SELECT MAX(date) as date, MAX(close) as high, MIN(close) as low, code, provider 
                    FROM `vnxindex_data`.`idx_day` GROUP BY code, YEAR(`date`), MONTH(`date`)) A,
                (SELECT close, code, date FROM `vnxindex_data`.`idx_day`) B
            WHERE A.code = B.code AND A.date = B.date');

        // CALCULATE PERFORM
        $this->db->query('SET @runtot = 0');
        $this->db->query('SET @runtot1 = 0');
        $this->db->query('SET @plr = NULL');
        $this->db->query('SET @plr1 = NULL');
        $this->db->query('DROP TABLE IF EXISTS tmp');
        $this->db->query('CREATE TEMPORARY TABLE tmp SELECT * FROM `vnxindex_data`.`idx_month` ORDER BY code, date');
        $this->db->query('DROP TABLE IF EXISTS tmp2');
        $this->db->query('CREATE TEMPORARY TABLE tmp2
        SELECT code, date, close,
        IF(@plr = code,(@runtot := ((a.close/ @runtot)-1)*100),null) AS perform,
        (@runtot := a.close ) p, (@runtot1 := a.close ) p1, @plr := code AS dummy, @plr1 := code AS dummy1 
        FROM tmp a');
        $this->db->query('ALTER TABLE tmp2 ADD INDEX codedate USING BTREE (code, date)');
        $this->db->query('UPDATE `vnxindex_data`.`idx_month` A, tmp2 B SET A.PERFORM = B.perform 
            WHERE A.code = B.code AND A.date = B.date;');
    }

    public function idx_year()
    {
        // YEAR //
        // $this->db->query('UPDATE `vnxindex_data`.`idx_year` SET provider = LEFT(CODE,3)');

        $this->db->query('TRUNCATE TABLE `vnxindex_data`.`idx_year`');

        // $this->db->query('INSERT INTO `vnxindex_data`.`idx_year` (`date`,`high`,`low`,`close`,`code`,`provider`) 
        //     SELECT MAX(date) as date, MAX(close) as high, MIN(close) as low, close, code, provider FROM `vnxindex_data`.`idx_day` GROUP BY YEAR(`date`), code');

        $this->db->query('INSERT INTO `vnxindex_data`.`idx_year` (`date`,`high`,`low`,`code`,`provider`,`close`) 
            SELECT A.*, B.close FROM
                (SELECT MAX(date) as date, MAX(close) as high, MIN(close) as low, code, provider 
                    FROM `vnxindex_data`.`idx_day` GROUP BY code, YEAR(`date`)) A,
                (SELECT close, code, date FROM `vnxindex_data`.`idx_day`) B
            WHERE A.code = B.code AND A.date = B.date');

        // CALCULATE PERFORM
        $this->db->query('SET @runtot = 0');
        $this->db->query('SET @runtot1 = 0');
        $this->db->query('SET @plr = NULL');
        $this->db->query('SET @plr1 = NULL');
        $this->db->query('DROP TABLE IF EXISTS tmp');
        $this->db->query('CREATE TEMPORARY TABLE tmp SELECT * FROM `vnxindex_data`.`idx_year` ORDER BY code, date');
        $this->db->query('DROP TABLE IF EXISTS tmp2');
        $this->db->query('CREATE TEMPORARY TABLE tmp2
        SELECT code, date, close,
        IF(@plr = code,(@runtot := ((a.close/ @runtot)-1)*100),null) AS perform,
        (@runtot := a.close ) p, (@runtot1 := a.close ) p1, @plr := code AS dummy, @plr1 := code AS dummy1 
        FROM tmp a');
        $this->db->query('ALTER TABLE tmp2 ADD INDEX codedate USING BTREE (code, date)');
        $this->db->query('UPDATE `vnxindex_data`.`idx_year` A, tmp2 B SET A.PERFORM = B.perform 
            WHERE A.code = B.code AND A.date = B.date;');
    }

    public function update_ifrc_lab()
    {
        if ($this->input->is_ajax_request())
        {
            set_time_limit(0);
            ini_set('memory_limit', '2048M');
            $from = microtime(true);

            $dataResultCode = $this->db->query('SELECT code FROM `vnxindex_data`.`idx_sample` WHERE provider = "IFRCLAB"')->result_array();
            $dataCode = array();
            foreach ($dataResultCode as $code)
            {
                $dataCode[] = $code['code'];
            }
            $stringCode = '("' . implode('","', $dataCode) . '")';

            $cnn = mysql_connect('ifrcims.com', 'admin_ims1', 'VietnamIfrc1') or die('Could not connect to mysql server.');
            ;
            mysql_select_db('admin_ims', $cnn) or die('Could not connect to database.');
            ;
            mysql_query("SET NAMES utf8", $cnn);

            $this->db->query('DROP TABLE IF EXISTS tmp');
            $this->db->query('CREATE TEMPORARY TABLE tmp SELECT code, date, close FROM `vnxindex_data`.`idx_day` LIMIT 0');

            // $sql = 'SELECT idx_code as code, date, close FROM vndb_index_vnx_daily WHERE idx_code IN '.$stringCode;
            $sql = 'SELECT idx_code as code, date, close FROM q_index_vnx_daily WHERE idx_code IN ' . $stringCode;
            $query = mysql_query($sql, $cnn);
            $dataPVN = array();
            while ($rows = mysql_fetch_assoc($query))
            {
                $dataPVN[] = $rows;
            }

            $this->db->insert_batch('tmp', $dataPVN);

            $query = mysql_query('SELECT MAX(date) as date FROM q_compo_vnx_daily', $cnn);
            $dataCurrent = mysql_fetch_row($query);

            $sql = 'SELECT stk_name as name, stk_shares as shares, stk_code as isin, wgt*100 as weight, pcls as last, stk_float as floats, stk_capp as capping, idx_code as code, date, stk_curr as curr 
            FROM q_compo_vnx_daily WHERE date = "' . $dataCurrent[0] . '" AND idx_code IN ' . $stringCode;
            $query = mysql_query($sql, $cnn);
            $numRows = mysql_num_rows($query);
            if ($numRows != 0)
            {
                $dataCompo = array();
                while ($rows = mysql_fetch_assoc($query))
                {
                    $dataCompo[] = $rows;
                }
                $this->db->query('DELETE FROM  `vnxindex_data`.`idx_compo` WHERE code IN ' . $stringCode);
                $this->db->insert_batch('`vnxindex_data`.`idx_compo`', $dataCompo);
            }

            // $this->updateLog($index, $action, array('end' => date('Y-m-d H:i:s'), 'seconds' => number_format(microtime_float() - $start)));
            // $action = 'CREATE TABLE IDX_DAY';
            // $start = microtime_float();
            // $log = array('index' => $index,
            //     'action' => $action,
            //     'start' => date('Y-m-d H:i:s'));
            // $this->insertLog($log);

            $sql = 'DELETE FROM  `vnxindex_data`.`idx_day` WHERE code IN ' . $stringCode;

            $this->db->query($sql);

            $sql = 'INSERT INTO `vnxindex_data`.`idx_day` (`date`,`code`,`close`) SELECT `date`, `code`, `close` FROM tmp';

            $this->db->query($sql);

            $dateMax = $this->db->query('SELECT MAX(date) as date, YEAR(MAX(date)) as year, MONTH(MAX(date)) as month FROM tmp')->row_array();

            $this->db->query('UPDATE `vnxindex_data`.`idx_day` SET provider = LEFT(CODE,3), high = close, low = close WHERE code IN ' . $stringCode);

            // CALCULATE PERFORM
            $this->db->query('SET @runtot = 0');
            $this->db->query('SET @runtot1 = 0');
            $this->db->query('SET @plr = NULL');
            $this->db->query('SET @plr1 = NULL');
            $this->db->query('DROP TABLE IF EXISTS tmp');
            $this->db->query('CREATE TEMPORARY TABLE tmp SELECT * FROM `vnxindex_data`.`idx_day` ORDER BY code, date');
            $this->db->query('DROP TABLE IF EXISTS tmp2');
            $this->db->query('CREATE TEMPORARY TABLE tmp2
            SELECT code, date, close,
            IF(@plr = code,(@runtot := ((a.close/ @runtot)-1)*100),null) AS perform,
            (@runtot := a.close ) p, (@runtot1 := a.close ) p1, @plr := code AS dummy, @plr1 := code AS dummy1 
            FROM tmp a');
            $this->db->query('ALTER TABLE tmp2 ADD INDEX codedate USING BTREE (code, date)');
            $this->db->query('UPDATE `vnxindex_data`.`idx_day` A, tmp2 B SET A.PERFORM = B.perform 
                WHERE A.code = B.code AND A.date = B.date;');

            // $this->updateLog($index, $action, array('end' => date('Y-m-d H:i:s'), 'seconds' => number_format(microtime_float() - $start)));
            // $action = 'CREATE TABLE IDX_MONTH';
            // $start = microtime_float();
            // $log = array('index' => $index,
            //     'action' => $action,
            //     'start' => date('Y-m-d H:i:s'));
            // $this->insertLog($log);

            $this->idx_month();

            $this->idx_year();

            $this->db->query('UPDATE `vnxindex_data`.`obs_home` A, (SELECT A.*, B.perform as varmonth, C.perform as varyear FROM
(SELECT code, date, CONCAT(YEAR(date),MONTH(date)) as yyyymm, YEAR(date) as `year`, close, perform as dvar
FROM `vnxindex_data`.`idx_day` WHERE date = "' . $dateMax['date'] . '" AND code IN ' . $stringCode . ') A
LEFT JOIN (SELECT code, date, perform FROM `vnxindex_data`.`idx_month`) B ON YEAR(A.date) = YEAR(B.date) AND 
MONTH(A.date) = MONTH(B.date) AND A.code = B.code
LEFT JOIN (SELECT code, date, perform FROM `vnxindex_data`.`idx_year`) C ON YEAR(A.date) = YEAR(C.date) AND A.code = C.code) B 
SET A.date = B.date, A.yyyymm = B.yyyymm, A.yyyy = B.year, A.close = B.close, A.varmonth = B.varmonth, A.varyear = B.varyear, 
A.dvar = B.dvar
WHERE A.code = B.code');

            // $this->updateLog($index, $action, array('end' => date('Y-m-d H:i:s'), 'seconds' => number_format(microtime_float() - $start)));

            $total = microtime(true) - $from;
            $result[0]['time'] = round($total, 2);
            $result[0]['task'] = 'Finish';

            echo json_encode($result);
        }
    }

	 public function update_ifrc_lab2()
    {
            set_time_limit(0);
            ini_set('memory_limit', '5000M');
            $from = microtime(true);
    
           // $this->db->query('TRUNCATE TABLE `q_index_vnx_daily`');
            // //LOCAL/IFRCDATA/IOBS/WORLD/UPLOAD/WORLD_INDEXES.csv
            // //Ifrccloud/works/IFRCDATA/IMS/IMSTXT/PROVINCIAL_PRICES.txt  ==> DISK K
            
           /*$this->db->query('LOAD DATA LOCAL INFILE "//192.168.1.14/ifrc_production/IFRCDATA/GEDB/VIETNAM/INDEXES/UPLOAD/PROVINCIAL_PRICES.txt" INTO TABLE q_index_vnx_daily
CHARACTER SET UTF8 FIELDS TERMINATED BY  "\t"  LINES TERMINATED BY  "\r\n" (id,idx_code,date,idx_mother,close,capi,divisor,idx_curr,nb)');
            $this->db->query('LOAD DATA LOCAL INFILE "//192.168.1.14/ifrc_production/IFRCDATA/GEDB/VIETNAM/INDEXES/UPLOAD/ALLSHARE_PRICES.txt" INTO TABLE q_index_vnx_daily
CHARACTER SET UTF8 FIELDS TERMINATED BY  "\t"  LINES TERMINATED BY  "\r\n" (id,idx_code,date,idx_mother,close,capi,divisor,idx_curr,nb)');

            $this->db->query('TRUNCATE TABLE `q_compo_vnx_daily`');
            $this->db->query('LOAD DATA LOCAL INFILE "U://IFRCDATA/GEDB/VIETNAM/INDEXES/UPLOAD/PROVINCIAL_COMPOSITION_DAILY.txt" INTO TABLE q_compo_vnx_daily
CHARACTER SET UTF8 FIELDS TERMINATED BY  "\t"  LINES TERMINATED BY  "\r\n" (id,start_date,idx_code,stk_code,stk_name,stk_shares,pcls,capi,stk_float,stk_capp,end_date,stk_curr,date,wgt)');
             $this->db->query('LOAD DATA LOCAL INFILE "U://IFRCDATA/GEDB/VIETNAM/INDEXES/UPLOAD/ALLSHARE_COMPOSITION_DAILY.txt" INTO TABLE q_compo_vnx_daily
CHARACTER SET UTF8 FIELDS TERMINATED BY  "\t"  LINES TERMINATED BY  "\r\n" (id,start_date,idx_code,stk_code,stk_name,stk_shares,pcls,capi,stk_float,stk_capp,end_date,stk_curr,date,wgt)');
*/
            $this->db->query('UPDATE q_compo_vnx_daily as a, `vnxindex_data`.stk_ref as b SET a.stk_name=UPPER(b.stk_name_sn) WHERE a.stk_code=b.stk_code');
            
            $dataResultCode = $this->db->query('SELECT code FROM `vnxindex_data`.`idx_sample` WHERE provider = "PROVINCIAL" OR provider = "IFRCLAB" OR provider = "IFRCRESEARCH"')->result_array();
            $dataCode = array();
            foreach ($dataResultCode as $code)
            {
                $dataCode[] = $code['code'];
            }
            $stringCode = '("' . implode('","', $dataCode) . '")';

            $this->db->query('DROP TABLE IF EXISTS tmp');
            $this->db->query('CREATE TABLE tmp SELECT idx_code as code, date, close FROM q_index_vnx_daily WHERE idx_code IN ' . $stringCode .'group by idx_code,date');
            $query = mysql_query('SELECT MAX(date) as date FROM q_compo_vnx_daily');
            $dataCurrent = mysql_fetch_row($query);

            $sql = 'SELECT stk_name as name, stk_shares as shares, stk_code as isin, wgt as weight, pcls as last, stk_float as floats, capi as capping, idx_code as code, date, stk_curr as curr 
            FROM q_compo_vnx_daily WHERE date = "' . $dataCurrent[0] . '" AND idx_code IN ' . $stringCode;
            $query = mysql_query($sql);
            $numRows = mysql_num_rows($query);
            if ($numRows != 0)
            {
                $dataCompo = array();
                while ($rows = mysql_fetch_assoc($query))
                {
                    $dataCompo[] = $rows;
                }
                $this->db->query('DELETE FROM  `vnxindex_data`.`idx_compo` WHERE code IN ' . $stringCode);
                $this->db->insert_batch('`vnxindex_data`.`idx_compo`', $dataCompo);
            }
            $this->db->query('UPDATE `vnxindex_data`.`idx_compo` AS a, `vnxindex_data`.`idx_sample` as b SET a.provider = b.PROVIDER where a.code=b.code');

            $sql = 'DELETE FROM  `vnxindex_data`.`idx_day` WHERE code IN ' . $stringCode;

            $this->db->query($sql);

            $sql = 'INSERT INTO `vnxindex_data`.`idx_day` (`date`,`code`,`close`) SELECT `date`, `code`, `close` FROM tmp GROUP BY code,date';

            $this->db->query($sql);

            $dateMax = $this->db->query('SELECT MAX(date) as date, YEAR(MAX(date)) as year, MONTH(MAX(date)) as month FROM tmp')->row_array();
            $this->db->query('UPDATE `vnxindex_data`.`idx_day` AS a, `vnxindex_data`.`idx_sample` as b SET a.provider = b.PROVIDER where a.code=b.code');

            $this->db->query('UPDATE `vnxindex_data`.`idx_day` SET high = close, low = close WHERE code IN ' . $stringCode);

            // CALCULATE PERFORM
            $this->db->query('SET @runtot = 0');
            $this->db->query('SET @runtot1 = 0');
            $this->db->query('SET @plr = NULL');
            $this->db->query('SET @plr1 = NULL');
            $this->db->query('DROP TABLE IF EXISTS tmp');
            $this->db->query('CREATE TEMPORARY TABLE tmp SELECT * FROM `vnxindex_data`.`idx_day` ORDER BY code, date');
            $this->db->query('DROP TABLE IF EXISTS tmp2');
            $this->db->query('CREATE TEMPORARY TABLE tmp2
            SELECT code, date, close,
            IF(@plr = code,(@runtot := ((a.close/ @runtot)-1)*100),null) AS perform,
            (@runtot := a.close ) p, (@runtot1 := a.close ) p1, @plr := code AS dummy, @plr1 := code AS dummy1 
            FROM tmp a');
           // $this->db->query('ALTER TABLE tmp2 ADD INDEX codedate USING BTREE (code, date)');
            $this->db->query('UPDATE `vnxindex_data`.`idx_day` A, tmp2 B SET A.PERFORM = B.perform 
                WHERE A.code = B.code AND A.date = B.date;');
            $this->idx_month();

            $this->idx_year();

            $this->db->query('UPDATE `vnxindex_data`.`obs_home` A, (SELECT A.*, B.perform as varmonth, C.perform as varyear FROM
(SELECT code, date, CONCAT(YEAR(date),MONTH(date)) as yyyymm, YEAR(date) as `year`, close, perform as dvar
FROM `vnxindex_data`.`idx_day` WHERE date = "' . $dateMax['date'] . '" AND code IN ' . $stringCode . ') A
LEFT JOIN (SELECT code, date, perform FROM `vnxindex_data`.`idx_month`) B ON YEAR(A.date) = YEAR(B.date) AND 
MONTH(A.date) = MONTH(B.date) AND A.code = B.code
LEFT JOIN (SELECT code, date, perform FROM `vnxindex_data`.`idx_year`) C ON YEAR(A.date) = YEAR(C.date) AND A.code = C.code) B 
SET A.date = B.date, A.yyyymm = B.yyyymm, A.yyyy = B.year, A.close = B.close, A.varmonth = B.varmonth, A.varyear = B.varyear, 
A.dvar = B.dvar
WHERE A.code = B.code');            
            $total = microtime(true) - $from;
            $result[0]['time'] = round($total, 2);
            $result[0]['task'] = 'Finish';

            echo json_encode($result);
    }
 public function update_womenceo_asia()
    {
            set_time_limit(0);
            ini_set('memory_limit', '5000M');
            $from = microtime(true);
           /* $this->db->query('TRUNCATE TABLE `q_index_vnx_daily`');
            // //LOCAL/IFRCDATA/IOBS/WORLD/UPLOAD/WORLD_INDEXES.csv
            // //Ifrccloud/works/IFRCDATA/IMS/IMSTXT/CEO_ASEAN_PRICES.txt
            $this->db->query('LOAD DATA LOCAL INFILE "//Ifrccloud/works/IFRCDATA/IMS/IMSTXT/CEO_ASEAN_PRICES.txt" INTO TABLE q_index_vnx_daily
CHARACTER SET UTF8 FIELDS TERMINATED BY  "\t"  LINES TERMINATED BY  "\r\n" (id,idx_code,date,idx_mother,close,capi,divisor,idx_curr,nb)');
            
            // //Ifrccloud/works/IFRCDATA/IMS/IMSTXT/CEO_ASEAN_COMPOSITION.txt     
            $this->db->query('TRUNCATE TABLE `q_compo_vnx_daily`');
            $this->db->query('LOAD DATA LOCAL INFILE "//Ifrccloud/works/IFRCDATA/IMS/IMSTXT/CEO_ASEAN_COMPOSITION.txt" INTO TABLE q_compo_vnx_daily
CHARACTER SET UTF8 FIELDS TERMINATED BY  "\t"  LINES TERMINATED BY  "\r\n" (id,start_date,idx_code,stk_code,stk_name,stk_shares,pcls,capi,stk_float,stk_capp,end_date,stk_curr,date,wgt)');
 */
            /*$this->db->query('UPDATE q_compo_vnx_daily as a SET a.wgt=100* a.wgt');*/
            
            $dataResultCode = $this->db->query('SELECT code FROM `vnxindex_data`.`idx_sample` WHERE provider = "IFRCGWC"')->result_array();
            $dataCode = array();
            foreach ($dataResultCode as $code)
            {
                $dataCode[] = $code['code'];
            }
            $stringCode = '("' . implode('","', $dataCode) . '")';

            $this->db->query('DROP TABLE IF EXISTS tmp');
            $this->db->query('CREATE TABLE tmp SELECT idx_code as code, date, close FROM q_index_vnx_daily WHERE idx_code IN ' . $stringCode .'group by idx_code,date');
            $query = mysql_query('SELECT MAX(date) as date FROM q_compo_vnx_daily');
            $dataCurrent = mysql_fetch_row($query);

            $sql = 'SELECT stk_name as name, stk_shares as shares, stk_code as isin, wgt as weight, pcls as last, stk_float as floats, capi as capping, idx_code as code, date, stk_curr as curr 
            FROM q_compo_vnx_daily WHERE date = "' . $dataCurrent[0] . '" AND idx_code IN ' . $stringCode;
            $query = mysql_query($sql);
            $numRows = mysql_num_rows($query);
            if ($numRows != 0)
            {
                $dataCompo = array();
                while ($rows = mysql_fetch_assoc($query))
                {
                    $dataCompo[] = $rows;
                }
                $this->db->query('DELETE FROM  `vnxindex_data`.`idx_compo` WHERE code IN ' . $stringCode);
                $this->db->insert_batch('`vnxindex_data`.`idx_compo`', $dataCompo);
            }
            $this->db->query('UPDATE `vnxindex_data`.`idx_compo` AS a, `vnxindex_data`.`idx_sample` as b SET a.provider = b.PROVIDER where a.code=b.code');

            $sql = 'DELETE FROM  `vnxindex_data`.`idx_day` WHERE code IN ' . $stringCode;

            $this->db->query($sql);

            $sql = 'INSERT INTO `vnxindex_data`.`idx_day` (`date`,`code`,`close`) SELECT `date`, `code`, `close` FROM tmp GROUP BY code,date';

            $this->db->query($sql);

            $dateMax = $this->db->query('SELECT MAX(date) as date, YEAR(MAX(date)) as year, MONTH(MAX(date)) as month FROM tmp')->row_array();
            $this->db->query('UPDATE `vnxindex_data`.`idx_day` AS a, `vnxindex_data`.`idx_sample` as b SET a.provider = b.PROVIDER where a.code=b.code');

            $this->db->query('UPDATE `vnxindex_data`.`idx_day` SET high = close, low = close WHERE code IN ' . $stringCode);

            // CALCULATE PERFORM
            $this->db->query('SET @runtot = 0');
            $this->db->query('SET @runtot1 = 0');
            $this->db->query('SET @plr = NULL');
            $this->db->query('SET @plr1 = NULL');
            $this->db->query('DROP TABLE IF EXISTS tmp');
            $this->db->query('CREATE TEMPORARY TABLE tmp SELECT * FROM `vnxindex_data`.`idx_day` ORDER BY code, date');
            $this->db->query('DROP TABLE IF EXISTS tmp2');
            $this->db->query('CREATE TEMPORARY TABLE tmp2
            SELECT code, date, close,
            IF(@plr = code,(@runtot := ((a.close/ @runtot)-1)*100),null) AS perform,
            (@runtot := a.close ) p, (@runtot1 := a.close ) p1, @plr := code AS dummy, @plr1 := code AS dummy1 
            FROM tmp a');
           // $this->db->query('ALTER TABLE tmp2 ADD INDEX codedate USING BTREE (code, date)');
            $this->db->query('UPDATE `vnxindex_data`.`idx_day` A, tmp2 B SET A.PERFORM = B.perform 
                WHERE A.code = B.code AND A.date = B.date;');
            $this->idx_month();

            $this->idx_year();

            $this->db->query('UPDATE `vnxindex_data`.`obs_home` A, (SELECT A.*, B.perform as varmonth, C.perform as varyear FROM
(SELECT code, date, CONCAT(YEAR(date),MONTH(date)) as yyyymm, YEAR(date) as `year`, close, perform as dvar
FROM `vnxindex_data`.`idx_day` WHERE date = "' . $dateMax['date'] . '" AND code IN ' . $stringCode . ') A
LEFT JOIN (SELECT code, date, perform FROM `vnxindex_data`.`idx_month`) B ON YEAR(A.date) = YEAR(B.date) AND 
MONTH(A.date) = MONTH(B.date) AND A.code = B.code
LEFT JOIN (SELECT code, date, perform FROM `vnxindex_data`.`idx_year`) C ON YEAR(A.date) = YEAR(C.date) AND A.code = C.code) B 
SET A.date = B.date, A.yyyymm = B.yyyymm, A.yyyy = B.year, A.close = B.close, A.varmonth = B.varmonth, A.varyear = B.varyear, 
A.dvar = B.dvar
WHERE A.code = B.code');            
            $total = microtime(true) - $from;
            $result[0]['time'] = round($total, 2);
            $result[0]['task'] = 'Finish';

            echo json_encode($result);
    }
    public function update_back_data()
    {
        if ($this->input->is_ajax_request())
        {
            $from = microtime(true);

            $date = $this->input->post('date');

            $this->db->query('SET @date="' . $date . '"');

            $this->db->query('DROP TABLE IF EXISTS ifrcdata_db.tmp');

            $this->db->query('create TEMPORARY table ifrcdata_db.tmp select * from `vnxindex_data`.`idx_day`');

            $this->db->query('insert into ifrcdata_db.tmp (date, high, low, close, code, provider) 
                select date, idx_last as high, idx_last as low, idx_last as close, idx_code as code, B.provider
             from ims_production.idx_specs_histoday A, ims_production.idx_sample B where 
                A.idx_code = B.code AND A.date <= @date AND B.provider = "IFRC"');

            $this->db->query('insert into ifrcdata_db.tmp (date, high, low, close, code, provider) 
                select date, idx_last as high, idx_last as low, idx_last as close, idx_code as code, B.provider
             from pvn.idx_specs_histoday A, ims_production.idx_sample B where 
                A.idx_code = B.code AND A.date <= @date');

            $cnn = mysql_connect('ifrcims.com', 'admin_ims1', 'VietnamIfrc1');
            mysql_select_db('admin_ims', $cnn);
            mysql_query("SET NAMES utf8");

            $sql = 'SELECT date, close as high, close as low, close, idx_code as code, B.provider FROM q_index_vnx_daily A, idx_sample B WHERE 
                idx_code = B.code AND B.provider = "IFRCLAB" AND A.date <= "' . $date . '"';
            $query = mysql_query($sql, $cnn);
            $numRows = mysql_num_rows($query);
            if ($numRows > 0)
            {
                $data1AND1 = array();
                while ($rows = mysql_fetch_assoc($query))
                {
                    $data1AND1[] = $rows;
                }
                $this->db->insert_batch('ifrcdata_db.tmp', $data1AND1);
            }

            $this->db->query('UPDATE `ifrcdata_db`.`tmp` SET id = NULL');

            $this->db->query('TRUNCATE TABLE `vnxindex_data`.`idx_day`');

            $this->db->query('INSERT INTO `vnxindex_data`.`idx_day` (SELECT * FROM `ifrcdata_db`.`tmp` GROUP BY date, code ORDER BY code, date DESC)');

            $this->db->query('delete from `vnxindex_data`.`idx_compo` where provider = "IFRCLAB"');

            $sql = 'select idx_code as code, stk_code as isin, stk_name as name, stk_curr as curr, stk_shares as shares, 
            stk_float as floats, stk_capp as capping, pcls as last, date as date, wgt as weight, "IFRCLAB" as provider
            from q_compo_vnx_daily as a, idx_sample as b where a.idx_code = b.code and b.PROVIDER = "IFRCLAB"';

            $query = mysql_query($sql, $cnn);
            $numRows = mysql_num_rows($query);
            if ($numRows > 0)
            {
                $data1AND1 = array();
                while ($rows = mysql_fetch_assoc($query))
                {
                    $data1AND1[] = $rows;
                }
                $this->db->insert_batch('vnxindex_data.idx_compo', $data1AND1);
            }

            // CALCULATE PERFORM
            $this->db->query('SET @runtot = 0');
            $this->db->query('SET @runtot1 = 0');
            $this->db->query('SET @plr = NULL');
            $this->db->query('SET @plr1 = NULL');
            $this->db->query('DROP TABLE IF EXISTS tmp');
            $this->db->query('CREATE TEMPORARY TABLE tmp SELECT * FROM `vnxindex_data`.`idx_day` ORDER BY code, date');
            $this->db->query('DROP TABLE IF EXISTS tmp2');
            $this->db->query('CREATE TEMPORARY TABLE tmp2
            SELECT code, date, close,
            IF(@plr = code,(@runtot := ((a.close/ @runtot)-1)*100),null) AS perform,
            (@runtot := a.close ) p, (@runtot1 := a.close ) p1, @plr := code AS dummy, @plr1 := code AS dummy1 
            FROM tmp a');
            $this->db->query('ALTER TABLE tmp2 ADD INDEX codedate USING BTREE (code, date)');
            $this->db->query('UPDATE `vnxindex_data`.`idx_day` A, tmp2 B SET A.PERFORM = B.perform 
                WHERE A.code = B.code AND A.date = B.date;');

            $sql = 'TRUNCATE TABLE `vnxindex_data`.`idx_month`';

            $this->db->query($sql);

            $sql = 'INSERT INTO `vnxindex_data`.`idx_month` (`date`,`high`,`low`,`close`,`code`,`provider`) 
            SELECT MAX(date) as date, MAX(close) as high, MIN(close) as low, close, code, provider FROM `vnxindex_data`.`idx_day` GROUP BY YEAR(`date`), MONTH(`date`), code';

            $this->db->query($sql);

            // CALCULATE PERFORM
            $this->db->query('SET @runtot = 0');
            $this->db->query('SET @runtot1 = 0');
            $this->db->query('SET @plr = NULL');
            $this->db->query('SET @plr1 = NULL');
            $this->db->query('DROP TABLE IF EXISTS tmp');
            $this->db->query('CREATE TEMPORARY TABLE tmp SELECT * FROM `vnxindex_data`.`idx_month` ORDER BY code, date');
            $this->db->query('DROP TABLE IF EXISTS tmp2');
            $this->db->query('CREATE TEMPORARY TABLE tmp2
            SELECT code, date, close,
            IF(@plr = code,(@runtot := ((a.close/ @runtot)-1)*100),null) AS perform,
            (@runtot := a.close ) p, (@runtot1 := a.close ) p1, @plr := code AS dummy, @plr1 := code AS dummy1 
            FROM tmp a');
            $this->db->query('ALTER TABLE tmp2 ADD INDEX codedate USING BTREE (code, date)');
            $this->db->query('UPDATE `vnxindex_data`.`idx_month` A, tmp2 B SET A.PERFORM = B.perform 
                WHERE A.code = B.code AND A.date = B.date;');

            $sql = 'TRUNCATE TABLE `vnxindex_data`.`idx_year`';

            $this->db->query($sql);

            $sql = 'INSERT INTO `vnxindex_data`.`idx_year` (`date`,`high`,`low`,`close`,`code`,`provider`) 
            SELECT MAX(date) as date, MAX(close) as high, MIN(close) as low, close, code, provider FROM `vnxindex_data`.`idx_day` GROUP BY YEAR(`date`), code';

            $this->db->query($sql);

            // CALCULATE PERFORM
            $this->db->query('SET @runtot = 0');
            $this->db->query('SET @runtot1 = 0');
            $this->db->query('SET @plr = NULL');
            $this->db->query('SET @plr1 = NULL');
            $this->db->query('DROP TABLE IF EXISTS tmp');
            $this->db->query('CREATE TEMPORARY TABLE tmp SELECT * FROM `vnxindex_data`.`idx_year` ORDER BY code, date');
            $this->db->query('DROP TABLE IF EXISTS tmp2');
            $this->db->query('CREATE TEMPORARY TABLE tmp2
            SELECT code, date, close,
            IF(@plr = code,(@runtot := ((a.close/ @runtot)-1)*100),null) AS perform,
            (@runtot := a.close ) p, (@runtot1 := a.close ) p1, @plr := code AS dummy, @plr1 := code AS dummy1 
            FROM tmp a');
            $this->db->query('ALTER TABLE tmp2 ADD INDEX codedate USING BTREE (code, date)');
            $this->db->query('UPDATE `vnxindex_data`.`idx_year` A, tmp2 B SET A.PERFORM = B.perform 
                WHERE A.code = B.code AND A.date = B.date;');

            $this->db->query('UPDATE `vnxindex_data`.`obs_home` A, (SELECT A.*, B.perform as varmonth, C.perform as varyear, 
D.idx_dvar as dvar FROM
(SELECT code, date, CONCAT(YEAR(date),MONTH(date)) as yyyymm, YEAR(date) as `year`, close 
FROM `vnxindex_data`.`idx_day` WHERE date = (SELECT MAX(date) as date FROM `vnxindex_data`.`idx_day`)) A
LEFT JOIN (SELECT code, date, perform FROM `vnxindex_data`.`idx_month`) B ON A.date = B.date AND A.code = B.code
LEFT JOIN (SELECT code, date, perform FROM `vnxindex_data`.`idx_year`) C ON A.date = C.date AND A.code = C.code
LEFT JOIN (SELECT idx_code, date, idx_dvar FROM `ifrcdata_db`.`idx_specs`) D ON A.date = D.date AND A.code = D.idx_code) B 
SET A.date = B.date, A.yyyymm = B.yyyymm, A.yyyy = B.year, A.close = B.close, A.varmonth = B.varmonth, A.varyear = B.varyear, 
A.dvar = B.dvar
WHERE A.code = B.code');

            $total = microtime(true) - $from;
            $result[0]['time'] = round($total, 2);
            $result[0]['task'] = 'Finish';
            echo json_encode($result);
        }
    }

    public function update_world_indexes()
    {
        if ($this->input->is_ajax_request())
        {
            $from = microtime(true);

            $date = $this->input->post('date');

            $this->db->query('TRUNCATE TABLE `vnxindex_data`.`idx_world_indexes`');
            //$this->db->query('LOAD DATA LOCAL INFILE "//Ifrccloud/ifrcdata/IOBS/WORLD/UPLOAD/WORLD_INDEXES.csv" INTO TABLE `vnxindex_data`.`idx_world_indexes` FIELDS TERMINATED BY  ","  LINES TERMINATED BY  "\r\n" IGNORE 1 LINES (date,`code`,`open`,high,low,`close`)');
            $this->db->query('LOAD DATA LOCAL INFILE "//192.168.1.14/ifrc_production/TOP100/WORLD/UPLOAD/WORLD_INDEXES.csv" INTO TABLE `vnxindex_data`.`idx_world_indexes` FIELDS TERMINATED BY  ","  LINES TERMINATED BY  "\r\n" IGNORE 1 LINES (date,`code`,`open`,high,low,`close`)');
            $this->db->query('UPDATE `vnxindex_data`.`idx_world_indexes` SET code = REPLACE(code, "\"","")');
            $this->db->query('UPDATE `vnxindex_data`.`idx_world_indexes` AS a, `vnxindex_data`.`update_code` AS b SET a.code=b.code_correct WHERE a.code=b.code');
            $this->db->query('update `vnxindex_data`.`idx_day` as a, `vnxindex_data`.`update_code` as b set a.code=b.code_correct where a.code=b.code');
            $dataCodeDaySql = $this->db->query('SELECT DISTINCT(code) as code FROM `vnxindex_data`.`idx_day` WHERE date = "' . $date . '"')->result_array();
            $dataCodeDay = array();
            foreach ($dataCodeDaySql as $item)
            {
                $dataCodeDay[] = str_replace('"', '', $item['code']);
            }

            $dataCodeWorldSql = $this->db->query('SELECT DISTINCT(code) as code FROM `vnxindex_data`.`idx_world_indexes` WHERE date = "' . $date . '"')->result_array();
            $dataCodeInsert = array();
            $dataCodeUpdate = array();
            foreach ($dataCodeWorldSql as $item)
            {
                $item['code'] = str_replace('"', '', $item['code']);
                if (in_array($item['code'], $dataCodeDay))
                {
                    $dataCodeUpdate[] = $item['code'];
                }
                else
                {
                    $dataCodeInsert[] = $item['code'];
                }
            }

            if (count($dataCodeUpdate) != 0)
            {
                $stringCode = '("' . implode('","', $dataCodeUpdate) . '")';
                $this->db->query('UPDATE `vnxindex_data`.`idx_day` a, (SELECT * FROM `vnxindex_data`.`idx_world_indexes` WHERE code IN ' . $stringCode . ' AND date = "' . $date . '") b
                    SET a.open = b.open, a.high = b.high, a.low = b.low, a.close = b.close, a.volume = b.volume, a.adjclose = b.adjclose 
                    WHERE a.code = b.code AND a.date = B.date');
            }

            if (count($dataCodeInsert) != 0)
            {
                $stringCode = '("' . implode('","', $dataCodeInsert) . '")';
                $this->db->query('INSERT INTO `vnxindex_data`.`idx_day` (`date`, `code`, `open`, `high`, `low`, `close`, `volume`, `adjclose`) SELECT `date`, `code`, `open`, `high`, `low`, `close`, `volume`, `adjclose` FROM `vnxindex_data`.`idx_world_indexes` WHERE code IN ' . $stringCode . ' AND date = "' . $date . '"');
            }

            $this->db->query('SET @runtot = 0');
            $this->db->query('SET @runtot1 = 0');
            $this->db->query('SET @plr = NULL');
            $this->db->query('SET @plr1 = NULL');
            $this->db->query('DROP TABLE IF EXISTS tmp');
            $this->db->query('CREATE TEMPORARY TABLE tmp SELECT * FROM `vnxindex_data`.`idx_day` ORDER BY code, date');
            $this->db->query('DROP TABLE IF EXISTS tmp2');
            $this->db->query('CREATE TEMPORARY TABLE tmp2
            SELECT code, date, close,
            IF(@plr = code,(@runtot := ((a.close/ @runtot)-1)*100),null) AS perform,
            (@runtot := a.close ) p, (@runtot1 := a.close ) p1, @plr := code AS dummy, @plr1 := code AS dummy1 
            FROM tmp a');
            $this->db->query('ALTER TABLE tmp2 ADD INDEX codedate USING BTREE (code, date)');
            $this->db->query('UPDATE `vnxindex_data`.`idx_day` A, tmp2 B SET A.PERFORM = B.perform 
                WHERE A.code = B.code AND A.date = B.date;');

            $sql = 'TRUNCATE TABLE `vnxindex_data`.`idx_month`';

            $this->db->query($sql);

            $sql = 'INSERT INTO `vnxindex_data`.`idx_month` (`date`,`high`,`low`,`close`,`code`,`provider`) 
            SELECT MAX(date) as date, MAX(close) as high, MIN(close) as low, close, code, provider FROM `vnxindex_data`.`idx_day` GROUP BY YEAR(`date`), MONTH(`date`), code';

            $this->db->query($sql);

            // CALCULATE PERFORM
            $this->db->query('SET @runtot = 0');
            $this->db->query('SET @runtot1 = 0');
            $this->db->query('SET @plr = NULL');
            $this->db->query('SET @plr1 = NULL');
            $this->db->query('DROP TABLE IF EXISTS tmp');
            $this->db->query('CREATE TEMPORARY TABLE tmp SELECT * FROM `vnxindex_data`.`idx_month` ORDER BY code, date');
            $this->db->query('DROP TABLE IF EXISTS tmp2');
            $this->db->query('CREATE TEMPORARY TABLE tmp2
            SELECT code, date, close,
            IF(@plr = code,(@runtot := ((a.close/ @runtot)-1)*100),null) AS perform,
            (@runtot := a.close ) p, (@runtot1 := a.close ) p1, @plr := code AS dummy, @plr1 := code AS dummy1 
            FROM tmp a');
            $this->db->query('ALTER TABLE tmp2 ADD INDEX codedate USING BTREE (code, date)');
            $this->db->query('UPDATE `vnxindex_data`.`idx_month` A, tmp2 B SET A.PERFORM = B.perform 
                WHERE A.code = B.code AND A.date = B.date;');

            $sql = 'TRUNCATE TABLE `vnxindex_data`.`idx_year`';

            $this->db->query($sql);

            $sql = 'INSERT INTO `vnxindex_data`.`idx_year` (`date`,`high`,`low`,`close`,`code`,`provider`) 
            SELECT MAX(date) as date, MAX(close) as high, MIN(close) as low, close, code, provider FROM `vnxindex_data`.`idx_day` GROUP BY YEAR(`date`), code';

            $this->db->query($sql);

            // CALCULATE PERFORM
            $this->db->query('SET @runtot = 0');
            $this->db->query('SET @runtot1 = 0');
            $this->db->query('SET @plr = NULL');
            $this->db->query('SET @plr1 = NULL');
            $this->db->query('DROP TABLE IF EXISTS tmp');
            $this->db->query('CREATE TEMPORARY TABLE tmp SELECT * FROM `vnxindex_data`.`idx_year` ORDER BY code, date');
            $this->db->query('DROP TABLE IF EXISTS tmp2');
            $this->db->query('CREATE TEMPORARY TABLE tmp2
            SELECT code, date, close,
            IF(@plr = code,(@runtot := ((a.close/ @runtot)-1)*100),null) AS perform,
            (@runtot := a.close ) p, (@runtot1 := a.close ) p1, @plr := code AS dummy, @plr1 := code AS dummy1 
            FROM tmp a');
            $this->db->query('ALTER TABLE tmp2 ADD INDEX codedate USING BTREE (code, date)');
            $this->db->query('UPDATE `vnxindex_data`.`idx_year` A, tmp2 B SET A.PERFORM = B.perform 
                WHERE A.code = B.code AND A.date = B.date;');

            $total = microtime(true) - $from;
            $result[0]['time'] = round($total, 2);
            $result[0]['task'] = 'Finish';
            echo json_encode($result);
        }
    }

    public function update_get_hnx()
    {
        if ($this->input->is_ajax_request())
        {
            $from = microtime(true);
            $arrCode = array(
                'HNX30' => 'IFRCHNX30',
                'HNX_INDEX' => 'IFRCHNX',
                'LARGEindex' => 'IFRCHNLG',
                'MEDIUMSMALLindex1' => 'IFRCHNMS',
                'Taichinh10000' => 'IFRCHNFI',
                'xaydung04000' => 'IFRCHNCT',
                'congnghiep03000' => 'IFRCHNMF',
                'HNX30TRI' => 'IFRCHN30TRI',
                'UPCOM_INDEX' => 'IFRCUPCOM'
            );

            foreach ($arrCode as $code => $name)
            {
                $url = 'http://hnx.vn/web/guest/day?p_p_id=hnxindexday_WAR_HnxIndexportlet&p_p_lifecycle=2&p_p_state=normal&p_p_mode=view&p_p_cacheability=cacheLevelPage&p_p_col_id=column-1&p_p_col_count=1&_hnxindexday_WAR_HnxIndexportlet_type=json';
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                // curl_setopt($ch, CURLOPT_POST, 1);
                // curl_setopt($ch, CURLOPT_POSTFIELDS,"");
                $post = array(
                    'sEcho' => '3',
                    'iColumns' => '8',
                    'sColumns' => '',
                    'iDisplayStart' => '0',
                    'iDisplayLength' => '100',
                    'mDataProp_0' => '0',
                    'mDataProp_1' => '1',
                    'mDataProp_2' => '2',
                    'mDataProp_3' => '3',
                    'mDataProp_4' => '4',
                    'mDataProp_5' => '5',
                    'mDataProp_6' => '6',
                    'mDataProp_7' => '7',
                    'iSortCol_0' => '0',
                    'sSortDir_0' => 'desc',
                    'iSortingCols' => '1',
                    'bSortable_0' => 'true',
                    'bSortable_1' => 'true',
                    'bSortable_2' => 'true',
                    'bSortable_3' => 'true',
                    'bSortable_4' => 'true',
                    'bSortable_5' => 'true',
                    'bSortable_6' => 'true',
                    'bSortable_7' => 'true',
                    'songay' => '100',
                    'loaithitruong' => $code,
                    'fromDate' => '',
                    'toDate' => ''
                );
                // in real life you should use something like:
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));

                // receive server response ...
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $resultCurl = curl_exec($ch);
                $resultCurl = json_decode($resultCurl, 1);

                $arrHeader = array('no', 'date', 'open', 'high', 'low', 'close', 'point', 'change');

                $dataMax = $this->db->query('SELECT MAX(date) as date FROM vndb_hnx_index WHERE idx_code = "' . $name . '"')->row_array();
                $dateMax = strtotime($dataMax['date']);
                $dataFinal = array();
                foreach ($resultCurl['aaData'] as $item)
                {
                    $dataItem = array();
                    $dataItem['idx_code'] = $name;
                    $dataDateItem = explode('/', $item[1]);
                    $dateItem = $dataDateItem[2] . '-' . $dataDateItem[1] . '-' . $dataDateItem[0];
                    $dateItem = strtotime($dateItem);
                    if ($dateItem > $dateMax)
                    {
                        foreach ($item as $key => $value)
                        {
                            if ($key != 0 && $key != 6)
                            {
                                if ($key == 1)
                                {
                                    $dataDate = explode('/', $value);
                                    $value = $dataDate[2] . '-' . $dataDate[1] . '-' . $dataDate[0];
                                }
                                $value = str_replace(',', '.', $value);
                                $dataItem[$arrHeader[$key]] = $value;
                            }
                        }
                        $dataFinal[] = $dataItem;
                    }
                }
                if (count($dataFinal) != 0)
                {
                    $this->db->insert_batch('vndb_hnx_index', $dataFinal);
                }
            }
            $total = microtime(true) - $from;
            $result[0]['time'] = round($total, 2);
            $result[0]['task'] = 'Finish';
            echo json_encode($result);
        }
    }

    public function insertLog($log = array())
    {
        $this->db->insert('vnxindex_data.vnx_log', $log);
        return true;
    }

    public function updateLog($index = '', $action = '', $log = array())
    {
        $this->db->where('index', $index);
        $this->db->where('action', $action);
        $this->db->update('vnxindex_data.vnx_log', $log);
        return true;
    }

    public function update_get_hsx()
    {
        if ($this->input->is_ajax_request())
        {
            $result = array();
            $from = microtime(true);

            $this->getDataVN();
            $this->getDataHOSE();

            $total = microtime(true) - $from;
            $result[0]['time'] = round($total, 2);
            $result[0]['task'] = 'Finish';
            echo json_encode($result);
        }
    }

    function getDataVN()
    {
        $url = "http://hsx.vn/hsx/Default.aspx";
        $dom = new DOMDocument;
        libxml_use_internal_errors(true);
        $dom->loadHTMLFile($url);
        libxml_use_internal_errors(false);
        $content = $dom->getElementsByTagName('contenttemplate')->item(0)->nodeValue;

        $idx_code = 'IFRCVNI';
        $date = date('Y-m-d');
        $open = str_replace(",", ".", $this->getStringBetween($content, "Open: ", " -"));
        $hi = str_replace(",", ".", $this->getStringBetween($content, "Hi: ", " -"));
        $lo = str_replace(",", ".", $this->getStringBetween($content, "Lo: ", "Vol:"));
        $close = trim($this->getStringBetween($content, "Năm", " ▲"));
        if ($close == '')
        {
            $close = trim($this->getStringBetween($content, "Năm", " ▼"));
        }
        $close = str_replace(",", ".", $close);
        //$vol = $this->getStringBetween($content, "Vol:", " -");
        $insertData = array('idx_code' => $idx_code,
            'date' => $date,
            'open' => $open,
            'high' => $hi,
            'low' => $lo,
            'close' => $close);

        $checkExists = $this->db->get_where('vndb_hsx_index', array('idx_code' => $idx_code, 'date' => $date));
        $count = $checkExists->num_rows();

        if ($count == 0)
        {
            $this->db->insert('vndb_hsx_index', $insertData);
            unset($insertData);
        }
        else
        {
            $sql = "update vndb_hsx_index
                    set open = '{$open}', high = '{$hi}', low = '{$lo}', close = '{$close}'
                    where idx_code = '{$idx_code}' and date = '{$date}';";
            $this->db->query($sql);
        }
        return;
    }

    function getDataHOSE()
    {
        $url = "http://hsx.vn/hsx/Default.aspx";
        $dom = new DOMDocument;
        libxml_use_internal_errors(true);
        $dom->loadHTMLFile($url);
        libxml_use_internal_errors(false);
        $content = $dom->getElementsByTagName('contenttemplate')->item(1)->nodeValue;
        $stringListIndexes = trim($this->getStringBetween($content, "Năm", "window"));
        $outputList = str_replace(array("\r\n", "\r"), "\n", $stringListIndexes);
        $linesList = explode("\n", $outputList);
        $listIndexes = array();
        foreach ($linesList as $lineList)
        {
            if (!empty($lineList) && trim($lineList) != '')
            {
                $listIndexes[] = trim($lineList);
            }
        }
        unset($lineList);

        $listIndexesContent = trim($this->getStringBetween($content, "}});", "window"));
        $outputData = str_replace(array("\r\n", "\r"), "\n", $listIndexesContent);
        $linesData = explode("\n", $outputData);
        $listIndexesGroupData = array();
        foreach ($linesData as $lineData)
        {
            if (!empty($lineData) && trim($lineData) != '')
            {
                $listIndexesGroupData[] = trim($lineData);
            }
        }
        unset($linesData);

        if (count($listIndexes) == count($listIndexesGroupData))
        {
            $date = date('Y-m-d');
            $data = array_combine($listIndexes, $listIndexesGroupData);
            unset($listIndexes);
            unset($listIndexesGroupData);

            foreach ($data as $key => $value)
            {
                $idx_code = "IFRC{$key}";
                $open = str_replace(",", ".", $this->getStringBetween($value, "Open: ", " -"));
                $hi = str_replace(",", ".", $this->getStringBetween($value, "Hi: ", " -"));
                $lo = str_replace(",", ".", $this->getStringBetween($value, "Lo: ", "Vol:"));
                $pos = strpos($value, " ▲") == 0 ? strpos($value, " ▼") : strpos($value, " ▲");
                $close = str_replace(",", ".", substr($value, 0, $pos));
                //$vol = $this->getStringBetween($value, "Vol:", " -");
                $insertData = array('idx_code' => $idx_code,
                    'date' => $date,
                    'open' => $open,
                    'high' => $hi,
                    'low' => $lo,
                    'close' => $close);

                $checkExists = $this->db->get_where('vndb_hsx_index', array('idx_code' => $idx_code, 'date' => $date));
                $count = $checkExists->num_rows();

                if ($count == 0)
                {
                    $this->db->insert('vndb_hsx_index', $insertData);
                    unset($insertData);
                }
                else
                {
                    $sql = "update vndb_hsx_index
                            set open = '{$open}', high = '{$hi}', low = '{$lo}', close = '{$close}'
                            where idx_code = '{$idx_code}' and date = '{$date}';";
                    $this->db->query($sql);
                }
            }
        }
        return;
    }

    function getStringBetween($string = "", $start = "", $end = "")
    {
        $string = " " . $string;
        $ini = strpos($string, $start);
        if ($ini == 0)
        {
            return "";
        }
        $ini += strlen($start);
        $len = strpos($string, $end, $ini) - $ini;
        return substr($string, $ini, $len);
    }
    
    
    public function getvbpl()
    {
        $url = "http://vbpl.vn/tw/pages/home.aspx";
        $dom = new DOMDocument;
        libxml_use_internal_errors(true);
        $dom->loadHTMLFile($url);
        libxml_use_internal_errors(false);
        $content = $dom->getElementsByTagName('listLaw')->item(1)->nodeValue;
echo $content;
       // $idx_code = 'IFRCVNI';
        $date = date('Y-m-d');
        $open = str_replace(",", ".", $this->getStringBetween($content, "Open: ", " -"));
        $hi = str_replace(",", ".", $this->getStringBetween($content, "Hi: ", " -"));
        $lo = str_replace(",", ".", $this->getStringBetween($content, "Lo: ", "Vol:"));
        $close = trim($this->getStringBetween($content, "Năm", " ▲"));
        /*if ($close == '')
        {
            $close = trim($this->getStringBetween($content, "Năm", " ▼"));
        }
        $close = str_replace(",", ".", $close);
        //$vol = $this->getStringBetween($content, "Vol:", " -");
        $insertData = array('idx_code' => $idx_code,
            'date' => $date,
            'open' => $open,
            'high' => $hi,
            'low' => $lo,
            'close' => $close);

        $checkExists = $this->db->get_where('vndb_hsx_index', array('idx_code' => $idx_code, 'date' => $date));
        $count = $checkExists->num_rows();

        if ($count == 0)
        {
            $this->db->insert('vndb_hsx_index', $insertData);
            unset($insertData);
        }
        else
        {
            $sql = "update vndb_hsx_index
                    set open = '{$open}', high = '{$hi}', low = '{$lo}', close = '{$close}'
                    where idx_code = '{$idx_code}' and date = '{$date}';";
            $this->db->query($sql);
        }
        return;*/
    }

}
