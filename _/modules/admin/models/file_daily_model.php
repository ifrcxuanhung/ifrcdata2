<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/* * ****************************************************************** */
/*     Client  Name  :  IFRC                                      */
/*     Project Name  :  cms v3.0                                     */
/*     Program Name  :  article.php                                      */
/*     Entry Server  :                                               */
/*     Called By     :  System                                       */
/*     Notice        :  File Code is utf-8                           */
/*     Copyright     :  IFRC                                         */
/* ------------------------------------------------------------------- */
/*     Comment       :  model article                             */
/* ------------------------------------------------------------------- */
/*     History       :                                               */
/* ------------------------------------------------------------------- */
/*     Version V001  :  2012.08.14 (LongNguyen)        New Create      */
/* * ****************************************************************** */

class File_daily_model extends CI_Model {
    public $table = 'vndb_day';
    function __construct() {
        parent::__construct();
        $method = $this->router->fetch_method();
        if($method == 'ref'){
            $this->table = 'vndb_reference_day';
        }
    }

    function truncate(){
        $this->db->truncate($this->table);
    }

    function addData($data){
        $columns = $this->getHeader();
        foreach($data as $key => $item){
            foreach($item as $k => $value){
                if(!in_array($k, $columns)){
                    unset($data[$key][$k]);
                }
            }
        }
        $this->db->insert_batch($this->table, $data);
        return $data;
    }

    function getHeader(){
        $columns = $this->db->list_fields($this->table);
        if($this->table == 'vndb_day'){
            array_shift($columns);
        }
        foreach($columns as $key => $value){
            if($value == 'id'){
                unset($columns[$key]);
            }
        }
        return $columns;
    }

    function find($date){
        $columns = $this->getHeader();
        $this->db->select(implode(',', $columns));
        $this->db->where('date', $date);
        $this->db->order_by('ticker', 'asc');
        return $this->db->get($this->table)->result_array();
    }

    function updateTable(){
        if($this->table == 'vndb_day'){
            $arr = array('shli', 'shou', 'shfn', 'pflr', 'popn', 'phgh', 'pref', 'pcei', 'plow', 'pbase', 'pavg', 'pcls', 'vlm', 'trn');
            $this->db->select('market, ticker, date, yyyymmdd');
        }else{
            $arr = array('ipo_shli', 'ipo_shou', 'ftrd_cls', 'shli', 'shou', 'shfn', 'capi', 'capi_fora', 'capi_forn', 'capi_stat');
            $this->db->select('source, market, ticker, date, yyyymmdd, name, ipo, ftrd');
        }
        foreach($arr as $item){
            $this->db->select_max($item);
        }
        $this->db->group_by('ticker, date');
        $rows = $this->db->get($this->table)->result_array();
        $this->db->truncate($this->table);
        if(!empty($rows)){
            pre($rows);
            $this->db->insert_batch($this->table, $rows);
            $sql = "UPDATE ". $this->table . " A, vndb_reference_day_hnxupc SET A.shou = vndb_reference_day_hnxupc.shou, A.shli = vndb_reference_day_hnxupc.shli WHERE A.ticker = vndb_reference_day_hnxupc.ticker AND A.date = vndb_reference_day_hnxupc.date AND A.market = vndb_reference_day_hnxupc.market";
            $this->db->query($sql);
            $sql = "UPDATE ". $this->table . " A, vndb_reference_day SET A.shou = vndb_reference_day.shou, A.shli = vndb_reference_day.shli WHERE A.ticker = vndb_reference_day.ticker AND A.date = vndb_reference_day.date AND A.market = vndb_reference_day.market";
            $this->db->query($sql);
            if($this->table == 'vndb_day'){
                $sql = "UPDATE ". $this->table . " SET last=IF(pcls <> 0, pcls, pref);";                
                $this->db->query($sql);
            }
        }
    }
}