<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/* * ******************************************************************************************************************* *
 * 	 																													 *
 *																														 *
 *																														 *
 *													Author: Minh Đẹp Trai			 									 *
 *																														 *
 *																														 *
 *																														 *
 * * ******************************************************************************************************************* */
class Statistics_daily extends Admin {
    public function __construct() {
        parent::__construct();
    }
	public function index() {
        $this->template->write_view('content', 'statistics_daily/index', $this->data);
        $this->template->write('title', 'Statistics Daily');
        $this->template->render();
    }

    public function list_title(){
    	if($this->input->is_ajax_request()){
    		$table = $this->input->post('table');
    		$column = $this->db->list_fields($table);
    		foreach($column as $item){
    			$response[] = array(
	    			"sTitle" => strtoupper($item),
	                "mData" => strtolower($item),
	                "sType" => "number",
	                "swidth" => "10%"	
                );
    		}
    		
    		$this->output->set_output(json_encode($response));
    	}
    }

    public function view_table(){
    	if($this->input->is_ajax_request()){
    		$table = $this->input->post('table');
    		if($table == 'vndb_stats_daily'){
    			$order = 'yyyymmdd';
    		}
    		if($table == 'vndb_stats_monthly'){
    			$order = 'yyyymm';
    		}
    		if($table == 'vndb_stats_yearly'){
    			$order = 'yyyy';
    		}
    		$this->db->order_by($order, 'DESC');
    		$data = $this->db->get($table)->result_array();
    		foreach($data as $key => $item){
    			foreach($item as $value){
    				$aaData[$key][] = $value;
    			}
    		}
    		$response = array(
                'aaData' => $aaData
            );
            echo json_encode($response);
    	}
    }
}