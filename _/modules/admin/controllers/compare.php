<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/* * ******************************************************************************************************************* *
 * 	 Author: Minh Rất Đẹp Trai Hehe 																					 *
 * 	 Description: Controller để test tất cả action			 															 *
 * * ******************************************************************************************************************* */

class Compare extends Admin {

    public function __construct() {
        parent::__construct();
        set_time_limit(0);
        $this->load->model('Compare_model', 'compare_model');
    }

    public function index(){
        if($this->input->is_ajax_request()){
            $output = $this->compare_model->data_compare();
            $this->output->set_output(json_encode($output));
        }else{
            $this->template->write_view('content', 'compare/index', $this->data);
            $this->template->write('title', 'Compare');
            $this->template->render();
        }
    }
}