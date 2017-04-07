<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Maintenance extends Admin {

    protected $data;

    function __construct() {
        parent::__construct();
        $this->load->model("maintenance_model","model_mainte");
    }

    public function index(){
        $date = '';
        $type = '';
        if(isset($_GET['date'])){
            $date = $_GET['date'];
        }
        if(isset($_GET['type'])){
            $type = $_GET['type'];
        }
        $changes = $this->model_mainte->listChanges($date,$type);
        $dividend = $this->model_mainte->listDividend($date,$type);
        $ca = $this->model_mainte->listCa($date,$type);

        /* ************************************************************ */

        $number_changes = count($changes);
        if($number_changes == 0){
             $number_changes = '';
        }
        $number_dividend = count($dividend);
        if($number_dividend == 0){
             $number_dividend = '';
        }
        $number_ca = count($ca);
        if($number_ca == 0){
             $number_ca = '';
        }

        /* ************************************************************ */       

        $this->data->changes = $changes;
        $this->data->dividend = $dividend;
        $this->data->ca = $ca;

        $this->data->number_changes = $number_changes;
        $this->data->number_dividend = $number_dividend;
        $this->data->number_ca = $number_ca;        
        $this->template->write_view('content', 'maintenance/index', $this->data);
        $this->template->write('title', 'Maintenance');
        $this->template->render();
    }
    // public function listChanges(){
    //     if($this->input->is_ajax_request()){
    //         $output = $this->model_mainte->listChanges();
    //         $this->output->set_output(json_encode($output));
    //     }
    // }
    // public function listDividend(){
    //     if($this->input->is_ajax_request()){
    //         $output = $this->model_mainte->listDividend();
    //         $this->output->set_output(json_encode($output));
    //     }
    // }
    // public function listCa(){
    //     if($this->input->is_ajax_request()){
    //         $output = $this->model_mainte->listCa();
    //         $this->output->set_output(json_encode($output));
    //     }
    // }
}
