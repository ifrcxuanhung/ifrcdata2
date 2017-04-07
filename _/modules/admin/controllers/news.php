<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/* * *******************************************************************************************************************
 * Client  Name ：  IFRC
 * ---------------------------------------------------------------------------------------------------------------------
 * Project Name ：  IMS v3.0
 * ---------------------------------------------------------------------------------------------------------------------
 * Program Name ：  hsx.php
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
 * Version V001 ：  2012.03.04 (Tung)        New Create
 * ******************************************************************************************************************* */

class News extends Admin {

    protected $data;
    private $cal_dates;
    protected $_option;

    public function __construct() {
        parent::__construct();
        set_time_limit(0);
        $this->load->model('download_model', 'mdownload');
        $this->load->library('curl');
        date_default_timezone_set('Asia/Ho_Chi_Minh');
    }
    
    public function hsx(){
        $now = time();
        $url = 'http://www.hsx.vn/hsx/Modules/News/News.aspx?type=CTCK';
        $curl = new curl;
        $html = $curl->makeRequest('get', $url, NULL);
        $start = '<tr style="padding-top:2px">';
        $end = '</tr>';
        $start = preg_quote($start);
        $end = preg_quote($end, '/');
        $rule = "/(?<=$start).*(?=$end)/msU";
        $data = array();
        preg_match_all($rule, $html, $result);
        if(!empty($result)){
            $result = $result[0];
            foreach($result as $key => $item){
                $temp = array(
                    'ticker' => '',
                    'market' => 'HSX',
                    'date_ann' => '',
                    'evname' => '',
                    'content' => ''
                );
                $start = '\<td.*\>\r\n';
                $end = '<\/td\>';
                $rule = "/$start(.*)$end/msU";
                preg_match_all($rule, $item, $result2);
                if(!empty($result2)){
                    $tmp_result2[$key] = $result2[1];
                    preg_match('/[0-9]{2}\-[0-9]{2}\-[0-9]{4}/', $result2[1][1], $td);
                    $temp['date_ann'] = implode('-', array_reverse(explode('-', $td[0])));
                    preg_match('/<a.*href=".*id=(.*)">(.*)\:(.*)\<\/a\>/', $result2[1][2], $td);
                    $id = $td[1];
                    $temp['ticker'] = $td[2];
                    $temp['evname'] = trim($td[3]);
                    $url2 = 'http://www.hsx.vn/hsx/Modules/News/NewsDetail.aspx?id=' . $id;
                    $html2 = $curl->makeRequest('get', $url2, NULL);
                    $c_start = '<span id="ctl00_mainContent_lblSumary" style="color:Black;font-family:Arial;font-size:10pt;font-weight:bold;">';
                    $c_start = preg_quote($c_start);
                    $c_end = '<img src="../../images/Icons/back.gif"';
                    $c_end = preg_quote($c_end, '/');
                    preg_match("/($c_start.*)\<p align\=\"right\"\>.*$c_end/msU", $html2, $contents);
                    $contents = trim(preg_replace('/\<\!\-\-.*\-\-\>|\r\n|\r|\n/msU', '', $contents[1]));
                    $temp['content'] = htmlentities($contents);

                }
                $data[] = $temp;
            }
        }
        foreach($data as $item){
            $where = array(
                'ticker' => $item['ticker'],
                'date_ann' => $item['date_ann'],
                'evname' => $item['evname']
            );
            $this->mdownload->update_exc('vndb_news_day', $item, $where);
        }
        $data_result = $this->db->query("select * from vndb_news_day where new_type = '' ")->result_array();
        foreach ($data_result as $dr) {
            $filter = $this->search_type($dr['evname']);
            $this->db->query("UPDATE vndb_news_day SET new_type = '" . $filter . "' WHERE ID = '" . $dr['id'] . "'");
        }
        echo time() - $now;
        redirect(admin_url());
    }

    public function search_type($content) {
        $data_keywords = $this->db->get("pvn_ca_keywords")->result_array();
        foreach ($data_keywords as $dk) {
            $arr_dk = explode(',', $dk['ca_keywords']);
            $keywords = array();
            foreach ($arr_dk as $adk) {
                $keywords[][$dk['ca_type']] = trim($adk);
            }
            $data_keywords_final[] = $keywords;
        }
        foreach ($data_keywords_final as $dkf) {
            foreach ($dkf as $sub_dkf) {
                //$filter = array();
                foreach ($sub_dkf as $sd_key => $sd_value) {
                    if (@strpos(trim($content), $sd_value)) {
                        $filter[] = $sd_key;
                    }
                }
            }
        }

        if (isset($filter) && !empty($filter)) {
            if (in_array("STOCK DIVIDEND", $filter) && in_array("CASH DIVIDEND", $filter)) {
                $filter[0] = 'CASH DIVIDEND';
            }
            return $filter[0];
        } else {
            return $filter = '';
        }
    }

    public function hsx_history($from = '', $to = ''){
        if($this->input->is_ajax_request()){
            $resp = array(
                'stt' =>'',
                'duration' => ''
            );
            $now = time();
            $file = 'assets\data_upload_indexes\news_hsx.txt';
            $contents = file($file);
            array_shift($contents);
            if($from > count($contents)){
                $resp['stt'] = 'end';
                echo json_encode($resp);
                exit();
            }
            $contents = array_slice($contents, $from, $to);
            $curl = new curl;
            // $this->db->trans_start();
            foreach($contents as $content){
                $content = explode(chr(9), $content);
                $data = array(
                    'ticker' => $content[0],
                    'market' => $content[1],
                    'date_ann' => $content[2],
                    'evname' => $content[3],
                    'content' => ''
                );
                $url = trim($content[4]);
                $html = $curl->makeRequest('get', $url, NULL);
                $c_start = '<span id="ctl00_mainContent_lblSumary" style="color:Black;font-family:Arial;font-size:10pt;font-weight:bold;">';
                $c_start = preg_quote($c_start);
                $c_end = '<img src="../../images/Icons/back.gif"';
                $c_end = preg_quote($c_end, '/');
                preg_match("/($c_start.*)\<p align\=\"right\"\>.*$c_end/msU", $html, $result);
                $result = trim(preg_replace('/\<\!\-\-.*\-\-\>|\r\n|\r|\n/msU', '', $result[1]));
                $data['content'] = htmlentities($result);
                $where = array(
                    'ticker' => $data['ticker'],
                    'date_ann' => $data['date_ann'],
                    'evname' => $data['evname']
                );
                $this->mdownload->update_exc('vndb_news_day', $data, $where);
            }
            // $this->db->trans_complete();
            $resp['duration'] = time() - $now;
            echo json_encode($resp);
        }else{
            $this->template->write_view('content', 'news/hsx', $this->data);
            $this->template->render();
        }
    }
    public function test(){
        $now = time();
        $file = 'assets\data_upload_indexes\evlink_hsx.txt';
        $contents = file($file);
        array_shift($contents);
        $count = 0;
        $curl = new curl;
        $this->db->trans_start();
        foreach($contents as $content){
            $content = explode(chr(9), $content);
            $data = array(
                'ticker' => $content[0],
                'market' => $content[1],
                'date_ann' => $content[2],
                'evname' => $content[3],
                'content' => ''
            );
            $url = trim($content[4]);
            $html = $curl->makeRequest('get', $url, NULL);
            $c_start = '<span id="ctl00_mainContent_lblSumary" style="color:Black;font-family:Arial;font-size:10pt;font-weight:bold;">';
            $c_start = preg_quote($c_start);
            $c_end = '<img src="../../images/Icons/back.gif"';
            $c_end = preg_quote($c_end, '/');
            preg_match("/($c_start.*)\<p align\=\"right\"\>.*$c_end/msU", $html, $result);
            $result = trim(preg_replace('/\<\!\-\-.*\-\-\>|\r\n|\r|\n/msU', '', $result[1]));
            $data['content'] = htmlentities($result);
            pre($data);die();
            $where = array(
                'ticker' => $data['ticker'],
                'date_ann' => $data['date_ann'],
                'evname' => $data['evname']
            );
            $this->mdownload->update_exc('vndb_news_day', $data, $where);
            $count++;
        }
        $this->db->trans_complete();
        echo $count . '<br />';
        echo time() - $now;

    }
}
