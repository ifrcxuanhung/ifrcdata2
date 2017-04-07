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
/*     Version V001  :  2012.09.28 (Tung)        New Create      		*/
/* * ****************************************************************** */

class Idx_page extends Admin{
	public function __construct(){
		parent::__construct();
		$this->load->Model('idx_page_model', 'midx_page');
	}
	public function index() {
		$idx_code = $this->uri->segment(4);
		$this->load->model('sysformat_model', 'msys');
		$row = $this->msys->findSysformatByTable('idx_composition', 'decimals');
		foreach($row as $item){
			$decimal[$item['fields']] = $item['decimals'];
		}
		$data = $this->midx_page->getIdx($idx_code);
		$this->data->decimal = $decimal;
		$this->data->ref = $data['idx_ref'];
		$this->data->ca = $data['idx_ca'];
		$this->data->composition = $data['idx_composition'];
		$this->data->specs = $data['idx_specs'];
		$this->data->const = count($data['idx_composition']);
		$this->data->linked = $this->midx_page->showLinkedIndex($idx_code);
        $this->data->title = 'Index Page';
        $this->template->write('title', 'Index Page');
		
        $this->template->write_view('content', 'idx_page/idx_page_index', $this->data);    
        $this->template->render();
    }

    public function showSpecs(){
    	if($this->input->is_ajax_request()){
	    	$code = $this->input->post('code');
	    	$response = $this->midx_page->findSpecs($code);
	    	echo json_encode($response);
	    }
    }
}