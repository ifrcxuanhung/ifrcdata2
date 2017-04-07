<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/* * ****************************************************************** */
/*     Client  Name  :  IFRC                                      */
/*     Project Name  :  cms v3.0                                     */
/*     Program Name  :  idx_page_model.php                                      */
/*     Entry Server  :                                               */
/*     Called By     :  System                                       */
/*     Notice        :  File Code is utf-8                           */
/*     Copyright     :  IFRC                                         */
/* ------------------------------------------------------------------- */
/*     Comment       :  model idx_page                             */
/* ------------------------------------------------------------------- */
/*     History       :                                               */
/* ------------------------------------------------------------------- */
/*     Version V001  :  2012.09.28 (Tung)        New Create      */
/* * ****************************************************************** */

class Idx_histoday_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function getIdx($idx_code) {
        $tables = array('idx_histoday', 'idx_intraday');
        foreach ($tables as $table) {
            if ($table == 'idx_histoday') {
                $this->db->select('h1.idx_code, h1.last, h1.changes, h1.var, h1.pclose, h1.dvar, h1.idx_mother, h1.times');
                $this->db->from('idx_histoday h1');
                $this->db->join('idx_histoday h2', 'h2.idx_code = h1.idx_mother');
                $this->db->where('h1.idx_code', $idx_code);
            } else {
                $this->db->from($table);
                $this->db->where('idx_code', $data['idx_histoday'][0]['idx_mother']);
            }
            $query = $this->db->get();
            $info = $query->result_array();
            if (count($info) != 0) {
                $data[$table] = $info;
            } else {
                $data[$table] = '';
            }
        }
        return $data;
    }

}