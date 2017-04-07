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

class Download_model extends CI_Model {
    protected $_table = 'vndb_download';
    function __construct() {
        parent::__construct();
    }

    function findAll($column = ''){
        if($column != ''){
            $this->db->select($column);
            $this->db->distinct();
        }
        return $this->db->get($this->_table)->result_array();
    }

    function listInfo($where = ''){
        if(is_array($where)){
            $this->db->where($where);            
        }
        $output = $this->db->get($this->_table)->result_array();
        //print_r($output); exit();
        return $output;
    }

    function findCodeByMarket($market = ''){
        if($market != '' && $market != 'ALL'){
            $this->db->where('market', $market);
        }
        $this->db->select('code');
        $rows = $this->db->get('vndb_company')->result_array();
        foreach($rows as $row){
            $data[] = $row['code'];
        }
        return $data;
    }

    function getTickerId(){
        $table = 'code_stoxplus';
        $rows = $this->db->get($table)->result_array();
        foreach($rows as $row){
            $data[$row['ticker']] = $row['code_stoxplus'];
        }
        return $data;
    }

    function getMarket($ticker){
        $table = 'vndb_company';
        $this->db->select('market');
        $this->db->where('code', $ticker);
        $rows = $this->db->get($table)->result_array();
        echo $ticker . '<br />';
        return $rows[0]['market'];

    }

    function update_exc($table, $data, $where = array()){
        // $table = 'vndb_stats_exc_day';
        // $where = array(
        //     'market' => $data['market'],
        //     'date' => $data['date']
        // );
        $this->db->where($where);
        if($this->db->count_all_results($table) != 0){
            foreach($where as $key=>$item){
                unset($data[$key]);
            }
            $this->db->where($where);
            $this->db->update($table, $data);
        }else{
            $this->db->insert($table, $data);
        }
    }

    function order_table($table, array $orders){
        $columns = $this->db->list_fields($table);
        foreach($columns as $k => $v){
            if($v == 'id'){
                unset($columns[$k]);
                break;
            }
        }
        $columns = '`' . implode('`,`', $columns) . '`';
        foreach($orders as $key => $item){
            $order[] = '`' . $key . '` ' . $item;
        }
        $order = implode(',', $order);
        $sql = "DROP TABLE IF EXISTS `temp_order`;
                CREATE TABLE `temp_order` (SELECT * FROM {$table} ORDER BY {$order})";
        $sql = explode(';', $sql);
        foreach($sql as $item){
            if(!$this->db->query(trim($item))){
                return FALSE;
            }
        }
        $sql = "TRUNCATE TABLE {$table};
                INSERT INTO {$table}({$columns}) (SELECT {$columns} FROM temp_order ORDER BY {$order})";
        $sql = explode(';', $sql);
        foreach($sql as $item){
            if(!$this->db->query(trim($item))){
                return FALSE;
            }
        }
        return TRUE;
    }
}