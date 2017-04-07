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
/*     Comment       :  model profile                             */
/* ------------------------------------------------------------------- */
/*     History       :                                               */
/* ------------------------------------------------------------------- */
/*     Version V001  :  2012.10.22 (Tung)        New Create      */
/* * ****************************************************************** */

class Profile_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }
    public function listTicker($id = ''){
        if(is_numeric($id)){
            $this->db->where('id', $id);
        }
        return $this->db->get('vndb_company')->result_array();
    }
    public function listDownload($src = '', $info = '', $market = ''){
        $where = array();
        if($src != '' && $src != '0'){
            $where['source'] = $src;
        }
        if($info != '' && $info != '0'){
            $where['information'] = $info;
        }
        if($market != '' && $market != '0'){
            $where['market'] = $market;
        }
        if(!empty($where)){
            $this->db->where($where);
        }
        //$this->db->order_by('source');
        return $this->db->get('vndb_download')->result_array();
    }
    public function listInfo($column){
        $this->db->select($column);
        $this->db->distinct();        
        $row = $this->db->get('vndb_download')->result_array();
        asort($row);
        return $row;
    }
    public function addResult($data, $table){
        if(!empty($data)){
            $this->db->insert_batch($table, $data);
        }
    }
    public function getResult(){
        return $this->db->get('result_view')->result_array();
    }
    public function truncate($table){
        $this->db->truncate($table);
    }

}
