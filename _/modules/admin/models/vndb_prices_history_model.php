<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Vndb_prices_history_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function insert_data_into_table_vndb_prices_history() {
        //array $result return error if exists when run query
        $result = array();
        //$count return number error
        $count = 0;
        //$start return time excute
        $start = microtime_float();
        $this->db->query('SET innodb_lock_wait_timeout = 10000000');

        $sql = "TRUNCATE TABLE `vndb_prices_history`";
        $this->db->simple_query($sql);

        $sql = "INSERT INTO `vndb_prices_history` (`vndb_prices_history`.adj_coeff, `vndb_prices_history`.adj_pcls, `vndb_prices_history`.date, `vndb_prices_history`.dividend,
                `vndb_prices_history`.last, `vndb_prices_history`.market, `vndb_prices_history`.pavg, `vndb_prices_history`.pbase,
                `vndb_prices_history`.pcei, `vndb_prices_history`.pcls, `vndb_prices_history`.pflr, `vndb_prices_history`.phgh, `vndb_prices_history`.plow,
                `vndb_prices_history`.popn, `vndb_prices_history`.pref, `vndb_prices_history`.shares, `vndb_prices_history`.shfn, `vndb_prices_history`.shli,
                `vndb_prices_history`.shou, `vndb_prices_history`.source, `vndb_prices_history`.ticker, `vndb_prices_history`.trn, `vndb_prices_history`.vlm,
                `vndb_prices_history`.yyyymmdd)
                SELECT `vndb_meta_prices`.adj_coeff, `vndb_meta_prices`.adj_pcls, `vndb_meta_prices`.date, `vndb_meta_prices`.dividend,
                `vndb_meta_prices`.last, `vndb_meta_prices`.market, `vndb_meta_prices`.pavg, `vndb_meta_prices`.pbase,
                `vndb_meta_prices`.pcei, `vndb_meta_prices`.pcls, `vndb_meta_prices`.pflr, `vndb_meta_prices`.phgh, `vndb_meta_prices`.plow,
                `vndb_meta_prices`.popn, `vndb_meta_prices`.pref, `vndb_meta_prices`.shares, `vndb_meta_prices`.shfn, `vndb_meta_prices`.shli,
                `vndb_meta_prices`.shou, `vndb_meta_prices`.source, `vndb_meta_prices`.ticker, `vndb_meta_prices`.trn, `vndb_meta_prices`.vlm,
                `vndb_meta_prices`.yyyymmdd
                FROM `vndb_meta_prices`
                GROUP BY `vndb_meta_prices`.`ticker`, `vndb_meta_prices`.`date`
                ORDER BY `vndb_meta_prices`.`ticker`, `vndb_meta_prices`.`date`;";
        $this->db->simple_query($sql);

        $sql = "TRUNCATE TABLE `vndb_shares_history`;";
        $this->db->simple_query($sql);

        $file_vndb_shares_history = str_replace("\\", "\\\\", "\\\LOCAL\IFRCDATA\VNDB\HISTORY\\VNDB_SHARES_HISTORY.txt");
        $sql = "LOAD DATA INFILE '{$file_vndb_shares_history}'
                INTO TABLE `vndb_shares_history`
                FIELDS TERMINATED BY '\t'
                IGNORE 1 LINES
                (`ticker`, `market`, `date`, `yyyymmdd`, `shli`, `shou`);";
        $err = $this->db->simple_query($sql);
        if (!$err) {
            $result[$count] = array('task' => trans('VNDB_SHARES_HISTORY.txt not found!', 1), 'time' => number_format(microtime_float() - $start, 2));
            $count++;
        } else {
            $sql = "UPDATE `vndb_shares_history`, `vndb_reference_daily`
                SET `vndb_shares_history`.`shli` = `vndb_reference_daily`.`shli`,
                `vndb_shares_history`.`shou` = `vndb_reference_daily`.`shou`
                WHERE `vndb_shares_history`.`ticker` = `vndb_reference_daily`.`ticker`
                AND `vndb_shares_history`.`yyyymmdd` = `vndb_reference_daily`.`yyyymmdd`;";
            $this->db->simple_query($sql);

            $sql = "UPDATE `vndb_prices_history`, `vndb_shares_history`
                SET `vndb_prices_history`.`shli` = `vndb_shares_history`.`shli`,
                `vndb_prices_history`.`shou` = `vndb_shares_history`.`shou`
                WHERE `vndb_prices_history`.`ticker` = `vndb_shares_history`.`ticker`
                AND `vndb_prices_history`.`yyyymmdd` = `vndb_shares_history`.`yyyymmdd`;";
            $this->db->simple_query($sql);
        }

        $sql = "TRUNCATE TABLE `vndb_dividends_history`;";
        $this->db->simple_query($sql);

        $file_vndb_dividends_history = str_replace("\\", "\\\\", "\\\LOCAL\IFRCDATA\VNDB\HISTORY\\VNDB_DIVIDENDS_HISTORY.txt");
        $sql = "LOAD DATA INFILE '{$file_vndb_dividends_history}'
                INTO TABLE `vndb_dividends_history`
                FIELDS TERMINATED BY '\t'
                IGNORE 1 LINES
                (`ticker`, `market`, `date`, `yyyymmdd`, `dividend`);";
        $err = $this->db->simple_query($sql);
        if (!$err) {
            $result[$count] = array('task' => trans('VNDB_DIVIDENDS_HISTORY.txt not found!', 1), 'time' => number_format(microtime_float() - $start, 2));
            $count++;
        } else {
            $sql = "UPDATE `vndb_prices_history`, `vndb_dividends_history`
                SET `vndb_prices_history`.`dividend` = `vndb_dividends_history`.`dividend`
                WHERE `vndb_prices_history`.`ticker` = `vndb_dividends_history`.`ticker`
                AND `vndb_prices_history`.`yyyymmdd` = `vndb_dividends_history`.`yyyymmdd`;";
            $this->db->simple_query($sql);
        }

        $sql = "TRUNCATE TABLE `vndb_apcls_history`;";
        $this->db->simple_query($sql);

        $file_vndb_apcls_history = str_replace("\\", "\\\\", "\\\LOCAL\IFRCDATA\VNDB\HISTORY\\VNDB_APCLS_HISTORY.txt");
        $sql = "LOAD DATA INFILE '{$file_vndb_apcls_history}'
                INTO TABLE `vndb_apcls_history`
                FIELDS TERMINATED BY '\t'
                IGNORE 1 LINES
                (`ticker`, `market`, `date`, `yyyymmdd`, `last`, `adj_pcls`, `rt_pcls`);";
        $err = $this->db->simple_query($sql);
        if (!$err) {
            $result[$count] = array('task' => trans('VNDB_APCLS_HISTORY.txt not found!', 1), 'time' => number_format(microtime_float() - $start, 2));
            $count++;
        } else {
            $sql = "UPDATE `vndb_prices_history`, `vndb_apcls_history`
                SET `vndb_prices_history`.`adj_pcls` = `vndb_apcls_history`.`adj_pcls`
                WHERE `vndb_prices_history`.`ticker` = `vndb_apcls_history`.`ticker`
                AND `vndb_prices_history`.`yyyymmdd` = `vndb_apcls_history`.`yyyymmdd`;";
            $this->db->simple_query($sql);
        }

        $sql = "TRUNCATE TABLE `vndb_prices_cor`;";
        $this->db->simple_query($sql);

        $file_vndb_prices_cor = str_replace("\\", "\\\\", "\\\LOCAL\IFRCDATA\VNDB\HISTORY\\VNDB_PRICES_COR.txt");
        $sql = "LOAD DATA INFILE '{$file_vndb_prices_cor}'
                INTO TABLE `vndb_prices_cor`
                FIELDS TERMINATED BY '\t'
                IGNORE 1 LINES
                (`ticker`, `date`, `yyyymmdd`, `pref`);";
        $err = $this->db->simple_query($sql);
        if (!$err) {
            $result[$count] = array('task' => trans('VNDB_PRICES_COR.txt not found!', 1), 'time' => number_format(microtime_float() - $start, 2));
            $count++;
        } else {
            $sql = "UPDATE `vndb_prices_history`, `vndb_prices_cor`
                SET `vndb_prices_history`.`last` = IF(`vndb_prices_history`.`pcls` = 0, `vndb_prices_cor`.`pref`, `vndb_prices_history`.`pcls`)
                WHERE `vndb_prices_history`.`ticker` = `vndb_prices_cor`.`ticker`
                AND `vndb_prices_history`.`yyyymmdd` = `vndb_prices_cor`.`yyyymmdd`;";
            $this->db->simple_query($sql);
        }

        $sql = "UPDATE `vndb_prices_history`, `vndb_reference_daily`
                SET `vndb_prices_history`.`shli` = `vndb_reference_daily`.`shli`,
                `vndb_prices_history`.`shou` = `vndb_reference_daily`.`shou`
                WHERE `vndb_prices_history`.`ticker` = `vndb_reference_daily`.`ticker`
                AND `vndb_prices_history`.`yyyymmdd` = `vndb_reference_daily`.`yyyymmdd`;";
        $this->db->simple_query($sql);
        return $result;
    }

    public function insert_data_into_table_qidx_mdata() {
        $this->db->query('SET innodb_lock_wait_timeout = 10000000');

        $sql = "TRUNCATE TABLE `qidx_mdata`;";
        $this->db->simple_query($sql);

        $sql = "INSERT INTO `qidx_mdata` (`stk_code`, `date`, `stk_shares`, `adjclose`, `close`, `divtr`, `stk_cur`)
                SELECT `vndb_prices_history`.`ticker` AS `stk_code`, `vndb_prices_history`.`date`,
                IF(`vndb_prices_history`.`shli` IS NOT NULL, `vndb_prices_history`.`shli`, 0) AS `stk_shares`,
                IF(`vndb_prices_history`.`adj_pcls` IS NOT NULL, `vndb_prices_history`.`adj_pcls`, 0) AS `adjclose`,
                IF(`vndb_prices_history`.`last` IS NOT NULL, `vndb_prices_history`.`last`, 0) AS `close`,
                IF(`vndb_prices_history`.`dividend` IS NOT NULL, `vndb_prices_history`.`dividend`, 0) AS `divtr`,
                'VND' AS `stk_cur`
                FROM `vndb_prices_history`
                WHERE `vndb_prices_history`.`date` >= '2007-12-28'
                AND LENGTH(`vndb_prices_history`.`ticker`) = 3
                AND `vndb_prices_history`.`market` != 'UPC'
                ORDER BY `vndb_prices_history`.`ticker`, `vndb_prices_history`.`date`;";
        $this->db->simple_query($sql);
    }

    public function export_qidx_mdata_txt_from_table_vndb_prices_history() {
        self::insert_data_into_table_qidx_mdata();
        
        $sql = "SELECT `vndb_prices_history`.`ticker` AS `stk_code`, `vndb_prices_history`.`date`,
                IF(`vndb_prices_history`.`shli` IS NOT NULL, `vndb_prices_history`.`shli`, 0) AS `stk_shares`,
                IF(`vndb_prices_history`.`adj_pcls` IS NOT NULL, `vndb_prices_history`.`adj_pcls`, 0) AS `adjclose`,
                IF(`vndb_prices_history`.`last` IS NOT NULL, `vndb_prices_history`.`last`, 0) AS `close`,
                IF(`vndb_prices_history`.`dividend` IS NOT NULL, `vndb_prices_history`.`dividend`, 0) AS `divtr`,
                'VND' AS `stk_cur`
                FROM `vndb_prices_history`
                WHERE `vndb_prices_history`.`date` >= '2007-12-28'
                AND LENGTH(`vndb_prices_history`.`ticker`) = 3
                AND `vndb_prices_history`.`market` != 'UPC'
                ORDER BY `vndb_prices_history`.`ticker`, `vndb_prices_history`.`date`;";
        $arr = $this->db->query($sql)->result_array();

        //export file QIDX_MDATA.txt
        if (isset($arr) == TRUE && is_array($arr) == TRUE && count($arr) > 0) {
            $path = APPPATH . '../../assets/download/views/';
            $path_file = $path . 'QIDX_MDATA.txt';
            $headers = array('stk_code', 'date', 'stk_shares', 'adjclose', 'close', 'divtr', 'stk_cur');
            $temp = '';
            foreach ($headers as $value) {
                $temp .= $value . chr(9);
            }
            $data[0] = trim($temp) . PHP_EOL;
            foreach ($arr as $key => $item) {
                $temp = '';
                foreach ($headers as $value) {
                    $temp .= $item[$value] . chr(9);
                }
                $data[] = trim($temp) . PHP_EOL;
            }
            file_put_contents($path_file, $data);
        }
        //end export file QIDX_MDATA.txt

        //download file QIDX_MDATA.txt
        
        //end download file QIDX_MDATA.txt
    }

}
