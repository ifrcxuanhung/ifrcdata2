<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/* * ******************************************************************************************************************* *
 *   Author: Minh Đẹp Trai                                                                                               *
 * * ******************************************************************************************************************* */

class Steps extends Admin {

    public function __construct() {
        parent::__construct();
        $this->load->model('Steps_model', 'steps_model');
    }

    public function update_indexes() {
        $this->template->write_view('content', 'steps/update_indexes', $this->data);
        $this->template->write('title', 'Update VNDB Step 1');
        $this->template->render();
    }

    public function update_calendar() {
        $this->template->write_view('content', 'steps/update_calendar', $this->data);
        $this->template->write('title', 'Update VNDB Step 2');
        $this->template->render();
    }

    public function update_prices_all() {
        $this->template->write_view('content', 'steps/update_prices_all', $this->data);
        $this->template->write('title', 'Update VNDB Step 3');
        $this->template->render();
    }

    public function import_prices() {
        $this->template->write_view('content', 'steps/import_prices', $this->data);
        $this->template->write('title', 'Update VNDB Step 3');
        $this->template->render();
    }

    public function import_prices_missing() {
        $this->template->write_view('content', 'steps/import_prices_missing', $this->data);
        $this->template->write('title', 'Update VNDB Step 3');
        $this->template->render();
    }

    public function update_shares_import_all() {
        $this->template->write_view('content', 'steps/update_shares_import_all', $this->data);
        $this->template->write('title', 'Update VNDB Step 5 - Import');
        $this->template->render();
    }

    public function update_shares_import_shares() {
        $this->template->write_view('content', 'steps/update_shares_import_shares', $this->data);
        $this->template->write('title', 'Update VNDB Step 5 - Import');
        $this->template->render();
    }

    public function update_shares_import_references() {
        $this->template->write_view('content', 'steps/update_shares_import_references', $this->data);
        $this->template->write('title', 'Update VNDB Step 5 - Import');
        $this->template->render();
    }

    public function update_shares_update_all() {
        $this->template->write_view('content', 'steps/update_shares_update_all', $this->data);
        $this->template->write('title', 'Update VNDB Step 5 - Update');
        $this->template->render();
    }

    public function update_shares_update_shli() {
        $this->template->write_view('content', 'steps/update_shares_update_shli', $this->data);
        $this->template->write('title', 'Update VNDB Step 5 - Update');
        $this->template->render();
    }

    public function update_shares_update_shou() {
        $this->template->write_view('content', 'steps/update_shares_update_shou', $this->data);
        $this->template->write('title', 'Update VNDB Step 5 - Update');
        $this->template->render();
    }

    public function update_shares_clean() {
        $this->template->write_view('content', 'steps/update_shares_clean', $this->data);
        $this->template->write('title', 'Update VNDB Step 5 - Clean');
        $this->template->render();
    }

    public function update_missing_shares() {
        $this->template->write_view('content', 'steps/update_missing_shares', $this->data);
        $this->template->write('title', 'Update VNDB Step 5 - Missing');
        $this->template->render();
    }

    public function update_adjusted_close() {
        $this->template->write_view('content', 'steps/update_adjusted_close', $this->data);
        $this->template->write('title', 'Update VNDB Step 3 - Adjusted Close');
        $this->template->render();
    }

	public function ownership_all() {
        $this->template->write_view('content', 'steps/ownership_all', $this->data);
        $this->template->write('title', 'Update VNDB Step 6 - Ownership All');
        $this->template->render();
    }

    public function import_ownership() {
        $this->template->write_view('content', 'steps/import_ownership', $this->data);
        $this->template->write('title', 'Update VNDB Step 6 - Import Ownership');
        $this->template->render();
    }

    public function update_free_float() {
        $this->template->write_view('content', 'steps/update_free_float', $this->data);
        $this->template->write('title', 'Update VNDB Step 6 - Free Float');
        $this->template->render();
    }

    public function update_anomalies_share() {
        $this->template->write_view('content', 'steps/update_anomalies_share', $this->data);
        $this->template->write('title', 'Update VNDB Step 5 - Anomalies Share');
        $this->template->render();
    }

    public function update_dividend_all() {
        $this->template->write_view('content', 'steps/update_dividend_all', $this->data);
        $this->template->write('title', 'Update VNDB Step 8 - Dividend All');
        $this->template->render();
    }

    public function download_dividend() {
        $this->template->write_view('content', 'steps/download_dividend', $this->data);
        $this->template->write('title', 'Update VNDB Step 8 - Dividend All');
        $this->template->render();
    }

    public function update_dividend_import() {
        $this->template->write_view('content', 'steps/update_dividend_import', $this->data);
        $this->template->write('title', 'Update VNDB Step 4 - Dividend Import');
        $this->template->render();
    }

    public function update_dividend_clean() {
        $this->template->write_view('content', 'steps/update_dividend_clean', $this->data);
        $this->template->write('title', 'Update VNDB Step 4 - Dividend Clean');
        $this->template->render();
    }

    public function shares_daily() {
        $this->template->write_view('content', 'steps/shares_daily', $this->data);
        $this->template->write('title', 'Update Shares Daily');
        $this->template->render();
    }

    public function process_indexes() {
        if ($this->input->is_ajax_request()) {
            $from = microtime(true);
            /*             * ************
             *   Download  *
             * ************ */
            $yesterday_y = date('Ymd', strtotime(date('Y-m-d')));
            $yesterday_d = date('dmY', strtotime(date('Y-m-d')));
            $arrDownload = array();
            $arrDownload[] = 'http://images1.cafef.vn/data/' . $yesterday_y . '/CafeF.Index.Upto' . $yesterday_d . '.zip';
            foreach ($arrDownload as $value) {
                if (url_exists($value)) {
                    $file = end(explode('/', $value));
                    $f = fopen('\\\LOCAL\IFRCVN\VNDB\METASTOCK\INDEXES\\CAF\\' . $file, 'w');
                    $data = file_get_contents($value);
                    fwrite($f, $data);
                    fclose($f);
                }
            }
            /*             * **********
             *   Import  *
             * ********** */
            $this->db->query("TRUNCATE TABLE vndb_metafile");
            $sub_dir = '\\\LOCAL\IFRCVN\VNDB\METASTOCK\INDEXES\\CAF\\';
            $sub_dir_old = '\\\LOCAL\IFRCVN\VNDB\METASTOCK\INDEXES\\CAF\\OLD\\';
            $files_zip = glob($sub_dir . '*.zip');
            foreach ($files_zip as $base_zip) {
                $zip = new ZipArchive;
                if ($zip->open($base_zip) === TRUE) {
                    for ($i = 0; $i < $zip->numFiles; $i++) {
                        $stat = $zip->statIndex($i);
                        $zip->renameName(basename($stat['name']), 'CAF_INDEX.csv');
                    }
                    $zip->close();
                    $zip->open($base_zip);
                    $zip->extractTo($sub_dir);
                    $zip->close();
                }
            }
            $files_csv = glob($sub_dir . '*.csv');
            foreach ($files_csv as $base_csv) {
                $base_url_csv = str_replace("\\", "\\\\", $base_csv);
                $this->db->query("LOAD DATA LOCAL INFILE '" . $base_url_csv . "' INTO TABLE vndb_metafile FIELDS TERMINATED BY ',' IGNORE 1 LINES");
            }
			
			$sub_cph = '\\\LOCAL\IFRCVN\VNDB\METASTOCK\INDEXES\CPH\\';
			$files_txt = glob($sub_cph . '*.txt');
            foreach ($files_txt as $base_txt) {
				$filename = basename($base_txt, ".txt");
				if($filename == 'CPH_INDEX'){
                                    $base_url_txt = str_replace("\\", "\\\\", $base_txt);
                                    $this->db->query("LOAD DATA LOCAL INFILE '" . $base_url_txt . "' INTO TABLE vndb_metafile FIELDS TERMINATED BY ',' IGNORE 1 LINES");
				}
            }
			
            $this->db->query("UPDATE vndb_metafile SET vndb_metafile.ticker='^VNINDEX' where vndb_metafile.ticker='VNINDEX'");

            $this->db->query("UPDATE vndb_metafile SET vndb_metafile.ticker='^HASTC' where vndb_metafile.ticker='HNX-INDEX'");

            $this->db->query("UPDATE vndb_metafile SET vndb_metafile.date = date_format(str_to_date(vndb_metafile.dtyyyymmdd,'%Y%m%d'),'%Y-%m-%d')");

            $this->db->query("TRUNCATE vndb_indexes");
            $this->db->query("INSERT INTO vndb_indexes(date,dtyyyymmdd)( SELECT date,dtyyyymmdd FROM vndb_metafile GROUP BY dtyyyymmdd ORDER BY dtyyyymmdd DESC)");
            $this->db->query("UPDATE vndb_indexes, vndb_metafile SET vndb_indexes.vni = vndb_metafile.`close` WHERE vndb_metafile.dtyyyymmdd = vndb_indexes.dtyyyymmdd AND vndb_metafile.`ticker` = '^VNINDEX'");
            $this->db->query("UPDATE vndb_indexes, vndb_metafile SET vndb_indexes.hnx = vndb_metafile.`close` WHERE vndb_metafile.dtyyyymmdd = vndb_indexes.dtyyyymmdd AND vndb_metafile.`ticker` = '^HASTC'");
            $this->db->query("UPDATE vndb_indexes, vndb_metafile SET vndb_indexes.upc = vndb_metafile.`close` WHERE vndb_metafile.dtyyyymmdd = vndb_indexes.dtyyyymmdd AND vndb_metafile.`ticker` = '^UPCOM'");
            $this->db->query("INSERT INTO vndb_indexes (date, dtyyyymmdd, vni, hnx, upc) (SELECT date, dtyyyymmdd, vni, hnx, upc FROM vndb_indexes_history GROUP BY dtyyyymmdd)");
            $query_b10 = mysql_query("SELECT * FROM vndb_indexes_history");
            $num_b10 = mysql_num_rows($query_b10);
            if ($num_b10 != 0) {
                $this->db->query("INSERT INTO vndb_indexes_history (date, dtyyyymmdd, vni, hnx, upc) (Select a.date, a.dtyyyymmdd, a.vni, a.hnx, a.upc from vndb_indexes AS a left join vndb_indexes_history AS b on a.date = b.date where b.date is null GROUP BY a.date);");
            } else {
                $this->db->query("INSERT INTO vndb_indexes_history (date, dtyyyymmdd, vni, hnx, upc) (Select a.date, a.dtyyyymmdd, a.vni, a.hnx, a.upc from vndb_indexes AS a GROUP BY a.date);");
            }
            $total = microtime(true) - $from;
            $result[0]['time'] = round($total, 2);
            $result[0]['task'] = 'Update Indexes';
            echo json_encode($result);
        }
    }

    public function process_calendar() {
        if ($this->input->is_ajax_request()) {
            $from = microtime(true);
            $query_b1 = mysql_query("select * from vndb_calendar");
            $num_b1 = mysql_num_rows($query_b1);
            if ($num_b1 != 0) {
                $this->db->query("INSERT INTO vndb_calendar (date, dtyyyymmdd, vni, hnx, upc)(Select a.date, a.dtyyyymmdd, a.vni, a.hnx, a.upc from vndb_indexes_history AS a left join vndb_calendar AS b on a.date = b.date where b.date is null GROUP BY a.date);");
                $this->db->query("update vndb_calendar a, (Select a.date, a.dtyyyymmdd, a.vni, a.hnx, a.upc from vndb_indexes_history AS a left join vndb_calendar AS b on a.date = b.date where b.prhnx is null and b.prhsx is null and b.prupc is null GROUP BY a.date) b set a.vni = b.vni, a.hnx = b.hnx, a.upc = b.upc where a.date = b.date");
            } else {
                $this->db->query("INSERT INTO vndb_calendar (date, dtyyyymmdd, vni, hnx, upc)(Select a.date, a.dtyyyymmdd, a.vni, a.hnx, a.upc from vndb_indexes_history AS a GROUP BY a.date);");
            }
            $this->db->query("TRUNCATE TABLE vndb_stats_day");
            $this->db->query("insert into vndb_stats_day (date, market, nbcomp, nbcomptr, svlm, strn, vlm, trn) (SELECT date, market, COUNT(date) AS nbcomp, SUM(IF(pcls<>0,1,0)) AS nbcomptr, SUM(vlm) AS svlm, SUM(trn) AS strn, SUM(IF(LENGTH(ticker)<4,vlm,0)) AS vlm, SUM(IF(LENGTH(ticker)<4,trn,0)) AS trn FROM vndb_meta_prices GROUP BY vndb_meta_prices.date, vndb_meta_prices.market ORDER BY date DESC,market)");
            $total = microtime(true) - $from;
            $result[0]['time'] = round($total, 2);
            $result[0]['task'] = 'Update Calendar';
            echo json_encode($result);
        }
    }

    public function process_prices_all() {
        if ($this->input->is_ajax_request()) {
            $from = microtime(true);
            set_time_limit(0);
            $this->steps_model->process_prices_all();
            $total = microtime(true) - $from;
            $response[0]['time'] = round($total, 2);
            $response[0]['task'] = 'Update Price';
            echo json_encode($response);
        }
    }

    public function process_import_prices() {
        if ($this->input->is_ajax_request()) {
            $from = microtime(true);
            set_time_limit(0);
            $this->db->query("TRUNCATE TABLE vndb_meta_prices");
            $sub_dir_exc = '\\\LOCAL\IFRCVN\VNDB\METASTOCK\PRICES\\EXC\\';
            $files_exc = glob($sub_dir_exc . '*.txt');
            foreach ($files_exc as $base_exc) {
                $base_url_exc = str_replace("\\", "\\\\", $base_exc);
                $this->db->query("LOAD DATA LOCAL INFILE '" . $base_url_exc . "' INTO TABLE vndb_meta_prices FIELDS TERMINATED BY '\t' IGNORE 1 LINES;");
            }
            $sub_dir_miss = '\\\LOCAL\IFRCVN\VNDB\METASTOCK\PRICES\\MISSING\\';
            $files_miss = glob($sub_dir_miss . '*.txt');
            foreach ($files_miss as $base_miss) {
                $base_url_miss = str_replace("\\", "\\\\", $base_miss);
                $this->db->query("LOAD DATA LOCAL INFILE '" . $base_url_miss . "' INTO TABLE vndb_meta_prices FIELDS TERMINATED BY '\t' IGNORE 1 LINES;");
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
            $this->db->query("CREATE TABLE IF NOT EXISTS vndb_calendar_missing (SELECT date, vni, hnx, upc, prhsx, prhnx, prupc FROM vndb_calendar where (vni>0 AND ISNULL(prhsx)) OR (hnx>0 AND ISNULL(prhnx)) OR (upc>0 AND ISNULL(prupc)))");
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
            $total = microtime(true) - $from;
            $response[0]['time'] = round($total, 2);
            $response[0]['task'] = 'Update Price';
            echo json_encode($response);
        }
    }

    public function process_import_prices_missing() {
        if ($this->input->is_ajax_request()) {
            $from = microtime(true);
            set_time_limit(0);
            $query_b1 = mysql_query("SELECT * FROM vndb_calendar_missing_file");
            $sub_dir_exc = '\\\LOCAL\IFRCVN\VNDB\METASTOCK\PRICES\\EXC\\';
            $sub_dir_miss = '\\\LOCAL\IFRCVN\VNDB\METASTOCK\PRICES\\MISSING\\';
            while ($result_b1 = mysql_fetch_assoc($query_b1)) {
                $file_exc = $sub_dir_exc . $result_b1['file_name'];
                $files_exc = glob($sub_dir_exc . '*.txt');
                if (in_array($file_exc, $files_exc)) {
                    $base_url_exc = str_replace("\\", "\\\\", $file_exc);
                    $this->db->query("LOAD DATA LOCAL INFILE '" . $base_url_exc . "' INTO TABLE vndb_meta_prices FIELDS TERMINATED BY '\t' IGNORE 1 LINES;");
                }
                $file_miss = $sub_dir_miss . $result_b3['file_name'];
                $files_miss = glob($sub_dir_miss . '*.txt');
                if (in_array($file_miss, $files_miss)) {
                    $base_url_miss = str_replace("\\", "\\\\", $file_miss);
                    $this->db->query("LOAD DATA LOCAL INFILE '" . $base_url_miss . "' INTO TABLE vndb_meta_prices FIELDS TERMINATED BY '\t' IGNORE 1 LINES;");
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
            $this->db->query("CREATE TABLE IF NOT EXISTS vndb_calendar_missing (SELECT date, vni, hnx, upc, prhsx, prhnx, prupc FROM vndb_calendar where (vni>0 AND ISNULL(prhsx)) OR (hnx>0 AND ISNULL(prhnx)) OR (upc>0 AND ISNULL(prupc)))");
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
            $total = microtime(true) - $from;
            $response[0]['time'] = round($total, 2);
            $response[0]['task'] = 'Update Price';
            echo json_encode($response);
        }
    }

    public function process_shares_import_all() {
        if ($this->input->is_ajax_request()) {
            $from = microtime(true);
            set_time_limit(0);
            $this->db->query("TRUNCATE TABLE vndb_shares_dwl");
            $this->db->query("TRUNCATE TABLE vndb_reference_dwl");
            $dir_fpt = '\\\LOCAL\IFRCVN\VNDB\METASTOCK\SHARES\\FPT\\';
            $files_fpt = glob($dir_fpt . '*.txt');
            foreach ($files_fpt as $base_fpt) {
                $file_name_fpt = basename($base_fpt, ".txt");
                $base_url_fpt = str_replace("\\", "\\\\", $base_fpt);
                $this->db->query("LOAD DATA LOCAL INFILE '" . $base_url_fpt . "' INTO TABLE vndb_shares_dwl FIELDS TERMINATED BY '\t' IGNORE 1 LINES (sources,ticker,market,date,yyyymmdd,shli,shou,shfn);");
            }
            $dir_vst = '\\\LOCAL\IFRCVN\VNDB\METASTOCK\PRICES\\VST\\';
            $files_vst = glob($dir_vst . '*.txt');
            foreach ($files_vst as $base_vst) {
                $file_name_vst = basename($base_vst, ".txt");
                $base_url_vst = str_replace("\\", "\\\\", $base_vst);
                $this->db->query("LOAD DATA LOCAL INFILE '" . $base_url_vst . "' INTO TABLE vndb_shares_dwl FIELDS TERMINATED BY '\t' IGNORE 1 LINES (sources,ticker,market,date,yyyymmdd,shli,shou,shfn);");
            }
            $dir_vst2 = '\\\LOCAL\IFRCVN\VNDB\METASTOCK\SHARES\\VST\\';
            $files_vst2 = glob($dir_vst2 . '*.txt');
            foreach ($files_vst2 as $base_vst2) {
                $file_name_vst2 = basename($base_vst2, ".txt");
                $base_url_vst2 = str_replace("\\", "\\\\", $base_vst2);
                $this->db->query("LOAD DATA LOCAL INFILE '" . $base_url_vst2 . "' INTO TABLE vndb_shares_dwl FIELDS TERMINATED BY '\t' IGNORE 1 LINES (sources,ticker,market,date,yyyymmdd,shli,shou,shfn);");
            }
            $dir_exc = '\\\LOCAL\IFRCVN\VNDB\METASTOCK\REFERENCE\EXC\\';
            $files_exc = glob($dir_exc . '*.txt');
            foreach ($files_exc as $base_exc) {
                $file_name_exc = basename($base_exc, ".txt");
                $base_url_exc = str_replace("\\", "\\\\", $base_exc);
                $this->db->query("LOAD DATA LOCAL INFILE '" . $base_url_exc . "' INTO TABLE vndb_reference_dwl FIELDS TERMINATED BY '\t' IGNORE 1 LINES (source,ticker,name,market,date,yyyymmdd,ipo,ipo_shli,ipo_shou,ftrd,ftrd_cls,shli,shou,shfn,capi,capi_fora,capi_forn,capi_stat);");
            }
            $dir_cph = '\\\LOCAL\IFRCVN\VNDB\METASTOCK\REFERENCE\CPH\\';
            $files_cph = glob($dir_cph . '*.txt');
            foreach ($files_cph as $base_cph) {
                $file_name_cph = basename($base_cph, ".txt");
                $base_url_cph = str_replace("\\", "\\\\", $base_cph);
                $this->db->query("LOAD DATA LOCAL INFILE '" . $base_url_cph . "' INTO TABLE vndb_reference_dwl FIELDS TERMINATED BY '\t' IGNORE 1 LINES (source,ticker,name,market,date,yyyymmdd,ipo,ipo_shli,ipo_shou,ftrd,ftrd_cls,shli,shou,shfn,capi,capi_fora,capi_forn,capi_stat);");
            }
            $dir_stp = '\\\LOCAL\IFRCVN\VNDB\METASTOCK\REFERENCE\STP\\';
            $files_stp = glob($dir_stp . '*.txt');
            foreach ($files_stp as $base_stp) {
                $file_name_stp = basename($base_stp, ".txt");
                $base_url_stp = str_replace("\\", "\\\\", $base_stp);
                $this->db->query("LOAD DATA LOCAL INFILE '" . $base_url_stp . "' INTO TABLE vndb_reference_dwl FIELDS TERMINATED BY '\t' IGNORE 1 LINES (source,ticker,name,market,date,yyyymmdd,ipo,ipo_shli,ipo_shou,ftrd,ftrd_cls,shli,shou,shfn,capi,capi_fora,capi_forn,capi_stat);");
            }
            $dir_caf = '\\\LOCAL\IFRCVN\VNDB\METASTOCK\REFERENCE\CAF\\';
            $files_caf = glob($dir_caf . '*.txt');
            foreach ($files_caf as $base_caf) {
                $file_name_caf = basename($base_caf, ".txt");
                $base_url_caf = str_replace("\\", "\\\\", $base_caf);
                $this->db->query("LOAD DATA LOCAL INFILE '" . $base_url_caf . "' INTO TABLE vndb_reference_dwl FIELDS TERMINATED BY '\t' IGNORE 1 LINES (source,ticker,name,market,date,yyyymmdd,ipo,ipo_shli,ipo_shou,ftrd,ftrd_cls,shli,shou,shfn,capi,capi_fora,capi_forn,capi_stat);");
            }
            $total = microtime(true) - $from;
            $response[0]['time'] = round($total, 2);
            $response[0]['task'] = 'Update Stats Import';
            echo json_encode($response);
        }
    }

    public function process_shares_import_shares() {
        if ($this->input->is_ajax_request()) {
            $from = microtime(true);
            set_time_limit(0);
            $this->db->query("TRUNCATE TABLE vndb_shares_dwl");
            $dir_fpt = '\\\LOCAL\IFRCVN\VNDB\METASTOCK\SHARES\\FPT\\';
            $files_fpt = glob($dir_fpt . '*.txt');
            foreach ($files_fpt as $base_fpt) {
                $file_name_fpt = basename($base_fpt, ".txt");
                $base_url_fpt = str_replace("\\", "\\\\", $base_fpt);
                $this->db->query("LOAD DATA LOCAL INFILE '" . $base_url_fpt . "' INTO TABLE vndb_shares_dwl FIELDS TERMINATED BY '\t' IGNORE 1 LINES (sources,ticker,market,date,yyyymmdd,shli,shou,shfn);");
            }
            $dir_vst = '\\\LOCAL\IFRCVN\VNDB\METASTOCK\PRICES\\VST\\';
            $files_vst = glob($dir_vst . '*.txt');
            foreach ($files_vst as $base_vst) {
                $file_name_vst = basename($base_vst, ".txt");
                $base_url_vst = str_replace("\\", "\\\\", $base_vst);
                $this->db->query("LOAD DATA LOCAL INFILE '" . $base_url_vst . "' INTO TABLE vndb_shares_dwl FIELDS TERMINATED BY '\t' IGNORE 1 LINES (sources,ticker,market,date,yyyymmdd,shli,shou,shfn);");
            }
            $dir_vst2 = '\\\LOCAL\IFRCVN\VNDB\METASTOCK\SHARES\\VST\\';
            $files_vst2 = glob($dir_vst2 . '*.txt');
            foreach ($files_vst2 as $base_vst2) {
                $file_name_vst2 = basename($base_vst2, ".txt");
                $base_url_vst2 = str_replace("\\", "\\\\", $base_vst2);
                $this->db->query("LOAD DATA LOCAL INFILE '" . $base_url_vst2 . "' INTO TABLE vndb_shares_dwl FIELDS TERMINATED BY '\t' IGNORE 1 LINES (sources,ticker,market,date,yyyymmdd,shli,shou,shfn);");
            }
            $total = microtime(true) - $from;
            $response[0]['time'] = round($total, 2);
            $response[0]['task'] = 'Update Stats Import';
            echo json_encode($response);
        }
    }

    public function process_shares_import_references() {
        if ($this->input->is_ajax_request()) {
            $from = microtime(true);
            set_time_limit(0);
            $this->db->query("TRUNCATE TABLE vndb_reference_dwl");
            $dir_exc = '\\\LOCAL\IFRCVN\VNDB\METASTOCK\REFERENCE\EXC\\';
            $files_exc = glob($dir_exc . '*.txt');
            foreach ($files_exc as $base_exc) {
                $file_name_exc = basename($base_exc, ".txt");
                $base_url_exc = str_replace("\\", "\\\\", $base_exc);
                $this->db->query("LOAD DATA LOCAL INFILE '" . $base_url_exc . "' INTO TABLE vndb_reference_dwl FIELDS TERMINATED BY '\t' IGNORE 1 LINES (source,ticker,name,market,date,yyyymmdd,ipo,ipo_shli,ipo_shou,ftrd,ftrd_cls,shli,shou,shfn,capi,capi_fora,capi_forn,capi_stat);");
            }
            $dir_cph = '\\\LOCAL\IFRCVN\VNDB\METASTOCK\REFERENCE\CPH\\';
            $files_cph = glob($dir_cph . '*.txt');
            foreach ($files_cph as $base_cph) {
                $file_name_cph = basename($base_cph, ".txt");
                $base_url_cph = str_replace("\\", "\\\\", $base_cph);
                $this->db->query("LOAD DATA LOCAL INFILE '" . $base_url_cph . "' INTO TABLE vndb_reference_dwl FIELDS TERMINATED BY '\t' IGNORE 1 LINES (source,ticker,name,market,date,yyyymmdd,ipo,ipo_shli,ipo_shou,ftrd,ftrd_cls,shli,shou,shfn,capi,capi_fora,capi_forn,capi_stat);");
            }
            $dir_stp = '\\\LOCAL\IFRCVN\VNDB\METASTOCK\REFERENCE\STP\\';
            $files_stp = glob($dir_stp . '*.txt');
            foreach ($files_stp as $base_stp) {
                $file_name_stp = basename($base_stp, ".txt");
                $base_url_stp = str_replace("\\", "\\\\", $base_stp);
                $this->db->query("LOAD DATA LOCAL INFILE '" . $base_url_stp . "' INTO TABLE vndb_reference_dwl FIELDS TERMINATED BY '\t' IGNORE 1 LINES (source,ticker,name,market,date,yyyymmdd,ipo,ipo_shli,ipo_shou,ftrd,ftrd_cls,shli,shou,shfn,capi,capi_fora,capi_forn,capi_stat);");
            }
            $dir_caf = '\\\LOCAL\IFRCVN\VNDB\METASTOCK\REFERENCE\CAF\\';
            $files_caf = glob($dir_caf . '*.txt');
            foreach ($files_caf as $base_caf) {
                $file_name_caf = basename($base_caf, ".txt");
                $base_url_caf = str_replace("\\", "\\\\", $base_caf);
                $this->db->query("LOAD DATA LOCAL INFILE '" . $base_url_caf . "' INTO TABLE vndb_reference_dwl FIELDS TERMINATED BY '\t' IGNORE 1 LINES (source,ticker,name,market,date,yyyymmdd,ipo,ipo_shli,ipo_shou,ftrd,ftrd_cls,shli,shou,shfn,capi,capi_fora,capi_forn,capi_stat);");
            }
            $this->db->query("update vndb_reference_dwl a,  vndb_reference_day_hnxupc b set a.shou = b.shou where a.yyyymmdd = b.yyyymmdd and a.ticker = b.ticker and a.source = b.source and a.market = b.market and a.shou = 0");
            $total = microtime(true) - $from;
            $response[0]['time'] = round($total, 2);
            $response[0]['task'] = 'Update Stats Import';
            echo json_encode($response);
        }
    }

    public function process_update_shares_update_all() {
        if ($this->input->is_ajax_request()) {
            $from = microtime(true);
            set_time_limit(0);
            //Update From Multi Sàn, Nguồn
            $this->db->query("update  vndb_meta_prices as a, vndb_shares_dwl as b set a.shli = b.shli, a.shou = b.shou, a.shfn = b.shfn where a.ticker = b.ticker and a.yyyymmdd = b.yyyymmdd and b.sources = 'FPT';");
            $this->db->query("update vndb_meta_prices as a, vndb_shares_dwl as b set a.shli = b.shli, a.shfn = b.shfn where a.ticker = b.ticker and a.yyyymmdd = b.yyyymmdd and b.sources = 'VSTH';");
            $this->db->query("update vndb_meta_prices as a, vndb_shares_dwl as b set a.shli = b.shli, a.shfn = b.shfn where a.ticker = b.ticker and a.yyyymmdd = b.yyyymmdd and b.sources = 'VSTX';");
            $this->db->query("update vndb_meta_prices as a, vndb_company as b set a.shli = b.ipo_shares where a.ticker = b.code and a.date = b.ftrd_date and a.market = b.market;");
            $this->db->query("update vndb_meta_prices as a, vndb_company as b set a.shli = b.ipo_shares where a.ticker = b.code and a.date = b.ipo and a.market = b.market;");
            $this->db->query("update vndb_meta_prices as a, vndb_company as b set a.shli = b.mindate_shli where a.ticker = b.code and a.date = b.mindate and a.market = b.market");
            $this->db->query("update vndb_meta_prices as a, vndb_reference_dwl as b set a.shli = b.shli, a.shou = b.shou, a.shfn = b.shfn where a.ticker = b.ticker and a.yyyymmdd = b.yyyymmdd and b.source = 'EXC';");
            $this->db->query("UPDATE vndb_meta_prices as a, vndb_shares_missing as b set a.shli = b.shli where a.date = b.date and a.ticker = b.ticker and a.market = b.market");

            $this->db->query("update vndb_meta_prices as a, vndb_reference_dwl as b set a.shou = b.shou where a.ticker = b.ticker and a.yyyymmdd = b.yyyymmdd and b.source='CPH'");
            $this->db->query("update vndb_meta_prices as a, vndb_reference_dwl as b set a.shou = b.shou where a.ticker = b.ticker and a.yyyymmdd = b.yyyymmdd and b.source='CAF'");
            $this->db->query("update vndb_meta_prices as a, vndb_reference_dwl as b set a.shou = b.shou where a.ticker = b.ticker and a.yyyymmdd = b.yyyymmdd and b.source='EXC' and vndb_reference_dwl.market='HSX'");

            $date_now = date('Y-m-d', time());
            $query_report = mysql_query("SELECT * FROM vndb_reports where date = '" . $date_now . "'");
            $num_report = mysql_num_rows($query_report);
            $arr['market'] = array('FIRST', 'HNX', 'HSX', 'UPC');
            if ($num_report != 0) {
                $this->db->query("DELETE FROM vndb_reports where date = '" . $date_now . "' and task = 'vndb_meta_prices.shli=0'");
                $this->db->query("DELETE FROM vndb_reports where date = '" . $date_now . "' and task = 'vndb_meta_prices.shou=0'");
            }
            foreach ($arr['market'] as $mk) {
                if ($mk == 'FIRST') {
                    $this->db->query("insert into vndb_reports (date,task,start_date,end_date,market,number)select now() as date, 'vndb_meta_prices.shli=0' as task ,min(date) Min_date, max(date) Max_date,'ALL' as market, count(*) from vndb_meta_prices where shli=0;");
                    $this->db->query("insert into vndb_reports (date,task,start_date,end_date,market,number)select now() as date, 'vndb_meta_prices.shou=0' as task ,min(date) Min_date, max(date) Max_date,'ALL' as market, count(*) from vndb_meta_prices where shou=0;");
                } else {
                    $this->db->query("insert into vndb_reports (date,task,start_date,end_date,market,number)select now() as date, 'vndb_meta_prices.shli=0' as task ,min(date) Min_date, max(date) Max_date,'" . $mk . "' as market, count(*) from vndb_meta_prices where market='" . $mk . "' and shli=0;");
                    $this->db->query("insert into vndb_reports (date,task,start_date,end_date,market,number)select now() as date, 'vndb_meta_prices.shou=0' as task ,min(date) Min_date, max(date) Max_date,'" . $mk . "' as market, count(*) from vndb_meta_prices where market='" . $mk . "' and shou=0;");
                }
            }
            $total = microtime(true) - $from;
            $response[0]['time'] = round($total, 2);
            $response[0]['task'] = 'Update Shares';
            echo json_encode($response);
        }
    }

    public function process_update_shares_update_shli() {
        if ($this->input->is_ajax_request()) {
            $from = microtime(true);
            set_time_limit(0);
            //Update From Multi Sàn, Nguồn
            $this->db->query("update  vndb_meta_prices as a, vndb_shares_dwl as b set a.shli = b.shli, a.shou = b.shou, a.shfn = b.shfn where a.ticker = b.ticker and a.yyyymmdd = b.yyyymmdd and b.sources = 'FPT';");
            $this->db->query("update vndb_meta_prices as a, vndb_shares_dwl as b set a.shli = b.shli, a.shfn = b.shfn where a.ticker = b.ticker and a.yyyymmdd = b.yyyymmdd and b.sources = 'VSTH';");
            $this->db->query("update vndb_meta_prices as a, vndb_shares_dwl as b set a.shli = b.shli, a.shfn = b.shfn where a.ticker = b.ticker and a.yyyymmdd = b.yyyymmdd and b.sources = 'VSTX';");
            $this->db->query("update vndb_meta_prices as a, vndb_company as b set a.shli = b.ipo_shares where a.ticker = b.code and a.date = b.ftrd_date and a.market = b.market;");
            $this->db->query("update vndb_meta_prices as a, vndb_company as b set a.shli = b.ipo_shares where a.ticker = b.code and a.date = b.ipo and a.market = b.market;");
            $this->db->query("update vndb_meta_prices as a, vndb_company as b set a.shli = b.mindate_shli where a.ticker = b.code and a.date = b.mindate and a.market = b.market");
            $this->db->query("update vndb_meta_prices as a, vndb_reference_dwl as b set a.shli = b.shli, a.shou = b.shou, a.shfn = b.shfn where a.ticker = b.ticker and a.yyyymmdd = b.yyyymmdd and b.source = 'EXC';");
            $this->db->query("UPDATE vndb_meta_prices as a, vndb_shares_missing as b set a.shli = b.shli where a.date = b.date and a.ticker = b.ticker and a.market = b.market");

            $date_now = date('Y-m-d', time());
            $query_report = mysql_query("SELECT * FROM vndb_reports where date = '" . $date_now . "'");
            $num_report = mysql_num_rows($query_report);
            $arr['market'] = array('FIRST', 'HNX', 'HSX', 'UPC');
            if ($num_report != 0) {
                $this->db->query("DELETE FROM vndb_reports where date = '" . $date_now . "' and task = 'vndb_meta_prices.shli=0'");
            }
            foreach ($arr['market'] as $mk) {
                if ($mk == 'FIRST') {
                    $this->db->query("insert into vndb_reports (date,task,start_date,end_date,market,number)select now() as date, 'vndb_meta_prices.shli=0' as task ,min(date) Min_date, max(date) Max_date,'ALL' as market, count(*) from vndb_meta_prices where shli=0;");
                } else {
                    $this->db->query("insert into vndb_reports (date,task,start_date,end_date,market,number)select now() as date, 'vndb_meta_prices.shli=0' as task ,min(date) Min_date, max(date) Max_date,'" . $mk . "' as market, count(*) from vndb_meta_prices where market='" . $mk . "' and shli=0;");
                }
            }
            $total = microtime(true) - $from;
            $response[0]['time'] = round($total, 2);
            $response[0]['task'] = 'Update Shares';
            echo json_encode($response);
        }
    }

    public function process_update_shares_update_shou() {
        if ($this->input->is_ajax_request()) {
            $from = microtime(true);
            set_time_limit(0);
            $this->db->query("update vndb_meta_prices as a, vndb_reference_dwl as b set a.shou = b.shou where a.ticker = b.ticker and a.yyyymmdd = b.yyyymmdd and b.source='CPH'");
            $this->db->query("update vndb_meta_prices as a, vndb_reference_dwl as b set a.shou = b.shou where a.ticker = b.ticker and a.yyyymmdd = b.yyyymmdd and b.source='CAF'");
            $this->db->query("update vndb_meta_prices as a, vndb_reference_dwl as b set a.shou = b.shou where a.ticker = b.ticker and a.yyyymmdd = b.yyyymmdd and b.source='EXC' and a.market='HSX'");
            $date_now = date('Y-m-d', time());
            $query_report = mysql_query("SELECT * FROM vndb_reports where date = '" . $date_now . "'");
            $num_report = mysql_num_rows($query_report);
            $arr['market'] = array('FIRST', 'HNX', 'HSX', 'UPC');
            if ($num_report != 0) {
                $this->db->query("DELETE FROM vndb_reports where date = '" . $date_now . "' and task = 'vndb_meta_prices.shou=0'");
            }
            foreach ($arr['market'] as $mk) {
                if ($mk == 'FIRST') {
                    $this->db->query("insert into vndb_reports (date,task,start_date,end_date,market,number)select now() as date, 'vndb_meta_prices.shou=0' as task ,min(date) Min_date, max(date) Max_date,'ALL' as market, count(*) from vndb_meta_prices where shou=0;");
                } else {
                    $this->db->query("insert into vndb_reports (date,task,start_date,end_date,market,number)select now() as date, 'vndb_meta_prices.shou=0' as task ,min(date) Min_date, max(date) Max_date,'" . $mk . "' as market, count(*) from vndb_meta_prices where market='" . $mk . "' and shou=0;");
                }
            }
            $total = microtime(true) - $from;
            $response[0]['time'] = round($total, 2);
            $response[0]['task'] = 'Update Shares';
            echo json_encode($response);
        }
    }

    public function process_shares_clean() {
        if ($this->input->is_ajax_request()) {
            $from = microtime(true);
            set_time_limit(0);
            $list_ticker = $this->db->query("select DISTINCT ticker from vndb_meta_prices order by ticker asc")->result_array();

            foreach ($list_ticker as $vlist_ticker) {
                $arr = $this->db->query("select id,shli,shou from vndb_meta_prices where ticker = '{$vlist_ticker['ticker']}'  ORDER BY `date` asc")->result_array();
                $arr_plus = array(
                    "3" => "shli",
                    "4" => "shou",
                );
                $max = count($arr);
                foreach ($arr_plus as $value_arr_plus) {
                    for ($i = 0; $i < $max - 1; $i++) {
                        if ($arr[$i][$value_arr_plus] != 0) {
                            for ($j = $i + 1; $j < $max; $j++) {
                                if ($arr[$j][$value_arr_plus] != 0) {
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
                $this->db->update_batch('vndb_meta_prices', $arr, 'id');
                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                } else {
                    $this->db->trans_commit();
                }
            }
            $this->db->query("DROP TABLE IF EXISTS vndb_prices_uni");
            $this->db->query("CREATE TABLE IF NOT EXISTS vndb_prices_uni select * from vndb_meta_prices where market <>'UPC' and yyyymmdd >=20081231 and LENGTH(ticker) = 3 group by date, ticker");
            $this->db->query("CREATE INDEX TICKERDATE ON VNDB_PRICES_UNI (TICKER,DATE) USING BTREE");
            $total = microtime(true) - $from;
            $response[0]['time'] = round($total, 2);
            $response[0]['task'] = 'Clean Stats';
            echo json_encode($response);
        }
    }

    public function process_check_setting() {
        $query = mysql_query("SELECT * FROM setting where `key` = 'meta_files_reset'");
        $result = mysql_fetch_array($query);
        echo json_encode($result);
    }

    public function process_missing_shares() {
        if ($this->input->is_ajax_request()) {
            $from = microtime(true);
            set_time_limit(0);
            $this->db->query("TRUNCATE TABLE vndb_shares_missing");
            $dir = '\\\LOCAL\IFRCVN\VNDB\METASTOCK\SHARES\MISSING\\';
            $files = glob($dir . '*.txt');
            foreach ($files as $base) {
                $basename = basename($base, '.txt');
                if ($basename = 'EXC_SHARES_MISSING') {
                    $base_url = str_replace("\\", "\\\\", $base);
                    $this->db->query("LOAD DATACLOCAL INFILE '" . $base_url . "' INTO TABLE vndb_shares_missing FIELDS TERMINATED BY '\t' IGNORE 1 LINES (source,ticker,market,date,yyyymmdd,shli,shou,shfn);");
                }
            }
            $total = microtime(true) - $from;
            $response[0]['time'] = round($total, 2);
            $response[0]['task'] = 'Missing Shares';
            echo json_encode($response);
        }
    }

    public function process_adjusted_close() {
        if ($this->input->is_ajax_request()) {
            $from = microtime(true);
            set_time_limit(0);
            $this->db->query("TRUNCATE TABLE vndb_apcls_vst");
            $this->db->query("TRUNCATE TABLE vndb_reuters");
            $this->db->query("TRUNCATE TABLE vndb_istock");
            $this->db->query("TRUNCATE TABLE vndb_phutoan_new");
            $this->db->query("TRUNCATE TABLE vndb_phutoan_old");
            $this->db->query("TRUNCATE TABLE vndb_vietstock");
            $this->db->query("TRUNCATE TABLE vndb_cafef");
            $dir_vst = '\\\LOCAL\IFRCVN\VNDB\METASTOCK\SHARES\VST\\';
            $files_vst = glob($dir_vst . '*.txt');
            foreach ($files_vst as $base_vst) {
                $base_url_vst = str_replace("\\", "\\\\", $base_vst);
                $this->db->query("LOAD DATA LOCAL INFILE '" . $base_url_vst . "' INTO TABLE vndb_apcls_vst FIELDS TERMINATED BY '\t' IGNORE 1 LINES (source,ticker,market,date,yyyymmdd,shli,shou,shfn,pref,pcei,pflr,popn,phgh,plow,pbase,pavg,pcls,vlm,trn,adj_pcls,adj_coeff);");
            }
            $dir_rts = '\\\LOCAL\IFRCVN\VNDB\METASTOCK\ADJPRICES\RTS\\';
            $files_rts = glob($dir_rts . '*.txt');
            foreach ($files_rts as $base_rts) {
                $base_url_rts = str_replace("\\", "\\\\", $base_rts);
                $this->db->query("LOAD DATA LOCAL INFILE '" . $base_url_rts . "' INTO TABLE vndb_reuters FIELDS TERMINATED BY '\t' IGNORE 1 LINES (ticker,date,yyyymmdd,adj_pcls,shou)");
            }
            $dir_ist = '\\\LOCAL\IFRCVN\VNDB\METASTOCK\ADJPRICES\IST\\';
            $files_ist = glob($dir_ist . '*.txt');
            foreach ($files_ist as $base_ist) {
                $base_url_ist = str_replace("\\", "\\\\", $base_ist);
                $this->db->query("LOAD DATA LOCAL INFILE '" . $base_url_ist . "' INTO TABLE vndb_istock FIELDS TERMINATED BY ',' IGNORE 1 LINES (ticker,per,dtyyyymmdd,time,open,high,low,close,vol,openint)");
            }
            $dir_pht_n = '\\\LOCAL\IFRCVN\VNDB\METASTOCK\ADJPRICES\PT2\\';
            $files_pht_n = glob("$dir_pht_n{*.txt,*.TXT}", GLOB_BRACE);
            foreach ($files_pht_n as $base_pht_n) {
                $base_url_pht_n = str_replace("\\", "\\\\", $base_pht_n);
                $this->db->query("LOAD DATA LOCAL INFILE '" . $base_url_pht_n . "' INTO TABLE vndb_phutoan_new FIELDS TERMINATED BY ',' IGNORE 1 LINES (ticker,per,dtyyyymmdd,time,open,high,low,close,vol,openint)");
            }
            $dir_pht_o = '\\\LOCAL\IFRCVN\VNDB\METASTOCK\ADJPRICES\PT1\\';
            $files_pht_o = glob("$dir_pht_o{*.txt,*.TXT}", GLOB_BRACE);
            foreach ($files_pht_o as $base_pht_o) {
                $base_url_pht_o = str_replace("\\", "\\\\", $base_pht_o);
                $this->db->query("LOAD DATA LOCAL INFILE '" . $base_url_pht_o . "' INTO TABLE vndb_phutoan_old FIELDS TERMINATED BY ',' IGNORE 1 LINES (ticker,per,dtyyyymmdd,time,open,high,low,close,vol,openint)");
            }
            $dir_vst2 = '\\\LOCAL\IFRCVN\VNDB\METASTOCK\ADJPRICES\VST\\';
            $files_vst2 = glob($dir_vst2 . '*.txt');
            foreach ($files_vst2 as $base_vst2) {
                $base_url_vst2 = str_replace("\\", "\\\\", $base_vst2);
                $this->db->query("LOAD DATA LOCAL INFILE '" . $base_url_vst2 . "' INTO TABLE vndb_vietstock FIELDS TERMINATED BY '\t' IGNORE 1 LINES (source,ticker,market,date,yyyymmdd,shli,shou,shfn,pref,pcei,pflr,popn,phgh,plow,pbase,pavg,pcls,vlm,trn,adj_pcls,adj_coeff)");
            }
            $dir_caf = '\\\LOCAL\IFRCVN\VNDB\METASTOCK\ADJPRICES\CAF\\';
            $files_caf = glob($dir_caf . '*.csv');
            foreach ($files_caf as $base_caf) {
                $base_url_caf = str_replace("\\", "\\\\", $base_caf);
                $this->db->query("LOAD DATA LOCAL INFILE '" . $base_url_caf . "' INTO TABLE vndb_cafef FIELDS TERMINATED BY ',' IGNORE 1 LINES (ticker,yyyymmdd,open,high,low,close,vlm)");
            }
            $total = microtime(true) - $from;
            $response[0]['time'] = round($total, 2);
            $response[0]['task'] = 'Adjusted Close';
            echo json_encode($response);
        }
    }
	
	public function process_ownership_all(){
		 if ($this->input->is_ajax_request()) {
            $from = microtime(true);
            set_time_limit(0);
			$this->source_import_ownership();
			$date = '2013-02-19'; // Đưa ngày vào
			$this->source_update_ownership($date);
			$total = microtime(true) - $from;
            $response[0]['time'] = round($total, 2);
            $response[0]['task'] = 'Ownership All';
            echo json_encode($response);
		 }
	}
	
    public function process_import_ownership() {
        if ($this->input->is_ajax_request()) {
            $from = microtime(true);
            set_time_limit(0);
            /*$this->db->query("TRUNCATE TABLE vndb_ownership_stb");
            $this->db->query("TRUNCATE TABLE vndb_ownership_cph");
            $this->db->query("TRUNCATE TABLE vndb_ownership_caf");
            $dir_stb = '\\\LOCAL\IFRCVN\VNDB\METASTOCK\OWNERSHIP\STB\\';
            $files_stb = glob($dir_stb . '*.txt');
            foreach ($files_stb as $base_stb) {
                $base_url_stb = str_replace("\\", "\\\\", $base_stb);
                $this->db->query("LOAD DATA INFILE '" . $base_url_stb . "' INTO TABLE vndb_ownership_stb FIELDS TERMINATED BY '\t' IGNORE 1 LINES (ticker,name,positions,shares,percent,last_date);");
            }
            $dir_cph = '\\\LOCAL\IFRCVN\VNDB\METASTOCK\OWNERSHIP\CPH\\';
            $files_cph = glob($dir_cph . '*.txt');
            foreach ($files_cph as $base_cph) {
                $base_url_cph = str_replace("\\", "\\\\", $base_cph);
                $this->db->query("LOAD DATA INFILE '" . $base_url_cph . "' INTO TABLE vndb_ownership_cph FIELDS TERMINATED BY '\t' IGNORE 1 LINES (ticker,name,positions,shares,percent,last_date);");
            }
            $this->db->query("update vndb_ownership_stb set last_date = str_to_date(last_date,'%d/%m/%Y')");
            $dir_caf = '\\\LOCAL\IFRCVN\VNDB\METASTOCK\OWNERSHIP\CAF\\';
            $files_caf = glob($dir_caf . '*.txt');
            foreach ($files_caf as $base_caf) {
                $base_url_caf = str_replace("\\", "\\\\", $base_caf);
                $this->db->query("LOAD DATA INFILE '" . $base_url_caf . "' INTO TABLE vndb_ownership_caf FIELDS TERMINATED BY '\t' IGNORE 1 LINES (TICKER,NAME,POSITIONS,SHARES,PERCENT,LAST_DATE)");
            }
            $this->db->query("UPDATE vndb_ownership_caf SET last_date = str_to_date(last_date,'%d/%m/%Y')");

            $this->db->query("DROP TABLE IF EXISTS vndb_ownership");
            $this->db->query("CREATE TABLE vndb_ownership (SELECT *, 'CAF' AS source FROM vndb_ownership_caf)");
            $this->db->query("INSERT INTO vndb_ownership (SELECT *, 'CPH' AS source FROM vndb_ownership_cph)");
            $this->db->query("INSERT INTO vndb_ownership (SELECT *, 'STB' AS source FROM vndb_ownership_stb)");*/
			$this->source_import_ownership();
            $total = microtime(true) - $from;
            $response[0]['time'] = round($total, 2);
            $response[0]['task'] = 'Free Loat';
            echo json_encode($response);
        }
    }

    public function process_free_float() {
        if ($this->input->is_ajax_request()) {
            $from = microtime(true);
            set_time_limit(0);
            /*$this->db->query("SET @MinFF :=0.05");
            $this->db->query("SET @RNDFF :=5");
            $this->db->query("DROP TABLE IF EXISTS VNDB_DAILY_FLOATS");
            $this->db->query("CREATE TABLE VNDB_DAILY_FLOATS (SELECT vndb_daily.date, vndb_daily.market, vndb_daily.ticker,vndb_ownership.source ,vndb_ownership.name, vndb_ownership.shares, if(vndb_daily.shou>0, vndb_daily.shou, vndb_daily.shli) as SH,vndb_daily.SHOU AS NFF, vndb_ownership.last_date FROM vndb_ownership,vndb_daily WHERE vndb_ownership.ticker = vndb_daily.ticker AND vndb_daily.market<>'UPC')");
            $this->db->query("UPDATE VNDB_DAILY_FLOATS SET NFF = NULL");
            $this->db->query("UPDATE VNDB_DAILY_FLOATS SET NFF = VNDB_DAILY_FLOATS.SHARES/VNDB_DAILY_FLOATS.SH WHERE VNDB_DAILY_FLOATS.SH>0");
            $this->db->query("DROP TABLE IF EXISTS VNDB_DAILY_FLOAT");
            $this->db->query("CREATE TABLE VNDB_DAILY_FLOAT
(SELECT A.DATE,A.MARKET,A.TICKER,A.NFF_CPH, A.FF_CPH, A.RFF_CPH, B.NFF_STB, B.FF_STB, B.RFF_STB, C.NFF_CAF, C.FF_CAF, C.RFF_CAF ,'' AS NFF, '' AS FF, '' AS RFF
FROM
    (SELECT DATE,MARKET,TICKER,SUM(IF(NFF>=@MinFF,NFF,0)) AS NFF_CPH, NFF AS FF_CPH, NFF AS RFF_CPH FROM VNDB_DAILY_FLOATS WHERE SOURCE = 'CPH' GROUP BY TICKER) A,
    (SELECT TICKER,SUM(IF(NFF>=@MinFF,NFF,0)) AS NFF_STB, NFF AS FF_STB, NFF AS RFF_STB FROM VNDB_DAILY_FLOATS WHERE SOURCE = 'STB' GROUP BY TICKER) B,
    (SELECT TICKER,SUM(IF(NFF>=@MinFF,NFF,0)) AS NFF_CAF, NFF AS FF_CAF, NFF AS RFF_CAF FROM VNDB_DAILY_FLOATS WHERE SOURCE = 'CAF' GROUP BY TICKER) C
WHERE A.TICKER = B.TICKER AND B.TICKER = C.TICKER)");

            $this->db->query("UPDATE VNDB_DAILY_FLOAT SET FF_CPH  = NULL, RFF_CPH  = NULL,FF_STB  = NULL, RFF_STB  = NULL");
            $this->db->query("UPDATE VNDB_DAILY_FLOAT SET FF_CPH  = IF(1-NFF_CPH>0,1-NFF_CPH,0), RFF_CPH = 100*IF(1-NFF_CPH>0,1-NFF_CPH,0) WHERE NFF_CPH>0");
            $this->db->query("UPDATE VNDB_DAILY_FLOAT SET FF_STB  = IF(1-NFF_STB>0,1-NFF_STB,0), RFF_STB = 100*IF(1-NFF_STB>0,1-NFF_STB,0) WHERE NFF_STB>0");
            $this->db->query("UPDATE VNDB_DAILY_FLOAT SET FF_CAF  = IF(1-NFF_CAF>0,1-NFF_CAF,0), RFF_CAF = 100*IF(1-NFF_CAF>0,1-NFF_CAF,0) WHERE NFF_CAF>0");

            $this->db->query("UPDATE VNDB_DAILY_FLOAT SET RFF_CPH = @RNDFF*CEIL(RFF_CPH/@RNDFF) WHERE NFF_CPH>0");
            $this->db->query("UPDATE VNDB_DAILY_FLOAT SET RFF_STB = @RNDFF*CEIL(RFF_STB/@RNDFF) WHERE NFF_STB>0");
            $this->db->query("UPDATE VNDB_DAILY_FLOAT SET RFF_CAF = @RNDFF*CEIL(RFF_CAF/@RNDFF) WHERE NFF_CAF>0");*/
			$date = '2013-02-19';
			$this->source_update_ownership($date);
			
            $total = microtime(true) - $from;
            $response[0]['time'] = round($total, 2);
            $response[0]['task'] = 'Free Float';
            echo json_encode($response);
        }
    }

    public function process_anomalies_share() {
        if ($this->input->is_ajax_request()) {
            $from = microtime(true);
            set_time_limit(0);
            $this->db->query("DROP TABLE IF EXISTS VNDB_CALENDAR_SHLI");
            $this->db->query("CREATE TABLE VNDB_CALENDAR_SHLI (SELECT DATE, DATE AS PDATE, DATE AS NDATE FROM vndb_meta_prices GROUP BY DATE)");
            $this->db->query("CREATE INDEX DATE ON VNDB_CALENDAR_SHLI (DATE) USING BTREE");
            $this->db->query("SET @a :=0");
            $this->db->query("SET @b :=1");
            $this->db->query("DROP TABLE IF EXISTS VNDB_CALENDAR_SHLIP");
            $this->db->query("CREATE TABLE VNDB_CALENDAR_SHLIP(SELECT r.date, r2.date AS 'pdate' FROM (SELECT if(@a, @a:=@a+1, @a:=1) as rownum, date FROM VNDB_CALENDAR_SHLI) AS r LEFT JOIN (SELECT if(@b, @b:=@b+1, @b:=1) as rownum, date FROM VNDB_CALENDAR_SHLI) AS r2 ON r.rownum = r2.rownum)");
            $this->db->query("CREATE INDEX DATES ON VNDB_CALENDAR_SHLIP(DATE, PDATE) USING BTREE");
            $this->db->query("UPDATE VNDB_CALENDAR_SHLI SET VNDB_CALENDAR_SHLI.PDATE = null, VNDB_CALENDAR_SHLI.NDATE = null");
            $this->db->query("UPDATE VNDB_CALENDAR_SHLI,VNDB_CALENDAR_SHLIP SET VNDB_CALENDAR_SHLI.PDATE = VNDB_CALENDAR_SHLIP.PDATE WHERE VNDB_CALENDAR_SHLI.DATE = VNDB_CALENDAR_SHLIP.DATE");
            $this->db->query("UPDATE VNDB_CALENDAR_SHLI,VNDB_CALENDAR_SHLIP SET VNDB_CALENDAR_SHLI.NDATE = VNDB_CALENDAR_SHLIP.DATE WHERE VNDB_CALENDAR_SHLI.DATE = VNDB_CALENDAR_SHLIP.PDATE");
            $this->db->query("DROP TABLE IF EXISTS VNDB_CLEAN_SHLI");

            $this->db->query("CREATE TABLE VNDB_CLEAN_SHLI (SELECT SOURCE AS ANOMALY, DATE, TICKER, DATE AS PDATE, DATE AS NDATE, SHLI AS PSHLI, SHLI, SHLI AS NSHLI FROM vndb_prices_uni)");
            $this->db->query("CREATE INDEX DATE ON VNDB_CLEAN_SHLI (DATE) USING BTREE");
            $this->db->query("UPDATE VNDB_CLEAN_SHLI SET VNDB_CLEAN_SHLI.PDATE = null, VNDB_CLEAN_SHLI.NDATE = null,VNDB_CLEAN_SHLI.PSHLI = null, VNDB_CLEAN_SHLI.NSHLI = null ");
            $this->db->query("UPDATE VNDB_CLEAN_SHLI,VNDB_CALENDAR_SHLI SET VNDB_CLEAN_SHLI.PDATE = VNDB_CALENDAR_SHLI.PDATE , VNDB_CLEAN_SHLI.NDATE = VNDB_CALENDAR_SHLI.NDATE WHERE VNDB_CLEAN_SHLI.DATE = VNDB_CALENDAR_SHLI.DATE");
            $this->db->query("CREATE INDEX TICKERDATE ON VNDB_CLEAN_SHLI (TICKER,DATE) USING BTREE");
            //$this->db->query("CREATE INDEX TICKERDATE ON VNDB_PRICES_UNI (TICKER,DATE) USING BTREE");
            //$this->db->query("UPDATE VNDB_CLEAN_SHLI SET VNDB_CLEAN_SHLI.PSHLI=NULL, VNDB_CLEAN_SHLI.NSHLI = NULL");
            $this->db->query("CREATE INDEX TICKERPDATE ON VNDB_CLEAN_SHLI (TICKER,PDATE) USING BTREE");
            $this->db->query("UPDATE VNDB_CLEAN_SHLI,vndb_prices_uni SET VNDB_CLEAN_SHLI.PSHLI = vndb_prices_uni.shli WHERE VNDB_CLEAN_SHLI.PDATE = vndb_prices_uni.DATE AND VNDB_CLEAN_SHLI.TICKER = vndb_prices_uni.TICKER");
            $this->db->query("CREATE INDEX TICKERNDATE ON VNDB_CLEAN_SHLI (TICKER,NDATE) USING BTREE");
            $this->db->query("UPDATE VNDB_CLEAN_SHLI,vndb_prices_uni SET VNDB_CLEAN_SHLI.NSHLI = vndb_prices_uni.shli WHERE VNDB_CLEAN_SHLI.NDATE = vndb_prices_uni.DATE AND VNDB_CLEAN_SHLI.TICKER = vndb_prices_uni.TICKER");
            $total = microtime(true) - $from;
            $response[0]['time'] = round($total, 2);
            $response[0]['task'] = 'Anomalies Share';
            echo json_encode($response);
        }
    }

    public function process_update_dividend_all() {
        if ($this->input->is_ajax_request()) {
            $from = microtime(true);
            set_time_limit(0);
           $this->db->query("TRUNCATE TABLE vndb_dividends_dwl");
			$dir = '\\\LOCAL\IFRCVN\VNDB\METASTOCK\DIVIDEND\FINAL\\';
			$files = glob($dir . '*.txt');
			foreach ($files as $base) {
				$base_url = str_replace("\\", "\\\\", $base);
				$this->db->query("LOAD DATA LOCAL INFILE '" . $base_url . "' INTO TABLE vndb_dividends_dwl FIELDS TERMINATED BY '\t' IGNORE 1 LINES");
			}	
			
			$this->db->query('DROP TABLE IF EXISTS VNDB_DIVIDENDS_DWL_CTRL');
			$this->db->query('CREATE TABLE VNDB_DIVIDENDS_DWL_CTRL(SELECT * FROM VNDB_DIVIDENDS_DWL GROUP BY TICKER, DATE_EX, SOURCE ORDER BY TICKER, DATE_EX, DIVIDEND)');

			$this->db->query('DROP TABLE IF EXISTS VNDB_DIVIDENDS_DWL_SUM');
			$this->db->query('CREATE TABLE VNDB_DIVIDENDS_DWL_SUM (SELECT TICKER, DATE_EX, MIN(IF(DIVIDEND=0,NULL,DIVIDEND)) AS MINDIV, MAX(IF(DIVIDEND=0,NULL,DIVIDEND)) AS MAXDIV, 
SUM(IF(DIVIDEND=0,NULL,1)) AS NBDIV, DIVIDEND AS CORRECT FROM VNDB_DIVIDENDS_DWL_CTRL GROUP BY TICKER, DATE_EX)');
			$this->db->query('UPDATE  VNDB_DIVIDENDS_DWL_SUM SET CORRECT = 0');

			$this->db->query('UPDATE VNDB_DIVIDENDS_DWL_SUM SET CORRECT = 1 WHERE NBDIV>=3 AND MINDIV = MAXDIV');
			$this->db->query('UPDATE VNDB_DIVIDENDS_DWL_SUM SET CORRECT = 9 WHERE MINDIV <> MAXDIV');  
			
            /*$this->db->query("update vndb_dividend_dwl,vndb_company set vndb_dividend_dwl.market= vndb_company.market where vndb_dividend_dwl.ticker=vndb_company.`code`");
            $this->db->query("insert into vndb_dividends (date, ticker, dateeff, market)(select exdate, ticker, effdate, market from vndb_dividend_dwl where LENGTH(ticker) = 3 and market <> 'UPC' and exdate >= '2008-12-31' GROUP BY exdate,ticker ORDER BY ticker,exdate asc)");
            $this->db->query("update vndb_dividends,(select exdate,ticker,effdate,amount from vndb_dividend_dwl where source = 'FPT' GROUP BY exdate,ticker ORDER BY ticker,exdate asc) a set div_fpt = a.amount where date = a.exdate and vndb_dividends.ticker = a.ticker and dateeff = a.effdate");
            $this->db->query("update vndb_dividends,(select exdate,ticker,effdate,amount from vndb_dividend_dwl where source = 'STP' GROUP BY exdate,ticker ORDER BY ticker,exdate asc) a set div_stp = a.amount where date = a.exdate and vndb_dividends.ticker = a.ticker and dateeff = a.effdate");
            $this->db->query("update vndb_dividends,(select exdate,ticker,effdate,amount from vndb_dividend_dwl where source = 'CPH' GROUP BY exdate,ticker ORDER BY ticker,exdate asc) a set div_cph = a.amount where date = a.exdate and vndb_dividends.ticker = a.ticker and dateeff = a.effdate");
            $this->db->query("update vndb_dividends
    set correct = CASE WHEN div_stp = div_fpt and div_stp = div_cph THEN 1 WHEN div_stp <> 0 and div_fpt <> 0 and div_stp = div_fpt and div_cph = 0 THEN 1 WHEN div_cph <> 0 and div_fpt <> 0 and div_cph = div_fpt and div_stp = 0 THEN 1 WHEN div_cph <> 0 and div_stp <> 0 and div_cph = div_stp and div_fpt = 0 THEN 1 WHEN div_cph <> 0 AND div_fpt = 0 AND div_stp = 0 THEN 1 WHEN div_cph = 0 AND div_fpt <> 0 AND div_stp = 0 THEN 1 WHEN div_cph = 0 AND div_fpt = 0 AND div_stp <> 0 THEN 1 ELSE 0 END");
            $this->db->query("update vndb_dividends set correct = 2, amount = 0 where date = '0000-00-00'");
            $this->db->query("update vndb_dividends a,(select ticker,market,dateeff from vndb_dividends GROUP BY ticker,dateeff HAVING count(dateeff) > 1) b set correct = 3, amount = 0 where a.ticker = b.ticker and a.market = b.market and a.dateeff = b.dateeff");
            $this->db->query("update vndb_dividends set amount = if(correct = 1, CASE WHEN div_stp <> 0 THEN div_stp  WHEN div_cph <> 0 THEN div_cph WHEN div_fpt <> 0 THEN div_fpt END,0), value = amount, date_ex = date");
            $this->db->query("TRUNCATE TABLE vndb_dividends_missing");
            $dir = '\\\LOCAL\IFRCVN\VNDB\METASTOCK\DIVIDEND\MISSING\\';
            $files = glob($dir . '*.txt');
            foreach ($files as $base) {
                $base_url = str_replace("\\", "\\\\", $base);
                $this->db->query("LOAD DATA INFILE '" . $base_url . "' INTO TABLE vndb_dividends_missing FIELDS TERMINATED BY '\t' IGNORE 1 LINES (date,ticker,market,amount,dateeff,div_stp,div_cph,div_fpt)");
            }
            $this->db->query("update vndb_dividends a,(select date,ticker,market,amount,dateeff from vndb_dividends_missing) b
set a.value = b.amount where a.correct = 0 and a.ticker = b.ticker and a.market = b.market and a.date = b.date");
            $this->db->query("update vndb_dividends a,(select date,ticker,market,amount,dateeff from vndb_dividends_missing) b
            set a.date_ex = b.date, a.amount = b.amount where a.correct = 2 and a.ticker = b.ticker and a.market = b.market and a.dateeff = b.dateeff");
            $this->db->query("update vndb_dividends a,(select date,ticker,market,amount,dateeff from vndb_dividends_missing) b
            set a.value = b.amount, a.date_ex = b.date where a.correct = 3 and a.ticker = b.ticker and a.market = b.market and a.dateeff = b.dateeff");
            $this->db->query("update vndb_dividends set correct = 1 where value <> 0");*/
            $total = microtime(true) - $from;
            $response[0]['time'] = round($total, 2);
            $response[0]['task'] = 'Dividend - All';
            echo json_encode($response);
        }
    }

    public function process_update_dividend_import() {
        if ($this->input->is_ajax_request()) {
            $from = microtime(true);
            set_time_limit(0);
           	$this->db->query("TRUNCATE TABLE vndb_dividends_dwl");
			$dir = '\\\LOCAL\IFRCVN\VNDB\METASTOCK\DIVIDEND\FINAL\\';
			$files = glob($dir . '*.txt');
			foreach ($files as $base) {
				$base_url = str_replace("\\", "\\\\", $base);
				$this->db->query("LOAD DATA LOCAL INFILE '" . $base_url . "' INTO TABLE vndb_dividends_dwl FIELDS TERMINATED BY '\t' IGNORE 1 LINES");
			}	
			
			$this->db->query('DROP TABLE IF EXISTS VNDB_DIVIDENDS_DWL_CTRL');
			$this->db->query('CREATE TABLE VNDB_DIVIDENDS_DWL_CTRL(SELECT * FROM VNDB_DIVIDENDS_DWL GROUP BY TICKER, DATE_EX, SOURCE ORDER BY TICKER, DATE_EX, DIVIDEND)');

			$this->db->query('DROP TABLE IF EXISTS VNDB_DIVIDENDS_DWL_SUM');
			$this->db->query('CREATE TABLE VNDB_DIVIDENDS_DWL_SUM (SELECT TICKER, DATE_EX, MIN(IF(DIVIDEND=0,NULL,DIVIDEND)) AS MINDIV, MAX(IF(DIVIDEND=0,NULL,DIVIDEND)) AS MAXDIV, 
SUM(IF(DIVIDEND=0,NULL,1)) AS NBDIV, DIVIDEND AS CORRECT FROM VNDB_DIVIDENDS_DWL_CTRL GROUP BY TICKER, DATE_EX)');
			$this->db->query('UPDATE  VNDB_DIVIDENDS_DWL_SUM SET CORRECT = 0');

			$this->db->query('UPDATE VNDB_DIVIDENDS_DWL_SUM SET CORRECT = 1 WHERE NBDIV>=3 AND MINDIV = MAXDIV');
			$this->db->query('UPDATE VNDB_DIVIDENDS_DWL_SUM SET CORRECT = 9 WHERE MINDIV <> MAXDIV');   
			
            $this->db->query('UPDATE VNDB_DIVIDENDS_DWL A, VNDB_COMPANY B SET A.MARKET = B.MARKET WHERE A.TICKER = B.`CODE`');  

			$this->db->query('TRUNCATE TABLE VNDB_DIVIDENDS');  

			$this->db->query("INSERT INTO VNDB_DIVIDENDS (DATE_EX, TICKER, MARKET)(
select DATE_EX, TICKER, MARKET 
FROM VNDB_DIVIDENDS_DWL 
WHERE LENGTH(TICKER) = 3 
AND MARKET <> 'UPC' 
AND DATE_EX >= '2008-12-31' 
GROUP BY DATE_EX, TICKER 
ORDER BY TICKER, DATE_EX ASC)");  

			$source = array('FPT','STB','CPH','STB','CAF');
			
			foreach($source as $sou){
				$this->db->query('DROP TABLE IF EXISTS TMP');  

				$this->db->query("CREATE TABLE TMP SELECT DATE_EX, TICKER, DIVIDEND FROM VNDB_DIVIDENDS_DWL WHERE SOURCE = '".$sou."' GROUP BY DATE_EX, TICKER ORDER BY TICKER, DATE_EX ASC");  

				$this->db->query("UPDATE VNDB_DIVIDENDS A, TMP B SET A.DIV_".$sou." = B.DIVIDEND WHERE A.DATE_EX = B.DATE_EX AND A.TICKER = B.TICKER"); 
			} 

			$this->db->query('UPDATE VNDB_DIVIDENDS A, VNDB_DIVIDENDS_DWL_SUM B SET A.CORRECT = B.CORRECT WHERE A.TICKER = B.TICKER AND A.DATE_EX = B.DATE_EX');  

			$this->db->query('UPDATE VNDB_DIVIDENDS A, VNDB_DIVIDENDS_DWL_SUM B SET A.DIV_VALUE = B.MINDIV WHERE A.TICKER = B.TICKER AND A.DATE_EX = B.DATE_EX AND B.CORRECT = 1');  

            $total = microtime(true) - $from;
            $response[0]['time'] = round($total, 2);
            $response[0]['task'] = 'Dividend - Import';
            echo json_encode($response);
        }
    }

    public function process_update_dividend_clean() {
        if ($this->input->is_ajax_request()) {
            $from = microtime(true);
            set_time_limit(0);
            $this->db->query("TRUNCATE TABLE vndb_dividends_missing");
            $dir = '\\\LOCAL\IFRCVN\VNDB\METASTOCK\DIVIDEND\MISSING\\';
            $files = glob($dir . '*.txt');
            foreach ($files as $base) {
                $base_url = str_replace("\\", "\\\\", $base);
                $this->db->query("LOAD DATA LOCAL INFILE '" . $base_url . "' INTO TABLE vndb_dividends_missing FIELDS TERMINATED BY '\t' IGNORE 1 LINES (date,ticker,market,amount,dateeff,div_stp,div_cph,div_fpt)");
            }
            $this->db->query("update vndb_dividends a,(select date,ticker,market,amount,dateeff from vndb_dividends_missing) b
set a.value = b.amount where a.correct = 0 and a.ticker = b.ticker and a.market = b.market and a.date = b.date");
            $this->db->query("update vndb_dividends a,(select date,ticker,market,amount,dateeff from vndb_dividends_missing) b
            set a.date_ex = b.date, a.amount = b.amount where a.correct = 2 and a.ticker = b.ticker and a.market = b.market and a.dateeff = b.dateeff");
            $this->db->query("update vndb_dividends a,(select date,ticker,market,amount,dateeff from vndb_dividends_missing) b
            set a.value = b.amount, a.date_ex = b.date where a.correct = 3 and a.ticker = b.ticker and a.market = b.market and a.dateeff = b.dateeff");
            $this->db->query("update vndb_dividends set correct = 1 where value <> 0");
            $total = microtime(true) - $from;
            $response[0]['time'] = round($total, 2);
            $response[0]['task'] = 'Dividend - Clean';
            echo json_encode($response);
        }
    }

    public function process_shares_daily() {
        if ($this->input->is_ajax_request()) {
            $start = $this->input->post('start');
            $end = $this->input->post('end');
            $start = ($start == '') ? date('Ymd') : date('Ymd', strtotime($start));
            $end = ($end == '') ? $start : date('Ymd', strtotime($end));
            $this->db->query("TRUNCATE TABLE vndb_reference_day");
            $dir_exc = '\\\LOCAL\IFRCVN\VNDB\METASTOCK\REFERENCE\EXC\\';
            $files_exc = glob($dir_exc . '*.txt');
            foreach ($files_exc as $base_exc) {
                $filename_exc = pathinfo($base_exc, PATHINFO_FILENAME);
                $arr_exc = explode('_', $filename_exc);
                $date_exc = end($arr_exc);
                $date1_exc = substr($date_exc, 0, 4) . '/' . substr($date_exc, 4, 2) . '/' . substr($date_exc, 6);
                $date1_exc = strtotime($date1_exc);
                if ($start <= $date_exc && $date_exc <= $end && date('D', $date1_exc) != 'Sat' && date('D', $date1_exc) != 'Sun') {
                    $file_name_exc = basename($base_exc, ".txt");
                    $base_url_exc = str_replace("\\", "\\\\", $base_exc);
                    $this->db->query("LOAD DATA LOCAL INFILE '" . $base_url_exc . "' INTO TABLE vndb_reference_day FIELDS TERMINATED BY '\t' IGNORE 1 LINES (source,ticker,name,market,date,yyyymmdd,ipo,ipo_shli,ipo_shou,ftrd,ftrd_cls,shli,shou,shfn,capi,capi_fora,capi_forn,capi_stat);");
                }
            }
            $this->db->query("CREATE TABLE vndb_tmp (SELECT source,ticker,name,market,date,yyyymmdd,ipo,ipo_shli,ipo_shou,ftrd,ftrd_cls,shli,shou,shfn,capi,capi_fora,capi_forn,capi_stat FROM vndb_reference_day ORDER BY ticker,date desc)");
            $this->db->query("TRUNCATE TABLE vndb_reference_day");
            $this->db->query("INSERT INTO vndb_reference_day (source,ticker,name,market,date,yyyymmdd,ipo,ipo_shli,ipo_shou,ftrd,ftrd_cls,shli,shou,shfn,capi,capi_fora,capi_forn,capi_stat) (SELECT source,ticker,name,market,date,yyyymmdd,ipo,ipo_shli,ipo_shou,ftrd,ftrd_cls,shli,shou,shfn,capi,capi_fora,capi_forn,capi_stat
    FROM vndb_tmp)");
            $this->db->query("DROP TABLE IF EXISTS vndb_tmp");

            $this->db->query("TRUNCATE TABLE vndb_reference_day_hnxupc");
            $dir_sd = '\\\LOCAL\IFRCVN\VNDB\METASTOCK\REFERENCE\EXC\SHOU\\';
            $files_sd = glob($dir_sd . '*.txt');
            foreach ($files_sd as $base_sd) {
                $base_url_sd = str_replace("\\", "\\\\", $base_sd);
                $this->db->query("LOAD DATA LOCAL INFILE '" . $base_url_sd . "' INTO TABLE vndb_reference_day_hnxupc FIELDS TERMINATED BY '\t' IGNORE 1 LINES (sources,ticker,name,market,date,yyyymmdd,ipo,ipo_shli,ipo_shou,ftrd,ftrd_cls,shli,shou,shfn,capi,capi_fora,capi_forn,capi_stat);");
                //}
            }
            $this->db->query("update vndb_reference_day a, vndb_reference_day_hnxupc b set a.shou = b.shou where a.yyyymmdd = b.yyyymmdd and a.ticker = b.ticker and a.market = b.market and a.shou = 0");
            $this->db->query("SET NAMES utf8");
            $data = $this->db->query("select source,ticker,name,market,date,yyyymmdd,ipo,ipo_shli,ipo_shou,ftrd,ftrd_cls,shli,shou,shfn,capi,capi_fora,capi_forn,capi_stat from vndb_reference_day")->result_array();
            $implode = array();
            foreach ($data as $item) {
                $header = array_keys($item);
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
            $filename = '\\\LOCAL\IFRCVN\VNDB\METASTOCK\REFERENCE\EXC\ALL\\REF_ALL_' . $start . '.txt';
            $create = fopen($filename, "w");
            $write = fwrite($create, $file);
            fclose($create);
        }
    }

    public function test2() {
        $this->db->query("TRUNCATE TABLE vndb_rmonth");
        $this->db->query("TRUNCATE TABLE vndb_exc30_float");
        $this->db->query("TRUNCATE TABLE vndb_rmonth_stats");
        $dir_rmonth = '\\\LOCAL\IFRCVN\VNDB\METASTOCK\RMONTH\\';
        $files_rmonth = glob("$dir_rmonth{*.csv,*.CSV}", GLOB_BRACE);
        foreach ($files_rmonth as $base_rmonth) {
            $base_url_rmonth = str_replace("\\", "\\\\", $base_rmonth);
            $this->db->query("LOAD DAT LOCAL INFILE '" . $base_url_rmonth . "' INTO TABLE vndb_rmonth FIELDS TERMINATED BY ',' IGNORE 1 LINES (ticker,market,yyyymm,date,svolume,sturnover,svelo,nb,nbt,shli,pcls,ssnb,ssnbt,ssturnover,ssvolume,ssvelo)");
        }
        $dir_ff = '\\\LOCAL\IFRCVN\VNDB\METASTOCK\FREE_FLOAT\\';
        $files_ff = glob("$dir_ff{*.txt,*.TXT}", GLOB_BRACE);
        foreach ($files_ff as $base_ff) {
            $base_url_ff = str_replace("\\", "\\\\", $base_ff);
            $this->db->query("LOAD DATA LOCAL INFILE '" . $base_url_ff . "' INTO TABLE vndb_exc30_float FIELDS TERMINATED BY '\t' IGNORE 1 LINES (sources,market,ticker,name,free_float,wgt,shou)");
        }
        $this->db->query("INSERT INTO vndb_rmonth_stats (ticker,idx_code)(select stk_code,idx_code from vndb_compo where idx_code in ('VNXLM','VNXLG') order by idx_code)");
        $this->db->query("UPDATE vndb_rmonth_stats a, (SELECT ticker,market,yyyymm,date, (shli * pcls) AS capi, (shli*pcls) AS capi_ff, (ssturnover/ssnb) AS turnover, ((ssvelo/ssnb)*MAX(ssnb)) AS velocity, (ssnbt/ssnb) AS quotation, ssnb AS vndb_float, ssnb AS exc30_float, ticker AS idx_code FROM vndb_rmonth WHERE yyyymm = '201211' GROUP BY ticker,yyyymm) b SET a.market = b.market, a.yyyymm = b.yyyymm, a.date = b.date, a.capi = b.capi, a.turnover = b.turnover, a.velocity = b.velocity, a.quotation = b.quotation WHERE a.ticker = b.ticker");
        $this->db->query("UPDATE vndb_rmonth_stats a,(SELECT TICKER,RFF_CAF AS RFF_CAF FROM vndb_daily_float) b
SET a.vndb_float = b.RFF_CAF WHERE a.ticker = b.TICKER");
        $this->db->query("UPDATE vndb_rmonth_stats a,(SELECT ticker,free_float AS exc30_float FROM vndb_exc30_float) b
SET a.exc30_float = b.exc30_float WHERE a.ticker = b.ticker");
    }

    public function test3() {
        $this->db->query("TRUNCATE TABLE vndb_compo");
        $dir = '\\\LOCAL\IFRCVN\VNDB\METASTOCK\COMPO\\';
        $files = glob("$dir{*.txt,*.TXT}", GLOB_BRACE);
        foreach ($files as $base) {
            $base_url = str_replace("\\", "\\\\", $base);
            $this->db->query("LOAD DATA LOCAL INFILE '" . $base_url . "' INTO TABLE vndb_compo FIELDS TERMINATED BY '\t' IGNORE 1 LINES (nridx,yyyymm,end_date,idx_code,stk_code,name,stk_shares,pcls,capi,stk_float,stk_capp,start_date)");
        }
    }

    public function test4() {
        $this->db->query("TRUNCATE TABLE vndb_reference_daily");
        $dir = '\\\LOCAL\IFRCVN\VNDB\METASTOCK\REFERENCE\EXC\ALL\\';
        $files = glob($dir . '*.txt');
        foreach ($files as $base) {
            $filename = pathinfo($base, PATHINFO_FILENAME);
            $arr = explode('_', $filename);
            $year = substr($arr[2], 0, 4);
            if ($year == '2013') {
                $base_url = str_replace("\\", "\\\\", $base);
                $this->db->query("LOAD DATA LOCAL INFILE '" . $base_url . "' INTO TABLE vndb_reference_daily FIELDS TERMINATED BY '\t' IGNORE 1 LINES (source,ticker,name,market,date ,yyyymmdd,ipo,ipo_shli,ipo_shou,ftrd,ftrd_cls,shli,shou,shfn,capi,capi_fora,capi_forn,capi_stat)");
            }
        }
        $this->db->query("CREATE TABLE vndb_tmp (SELECT * FROM vndb_reference_daily ORDER BY ticker,date desc)");
        $this->db->query("TRUNCATE TABLE vndb_reference_daily");
        $this->db->query("INSERT INTO vndb_reference_daily (source,ticker,name,market,date,yyyymmdd,ipo,ipo_shli,ipo_shou,ftrd,ftrd_cls,shli,shou,shfn,capi,capi_fora,capi_forn,capi_stat) (SELECT source,ticker,name,market,date,yyyymmdd,ipo,ipo_shli,ipo_shou,ftrd,ftrd_cls,shli,shou,shfn,capi,capi_fora,capi_forn,capi_stat
FROM vndb_tmp)");
        $this->db->query("DROP TABLE IF EXISTS vndb_tmp");

        $this->db->query("TRUNCATE TABLE vndb_prices_daily");
        $dir = '\\\LOCAL\IFRCVN\VNDB\METASTOCK\PRICES\EXC\\';
        $files = glob($dir . '*.txt');
        foreach ($files as $base) {
            $filename = pathinfo($base, PATHINFO_FILENAME);
            $arr = explode('_', $filename);
            $year = substr($arr[2], 0, 4);
            if ($year == '2013') {
                $file_name = basename($base, ".txt");
                $base_url = str_replace("\\", "\\\\", $base);
                $this->db->query("LOAD DATA LOCAL INFILE '" . $base_url . "' INTO TABLE vndb_prices_daily FIELDS TERMINATED BY '\t' IGNORE 1 LINES");
            }
        }
        $this->db->query("CREATE TABLE vndb_tmp (SELECT * FROM vndb_prices_daily ORDER BY ticker,date desc)");
        $this->db->query("TRUNCATE TABLE vndb_prices_daily");
        $this->db->query("INSERT INTO vndb_prices_daily (source,ticker,market,date,yyyymmdd,shli,shou,shfn,pref,pcei,pflr,popn,phgh,plow,pbase,pavg,pcls,vlm,trn) (SELECT source,ticker,market,date,yyyymmdd,shli,shou,shfn,pref,pcei,pflr,popn,phgh,plow,pbase,pavg,pcls,vlm,trn FROM vndb_tmp)");
        $this->db->query("DROP TABLE IF EXISTS vndb_tmp");

        $this->db->query("TRUNCATE TABLE vndb_reference_day");
        $dir = '\\\LOCAL\IFRCVN\VNDB\METASTOCK\REFERENCE\EXC\\';
        $files = glob($dir . '*.txt');
        foreach ($files as $base) {
            $filename = pathinfo($base, PATHINFO_FILENAME);
            $arr = explode('_', $filename);
            $date[] = $arr[2];
        }
        $main_date = max($date);
        foreach ($files as $base) {
            $filename = pathinfo($base, PATHINFO_FILENAME);
            $arr = explode('_', $filename);
            if ($arr[2] == $main_date) {
                $base_url = str_replace("\\", "\\\\", $base);
                $this->db->query("LOAD DATA LOCAL INFILE '" . $base_url . "' INTO TABLE vndb_reference_day FIELDS TERMINATED BY '\t' IGNORE 1 LINES");
            }
        }

        $this->db->query("TRUNCATE TABLE vndb_prices_day");
        $dir = '\\\LOCAL\IFRCVN\VNDB\METASTOCK\PRICES\EXC\\';
        $files = glob($dir . '*.txt');
        foreach ($files as $base) {
            $filename = pathinfo($base, PATHINFO_FILENAME);
            $arr = explode('_', $filename);
            $date[] = $arr[2];
        }
        $main_date = max($date);
        foreach ($files as $base) {
            $filename = pathinfo($base, PATHINFO_FILENAME);
            $arr = explode('_', $filename);
            if ($arr[2] == $main_date) {
                $base_url = str_replace("\\", "\\\\", $base);
                $this->db->query("LOAD DATA LOCAL INFILE '" . $base_url . "' INTO TABLE vndb_prices_day FIELDS TERMINATED BY '\t' IGNORE 1 LINES");
            }
        }
    }
	/***************************************************************************************************************************************************************/
	public function source_import_ownership(){
		$dir = '\\\LOCAL\IFRCVN\VNDB\METASTOCK\OWNERSHIP\SOURCES\\';
		$dir_final = '\\\LOCAL\IFRCVN\VNDB\METASTOCK\OWNERSHIP\FINAL\\';
		$now_date = date('Ymd',time());
		$files_final = glob($dir_final . '*.txt');
		foreach($files_final as $base_final){
			$file_name_final = basename($base_final, ".txt");
			$file_name_final = explode('_',$file_name_final);
			if($file_name_final[2] == $now_date && $file_name_final[1] != 'HNX' && $file_name_final[1] != 'VNI'){
				unlink($base_final);
			}
		}
		if (is_dir ($dir)) {
			$dh = opendir ($dir) or die (" Directory Open failed !");
			while ($file = readdir ($dh)) {
				if($file != '.' && $file != '..'){
					$dir_source = $dir.$file.'\\';
					$filename = $dir_final.'OWNERSHIP_'.$file.'_'.$now_date.'.txt';
					$files = glob($dir_source . '*.txt');
					$data = array();
					foreach ($files as $base) {
						$file_name = basename($base, ".txt");
						$file_name = explode('_',$file_name);
						$data = file_get_contents($base, FILE_USE_INCLUDE_PATH);
						$data = explode("\r\n",trim($data));
						if($file_name[1] != 'AAA'){
							unset($data[0]);
						}
						if(empty($data)){
							unset($data);	
						}else{
							$data = implode("\r\n",$data);
							$data .= "\r\n";
							$file=fopen($filename, "a");
							$write=fwrite($file,$data);
							fclose($file);
						}
					}
				}
			}
		}
		
		
		$table = 'VNDB_OWNERSHIP_DWL';
		$option = '\t';
		$column = 'TICKER,NAME,OWN_POS,OWN_SHARES,OWN_PERC,DATEUPD';
		$day = date("Y-m-d", time());
		$latest_ctime = 1360037108;
		$latest_filename = '';  
		$this->db->query("TRUNCATE TABLE VNDB_OWNERSHIP_DWL");
		$path = '\\\LOCAL\IFRCVN\VNDB\METASTOCK\OWNERSHIP\FINAL\\';
		$d = dir($path);
		while (false !== ($entry = $d->read())) {
			$filepath = "{$path}{$entry}";
		  	if (is_file($filepath) && filectime($filepath) > $latest_ctime) {
				$latest_ctime = filectime($filepath);
				$latest_filename = $entry;
				$data_file[] = $filepath;
		  	}
		}
		$output = array_slice($data_file, count($data_file)-4, count($data_file));
		foreach ($output as $base_output) {
			$filename_output = basename($base_output, ".txt");
			$filename_output = explode('_',$filename_output);
			$base_url = str_replace("\\", "\\\\", $base_output);
			$this->db->query("LOAD DATA LOCAL INFILE '{$base_url}' INTO TABLE {$table} FIELDS TERMINATED BY '{$option}' IGNORE 1 LINES ({$column}) SET SOURCE = '{$filename_output[1]}', DATE = '{$day}'");
		}
	}
	public function source_update_ownership($date){
		$this->db->query("SET @TEST = 10");

		$this->db->query("drop table if EXISTS temp");
		$this->db->query("create table temp (SELECT * FROM VNDB_OWNERSHIP_DWL ORDER BY DATEUPD DESC )");
		$this->db->query("DROP TABLE IF EXISTS VNDB_OWNERSHIP_UNI");
		$this->db->query("CREATE TABLE VNDB_OWNERSHIP_UNI select * from temp GROUP BY SOURCE, TICKER, `NAME`");
		$this->db->query("CREATE INDEX TICKER ON VNDB_OWNERSHIP_UNI (TICKER) USING BTREE");
		
		$this->db->query("drop table if EXISTS temp");
		$this->db->query("create table temp (SELECT * FROM VNDB_REFERENCE_DAILY ORDER BY DATE DESC )");
		$this->db->query("DROP TABLE IF EXISTS VNDB_REFERENCE_LAST");
		$this->db->query("CREATE TABLE VNDB_REFERENCE_LAST select * from temp GROUP BY TICKER");
		$this->db->query("CREATE INDEX TICKER ON VNDB_REFERENCE_LAST (TICKER) USING BTREE");
		$this->db->query("drop table if EXISTS temp");
		
		$this->db->query("TRUNCATE TABLE VNDB_OWNERSHIP_COMPARE");
		$this->db->query("INSERT INTO VNDB_OWNERSHIP_COMPARE (TICKER, MARKET) (SELECT TICKER, MARKET FROM VNDB_REFERENCE_LAST WHERE MARKET <> 'UPC' AND LENGTH(TICKER) = 3)");
		
		$this->db->query("UPDATE VNDB_OWNERSHIP_UNI A, VNDB_REFERENCE_LAST B SET A.`share`= GREATEST(B.shli,B.shou) WHERE A.TICKER = B.TICKER");
		$this->db->query("UPDATE VNDB_OWNERSHIP_UNI SET OWN_PERC = 0");
		$this->db->query("UPDATE VNDB_OWNERSHIP_UNI SET NFF = 0");
		$this->db->query("UPDATE VNDB_OWNERSHIP_UNI SET OWN_PERC = 100*(OWN_SHARES/`SHARE`) WHERE `SHARE` > 0");
		$this->db->query("UPDATE VNDB_OWNERSHIP_UNI SET NFF = 1 WHERE OWN_PERC >= 5 AND NOT ((LOCATE('FUND', UPPER(NAME)) > 0  or LOCATE('Quỹ', (NAME)) > 0 or LOCATE('ETF', (NAME)) > 0) AND NOT LOCATE('Quỳnh', (NAME)) > 0)");
		
		$this->db->query("drop table if EXISTS temp");
		$this->db->query("create table temp (SELECT SOURCE, TICKER, SUM(OWN_PERC * NFF) AS SNFF, 100-@TEST*FLOOR(SUM(OWN_PERC * NFF)/@TEST) as FLOATS 
		FROM VNDB_OWNERSHIP_UNI GROUP BY TICKER, SOURCE ORDER BY TICKER, SOURCE)");
		$this->db->query("CREATE INDEX TICKER ON temp (TICKER) USING BTREE");
		
		$this->db->query("UPDATE VNDB_OWNERSHIP_COMPARE A, VNDB_DAILY B SET A.CAPI = B.LAST*greatest(B.SHLI,B.SHOU) WHERE A.TICKER = B.TICKER AND B.YYYYMMDD = '20130204'");
		
		$this->db->query("UPDATE VNDB_OWNERSHIP_COMPARE A, TEMP B SET A.STB = B.FLOATS WHERE A.TICKER = B.TICKER AND SOURCE = 'STB'");
		$this->db->query("UPDATE VNDB_OWNERSHIP_COMPARE A, TEMP B SET A.CPH = B.FLOATS WHERE A.TICKER = B.TICKER AND SOURCE = 'CPH'");
		$this->db->query("UPDATE VNDB_OWNERSHIP_COMPARE A, TEMP B SET A.CAF = B.FLOATS WHERE A.TICKER = B.TICKER AND SOURCE = 'CAF'");
		$this->db->query("UPDATE VNDB_OWNERSHIP_COMPARE A, TEMP B SET A.STP = B.FLOATS WHERE A.TICKER = B.TICKER AND SOURCE = 'STP'");
		$this->db->query("UPDATE VNDB_OWNERSHIP_COMPARE A, vndb_exc30_float B SET A.EXC = B.FREE_FLOAT WHERE A.TICKER = B.TICKER");
		
		$this->db->query("drop table if EXISTS temp");
		$this->db->query("create table temp (SELECT * FROM VNDB_OWNERSHIP_COMPARE ORDER BY CAPI DESC )");
		$this->db->query("DROP TABLE IF EXISTS VNDB_OWNERSHIP_COMPARE");
		$this->db->query("CREATE TABLE VNDB_OWNERSHIP_COMPARE select * from temp");
		$this->db->query("drop table if EXISTS temp");
		
		$this->db->query("UPDATE VNDB_OWNERSHIP_COMPARE SET VALUE = NULL, STB = IF(STB IS NULL,0,STB), CPH = IF(CPH IS NULL,0,CPH), CAF = IF(CAF IS NULL,0,CAF), VALUE = 
		IF(EXC IS NOT NULL,EXC,
		CASE
		WHEN STB = CPH AND CPH = CAF THEN STB
		WHEN STB = CPH AND CPH <> CAF THEN STB
		WHEN CPH = CAF AND STB <> CAF THEN CPH
		WHEN STB = CAF AND CPH <> CAF THEN STB
		ELSE NULL
		END), VALUE = IF(VALUE <= 0, NULL, VALUE), VALUE = 
		IF(VALUE IS NULL, IF((CAF > 0) AND ((GREATEST(STB,CPH,CAF) - CAF <= 20) AND ((LEAST(STB,CPH,CAF) - CAF >= -20))),CAF,NULL),VALUE)");
		
		$this->db->query("DROP TABLE IF EXISTS VNDB_FREEFLOAT_CORRECT");
		$this->db->query("CREATE TABLE VNDB_FREEFLOAT_CORRECT SELECT ticker, market, capi, `value`, `value` AS pvalue, `value` AS fvalue, correct, CURDATE() AS `update` FROM VNDB_OWNERSHIP_COMPARE");
		
		$this->db->query("TRUNCATE TABLE VNDB_FREEFLOAT_CORRECT");
		
		$this->db->query("ALTER TABLE VNDB_FREEFLOAT_CORRECT ADD id INT (10) NOT NULL AUTO_INCREMENT PRIMARY KEY");
		
		$path = '\\\LOCAL\IFRCVN\VNDB\METASTOCK\OWNERSHIP\FREE_FLOAT\FINAL\\';
		$d = dir($path);
		$latest_ctime = 1360037108;
		$latest_filename = '';  
		while (false !== ($entry = $d->read())) {
			$filepath = "{$path}{$entry}";
		  	if (is_file($filepath) && filectime($filepath) > $latest_ctime) {
				$latest_ctime = filectime($filepath);
				$latest_filename = $entry;
				$data_file[] = $filepath;
		  	}
		}
		$output = array_slice($data_file, count($data_file)-1, count($data_file));
		foreach ($output as $base_final) {
			$base_url_final = str_replace("\\", "\\\\", $base_final);
			$this->db->query("LOAD DATA LOCAL INFILE '".$base_url_final."' INTO TABLE VNDB_FREEFLOAT_CORRECT FIELDS TERMINATED BY '\t' IGNORE 1 LINES 
		(ticker,market,capi,pvalue,correct,`update`)");
		}
		
		$this->db->query("UPDATE VNDB_FREEFLOAT_CORRECT A, VNDB_OWNERSHIP_COMPARE B SET A.`VALUE` = B.`VALUE` WHERE A.TICKER = B.TICKER AND A.MARKET = B.MARKET");
		
		$this->db->query("UPDATE VNDB_FREEFLOAT_CORRECT SET FVALUE = IF(`VALUE` = PVALUE AND (`VALUE` * PVALUE <> 0), `VALUE`,NULL)");
				
		$this->db->query("UPDATE VNDB_FREEFLOAT_CORRECT SET FVALUE = IF(`UPDATE` > '2013-02-21', PVALUE, NULL) WHERE FVALUE IS NULL");
				
		$this->db->query("UPDATE VNDB_FREEFLOAT_CORRECT SET CORRECT = NULL, CORRECT = IF(FVALUE IS NOT NULL,1,0), `UPDATE` = IF(FVALUE IS NOT NULL,CURDATE(),`UPDATE`)");
		
		$this->db->query("DROP TABLE IF EXISTS VNDB_FREEFLOAT_FINAL");
		$this->db->query("CREATE TABLE VNDB_FREEFLOAT_FINAL SELECT ticker, market, capi, fvalue as value, correct , `update` FROM VNDB_FREEFLOAT_CORRECT");
		
		$this->db->query("ALTER TABLE VNDB_FREEFLOAT_FINAL ADD id INT (10) NOT NULL AUTO_INCREMENT PRIMARY KEY");
		
		$data = $this->db->query("SELECT ticker, market, capi, value, correct , `update` FROM VNDB_FREEFLOAT_FINAL")->result_array();
		$implode = array();
		foreach ($data as $item) {
			$header = array_keys($item);
			$implode[] = implode("\t", $item);
		}
		$header = implode("\t", $header);
		$implode = implode("\r\n", $implode);
		$file = $header . "\r\n";
		$file .= $implode;
		$filename = $dir_final."FINAL_FREEFLOAT_".date('Ymd',time()).".txt";
		$create = fopen($filename, "w");
		$write = fwrite($create, $file);
		fclose($create);				
	}
	public function test_minh(){
		  $this->db->query("TRUNCATE TABLE vndb_dividends_dwl");
            $dir_div = '\\\LOCAL\IFRCVN\VNDB\METASTOCK\DIVIDEND\FINAL\\';
            $files_div = glob($dir_div . '*.txt');
            foreach ($files_div as $base_div) {
                $base_url_div = str_replace("\\", "\\\\", $base_div);
                $this->db->query("LOAD DATA LOCAL INFILE '" . $base_url_div . "' INTO TABLE vndb_dividends_dwl FIELDS TERMINATED BY '\t' IGNORE 1 LINES (source,ticker,date, date_ex, date_rec, date_pay, pay_method, pay_year, pay_period, dividend, yield, price_exd )");
            }
	}
}
