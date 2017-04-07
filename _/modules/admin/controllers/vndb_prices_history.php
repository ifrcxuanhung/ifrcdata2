<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Vndb_prices_history extends Admin {

    protected $data;

    function __construct() {
        parent::__construct();

        $this->load->model('Steps_model', 'steps_model');
        $this->load->model('Vndb_prices_history_model', 'vndb_prices_history_model');
        $this->load->helper(array('my_array_helper', 'form'));
    }

    function index() {
        if ($this->input->is_ajax_request()) {
            set_time_limit(0);
            $start = microtime_float();
            $output = array();
            //$count return number of task
            $count = 0;
            //insert data into table vndb_meta_prices
            $value = $this->db->query("SELECT * FROM `setting` WHERE `setting`.`key` = 'meta_files_reset'")->row()->value;
            if (isset($value) == TRUE && $value == '1') {
                $this->steps_model->process_prices_all();
            } else {
                $sql = "UPDATE `setting`
                        SET `setting`.`value` = '1'
                        WHERE `setting`.`key` = 'meta_files_reset'";
                $this->db->query($sql);
                $this->steps_model->process_prices_all();
                $sql = "UPDATE `setting`
                        SET `setting`.`value` = '0'
                        WHERE `setting`.`key` = 'meta_files_reset'";
                $this->db->query($sql);
            }
            //end insert data into table vndb_meta_prices
            $output[$count] = array('task' => trans('step_insert_data_vndb_meta_prices', 1), 'time' => number_format(microtime_float() - $start, 2));
            //insert data into table vndb_prices_history
            $result = $this->vndb_prices_history_model->insert_data_into_table_vndb_prices_history();
            if (is_array($result) == TRUE && count($result) > 0) {
                foreach ($result as $key => $value) {
                    $output[++$count] = $result[$key];
                }
            }
            //end insert data into table vndb_prices_history
            $output[++$count] = array('task' => trans('step_insert_data_vndb_prices_history', 1), 'time' => number_format(microtime_float() - $start, 2));
            $this->output->set_output(json_encode($output));
        }
    }

    function qidx_mdata() {
        if ($this->input->is_ajax_request()) {
            set_time_limit(0);
            $start = microtime_float();
            $output = array();
            //insert data into table qidx_mdata
            $this->vndb_prices_history_model->insert_data_into_table_qidx_mdata();
            //end insert data into table qidx_mdata
            $output[0] = array('task' => trans('step_insert_data_qidx_mdata', 1), 'time' => number_format(microtime_float() - $start, 2));
            $this->output->set_output(json_encode($output));
        }
    }

    function export_qidx_mdata_txt() {
        if ($this->input->is_ajax_request()) {
            set_time_limit(0);
            $start = microtime_float();
            $output = array();
            //export QIDX_MDATA.txt
            $this->vndb_prices_history_model->export_qidx_mdata_txt_from_table_vndb_prices_history();
            //end export QIDX_MDATA.txt
            $output[0] = array('task' => trans('step_export_data_qidx_mdata', 1), 'time' => number_format(microtime_float() - $start, 2));
            $this->output->set_output(json_encode($output));
        }
    }

    function insert_meta_prices() {
        if ($this->input->is_ajax_request()) {
            set_time_limit(0);
            $start = microtime_float();
            $output = array();
            //$count return number of task
            $count = 0;
            //insert data into table vndb_meta_prices
            $value = $this->db->query("SELECT * FROM `setting` WHERE `setting`.`key` = 'meta_files_reset'")->row()->value;
            if (isset($value) == TRUE && $value == '1') {
                $this->steps_model->process_prices_all();
            } else {
                $sql = "UPDATE `setting`
                        SET `setting`.`value` = '1'
                        WHERE `setting`.`key` = 'meta_files_reset'";
                $this->db->query($sql);
                $this->steps_model->process_prices_all();
                $sql = "UPDATE `setting`
                        SET `setting`.`value` = '0'
                        WHERE `setting`.`key` = 'meta_files_reset'";
                $this->db->query($sql);
            }
            //end insert data into table vndb_meta_prices
            $output[$count] = array('task' => trans('step_insert_data_vndb_meta_prices', 1), 'time' => number_format(microtime_float() - $start, 2));
            $this->output->set_output(json_encode($output));
        }
    }

    function update_references() {
        if ($this->input->is_ajax_request()) {
            set_time_limit(0);
            $start = microtime_float();
            $output = array();
            //$count return number of task
            $count = 0;
            //insert data into table vndb_prices_history
            $result = $this->vndb_prices_history_model->insert_data_into_table_vndb_prices_history();
            if (is_array($result) == TRUE && count($result) > 0) {
                foreach ($result as $key => $value) {
                    $output[++$count] = $result[$key];
                }
            }
            //end insert data into table vndb_prices_history
            $output[++$count] = array('task' => trans('step_insert_data_vndb_prices_history', 1), 'time' => number_format(microtime_float() - $start, 2));
            $this->output->set_output(json_encode($output));
        }
    }

}