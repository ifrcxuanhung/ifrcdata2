<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/* * ***************************************************************************************************************** *
 *     Client  Name  :  IFRC
 * ---------------------------------------------------------------------------------------------------------------------
 *     Project Name  :  IMS
 * ---------------------------------------------------------------------------------------------------------------------
 *     Program Name  :  vndb_report_history.php
 * ---------------------------------------------------------------------------------------------------------------------
 *     Entry Server  :
 * ---------------------------------------------------------------------------------------------------------------------
 *     Called By     :  System
 * ---------------------------------------------------------------------------------------------------------------------
 *     Notice        :  File Code is utf-8
 * ---------------------------------------------------------------------------------------------------------------------
 *     Copyright     :  IFRC
 * ---------------------------------------------------------------------------------------------------------------------
 *     Comment       :  controller Vndb report history
 * ---------------------------------------------------------------------------------------------------------------------
 *     History       :
 * ---------------------------------------------------------------------------------------------------------------------
 *     Version V001  :  2012.12.13 (LongNguyen)        New Create
 * * ****************************************************************** ********************************************** */

class Vndb_report_history extends Admin {

    protected $data;

    function __construct() {
        parent::__construct();
        $this->load->helper(array('my_array_helper', 'form'));
        $this->load->Model('vndb_report_model', 'mreport');
    }

    /*     * ********************************************************************************************************* *
     *    Name         ： index
     * -----------------------------------------------------------------------------------------------------------------
     *    Description  ：  this function will be called automatically
     *                   when the controller is called
     * -----------------------------------------------------------------------------------------------------------------
     *    Params       ：  None
     * -----------------------------------------------------------------------------------------------------------------
     *    Return       ：
     * -----------------------------------------------------------------------------------------------------------------
     *    Warning      ：
     * -----------------------------------------------------------------------------------------------------------------
     *    Copyright    ： IFRC
     * -----------------------------------------------------------------------------------------------------------------
     *    M001         ： New  2012.12.13 (LongNguyen)
     *     * ********************************************************************************************************* */

    function index() {
        if ($this->input->is_ajax_request()) {
            $start = $this->input->post('start');
            $end = $this->input->post('end');
            $data = $this->mreport->intro_remove_company($start, $end);
            $aaData = '';
            if (isset($data) && is_array($data) && count($data) > 0) {
                $key = 0;
                foreach ($data as $k => $value) {
                    if (isset($value['plus'])) {
                        $date = $value['plus'][0]['date'];
                    } else {
                        $date = $value['subtract'][0]['date'];
                    }
                    $aaData[$key][] = $date;
                    $tt = '';
                    if (isset($value['plus'])) {
                        $tts = '<strong>' . trans('Add', true) . ': </strong>';
                        foreach ($value['plus'] as $vplus) {
                            if (strtolower($vplus['market']) == 'hnx') {
                                $tt.= $vplus['ticker'] . ' , ';
                            }
                        }
                        if ($tt != '') {
                            $tt = $tts . $tt;
                            $tt.='<br/>';
                        }
                    }
                    $ttt = '';
                    if (isset($value['subtract'])) {
                        $tts = '<strong>' . trans('Remove', true) . ': </strong>';
                        foreach ($value['subtract'] as $vsubtract) {
                            if (strtolower($vsubtract['market']) == 'hnx') {
                                $ttt.= $vsubtract['ticker'] . ' , ';
                            }
                        }
                        if ($ttt != '') {
                            $tt = $tts . $ttt . '<br/>' . $tt;
                            $tt.='<br/>';
                        }
                    }
                    $aaData[$key][] = $tt;

                    $tt2 = '';
                    if (isset($value['plus'])) {
                        $tt2s = '<strong>' . trans('Add', true) . ': </strong>';
                        foreach ($value['plus'] as $vplus) {
                            if (strtolower($vplus['market']) == 'hsx') {
                                $tt2.= $vplus['ticker'] . ' , ';
                            }
                        }
                        if ($tt2 != '') {
                            $tt2 = $tt2s . $tt2;
                            $tt2.='<br/>';
                        }
                    }
                    $tt2t = '';
                    if (isset($value['subtract'])) {
                        $tt2s = '<strong>' . trans('Remove', true) . ': </strong>';
                        foreach ($value['subtract'] as $vsubtract) {
                            if (strtolower($vsubtract['market']) == 'hsx') {
                                $tt2t.= $vsubtract['ticker'] . ' , ';
                            }
                        }
                        if ($tt2t != '') {
                            $tt2 = $tt2s . $tt2t . '<br/>' . $tt2;
                            $tt2.='<br/>';
                        }
                    }
                    $aaData[$key][] = $tt2;
                    $tt3 = '';
                    if (isset($value['plus'])) {
                        $tt3s = '<strong>' . trans('Add', true) . ': </strong>';
                        foreach ($value['plus'] as $vplus) {
                            if (strtolower($vplus['market']) == 'upc') {
                                $tt3.= $vplus['ticker'] . ' , ';
                            }
                        }
                        if ($tt3 != '') {
                            $tt3 = $tt3s . $tt3;
                            $tt3.='<br/>';
                        }
                    }
                    $tt3t = '';
                    if (isset($value['subtract'])) {
                        $tt3s = '<strong>' . trans('Remove', true) . ': </strong>';
                        foreach ($value['subtract'] as $vsubtract) {
                            if (strtolower($vsubtract['market']) == 'upc') {
                                $tt3t.= $vsubtract['ticker'] . ' , ';
                            }
                        }
                        if ($tt3t != '') {
                            $tt3 = $tt3s . $tt3t . '<br/>' . $tt3;
                            $tt3.='<br/>';
                        }
                    }
                    $aaData[$key][] = $tt3;
                    $key++;
                }
            }
            $response = array(
                'aaData' => $aaData
            );
            echo json_encode($response);
        } else {
            $this->data->title = 'VNDB History Report';
            $this->template->write_view('content', 'vndb_report_history/index', $this->data);
            $this->template->write('title', 'VNDB History Report');
            $this->template->render();
        }
    }

    function date() {
        $response = $this->mreport->min_date_hostory();
        $this->output->set_output(json_encode($response));
    }

    function report() {
        if ($this->input->is_ajax_request()) {
            $markets = array('HNX', 'HSX', 'UPC');
            $start = $this->input->post('start');
            $end = $this->input->post('end');
            foreach ($markets as $key => $market) {
                $data = $this->mreport->countData_history($market, $start, $end);
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
        }
    }

    function checkShares(){
        if($this->input->is_ajax_request()){
            $start = $this->input->post('start');
            $end = $this->input->post('end');
            $data = $this->mreport->checkAllTicker('vndb_prices_uni', $start, $end);
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

}