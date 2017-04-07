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

class Stk_page extends Admin{
	public function __construct(){
		parent::__construct();
		$this->load->Model('stk_page_model', 'mstk_page');
	}
	function index() {
		$stk_code = $this->uri->segment(4);
		$this->load->model('sysformat_model', 'msys');
		$row = $this->msys->findSysformatByTable('idx_composition', 'decimals');
		foreach($row as $item){
			$decimal[$item['fields']] = $item['decimals'];
		}
		$data = $this->mstk_page->getStk($stk_code);
		$this->data->decimal = $decimal;
		$this->data->ref = $data['stk_ref'];
		$this->data->ca = $data['idx_ca'];
		$this->data->composition = $data['idx_composition'];
        $this->data->title = 'List user group';
        $this->template->write('title', 'User group');
		
        $this->template->write_view('content', 'stk_page/stk_page_index', $this->data);    
        $this->template->render();
    }
}