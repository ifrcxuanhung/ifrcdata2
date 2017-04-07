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

class Event extends Admin {

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

    public function hnx(){
        $url = 'http://hnx.vn/web/guest/home?p_p_id=tincongbolichsukien_WAR_HnxIndexportlet&p_p_lifecycle=2&p_p_state=normal&p_p_mode=view&p_p_cacheability=cacheLevelPage&p_p_col_id=column-5&p_p_col_count=1&_tincongbolichsukien_WAR_HnxIndexportlet_type=json';
        $params = 'iDisplayLength=15&iDisplayStart=<<_start>>&iSortCol_0=1&ngay=<<_date>>&sSortDir_0=desc';
        $url2 = 'http://hnx.vn/web/guest/home';
        $params2 = 'p_p_id=tincongbolichsukien_WAR_HnxIndexportlet&p_p_lifecycle=1&p_p_state=exclusive&p_p_mode=view&p_p_col_id=column-5&p_p_col_count=1&_tincongbolichsukien_WAR_HnxIndexportlet_anchor=viewAction&_tincongbolichsukien_WAR_HnxIndexportlet_news_id=<<_id>>&_tincongbolichsukien_WAR_HnxIndexportlet_exist_file=0';
        
        $startdate = strtotime('2011-01-01');
        // $enddate = strtotime('2013-02-01');

        $now = time();
        // $startdate = $now;
        $enddate = $now;
        while($startdate <= $enddate){
            $this->db->close();
            $this->db->initialize();
            $tmp_params = str_replace('<<_date>>', date('d/m/Y', $startdate), $params);
            $response = array(
                'data' => array()
            );
            $start = 0;
            $i = 0;
            // if fail 3 times, break the loop
            while($i < 3){
                $tmp_tmp_params = str_replace('<<_start>>', $start, $tmp_params);
                $result = array();      
                $param = explode('&', $tmp_tmp_params);
                $curl = new curl(false);
                $html = $curl->makeRequest2('post', $url, $param);
                $data = json_decode($html, true);
                if(is_array($data) && !empty($data['aaData'])){
                    $temp = $data['aaData'];
                    foreach($temp as $k => $v){
                        $j = 0;
                        while($j < 3){
                            $tmp_params2 = str_replace('<<_id>>', $v[1], $params2);
                            $param2 = explode('&', $tmp_params2);
                            $html2 = $curl->makeRequest2('post', $url2, $param2);
                            $result[$k] = array(
                                'ticker' => $v[6],
                                'market' => $v[5] == 'UPCoM' ? 'UPC' : 'HNX',
                                'date_ann' => date('Y-m-d', $startdate),
                                'evname' => $v[7],
                                'content' => ''                                
                            );
                            if($html2 != ''){
                                // $s = 'auto;"\>';
                                $s = 'auto;">';
                                $e = '\<\/div\>';
                                $rule = "/(?<=$s).*(?=$e)/msU";
                                preg_match($rule, $html2, $tmp_html2);               
                                if(isset($tmp_html2[0])){
                                    $tmp_html2 = trim(preg_replace(array('/\r\n/', '/\<br\>/'), '', $tmp_html2[0]));
                                    $result[$k]['content'] = htmlentities($tmp_html2);                                    
                                }
                                $where = array(
                                    'ticker' => $result[$k]['ticker'],
                                    'date_ann' => $result[$k]['date_ann']
                                );
                                $this->mdownload->update_exc('vndb_events_day', $result[$k], $where);
                                break;
                            }
                            $j++;
                        }
                    }
                    $response['data'] = array_merge($response['data'], $result);
                    unset($temp);
                    unset($data);
                    $i++;
                    $start += 15;
                    //break;
                }else{
                    $i++;
                }
            }
            $startdate = strtotime("+1 day", $startdate);
        }
        header('Content-Type: text/html; charset=utf-8');
        $data_result = $this->db->query("select * from vndb_events_day where event_type = 'OTHER' OR event_type is null")->result_array();
        if(count($data_result) != 0){
            foreach ($data_result as $dr) {
                $filter = $this->search_type($dr['evname']);
                if($filter == ''){
                    $filter = 'OTHER';
                }
                $data_update[] = array(
                    'id' => $dr['id'],
                    'event_type' => $filter
                );
            }
            @$this->db->update_batch('vndb_events_day',$data_update,'id');
        }
        //echo 'success';
        //redirect(admin_url());
    }

    public function search_type($content) {
        $this->db->where('ca_type <>','OTHER');
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
                    if (strpos(trim($content), $sd_value)) {
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

    public function hsx(){
        $now = time();
        $url = 'http://www.hsx.vn/hsx/Modules/News/News.aspx?type=TCNY';
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
        //echo time() - $now;
        foreach($data as $item){
            $where = array(
                'ticker' => $item['ticker'],
                'date_ann' => $item['date_ann'],
                'evname' => $item['evname']
            );
            $this->mdownload->update_exc('vndb_events_day', $item, $where);
        }
        $data_result = $this->db->query("select * from vndb_events_day where event_type = 'OTHER' OR event_type is null")->result_array();
        if(count($data_result) != 0){
            foreach ($data_result as $dr) {
                $filter = $this->search_type($dr['evname']);
                if($filter == ''){
                    $filter = 'OTHER';
                }
                $data_update[] = array(
                    'id' => $dr['id'],
                    'event_type' => $filter
                );
            }
            @$this->db->update_batch('vndb_events_day',$data_update,'id');
        }
        //redirect(admin_url());
    }

    public function hsx_history(){
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
            $where = array(
                'ticker' => $data['ticker'],
                'date_ann' => $data['date_ann'],
                'evname' => $data['evname']
            );
            $this->mdownload->update_exc('vndb_events_day', $data, $where);
            $count++;
        }
        $this->db->trans_complete();
        echo $count . '<br />';
        echo time() - $now;

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
            $this->mdownload->update_exc('vndb_events_day', $data, $where);
            $count++;
        }
        $this->db->trans_complete();
        echo $count . '<br />';
        echo time() - $now;

    }
}
