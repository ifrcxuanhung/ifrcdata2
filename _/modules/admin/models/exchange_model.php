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

class Exchange_model extends CI_Model {
    protected $_code_dwl;
    function __construct() {
        parent::__construct();
    }

    function getCodeInfo($code, $code_src){
        $data = '';
        $this->_code_dwl = $code_src . 'PR' . $code . 'HH';
        $this->db->where('code_dwl', $this->_code_dwl);
        $row = $this->db->get('vndb_download')->result_array();
        if(!empty($row)){
            $data = $row[0];
        }
        return $data;
    }

    function getCodeInfoByCodeDwl($code_dwl){
        if($code_dwl != ''){
            $this->_code_dwl = $code_dwl;
            $this->db->where('code_dwl', $code_dwl);
            $row = $this->db->get('vndb_download')->result_array();
            return $row[0];
        }
    }

    function getTicker($market = ''){
        if($market != '' && strtolower($market) != 'all'){
            $this->db->where('market', $market);
        }
        $this->db->group_by('code');
        // $this->db->limit(2, 0);
        $row = $this->db->get('vndb_company')->result_array();
        return $row;
    }

    function getTicker2($market = ''){
        $this->db->select('ticker');
        if($market != ''){
            $this->db->where('market', $market);
        }
        // $this->db->limit(10, 0);
        $row = $this->db->get('vndb_reference_day')->result_array();
        return $row;
    }

    function getOption($code_dwl = ''){
        if($code_dwl != ''){
            $this->db->where('code_dwl', $code_dwl);
        }else{
            $this->db->where('code_dwl', $this->_code_dwl);
        }
        $row = $this->db->get('vndb_body')->result_array();
        if(!empty($row)){
            foreach($row as $item){
                $options[$item['column']][] = array(
                    'datafield' => $item['datafield'], 
                    'mult' => $item['mult'],
                    'type' => $item['type'],
                    'left' => $item['left'],
                    'right' => $item['right'],
                    'occurrence' => $item['occurrence']
                );
            }
        }else{
            $options = false;
        }
        return $options;
    }
    function getMetaFormat($type = 'PRICE'){
        $this->db->where('output', $type);
        $this->db->order_by('order', 'asc');
        $row = $this->db->get('dms_metafield')->result_array();
        if(!empty($row)){
            foreach($row as $item){
                $data[$item['field']] = '';
            }
        }else{
            $data = false;
        }
        return $data;
    }

    function delByDate($date){
        $this->db->where('yyyymmdd', $date);
        $this->db->delete('dms_dowmoad_prices');
    }
    function addData($data){
        $this->db->insert_batch('dms_dowmoad_prices', $data);
    }
}