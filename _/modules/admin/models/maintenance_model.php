<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Maintenance_model extends CI_Model {

    public $_table_1 = 'vndb_changes_day';
    public $_table_2 = 'vndb_dividends_history';
    public $_table_3 = 'vndb_ca_daily';

    function __construct() {
        parent::__construct();
    }

    public function listChanges($date,$type){
        if($date != ''){
            $this->db->where('date',$date);
        }else{
            $this->db->where('date',date("Y-m-d",time()));
        }
        if($type != ''){
            if($type == "pvn"){
                $data_ticker = $this->get_ticker();
                $this->db->where_in('ticker',$data_ticker);
            }
        }
        $this->db->order_by('date','asc');
        $this->db->order_by('ticker','asc');
        $this->db->order_by('CHANGEevt','asc');
        return $this->db->get($this->_table_1)->result_array();
    }
    public function listDividend($date,$type){
        if($date != ''){
            $filter_date = 'a.date = "'.$date.'"';
        }else{
            $current_date = date("Y-m-d",time());
            $filter_date = 'a.date = "'.$current_date.'"';
        }
        $filter_type = '';
        if($type != ''){
            if($type == "pvn"){
                $data_ticker = $this->get_ticker();
                $item_ticker = '"'.implode('","',$data_ticker).'"';
                $filter_type = ' AND a.ticker in ('.$item_ticker.')';
            }
        }

        return $this->db->query("SELECT
            a.ticker,a.market,a.date,a.dividend, if((a.ticker = b.ticker) and (a.date = b.date_ex),b.div,'') as div_news, CONCAT(CONCAT('CAF:',if(div_caf is not null,div_caf,'NULL')),', ',CONCAT('CPH:',if(div_cph is not null,div_cph,'NULL')),', ',CONCAT('STP:',if(div_stp is not null,div_stp,'NULL')),', ',CONCAT('VST:',if(div_vst is not null,div_stp,'NULL')),', ',CONCAT('FPT:',if(div_fpt is not null,div_fpt,'NULL')),', ',CONCAT('STB:',if(div_stb is not null,div_stb,'NULL'))) as compare
        FROM
            vndb_dividends_history a
            LEFT JOIN vndb_ca_daily b ON a.ticker = b.ticker AND a.date = b.date_ex
            LEFT JOIN vndb_dividends_compare c ON a.ticker = c.ticker AND a.date = c.date_ex
        WHERE
            {$filter_date}{$filter_type}")->result_array();
    }
    public function listCa($date,$type){
        $data = $this->data_ca($date,$type);
        return $data;
    }
    public function data_ca($date,$type){
        if($date != ''){
            $this->db->where('ftrd',$date);
            $this->db->or_where('date_ex',$date);
            $this->db->or_where('ipo_date',$date);
            $this->db->or_where('date_pay',$date);
        }else{
            $this->db->where('ftrd',date("Y-m-d",time()));
            $this->db->or_where('date_ex',date("Y-m-d",time()));
            $this->db->or_where('ipo_date',date("Y-m-d",time()));
            $this->db->or_where('date_pay',date("Y-m-d",time()));
        }
        if($type != ''){
            if($type == "pvn"){
                $data_ticker = $this->get_ticker();
                $this->db->where_in('ticker',$data_ticker);
            }
        }
        return $this->db->get($this->_table_3)->result_array();
    }
    public function get_ticker(){
        $data = $this->db->query("SELECT * FROM vndb_list_pvn")->result_array();
        $respone = array();
        foreach($data as $item){
            $respone[] = $item['code'];
        }
        return $respone;
    }
}