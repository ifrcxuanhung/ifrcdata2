<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/* * ****************************************************************** */
/*     Client  Name  :  IFRC                                      */
/*     Project Name  :  cms v3.0                                     */
/*     Program Name  :  home.php                                      */
/*     Entry Server  :                                               */
/*     Called By     :  System                                       */
/*     Notice        :  File Code is utf-8                           */
/*     Copyright     :  IFRC                                         */
/* ------------------------------------------------------------------- */
/*     Comment       :  controller home                             */
/* ------------------------------------------------------------------- */
/*     History       :                                               */
/* ------------------------------------------------------------------- */
/*     Version V001  :  2012.08.14 (Tung)        New Create      		*/
/* * ****************************************************************** */

class Home extends Admin{
	public function __construct(){
		parent::__construct();
		$this->load->Model('sysformat_model', 'msys');
	}

	public function index(){
	redirect(admin_url() . 'sysformat/show_view');
		$this->template->write_view('content', 'home/home_list', $this->data);
        $this->template->write('title', 'Home');
        $this->template->render();
	}



	public function sdflaskjflasf(){
		if(!$this->input->is_ajax_request()){
			$this->data->info = 123;
			$this->template->write_view('content', 'home/home_list', $this->data);
	        $this->template->write('title', 'Home');
	        $this->template->render();
    	}else{
    		$table = $this->input->get('table');
    		$code = $this->input->get('code');
    		$page = $this->input->get('page'); // get the requested page
			$limit = $this->input->get('rows'); // get how many rows we want to have into the grid
			$sidx = $this->input->get('sidx'); // get index row - i.e. user click to sort
			$sord = $this->input->get('sord'); // get the direction
			//$col = array('dates', 'times', 'idx_code', 'idx_name', 'idx_curr', 'idx_base', 'idx_type', 'idx_mother', 'idx_divisor' , 'idx_mcap', 'idx_dcap', 'idx_last', 'ip_plast', 'idx_var');
			$where = '';
			$current = $this->input->get('current');
			//unset($this->input->get('current'));
			$order_start = $current * 9 + 1;
			if($code != ''){
				$where = "idx_code = '$code'";
			}
			//$response = $this->msys->getTableData($table, $page, $limit, $sidx, $sord, '', $where, $order_start);
			$response = $this->msys->getTableData($table, $page, $limit, $sidx, $sord, '', $where);
			echo json_encode($response);
    	}
	}
	/*public function getComposition(){
		$table = $this->input->get('table');
		$code = $this->input->get('code');
		$page = $this->input->get('page'); // get the requested page
		$limit = $this->input->get('rows'); // get how many rows we want to have into the grid
		$sidx = $this->input->get('sidx'); // get index row - i.e. user click to sort
		$sord = $this->input->get('sord'); // get the direction
		$col = array('dates', 'times', 'stk_code', 'stk_name', 'idx_code', 'stk_shares_idx', 'stk_float_idx', 'stk_capp_idx', 'stk_price', 'stk_curr', 'stk_mult', 'stk_mcap_idx', 'stk_wgt', 'stk_dcap_idx', 'to_adjust');
		$response = $this->msys->getTableData($table, $page, $limit, $sidx, $sord, $col, "idx_code = '$code'");
		echo json_encode($response);
	}*/

	public function getConfig(){
		if($this->input->is_ajax_request()){
			$table = $this->input->get('table');
			$response = $this->msys->load_format($table, 'headers');
			echo json_encode($response);

		}
	}
	
	public function updateWidth(){
        if($this->input->is_ajax_request()){
            $header = $this->input->get('header');
            $table = $this->input->get('table');
            $data['widths'] = $this->input->get('width');
            $this->msys->updateWidth($table, $header, $data);
        }
    }

    public function getStk(){
    	if($this->input->is_ajax_request()){
    		$table = $this->input->get('table');
    		$idx_code = $this->input->get('idx_code');
    		$stk_code = $this->input->get('stk_code');
			$response = $this->msys->getStk($table, $idx_code, $stk_code);
	    	echo json_encode($response);
    	}
    }

	protected function _getTableData(){
		//khai bao table
		$page = $this->input->get('page'); // get the requested page
		$limit = $this->input->get('rows'); // get how many rows we want to have into the grid
		$sidx = $this->input->get('sidx'); // get index row - i.e. user click to sort
		$sord = $this->input->get('sord'); // get the direction
		$response = $this->msys->getTableData($this->table, $page, $limit, $sidx, $sord);
		echo json_encode($response);
	}
}