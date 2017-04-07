<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class UpdateReturn_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function insert_data() {
        set_time_limit(0);
        $this->db->query('set innodb_lock_wait_timeout=100000');
        $return = array();
        $time_start = microtime(true);
        //1
        $sql = "DROP TABLE IF EXISTS vndb_return";
        $this->db->simple_query($sql);
        $sql = "CREATE TABLE IF NOT EXISTS `vndb_return` (
                `id` int(15) NOT NULL AUTO_INCREMENT,
                `ticker` varchar(10) DEFAULT NULL,
                `market` varchar(5) DEFAULT NULL,
                `date` date DEFAULT NULL,
                `pdate` date DEFAULT NULL,
                `last` double DEFAULT NULL,
                `adj_pcls` double DEFAULT NULL,
                `rt_pcls` double DEFAULT NULL,
                `pcls_rts` double DEFAULT NULL,
                `pcls_vst` double DEFAULT NULL,
                `pcls_pt1` double DEFAULT NULL,
                `pcls_pt2` double DEFAULT NULL,
                `pcls_caf` double DEFAULT NULL,
                `rcls_rts` double DEFAULT NULL,
                `rcls_vst` double DEFAULT NULL,
                `rcls_pt1` double DEFAULT NULL,
                `rcls_pt2` double DEFAULT NULL,
                `rcls_caf` double DEFAULT NULL,
                `yyyymmdd` varchar(8) DEFAULT NULL,
                PRIMARY KEY (`id`),
                KEY `tickerdate` (`ticker`,`date`) USING BTREE,
                KEY `tickeryyyymmdd` (`ticker`,`yyyymmdd`) USING BTREE
                )";
        $this->db->simple_query($sql);
        //repair table
        //$sql = "REPAIR TABLE vndb_return \G;";
        //$this->db->simple_query($sql);
        //end repair table
        $time_end = microtime(true);
        $return['time-1'] = number_format(($time_end - $time_start), 3);
        $time_start = microtime(true);
        //2
        $sql = "INSERT INTO vndb_return (ticker,date,yyyymmdd,last, market)(SELECT ticker,date,yyyymmdd,last, market FROM vndb_prices_uni ORDER BY ticker ASC, date DESC)";
        $this->db->simple_query($sql);
        //optimize table
        //$sql = "ANALYZE TABLE vndb_return";
        //$this->db->simple_query($sql);
        //$sql = "CHECK TABLE vndb_return EXTENDED";
        //$this->db->simple_query($sql);
        //$sql = "OPTIMIZE TABLE vndb_return \G";
        //$this->db->simple_query($sql);
        //end optimize table
        $time_end = microtime(true);
        $return['time-2'] = number_format(($time_end - $time_start), 3);
        $time_start = microtime(true);
        //3
        $sql = "UPDATE vndb_return, vndb_calendar_shli SET vndb_return.pdate = vndb_calendar_shli.pdate where vndb_return.date = vndb_calendar_shli.date";
        $this->db->simple_query($sql);
        $time_end = microtime(true);
        $return['time-3'] = number_format(($time_end - $time_start), 3);
        $time_start = microtime(true);
        //4
        $sql = "UPDATE vndb_return,vndb_phutoan_old set vndb_return.pcls_pt1=vndb_phutoan_old.`close` WHERE vndb_return.ticker=vndb_phutoan_old.ticker AND vndb_return.yyyymmdd=vndb_phutoan_old.dtyyyymmdd";
        $this->db->simple_query($sql);
        $time_end = microtime(true);
        $return['time-4'] = number_format(($time_end - $time_start), 3);
        $time_start = microtime(true);
        //5
        $sql = "UPDATE vndb_return,vndb_cafef set vndb_return.pcls_caf=vndb_cafef.`close` WHERE vndb_return.ticker=vndb_cafef.ticker AND vndb_return.yyyymmdd=vndb_cafef.yyyymmdd";
        $this->db->simple_query($sql);
        $time_end = microtime(true);
        $return['time-5'] = number_format(($time_end - $time_start), 3);
        $time_start = microtime(true);
        //6
        $sql = "UPDATE vndb_return,vndb_reuters set vndb_return.pcls_rts=vndb_reuters.adj_pcls WHERE vndb_return.ticker=vndb_reuters.ticker AND vndb_return.yyyymmdd=vndb_reuters.yyyymmdd";
        $this->db->simple_query($sql);
        $time_end = microtime(true);
        $return['time-6'] = number_format(($time_end - $time_start), 3);
        $time_start = microtime(true);
        //7
        $sql = "UPDATE vndb_return,vndb_phutoan_new set vndb_return.pcls_pt2=vndb_phutoan_new.`close` WHERE vndb_return.ticker=vndb_phutoan_new.ticker AND vndb_return.yyyymmdd=vndb_phutoan_new.dtyyyymmdd";
        $this->db->simple_query($sql);
        $time_end = microtime(true);
        $return['time-7'] = number_format(($time_end - $time_start), 3);
        $time_start = microtime(true);
        //8
        $sql = "UPDATE vndb_return,vndb_vietstock set vndb_return.pcls_vst=vndb_vietstock.`adj_pcls` WHERE vndb_return.ticker=vndb_vietstock.ticker AND vndb_return.yyyymmdd=vndb_vietstock.yyyymmdd";
        $this->db->simple_query($sql);
        $time_end = microtime(true);
        $return['time-8'] = number_format(($time_end - $time_start), 3);

        return $return;
    }

    function clear_data() {
        set_time_limit(0);
        $this->db->query('set innodb_lock_wait_timeout=100000');
        $return = array();

        $time_start = microtime(true);
        //-- INSERT INTO `vndb_return_des`
        $sql = "DROP TABLE IF EXISTS vndb_return_des";
        $this->db->simple_query($sql);
        $sql = "CREATE TABLE IF NOT EXISTS `vndb_return_des` (
                `ticker` varchar(10) DEFAULT NULL,
                `pdate` date DEFAULT NULL,
                `date` date DEFAULT NULL,
                `yyyymmdd` int(11) DEFAULT NULL,
                `last` double DEFAULT NULL,
                `adj_pcls` double DEFAULT NULL,
                `rt_pcls` double DEFAULT NULL,
                `pcls_rts` double DEFAULT NULL,
                `rcls_tmp` double DEFAULT NULL,
                `pcls_tmp` double DEFAULT NULL,
                `pcls_pt1` double DEFAULT NULL,
                `pcls_pt2` double DEFAULT NULL,
                `pcls_caf` double DEFAULT NULL,
                `rcls_rts` double DEFAULT NULL,
                `rcls_vst` double DEFAULT NULL,
                `rcls_pt1` double DEFAULT NULL,
                `rcls_pt2` double DEFAULT NULL,
                `rcls_caf` double DEFAULT NULL,
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `pcls_vst` double DEFAULT NULL,
                `market` varchar(5) DEFAULT NULL,
                PRIMARY KEY (`id`),
                KEY `date` (`date`),
                KEY `yyyymmdd` (`yyyymmdd`),
                KEY `tickeryyyymmdd` (`ticker`,`yyyymmdd`),
                KEY `tickerdate` (`ticker`,`date`)
                )";
        $this->db->simple_query($sql);

        $sql = "insert into `vndb_return_des` (`ticker`, `market`, `date`,`pdate`,`last`, `pcls_rts`,`pcls_vst`, `pcls_pt1`, `pcls_pt2`, `pcls_caf`, `yyyymmdd`)(
          select
          `ticker`, `market`, `date`, `pdate`, `last`, `pcls_rts`, `pcls_vst`, `pcls_pt1` * 1000 as `pcls_pt1`, `pcls_pt2` * 1000 as `pcls_pt2`, `pcls_caf` * 1000 as `pcls_caf`, `yyyymmdd`
          from vndb_return
          where date <= '2013-01-31'
          and length(ticker) = 3
          and market != 'UPC'
          order by ticker, `date` desc
          )";
        //-- phan chu gui
        $this->db->simple_query($sql);

        $arr = $this->db->query("select id,pcls_caf,pcls_pt2,pcls_pt1,pcls_rts,pcls_vst from vndb_return_des where 1=1  ORDER BY `ticker`, `date` desc")->result_array();
        $arr_plus = array(
            "3" => "pcls_caf",
            "4" => "pcls_pt2",
            '5' => 'pcls_pt1',
            '6' => 'pcls_rts',
            '7'=>'pcls_vst'
        );
        if (count($arr) > 0) {
            $max = count($arr);
            foreach ($arr_plus as $value_arr_plus) {
                for ($i = 0; $i < $max - 1; $i++) {
                    if ($arr[$i][$value_arr_plus] != 0 || !empty($arr[$i][$value_arr_plus])) {
                        for ($j = $i + 1; $j < $max; $j++) {
                            if ($arr[$j][$value_arr_plus] != 0 || !empty($arr[$j][$value_arr_plus])) {
                                if ($arr[$j][$value_arr_plus] == $arr[$i][$value_arr_plus]) {
                                    for ($k = $i + 1; $k < $j; $k++) {
                                        if (isset($k)) {
                                            $arr[$k][$value_arr_plus] = $arr[$i][$value_arr_plus];
                                        }
                                    }
                                }
                                $i = $j - 1;
                                break;
                            }
                        }
                    }
                }
            }
            error_reporting(E_ALL ^ E_NOTICE);
            $this->db->trans_begin();
            $this->db->query('set innodb_lock_wait_timeout=100000');
            $this->db->update_batch('vndb_return_des', $arr, 'id');
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
            } else {
                $this->db->trans_commit();
            }
        }
        $time_end = microtime(true);
        $return['time-9'] = number_format(($time_end - $time_start), 3);
        return $return;
    }

    function calculate_return() {
        set_time_limit(0);
        $this->db->query('set innodb_lock_wait_timeout=100000');
        $return = array();
        $time_start = microtime(true);
        $sql = "drop table if exists vndb_return_cmp";
        $this->db->simple_query($sql);
        $time_end = microtime(true);
        $return['time-10'] = number_format(($time_end - $time_start), 3);
        $time_start = microtime(true);
        $sql = "create table vndb_return_cmp (select vndb_return_des.ticker, vndb_return_des.date, A.maxdate, vndb_return_des.last
                from vndb_return_des, (select ticker, date, max(date) as maxdate from vndb_return_des group by ticker) A
                where vndb_return_des.ticker = A.ticker
                and vndb_return_des.date = A.maxdate
                group by vndb_return_des.ticker)";
        $this->db->simple_query($sql);
        $time_end = microtime(true);
        $return['time-11'] = number_format(($time_end - $time_start), 3);
        $time_start = microtime(true);

        $sql = "update vndb_return_des,vndb_return_cmp set vndb_return_des.adj_pcls = vndb_return_cmp.last
          where vndb_return_des.date = vndb_return_cmp.maxdate and vndb_return_des.ticker= vndb_return_cmp.ticker and isnull(vndb_return_des.adj_pcls)";
        $this->db->simple_query($sql);
        $time_end = microtime(true);
        $return['time-12'] = number_format(($time_end - $time_start), 3);
        $time_start = microtime(true);

        $sql = "update vndb_return_des set rcls_tmp = null, rcls_caf = null, rcls_vst = null, rcls_pt1 = null, rcls_pt2 = null, rcls_rts = null";
        $this->db->simple_query($sql);
        $time_end = microtime(true);
        $return['time-13'] = number_format(($time_end - $time_start), 3);

        // tinh smoot
        $time_start = microtime(true);

        //  -- RETURN CAF
        $sql = "update vndb_return_des, vndb_return_des as vndb_return_des2 set vndb_return_des.rcls_caf = (vndb_return_des.pcls_caf / vndb_return_des2.pcls_caf)-1
          where vndb_return_des.ticker = vndb_return_des2.ticker and vndb_return_des.pdate = vndb_return_des2.date and vndb_return_des.pcls_caf * vndb_return_des2.pcls_caf>0";
        $this->db->simple_query($sql);
        $time_end = microtime(true);
        $return['time-14'] = number_format(($time_end - $time_start), 3);
        $time_start = microtime(true);

        //  -- RETURN VST
        $sql = "update vndb_return_des, vndb_return_des as vndb_return_des2 set vndb_return_des.rcls_vst = (vndb_return_des.pcls_vst / vndb_return_des2.pcls_vst)-1
          where vndb_return_des.ticker = vndb_return_des2.ticker and vndb_return_des.pdate = vndb_return_des2.date and vndb_return_des.pcls_vst * vndb_return_des2.pcls_vst>0";
        $this->db->simple_query($sql);
        $time_end = microtime(true);
        $return['time-15'] = number_format(($time_end - $time_start), 3);
        $time_start = microtime(true);

        // -- RETURN PT1
        $sql = "update vndb_return_des, vndb_return_des as vndb_return_des2 set vndb_return_des.rcls_pt1 = (vndb_return_des.pcls_pt1 / vndb_return_des2.pcls_pt1)-1
          where vndb_return_des.ticker = vndb_return_des2.ticker and vndb_return_des.pdate = vndb_return_des2.date and vndb_return_des.pcls_pt1 * vndb_return_des2.pcls_pt1>0";
        $this->db->simple_query($sql);
        $time_end = microtime(true);
        $return['time-16'] = number_format(($time_end - $time_start), 3);

        $time_start = microtime(true);

        //-- RETURN PT2
        $sql = "update vndb_return_des, vndb_return_des as vndb_return_des2 set vndb_return_des.rcls_pt2 = (vndb_return_des.pcls_pt1 / vndb_return_des2.pcls_pt2)-1
          where vndb_return_des.ticker = vndb_return_des2.ticker and vndb_return_des.pdate = vndb_return_des2.date and vndb_return_des.pcls_pt2 * vndb_return_des2.pcls_pt2>0";
        $this->db->simple_query($sql);
        $time_end = microtime(true);
        $return['time-17'] = number_format(($time_end - $time_start), 3);

        $time_start = microtime(true);

        //  -- RETURN RTS
        $sql = "update vndb_return_des, vndb_return_des as vndb_return_des2 set vndb_return_des.rcls_rts = (vndb_return_des.pcls_rts / vndb_return_des2.pcls_rts)-1
          where vndb_return_des.ticker = vndb_return_des2.ticker and vndb_return_des.pdate = vndb_return_des2.date and vndb_return_des.pcls_rts * vndb_return_des2.pcls_rts>0";
        $this->db->simple_query($sql);
        $time_end = microtime(true);
        $return['time-18'] = number_format(($time_end - $time_start), 3);

        $time_start = microtime(true);
        $sql = "update vndb_return_des set rt_pcls=rcls_rts where !ISNULL(rcls_rts)";
        $this->db->simple_query($sql);
        $sql = "update vndb_return_des set rt_pcls=rcls_caf where ISNULL(rt_pcls) and !ISNULL(rcls_caf)";
        $this->db->simple_query($sql);
        $sql = "update vndb_return_des set rt_pcls=rcls_vst where ISNULL(rt_pcls) and !ISNULL(rcls_vst)";
        $this->db->simple_query($sql);
        $sql = "update vndb_return_des set rt_pcls=rcls_pt1 where ISNULL(rt_pcls) and !ISNULL(rcls_pt1)";
        $this->db->simple_query($sql);
        $sql = "update vndb_return_des set rt_pcls=rcls_pt2 where ISNULL(rt_pcls) and !ISNULL(rcls_pt2)";
        $this->db->simple_query($sql);
        $time_end = microtime(true);
        $return['time-19'] = number_format(($time_end - $time_start), 3);
        return $return;
    }

    function adjusted_price() {
        $this->db->query('set innodb_lock_wait_timeout=100000');
        set_time_limit(0);
        $return = array();
        $time_start = microtime(true);
        //update previous adj_pcls = adj_pcls / (rt_pcls + 1)
        //get list ticker to update
        $sql = "select DISTINCT ticker
                from vndb_return_des
                order by ticker";
        $ticker = $this->db->query($sql)->result_array();
        if (is_array($ticker) == TRUE && count($ticker) > 0) {
            foreach ($ticker as $key => $value) {
                $sql = "SELECT * FROM vndb_return_des WHERE ticker = '{$value['ticker']}' GROUP BY ticker, date ORDER BY ticker, date DESC";
                $arr = $this->db->query($sql)->result_array();
                if (is_array($arr) == TRUE && count($arr) > 0) {
                    foreach ($arr as $k => $v) {
                        if ($k > 0) {
                            $arr[$k]['adj_pcls'] = $arr[$k - 1]['adj_pcls'] / ($arr[$k - 1]['rt_pcls'] + number_format(1, 12));
                        }
                    }
                }
                $sql = "DELETE FROM vndb_return_des WHERE ticker = '{$value['ticker']}'";
                $this->db->query($sql);
                $this->db->insert_batch('vndb_return_des', $arr);
                unset($arr);
            }
        }
        //end update previous adj_pcls
        $time_end = microtime(true);
        $return['time-20'] = number_format(($time_end - $time_start), 3);
        return $return;
    }

}