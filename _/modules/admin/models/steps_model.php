<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Steps_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }
    public function process_prices_all($result_value = '') {
        $query = mysql_query("SELECT * FROM setting where `key` = 'meta_files_reset'");
        $result = mysql_fetch_row($query);
        if ($result_value != '0') {
            $this->db->query("TRUNCATE TABLE vndb_meta_prices");
            $sub_dir_exc = '\\\LOCAL\IFRCVN\VNDB\METASTOCK\PRICES\EXC\\';
            $files_exc = glob($sub_dir_exc . '*.txt');
            foreach ($files_exc as $base_exc) {
                $base_url_exc = str_replace("\\", "\\\\", $base_exc);
                $this->db->query("LOAD DATA INFILE '" . $base_url_exc . "' INTO TABLE vndb_meta_prices FIELDS TERMINATED BY '\t' IGNORE 1 LINES;");
            }

            $sub_dir_his = '\\\LOCAL\IFRCVN\VNDB\METASTOCK\PRICES\EXC\HISTORY\\';
            $files_his = glob($sub_dir_his . '*.txt');
            foreach ($files_his as $base_his) {
                $base_url_his = str_replace("\\", "\\\\", $base_his);
                $this->db->query("LOAD DATA INFILE '" . $base_url_his . "' INTO TABLE vndb_meta_prices FIELDS TERMINATED BY '\t' IGNORE 1 LINES;");
            }

            $sub_dir_miss = '\\\LOCAL\IFRCVN\VNDB\METASTOCK\PRICES\MISSING\\';
            $files_miss = glob($sub_dir_miss . '*.txt');
            foreach ($files_miss as $base_miss) {
                $base_url_miss = str_replace("\\", "\\\\", $base_miss);
                $this->db->query("LOAD DATA INFILE '" . $base_url_miss . "' INTO TABLE vndb_meta_prices FIELDS TERMINATED BY '\t' IGNORE 1 LINES;");
            }
        } else {
            $query_b1 = mysql_query("SELECT * FROM vndb_calendar_missing_file");
            $sub_dir_exc = '\\\LOCAL\IFRCVN\VNDB\METASTOCK\PRICES\\EXC\\';
            $sub_dir_miss = '\\\LOCAL\IFRCVN\VNDB\METASTOCK\PRICES\\MISSING\\';
            while ($result_b1 = mysql_fetch_assoc($query_b1)) {
                $file_exc = $sub_dir_exc . $result_b1['file_name'];
                $files_exc = glob($sub_dir_exc . '*.txt');
                if (in_array($file_exc, $files_exc)) {
                    $base_url_exc = str_replace("\\", "\\\\", $file_exc);
                    $this->db->query("LOAD DATA INFILE '" . $base_url_exc . "' INTO TABLE vndb_meta_prices FIELDS TERMINATED BY '\t' IGNORE 1 LINES;");
                }
                $file_miss = $sub_dir_miss . $result_b3['file_name'];
                $files_miss = glob($sub_dir_miss . '*.txt');
                if (in_array($file_miss, $files_miss)) {
                    $base_url_miss = str_replace("\\", "\\\\", $file_miss);
                    $this->db->query("LOAD DATA INFILE '" . $base_url_miss . "' INTO TABLE vndb_meta_prices FIELDS TERMINATED BY '\t' IGNORE 1 LINES;");
                }
            }
        }
        $this->db->query("TRUNCATE TABLE vndb_stats_day");
        $this->db->query("insert into vndb_stats_day (date, market, nbcomp, nbcomptr, svlm, strn, vlm, trn) (SELECT date, market, COUNT(date) AS nbcomp, SUM(IF(pcls<>0,1,0)) AS nbcomptr, SUM(vlm) AS svlm, SUM(trn) AS strn, SUM(IF(LENGTH(ticker)<4,vlm,0)) AS vlm, SUM(IF(LENGTH(ticker)<4,trn,0)) AS trn FROM vndb_meta_prices GROUP BY vndb_meta_prices.date, vndb_meta_prices.market ORDER BY date DESC,market)");
        $this->db->query("update vndb_calendar,vndb_stats_day set vndb_calendar.prhnx = vndb_stats_day.nbcomp
        where vndb_calendar.date = vndb_stats_day.date AND vndb_stats_day.market= 'HNX'");
        $this->db->query("update vndb_calendar,vndb_stats_day set vndb_calendar.prhsx = vndb_stats_day.nbcomp
    where vndb_calendar.date = vndb_stats_day.date AND vndb_stats_day.market= 'HSX'");
        $this->db->query("update vndb_calendar,vndb_stats_day set vndb_calendar.prupc = vndb_stats_day.nbcomp
    where vndb_calendar.date = vndb_stats_day.date AND vndb_stats_day.market= 'UPC';");
        $this->db->query("DROP TABLE IF EXISTS vndb_calendar_missing");
        $this->db->query("CREATE TABLE IF NOT EXISTS vndb_calendar_missing (SELECT date, vni, hnx, upc, prhsx, prhnx, prupc FROM vndb_calendar where (vni>0 AND ISNULL(prhsx)) OR (hnx>0 AND ISNULL(prhnx)) OR (upc>0 AND ISNULL(prupc)) OR (ISNULL(upc) AND ISNULL(prupc)))");
        $query_b9 = mysql_query("SELECT * FROM vndb_calendar_missing");
        $this->db->query("TRUNCATE vndb_calendar_missing_file");
        $num_b9 = mysql_num_rows($query_b9);
        if ($num_b9 != 0) {
            while ($result_b9 = mysql_fetch_assoc($query_b9)) {
                $data_b9[] = $result_b9;
            }
            foreach ($data_b9 as $i_b9) {
                if ($i_b9['vni'] != "" && $i_b9['prhsx'] == "") {
                    $date = $i_b9['date'];
                    $y = substr($date, 0, 4);
                    $m = substr($date, 5, 2);
                    $d = substr($date, 8, 2);
                    $cld_m_f = "EXC_HSX_" . $y . $m . $d . ".txt";
                    $this->db->query("INSERT INTO vndb_calendar_missing_file (`file_name`) VALUES ('" . $cld_m_f . "')");
                }
                if ($i_b9['hnx'] != "" && $i_b9['prhnx'] == "") {
                    $date = $i_b9['date'];
                    $y = substr($date, 0, 4);
                    $m = substr($date, 5, 2);
                    $d = substr($date, 8, 2);
                    $cld_m_f = "EXC_HNX_" . $y . $m . $d . ".txt";
                    $this->db->query("INSERT INTO vndb_calendar_missing_file (`file_name`) VALUES ('" . $cld_m_f . "')");
                }
                if ($i_b9['upc'] != "" && $i_b9['prupc'] == "") {
                    $date = $i_b9['date'];
                    $y = substr($date, 0, 4);
                    $m = substr($date, 5, 2);
                    $d = substr($date, 8, 2);
                    $cld_m_f = "EXC_UPC_" . $y . $m . $d . ".txt";
                    $this->db->query("INSERT INTO vndb_calendar_missing_file (`file_name`) VALUES ('" . $cld_m_f . "')");
                }
                if ($i_b9['upc'] == "" && $i_b9['prupc'] == "") {
                    $date = $i_b9['date'];
                    $y = substr($date, 0, 4);
                    $m = substr($date, 5, 2);
                    $d = substr($date, 8, 2);
                    $cld_m_f = "EXC_UPC_" . $y . $m . $d . ".txt";
                    $this->db->query("INSERT INTO vndb_calendar_missing_file (`file_name`) VALUES ('" . $cld_m_f . "')");
                }
            }
        }
        $this->db->query("update vndb_meta_prices set vndb_meta_prices.last = 0;");
        $this->db->query("update vndb_meta_prices set last = pcls where ticker = ticker and market = market and yyyymmdd = yyyymmdd;");
        $this->db->query("update vndb_meta_prices set last = pref where last=0 and ticker = ticker and market = market and yyyymmdd = yyyymmdd;");
        $date_now = date('Y-m-d', time());
        $arr['database'] = array('vndb_indexes', 'vndb_indexes_history', 'vndb_calendar', 'vndb_calendar_missing', 'vndb_meta_prices', 'vndb_metafile', 'vndb_stats_day');
        $arr['market_1'] = array('First', 'HNX', 'VNI', 'UPC');
        $arr['market_2'] = array('First', 'HNX', 'HSX', 'UPC');
        $arr['market_3'] = array('First', '^HASTC', '^VNINDEX');
        $query_report = mysql_query("SELECT * FROM vndb_reports where date = '" . $date_now . "'");
        $num_report = mysql_num_rows($query_report);
        if ($num_report != 0) {
            $this->db->query("DELETE FROM vndb_reports where date = '" . $date_now . "'");
        }
        foreach ($arr['database'] as $db) {
            if ($db == 'vndb_meta_prices' || $db == 'vndb_stats_day') {
                foreach ($arr['market_2'] as $mk2) {
                    if ($mk2 == 'First') {
                        $this->db->query("insert into vndb_reports (date,task,start_date,end_date,market,number)select now() as date, '" . $db . "' as task ,min(date) Min_date, max(date) Max_date, 'ALL' as market, count(*) from $db;");
                        if ($db == 'vndb_meta_prices') {
                            $this->db->query("insert into vndb_reports (date,task,start_date,end_date,market,number)select now() as date, '" . $db . ".last=0' as task ,min(date) Min_date, max(date) Max_date,'ALL' as market, count(*) from $db where last=0;");
                        }
                    } else {
                        $this->db->query("insert into vndb_reports (date,task,start_date,end_date,market,number)select now() as date, '" . $db . "' as task ,min(date) Min_date, max(date) Max_date,market, count(*) from $db where market='" . $mk2 . "';");
                        if ($db == 'vndb_meta_prices') {
                            $this->db->query("insert into vndb_reports (date,task,start_date,end_date,market,number)select now() as date, '" . $db . ".last=0' as task ,min(date) Min_date, max(date) Max_date,'" . $mk2 . "' as market, count(*) from $db where market='" . $mk2 . "' and last=0;");
                        }
                    }
                }
            } elseif ($db == 'vndb_metafile') {
                foreach ($arr['market_3'] as $mk3) {
                    if ($mk3 == 'First') {
                        $this->db->query("insert into vndb_reports (date,task,start_date,end_date,market,number)select now() as date, '" . $db . "' as task ,min(date) Min_date, max(date) Max_date, 'ALL' as market, count(*) from $db;");
                    } else {
                        $this->db->query("insert into vndb_reports (date,task,start_date,end_date,market,number)select now() as date, '" . $db . "' as task ,min(date) Min_date, max(date) Max_date,'" . $mk3 . "' as market, count(*) from $db where ticker = '" . $mk3 . "';");
                    }
                }
            } else {
                foreach ($arr['market_1'] as $mk1) {
                    if ($mk1 == 'First') {
                        $this->db->query("insert into vndb_reports (date,task,start_date,end_date,market,number)select now() as date, '" . $db . "' as task ,min(date) Min_date, max(date) Max_date, 'ALL' as market, count(*) from $db;");
                    } else {
                        $this->db->query("insert into vndb_reports (date,task,start_date,end_date,market,number)select now() as date, '" . $db . "' as task ,min(date) Min_date, max(date) Max_date,'" . $mk1 . "' as market, count(*) from $db where $mk1;");
                    }
                }
            }
        }
    }

}
