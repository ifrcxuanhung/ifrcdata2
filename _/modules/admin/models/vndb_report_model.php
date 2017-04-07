<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Vndb_report_model extends CI_Model {
    public $change;
    function __construct() {
        parent::__construct();
    }

    function countData($market, $start, $end) {
        //if (date('Y-m-d', time()) == $end) {
        $table = 'vndb_daily';
        $last = 'pref = 0 AND plow = 0';
        // } else {
        //     $table = 'vndb_prices_uni';
        //     $last = '`last` = 0';
        // }
        $sql = "SELECT (SELECT COUNT(*) FROM $table WHERE market = '$market' AND `date` >= '$start' AND `date` <= '$end' AND LENGTH(`ticker`) = 3) as row, (SELECT COUNT(*) FROM $table WHERE market = '$market' AND `date` >= '$start' AND `date` <= '$end' AND shli = 0 AND LENGTH(`ticker`) = 3) as shli, (SELECT COUNT(*) FROM $table WHERE market = '$market' AND `date` >= '$start' AND `date` <= '$end' AND shou = 0 AND LENGTH(`ticker`) = 3) as shou, (SELECT COUNT(*) FROM $table WHERE market = '$market' AND `date` >= '$start' AND `date` <= '$end' AND $last AND LENGTH(`ticker`) = 3) as last, (SELECT COUNT(*) FROM vndb_return_des WHERE market = '$market' AND `date` >= '$start' AND `date` <= '$end' AND adj_pcls = 0 AND LENGTH(`ticker`) = 3) as adj_cls";
        $rows = $this->db->query($sql)->result_array();
        if($rows[0]['shou'] <= 10 && $rows[0]['shou'] > 0){
            $sql = "SELECT ticker FROM $table WHERE market = '$market' AND `date` >= '$start' AND `date` <= '$end' AND shou = 0 AND LENGTH(`ticker`) = 3";
            $result = $this->db->query($sql)->result_array();
            foreach($result as $item){
                $temp[] = $item['ticker'];
            }
            $rows[0]['shou'] .= ' (' . implode(', ', $temp) . ')';
        }
        if($rows[0]['shli'] <= 10 && $rows[0]['shli'] > 0){
            $sql = "SELECT ticker FROM $table WHERE market = '$market' AND `date` >= '$start' AND `date` <= '$end' AND shli = 0 AND LENGTH(`ticker`) = 3";
            $result = $this->db->query($sql)->result_array();
            foreach($result as $item){
                $temp[] = $item['ticker'];
            }
            $rows[0]['shli'] .= ' (' . implode(', ', $temp) . ')';
        }
        if($rows[0]['last'] <= 10 && $rows[0]['last'] > 0){
            $sql = "SELECT ticker FROM $table WHERE market = '$market' AND `date` >= '$start' AND `date` <= '$end' AND $last AND LENGTH(`ticker`) = 3";
            $result = $this->db->query($sql)->result_array();
            foreach($result as $item){
                $temp[] = $item['ticker'];
            }
            $rows[0]['last'] .= ' (' . implode(', ', $temp) . ')';
        }
        if($rows[0]['adj_cls'] <= 10 && $rows[0]['adj_cls'] > 0){
            $sql = "SELECT ticker FROM vndb_return_des WHERE market = '$market' AND `date` >= '$start' AND `date` <= '$end' AND adj_pcls = 0 AND LENGTH(`ticker`) = 3";
            $result = $this->db->query($sql)->result_array();
            foreach($result as $item){
                $temp[] = $item['ticker'];
            }
            $rows[0]['adj_cls'] .= ' (' . implode(', ', $temp) . ')';
        }
        return $rows[0];
    }

    public function intro_remove_company($start, $end) {
        $where = '';
        if ($start != '' && $end != '') {
            $where = "AND `date` >= '{$start}' AND `date` <= '{$end}'";
        }
        $sql = "select id,market,ticker,date,yyyymmdd from vndb_prices_uni where 1=1 {$where} ORDER BY date ASC";
        $data = $this->db->query($sql)->result_array();
        $data_group_date = array();
        if (is_array($data) && count($data) > 0) {
            foreach ($data as $key => $value) {
                $data_group_date[$value['yyyymmdd']][$value['market'] . $value['ticker']] = $value;
            }

            $data_group_date = array_values($data_group_date);
            $count = count($data_group_date) - 1;
            $result = array();
            foreach ($data_group_date as $key => $value) {
                $j = $key + 1;
                if ($j <= $count) {
                    $curent = array_keys($value);
                    $next = array_keys($data_group_date[$j]);
                    $plus = array_diff($next, $curent);
                    $subtract = array_diff($curent, $next);
                    if ((is_array($plus) && count($plus) > 0) or (is_array($subtract) && count($subtract) > 0)) {
                        $date = $data_group_date[$j][$next[0]]['yyyymmdd'];
                        if (is_array($plus) && count($plus) > 0) {
                            foreach ($plus as $vplus) {
                                $result[$date]['plus'][] = $data_group_date[$j][$vplus];
                            }
                        }
                        if (is_array($subtract) && count($subtract) > 0) {
                            foreach ($subtract as $vsubtract) {
                                $result[$date]['subtract'][] = $value[$vsubtract];
                            }
                        }
                    }
                }
            }
            return $result;
        }
    }

    public function new_company() {
        $sql = "SELECT DISTINCT ticker FROM vndb_daily WHERE ticker NOT IN ( SELECT `code` FROM vndb_company)";
        return $this->db->query($sql)->result_array();
    }

    public function min_date() {
        $sql = "SELECT date FROM vndb_daily ORDER BY date ASC limit 1";
        return $this->db->query($sql)->row_array();
    }

    public function min_date_hostory() {
        $sql = "SELECT date FROM vndb_prices_uni ORDER BY date ASC limit 1";
        return $this->db->query($sql)->row_array();
    }

    function countData_history($market, $start, $end) {
        $table = 'vndb_prices_uni';
        $last = 'pref = 0 AND plow = 0';
        $sql = "SELECT (SELECT COUNT(*) FROM $table WHERE market = '$market' AND `date` >= '$start' AND `date` <= '$end' AND LENGTH(`ticker`) = 3) as row, (SELECT COUNT(*) FROM $table WHERE market = '$market' AND `date` >= '$start' AND `date` <= '$end' AND shli = 0 AND LENGTH(`ticker`) = 3) as shli,  (SELECT COUNT(*) FROM $table WHERE market = '$market' AND `date` >= '$start' AND `date` <= '$end' AND shou = 0 AND LENGTH(`ticker`) = 3) as shou, (SELECT COUNT(*) FROM $table WHERE market = '$market' AND `date` >= '$start' AND `date` <= '$end' AND $last AND LENGTH(`ticker`) = 3) as last, (SELECT COUNT(*) FROM vndb_return_des WHERE market = '$market' AND `date` >= '$start' AND `date` <= '$end' AND adj_pcls = 0 AND LENGTH(`ticker`) = 3) as adj_cls";
        $rows = $this->db->query($sql)->result_array();
        //echo $sql; die();
        return $rows[0];
    }

    function checkSharesChange($table, $ticker, $date1 = '0000-00-00', $date2 = '0000-00-00', $enddate = '0000-00-00', $shli = 0, $shou = 0){
        if($shli != -1){
            $sql = "SELECT ticker, market, date, shli, shou FROM $table WHERE ticker = '$ticker' AND date >= '$date1' AND date <= '$enddate' AND shli <> '$shli' ORDER BY date ASC LIMIT 0, 1";
            $rows1 = $this->db->query($sql)->result_array();
        }
        if($shou != -1){
            $sql = "SELECT ticker, market, date, shli, shou FROM $table WHERE ticker = '$ticker' AND date >= '$date2' AND date <= '$enddate' AND shou <> '$shou' ORDER BY date ASC LIMIT 0, 1";
            $rows2 = $this->db->query($sql)->result_array();
        }

        if(!empty($rows1)){
            if($shli != 0){
                $rows1[0]['shli_change'] = $rows1[0]['shli'] - $shli;
                $this->change[$rows1[0]['date']] = $rows1[0];
            }
            $shli = $rows1[0]['shli'];
            $date1 = $rows1[0]['date'];
        }else{
            $shli = -1;
            $date1 = '';
        }
        if(!empty($rows2)){
            if($shou != 0){
                $rows2[0]['shou_change'] = $rows2[0]['shou'] - $shou;
                $this->change[$rows2[0]['date']] = $rows2[0];
            }
            $shou = $rows2[0]['shou'];
            $date2 = $rows2[0]['date'];
        }else{
            $shou = -1;
            $date2 = '';
        }
        if($shli != -1 || $shou != -1){
            $this->checkSharesChange($table, $ticker, $date1, $date2, $enddate, $shli, $shou);
        }
        return $this->change;
    }

    function checkAllTicker($table, $start = '0000-00-00', $end = '0000-00-00'){
        ini_set('memory_limit', '-1');
        $this->db->distinct('ticker');
        $this->db->select('ticker');
        $rows = $this->db->get('vndb_prices_uni')->result_array();
        $i = 1;
        $data = '';
        foreach($rows as $ticker){
            $ticker = $ticker['ticker'];
            $change = $this->checkSharesChange($table, $ticker, $start, $start, $end);
            if($change != ''){
                $data[$ticker] = $change;
            }
            $this->change = '';
        }
        return $data;
    }

}

