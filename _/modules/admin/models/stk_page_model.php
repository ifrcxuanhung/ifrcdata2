<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/* * ****************************************************************** */
/*     Client  Name  :  IFRC                                      */
/*     Project Name  :  cms v3.0                                     */
/*     Program Name  :  stk_page_model.php                                      */
/*     Entry Server  :                                               */
/*     Called By     :  System                                       */
/*     Notice        :  File Code is utf-8                           */
/*     Copyright     :  IFRC                                         */
/* ------------------------------------------------------------------- */
/*     Comment       :  model stk_page                             */
/* ------------------------------------------------------------------- */
/*     History       :                                               */
/* ------------------------------------------------------------------- */
/*     Version V001  :  2012.09.27 (Tung)        New Create      */
/* * ****************************************************************** */


class Stk_page_model extends CI_Model {
	public function __construct(){
		parent::__construct();
	}
	public function getStk($stk_code){
		$tables = array('stk_ref', 'idx_ca', 'idx_composition');
		foreach($tables as $table){
			$this->db->from($table);
			if($table == 'stk_ref'){
				$this->db->join('sec_ref', 'sec_code = stk_sector', 'left');
				$this->db->join('mar_ref', 'mar_code = stk_market', 'left');
			}
			$this->db->where('stk_code', $stk_code);
			$query = $this->db->get();
			$info = $query->result_array();
			if(count($info) != 0){
				$data[$table] = $info;
			}else{
				$data[$table] = '';
			}
		}
		return $data;
	}
}