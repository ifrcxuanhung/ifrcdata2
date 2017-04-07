<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/* * *******************************************************************************************************************
 * Client  Name ：  IFRC
 * ---------------------------------------------------------------------------------------------------------------------
 * Project Name ：  IMS v3.0
 * ---------------------------------------------------------------------------------------------------------------------
 * Program Name ：  update_return.php
 * ---------------------------------------------------------------------------------------------------------------------
 * Entry Server ：
 * ---------------------------------------------------------------------------------------------------------------------
 * Called By    ：  System
 * ---------------------------------------------------------------------------------------------------------------------
 * Notice       ：
 * ---------------------------------------------------------------------------------------------------------------------
 * Copyright    ：  IFRC
 * ---------------------------------------------------------------------------------------------------------------------
 * Comment      ：
 * ---------------------------------------------------------------------------------------------------------------------
 * History      ：
 * ---------------------------------------------------------------------------------------------------------------------
 * Version V001 ：  2012.12.14 (LongNguyen)        New Create
 * ******************************************************************************************************************* */

class Update_return extends Admin {

    protected $data;

    public function __construct() {
        parent::__construct();
    }

    /*     * ***********************************************************************************************************
     * Name         ： index
     * -----------------------------------------------------------------------------------------------------------------
     * Description  ：
     * -----------------------------------------------------------------------------------------------------------------
     * Params       ：
     * -----------------------------------------------------------------------------------------------------------------
     * Return       ：
     * -----------------------------------------------------------------------------------------------------------------
     * Warning      ：
     * -----------------------------------------------------------------------------------------------------------------
     * Copyright    ： IFRC
     * -----------------------------------------------------------------------------------------------------------------
     * M001         ： New  2012.12.14 (LongNguyen)
     * *************************************************************************************************************** */

    public function index() {
        if ($this->input->is_ajax_request()) {
            $return = array();
            $time_start = microtime(true);
            $this->load->model('UpdateReturn_model', 'update_return_model');
            $return['all-time'] = $this->update_return_model->update();
            $time_end = microtime(true);
            $return['time'] = number_format(($time_end - $time_start), 3);
            echo json_encode($return);
            return TRUE;
        }
    }

    public function insert_data() {
        if ($this->input->is_ajax_request()) {
            $return = array();
            $time_start = microtime(true);
            $this->load->model('UpdateReturn_model', 'update_return_model');
            $return['all-time'] = $this->update_return_model->insert_data();
            $time_end = microtime(true);
            $return['time'] = number_format(($time_end - $time_start), 3);
            echo json_encode($return);
            return TRUE;
        }
    }

    public function clear_data() {
        if ($this->input->is_ajax_request()) {
            $return = array();
            $time_start = microtime(true);
            $this->load->model('UpdateReturn_model', 'update_return_model');
            $return['all-time'] = $this->update_return_model->clear_data();
            $time_end = microtime(true);
            $return['time'] = number_format(($time_end - $time_start), 3);
            echo json_encode($return);
            return TRUE;
        }
    }

    public function calculate_return() {
        if ($this->input->is_ajax_request()) {
            $return = array();
            $time_start = microtime(true);
            $this->load->model('UpdateReturn_model', 'update_return_model');
            $return['all-time'] = $this->update_return_model->calculate_return();
            $time_end = microtime(true);
            $return['time'] = number_format(($time_end - $time_start), 3);
            echo json_encode($return);
            return TRUE;
        }
    }

    public function adjusted_price() {
        if ($this->input->is_ajax_request()) {
            $return = array();
            $time_start = microtime(true);
            $this->load->model('UpdateReturn_model', 'update_return_model');
            $return['all-time'] = $this->update_return_model->adjusted_price();
            $time_end = microtime(true);
            $return['time'] = number_format(($time_end - $time_start), 3);
            echo json_encode($return);
            return TRUE;
        }
    }

}