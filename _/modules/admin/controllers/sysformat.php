<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/* * ****************************************************************** */
/*     Client  Name  :  IFRC                                      */
/*     Project Name  :  cms v3.0                                     */
/*     Program Name  :  sysformat.php                                      */
/*     Entry Server  :                                               */
/*     Called By     :  System                                       */
/*     Notice        :  File Code is utf-8                           */
/*     Copyright     :  IFRC                                         */
/* ------------------------------------------------------------------- */
/*     Comment       :  controller sys format                             */
/* ------------------------------------------------------------------- */
/*     History       :                                               */
/* ------------------------------------------------------------------- */
/*     Version V001  :  2012.08.14 (Tung)        New Create      */
/* * ****************************************************************** */

class Sysformat extends Admin {

    public $table;
    public $pages;

    public function __construct() {
        parent::__construct();
        $this->load->Model('sysformat_model', 'msys');
        $this->table = $this->input->get('table');
        if ($this->table == '') {
            $this->table = 'sys_format';
        }
        $this->pages = $this->uri->segment(2);
    }

    public function index() {
        if ($this->input->is_ajax_request()) {
            $table = $this->input->get('table');
            $page = $this->input->get('page'); // get the requested page
            $limit = $this->input->get('rows'); // get how many rows we want to have into the grid
            $sidx = $this->input->get('sidx'); // get index row - i.e. user click to sort
            $sord = $this->input->get('sord'); // get the direction
            //$col = array('dates', 'times', 'idx_code', 'idx_name', 'idx_curr', 'idx_base', 'idx_type', 'idx_mother', 'idx_divisor' , 'idx_mcap', 'idx_dcap', 'idx_last', 'ip_plast', 'idx_var');
            $where = '';
            $current = $this->input->get('current');
            //unset($this->input->get('current'));
            $order_start = $current * 9 + 1;
            $response = $this->msys->getTableData($table, $page, $limit, $sidx, $sord, '', $where, $order_start);
            echo json_encode($response);
        } else {
            echo $this->input->get('type');
            $this->template->write_view('content', 'sysformat/sysformat_list', $this->data);
            $this->template->write('title', 'View');
            $this->template->render();
        }
    }

    public function upload_view() {

    }

    public function show_view() {
        $this->data->content = $this->msys->find_view();
        $this->template->write_view('content', 'sysformat/view_list', $this->data);
        $this->template->write('title', 'View');
        $this->template->render();
    }

    public function add() {
        $this->template->write_view('content', 'sysformat/sysformat_add', $this->data);
        $this->template->write('title', 'Sysformat');
    }

    public function edit() {
        if ($this->input->is_ajax_request()) {
            unset($_POST['oper']);
            $this->msys->update($this->table, $this->input->post('id'), $this->input->post());
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

    public function active() {
        if ($this->input->is_ajax_request()) {
            $status = $this->input->get('status');
            $id = $this->input->get('id');
            $data['active'] = $status;
            if ($this->msys->update('idx_view', $id, $data)) {
                echo "Success";
            }
        }
    }

    public function getConfig(){
        if($this->input->is_ajax_request()){
            $table = $this->input->get('table');
            $response = $this->msys->load_format($table, 'headers');
            echo json_encode($response);

        }
    }

    protected function _getTableData() {
        //khai bao table
        $page = $this->input->get('page'); // get the requested page
        $limit = $this->input->get('rows'); // get how many rows we want to have into the grid
        $sidx = $this->input->get('sidx'); // get index row - i.e. user click to sort
        $sord = $this->input->get('sord'); // get the direction
        $response = $this->msys->getTableData($this->table, $page, $limit, $sidx, $sord);
        echo json_encode($response);
    }

    public function export() {
        if ($this->input->is_ajax_request()) {
            $path = APPPATH . '../../assets/download/views/';
            $table = $this->input->get('table');
            $filename = $table . '.txt';
            $path_file = $path . $filename;
            $id = '';
            if($this->input->get('id')){
                $id = $this->input->get('id');
            }
            $data = $this->msys->listView($table, $id);
            file_put_contents($path_file, $data);
            $response['file'] = $filename;
            $response['path'] = $path_file;
            echo json_encode($response);
        }
    }

    public function download_file() {
        $file = $this->input->get('file');
        $path = $this->input->get('path');
        header("Cache-Control: public");
        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=$file");
        header("Content-Type: text/plain");
        header("Content-Transfer-Encoding: binary");
        //header("location:http://google.com.vn");
        readfile($path);
    }

    public function test() {
        $file = 'idx_calendar.txt';
        $path = 'E:\\xampp\\htdocs\\ims\\assets\\download\\views\\' . $file;
        header("Cache-Control: public");
        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=$file");
        header("Content-Type: text/plain");
        header("Content-Transfer-Encoding: binary");
        readfile($path);
    }

}