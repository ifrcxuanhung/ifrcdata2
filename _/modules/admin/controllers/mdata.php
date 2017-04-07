<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/* * ******************************************************************************************************************* *
 *   Author: Minh Đẹp Trai                                                                                               *
 * * ******************************************************************************************************************* */

class Mdata extends Admin {

    public function __construct() {
        parent::__construct();
        $this->load->model('Mdata_model', 'mdata_model');
        set_time_limit(0);
    }
    
    public function all(){
        $this->template->write_view('content', 'mdata/all', $this->data);
        $this->template->write('title', 'Mdata All');
        $this->template->render(); 
    }
    
    public function price_history(){
        $this->template->write_view('content', 'mdata/price_history', $this->data);
        $this->template->write('title', 'Price History');
        $this->template->render(); 
    }
    
    public function update_dividend(){
        $this->template->write_view('content', 'mdata/update_dividend', $this->data);
        $this->template->write('title', 'Update Dividend');
        $this->template->render(); 
    }
    
    public function create_qidx(){
        $this->template->write_view('content', 'mdata/create_qidx', $this->data);
        $this->template->write('title', 'Create Qidx Mdata');
        $this->template->render();
    }
    
    public function create(){
        $this->template->write_view('content', 'mdata/create', $this->data);
        $this->template->write('title', 'Create Mdata');
        $this->template->render();
    }
    
    public function calculation(){
        $this->template->write_view('content', 'mdata/calculation', $this->data);
        $this->template->write('title', 'Calculation Mdata');
        $this->template->render();
    }
    
    public function update(){
        $this->template->write_view('content', 'mdata/update', $this->data);
        $this->template->write('title', 'Update Mdata');
        $this->template->render();
    }
    
    public function update_event(){
        $this->template->write_view('content', 'mdata/update_event', $this->data);
        $this->template->write('title', 'Update Event');
        $this->template->render();
    }
    
    public function process_all(){
         if ($this->input->is_ajax_request()) {
            $from = microtime(true);
            $this->mdata_model->create();
            $this->mdata_model->calculation();
            $this->mdata_model->update();
            $total = microtime(true) - $from;
            $result[0]['time'] = round($total, 2);
            $result[0]['task'] = 'Mdata All';
            echo json_encode($result);
         }
    }
    
    public function process_get_setting(){
        if ($this->input->is_ajax_request()) {
            $setting = $this->mdata_model->get_setting();
            echo json_encode($setting);
         } 
    }
    
    public function process_price_history(){
        if ($this->input->is_ajax_request()) {
            $from = microtime(true);
            $this->mdata_model->update_date_diff();
            $total = microtime(true) - $from;
            $result[0]['time'] = round($total, 2);
            $result[0]['task'] = 'Price History';
            echo json_encode($result);
         } 
    }
    
    public function process_create_qidx(){
	if ($this->input->is_ajax_request()) {
            $from = microtime(true);
            $path = '//LOCAL/IFRCVN/VNDB/ADJCLS/';
            $file_name = 'QIDX_MDATA.txt';
            $this->mdata_model->export_qidx($path, $file_name);
            $total = microtime(true) - $from;
            $result[0]['time'] = round($total, 2);
            $result[0]['task'] = 'Create Qidx Mdata';
            echo json_encode($result);
         }
    }
    
    public function process_create(){
         if ($this->input->is_ajax_request()) {
            $from = microtime(true);
            $this->source_create();
            $total = microtime(true) - $from;
            $result[0]['time'] = round($total, 2);
            $result[0]['task'] = 'Create Mdata';
            echo json_encode($result);
         }
    }
    
    public function process_calculation(){
         if ($this->input->is_ajax_request()) {
            $from = microtime(true);
            $this->source_calculation();
            $total = microtime(true) - $from;
            $result[0]['time'] = round($total, 2);
            $result[0]['task'] = 'Calculation Mdata';
            echo json_encode($result);
         }
    }
    
    public function process_update_dividend(){
         if ($this->input->is_ajax_request()) {
            $from = microtime(true);
            $this->mdata_model->update_dividend();
            $total = microtime(true) - $from;
            $result[0]['time'] = round($total, 2);
            $result[0]['task'] = 'Update Dividend';
            echo json_encode($result);
         }
    }
    
    public function process_update(){
         if ($this->input->is_ajax_request()) {
            $from = microtime(true);
            $this->source_update();
            $total = microtime(true) - $from;
            $result[0]['time'] = round($total, 2);
            $result[0]['task'] = 'Update Mdata';
            echo json_encode($result);
         }
    }
    
    public function process_update_event(){
         if ($this->input->is_ajax_request()) {
            $from = microtime(true);
            $this->source_update_event();
            $total = microtime(true) - $from;
            $result[0]['time'] = round($total, 2);
            $result[0]['task'] = 'Update Event';
            echo json_encode($result);
         }
    }
    
    public function source_update_event(){
        $this->mdata_model->update_event();
    }
    
    public function source_create(){
        $this->mdata_model->create();
    }
    
    public function source_calculation(){
        $this->mdata_model->calculation();
    }
    
    public function source_update(){
        $this->mdata_model->update();
        $path = '//LOCAL/IFRCVN/VNDB/ADJCLS/';
        $file_name = 'IDX_MDATA.txt';
        $new_path = '//LOCAL/IFRCVN/IMS/IMSTXT/idx_mdata.vn.txt';
        if(file_exists($path.$file_name)){
            unlink($path.$file_name);
        }
        $this->mdata_model->export($path,$file_name);
        copy($path.$file_name, $new_path);
    }
    
    public function mdata_special(){
        $this->source_update_event();
        $this->source_create();
        $this->source_calculation();
        $this->source_update();
    }
}
