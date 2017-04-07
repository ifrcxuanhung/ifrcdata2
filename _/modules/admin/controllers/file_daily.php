<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/* * *******************************************************************************************************************
 * Client  Name ：  IFRC
 * ---------------------------------------------------------------------------------------------------------------------
 * Project Name ：  IMS v3.0
 * ---------------------------------------------------------------------------------------------------------------------
 * Program Name ：  ref.php
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
 * Version V001 ：  2012.09.20 (Tung)        New Create
 * ******************************************************************************************************************* */

class File_daily extends Admin {

    protected $data;
    private $cal_dates;
    protected $_option;

    public function __construct() {
        parent::__construct();
        set_time_limit(0);
        $this->load->library('curl');
        $this->load->library('simple_html_dom');
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
     * M001         ： New  2012.10.16 (Tung)
     * *************************************************************************************************************** */


    public function index(){
        if($this->input->is_ajax_request()){
            $this->load->Model('file_daily_model', 'mfile');
            $this->mfile->truncate();
            $dir = '\\\LOCAL\IFRCVN\VNDB\METASTOCK\\';
            $dirs = glob($dir . '*');
            $start = $this->input->post('start');
            $end = $this->input->post('end');
            $start = ($start == '') ? date('Ymd') : date('Ymd', strtotime($start));
            $end = ($end == '') ? $start : date('Ymd', strtotime($end));
            
            foreach($dirs as $item){
                switch(pathinfo($item, PATHINFO_BASENAME)){
                    case 'PRICES':
                        $price_dir = $dir . 'PRICES\EXC\\';
                    break;
                    case 'REFERENCE':
                        $ref_dir = $dir . 'REFERENCE\EXC\\';
                    break;
                }
            }
            $dirs = array($price_dir, $ref_dir);
            foreach($dirs as $dir){
                $files = glob($dir . '*.txt');
                $contents = array();
                foreach($files as $file){
                    $filename = pathinfo($file, PATHINFO_FILENAME);
                    $arr = explode('_', $filename);
                    $date = end($arr);
                    $date1 = substr($date, 0, 4) . '/' . substr($date, 4, 2) . '/' . substr($date, 6);
                    $date1 = strtotime($date1);
                    if($start <= $date && $date <= $end && date('D', $date1) != 'Sat' && date('D', $date1) != 'Sun'){
                        echo 
                        $f = fopen($file, 'r');
                        $i = 0;
                        while($content = fgetcsv($f, 0, chr(9))){

                            pre($content);  //test

                            if($i == 0){
                                $headers = $content;
                            }else{
                                $temp = array();
                                foreach($headers as $key => $header){
                                    $temp[$header] = $content[$key];
                                }
                                $contents[] = $temp;
                            }

                            $i++;
                        }

                        $contents = $this->mfile->addData($contents);
                        $contents = array();
                        $content = '';
                    }
                }
            }
            $this->mfile->updateTable();
            $startdate = strtotime(substr($start, 0, 4) . '-' . substr($start, 4, 2) . '-' . substr($start, 6));
            $enddate = strtotime(substr($end, 0, 4) . '-' . substr($end, 4, 2) . '-' . substr($end, 6));

            while($startdate <= $enddate){
                $data = $this->mfile->find(date('Y-m-d', $startdate));
                $content = implode(chr(9), $this->mfile->getHeader()) . PHP_EOL;
                foreach($data as $item){
                    $item['date'] = str_replace('-', '/', $item['date']);
                    $content .= implode(chr(9), $item) . PHP_EOL;
                }
                $path_file = '\\\LOCAL\IFRCVN\VNDB\METASTOCK\DAILY\FINAL\DAILY_' . date('Ymd', $startdate) . '.txt';
                $f = fopen($path_file, 'w');
                fwrite($f, $content);
                $startdate = strtotime("+1 day", $startdate);
            }
        }else{
            $this->data->method = $this->router->fetch_method();
            $this->data->title = "File Daily";
            $this->template->write_view('content', 'file_daily/index', $this->data);
            $this->template->write('title', 'File Daily');
            $this->template->render();
        }
    }
    
    public function ref(){
        if($this->input->is_ajax_request()){
            $this->load->Model('file_daily_model', 'mfile');
            $this->mfile->truncate();
            $dir = '\\\LOCAL\IFRCVN\VNDB\METASTOCK\REFERENCE\EXC\\';
            $files = glob($dir . '*.txt');
            $start = $this->input->post('start');
            $end = $this->input->post('end');
            $start = ($start == '') ? date('Ymd') : date('Ymd', strtotime($start));
            $end = ($end == '') ? $start : date('Ymd', strtotime($end));
            $contents = array();
            foreach($files as $file){
                $filename = pathinfo($file, PATHINFO_FILENAME);
                $arr = explode('_', $filename);
                $date = end($arr);
                if($start <= $date && $date <= $end && date('D', $date) != 'Sat' && date('D', $date) != 'Sun'){
                    $f = fopen($file, 'r');
                    $i = 0;
                    while($content = fgetcsv($f, 0, chr(9))){

                        //pre($content);  //test

                        if($i == 0){
                            $headers = $content;
                        }else{
                            $temp = array();
                            foreach($headers as $key => $header){
                                $temp[$header] = $content[$key];
                            }
                            $contents[] = $temp;
                        }

                        $i++;
                    }
                    $contents = $this->mfile->addData($contents);
                    $contents = array();
                    $content = '';
                }
            }
            $this->mfile->updateTable();
            $startdate = strtotime(substr($start, 0, 4) . '-' . substr($start, 4, 2) . '-' . substr($start, 6));
            $enddate = strtotime(substr($end, 0, 4) . '-' . substr($end, 4, 2) . '-' . substr($end, 6));
            while($startdate <= $enddate){
                $data = $this->mfile->find(date('Y-m-d', $startdate));
                pre($data);
                $content = implode(chr(9), $this->mfile->getHeader()) . PHP_EOL;
                foreach($data as $item){
                    $item['date'] = str_replace('-', '/', $item['date']);
                    $item['ipo'] = str_replace('-', '/', $item['ipo']);
                    $item['ftrd'] = str_replace('-', '/', $item['ftrd']);
                    $content .= implode(chr(9), $item) . PHP_EOL;
                }
                $path_file = '\\\LOCAL\IFRCVN\VNDB\METASTOCK\DAILY\REF\REF_' . date('Ymd', $startdate) . '.txt';
                $f = fopen($path_file, 'w');
                fwrite($f, $content);
                $startdate = strtotime("+1 day", $startdate);
            }
        }else{
            $this->data->method = $this->router->fetch_method(); 
            $this->data->title = "File Daily";
            $this->template->write_view('content', 'file_daily/index', $this->data);
            $this->template->write('title', 'File Daily');
            $this->template->render();
        }
    }

}