<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/* * ****************************************************************** */
/*     Client  Name  :  IFRC                                      */
/*     Project Name  :  cms v3.0                                     */
/*     Program Name  :  calendar_page_model.php               	      */
/*     Entry Server  :                                               */
/*     Called By     :  System                                       */
/*     Notice        :  File Code is utf-8                           */
/*     Copyright     :  IFRC                                         */
/* ------------------------------------------------------------------- */
/*     Comment       :  model calendar_page                             */
/* ------------------------------------------------------------------- */
/*     History       :                                               */
/* ------------------------------------------------------------------- */
/*     Version V001  :  2012.09.28 (Tung)        New Create      */
/* * ****************************************************************** */


class Calendar_page_model extends CI_Model {
	public $table = 'idx_calendar';
	public function __construct(){
		parent::__construct();
	}

	public function listCurrDate(){
		$this->db->select('currdate');
		$query = $this->db->get($this->table);
		$row = $query->result_array();

		foreach($row as $item){
			$date[] = date('j-m-Y', strtotime($item['currdate']));
		}
		return $date;
	}

	public function findEventByDate($month, $year){
		$this->db->from('idx_ca');
		$this->db->where('MONTH(dates)', $month);
		$this->db->where('YEAR(dates)', $year);
		return $this->db->get()->result_array();
	}
}