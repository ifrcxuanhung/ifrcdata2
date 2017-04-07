<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/* * ******************************************************************************************************************* *
 *   Author: Minh Đẹp Trai                                                                                               *
 * * ******************************************************************************************************************* */

class Performance extends Admin {

    public function __construct() {
        parent::__construct();
        $this->load->model('Performance_model','Mper');
    }

    public function update_month_year() {
        $this->template->write_view('content', 'performance/update_month_year', $this->data);
        $this->template->write('title', 'Update Performance Month Year');
        $this->template->render();
    }

    public function chart() {
        $this->data->data_code = $this->Mper->get_code();
        $this->data->data_currency = $this->Mper->get_currency();
        $this->data->data_type = $this->Mper->get_type();
        $this->template->write_view('content', 'performance/chart', $this->data);
        $this->template->write('title', 'Chart');
        $this->template->render();
    }    

    public function process_update_month_year(){
        if ($this->input->is_ajax_request()) {
            $from = microtime(true);
            $this->Mper->get_table_daily($this->input->post('data_table'));
            $total = microtime(true) - $from;
            $result[0]['time'] = round($total, 2);
            $result[0]['task'] = 'Update Month Year';
            echo json_encode($result);
        }
    }

    public function process_check_table_exists(){
        if ($this->input->is_ajax_request()) {
            $check['table'] = array(
                'database' => array(
                    'local' => 'ims_production',
                    'ifrcims.com' => 'admin_ims'
                ),
                'table' => array(
                    'q_index_vnx_daily',
                    'idx_stats_histoday'
                ),
            );
            $final['check_table'] = $this->Mper->check_table_exists($check['table']);
            echo json_encode($final);
        }
    }

    public function get_table(){
        if ($this->input->is_ajax_request()) {
            $table = $this->input->post('table');
            $code_mother = $this->input->post('code_mother');
            $currency = $this->input->post('currency');
            $type = $this->input->post('type');
            $output = $this->Mper->listTable($table,$code_mother,$currency,$type);
            $this->output->set_output(json_encode($output));
        }
    }

     public function get_chart(){
        if ($this->input->is_ajax_request()) {
            $table = $this->input->post('table');
            $code_mother = $this->input->post('code_mother');
            $currency = $this->input->post('currency');
            $type = $this->input->post('type');
            $data_result = $this->db->query("SELECT date, close
                    FROM {$table} WHERE idx_mother = '{$code_mother}' AND idx_curr = '{$currency}' AND SUBSTRING(idx_code FROM -5 FOR 2) = '{$type}'")->result_array();
            $data_final = array();
            foreach($data_result as $dr_k => $dr_v){
                list($year,$month,$day) = explode('-',$dr_v['date']);
                $dr_v['date'] = strtotime("$year-$month-$day") * 1000;
                $data_final[$dr_k][] = $dr_v['date'];
                $data_final[$dr_k][] = $dr_v['close'] *1;
            }
            $this->output->set_output(json_encode($data_final));
        }
    }

}
