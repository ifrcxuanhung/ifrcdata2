<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/* * ****************************************************************** */
/*     Client  Name  :  IFRC                                      */
/*     Project Name  :  cms v3.0                                     */
/*     Program Name  :  article.php                                      */
/*     Entry Server  :                                               */
/*     Called By     :  System                                       */
/*     Notice        :  File Code is utf-8                           */
/*     Copyright     :  IFRC                                         */
/* ------------------------------------------------------------------- */
/*     Comment       :  controller article                             */
/* ------------------------------------------------------------------- */
/*     History       :                                               */
/* ------------------------------------------------------------------- */
/*     Version V001  :  2012.08.14 (LongNguyen)        New Create      */
/* * ****************************************************************** */

class Vndb_report extends Admin {

    protected $data;

    function __construct() {
        parent::__construct();
        $this->load->helper(array('my_array_helper', 'form'));
        $this->load->Model('vndb_report_model', 'mreport');
    }

    /*     * ************************************************************** */
    /*    Name ： index                                                */
    /* --------------------------------------------------------------- */
    /*    Description  ：  this function will be called automatically  */
    /*                   when the controller is called               */
    /* --------------------------------------------------------------- */
    /*    Params  ：  None                                             */
    /* --------------------------------------------------------------- */
    /*    Return  ：                                                 */
    /* --------------------------------------------------------------- */
    /*    Warning ：                                                  */
    /* --------------------------------------------------------------- */
    /*    Copyright : IFRC                                         */
    /* --------------------------------------------------------------- */
    /*    M001 : New  2012.08.14 (Tung)                            */
    /*     * ************************************************************** */

    function index() {
        // $start = time();
        // $end = time();
        // while($start <= $end){
        //     $date = date('Y-m-d', $start);
        //     $data = $this->mreport->getDataByDate($date);
        //     $start = strtotime("+1 day", $start);
        // }
        // pre($data);
        if ($this->input->is_ajax_request()) {
            $markets = array('HNX', 'HSX', 'UPC');
            $start = $this->input->post('start');
            $end = $this->input->post('end');
            foreach ($markets as $key => $market) {
                $data = $this->mreport->countData($market, $start, $end);
                $aaData[$key][] = $market;
                $aaData[$key][] = $data['row'];
                $aaData[$key][] = $data['last'];
                $aaData[$key][] = $data['shli'];
                $aaData[$key][] = $data['shou'];
                $aaData[$key][] = $data['adj_cls'];
            }
            $response = array(
                'aaData' => $aaData
            );
            echo json_encode($response);
        } else {
            $this->data->new_company = $this->mreport->new_company();
            $this->data->title = 'VNDB Report';
            $this->template->write_view('content', 'vndb_report/index', $this->data);
            $this->template->write('title', 'VNDB Report');
            $this->template->render();
        }
    }

    function checkShares(){
        if($this->input->is_ajax_request()){
            $start = $this->input->post('start');
            $end = $this->input->post('end');
            $data = $this->mreport->checkAllTicker('vndb_daily', $start, $end);
            $i = 0;
            if(is_array($data)){
                foreach($data as $item){
                    foreach($item as $value){
                        if(!isset($value['shli_change'])){
                            $shli_change = 'unchange: ' . $value['shli'];
                        }else{
                            $before = $value['shli'] - $value['shli_change'];
                            $shli_change = 'BF: ' . $before . '<br />CHG: ' . $value['shli_change'] . '<br />AF: ' .$value['shli'];
                        }
                        if(!isset($value['shou_change'])){
                            $value['shou_change'] = 'unchange: ' . $value['shou'];
                        }else{
                            $before = $value['shou'] - $value['shou_change'];
                            $shou_change = 'BF: ' . $before . '<br />CHG: ' . $value['shou_change'] . '<br />AF: ' .$value['shou'];
                        }
                        $aaData[] = array(
                            $value['date'],
                            $value['ticker'],
                            $value['market'],
                            $shli_change,
                            $shou_change                        
                        );
                    }
                }
                $response = array(
                    'aaData' => $aaData
                );
                echo json_encode($response);
            }else{
                 $response = array(
                    'aaData' => ''
                );
                echo json_encode($response);
            }
        }
    }

    function test() {
        // $data = $this->mreport->checkSharesChange('AAM', '2010-01-01', '2010-01-01', '2012-12-10');
        // pre($data);
        $data = $this->mreport->checkAllTicker('vndb_daily','0000-00-00', '2012-12-10');
        echo count($data);
    }

    function date() {
        $response = $this->mreport->min_date();
        $this->output->set_output(json_encode($response));
    }

}