<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/* * *******************************************************************************************************************
 * Client  Name ：  IFRC
 * ---------------------------------------------------------------------------------------------------------------------
 * Project Name ：  IMS v3.0
 * ---------------------------------------------------------------------------------------------------------------------
 * Program Name ：  profile.php
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
 * Version V001 ：  2012.10.22 (Tung)        New Create
 * ******************************************************************************************************************* */

class Profile extends Admin {

    protected $data;
    protected $_data;
    public function __construct() {
        parent::__construct();
        $this->load->library('curl');
        $this->load->library('simple_html_dom');
        $this->load->Model('profile_model', 'mprofile');
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
     * M001         ： New  2012.10.22 (Tung)
     * *************************************************************************************************************** */

    public function index() {
        set_time_limit(0);
        if($this->input->is_ajax_request()){
            $this->mprofile->truncate('result_view');
            $src = $this->input->post('source');
            $info = $this->input->post('information');
            $market = $this->input->post('market');
            $ticker = $this->input->post('code');
            $data = $this->mprofile->listDownload($src, $info, $market);
            $temp_url = '';
            if($ticker == '0'){
                $ticker = $this->mprofile->listTicker();
            }


            foreach($data as $key => $item){
                if(is_array($ticker)){
                    foreach($ticker as $code){
                        $url = str_replace('<<TICKER>>', $code['code'], $item['url']);
                        $url = str_replace('<<ticker>>', strtolower($code['code']), $url);
                        $contents[$url . $key] = array(
                            'source' => $item['source'],
                            'information' => $item['information'],
                            'market' => $item['market'],
                            'ticker' => $code['code'],
                            'url' => $url,
                            'left' => $item['left'],
                            'right' => $item['right'],
                            'left2' => $item['left2'],
                            'right2' => $item['right2']
                        );
                        //$contents[$key]['ticker'] = $code['code'];
                        //$contents[$key]['values'] = $this->_getData($url, $item['left'], $item['right'], $item['left2']);
                   }
                }else{
                    $url = str_replace('<<TICKER>>', $ticker, $item['url']);
                    $url = str_replace('<<ticker>>', strtolower($ticker), $url);
                    // $url = str_replace('<<TICKER>>', $ticker, $item['url']);
                    // $url = str_replace('<<ticker>>', strtolower($ticker), $item['url']);
                    $contents[$url . $key] = array(
                        'source' => $item['source'],
                        'information' => $item['information'],
                        'market' => $item['market'],
                        'ticker' => $ticker,
                        'url' => $url,
                        'left' => $item['left'],
                        'right' => $item['right'],
                        'left2' => $item['left2'],
                        'right2' => $item['right2']
                    );
                    //$contents[$key]['values'] = $this->_getData($url, $item['left'], $item['right'], $item['right2']);
                }
            }
            ksort($contents);
            foreach($contents as $key => $item){
                if($item['url'] != $temp_url){
                    $temp_url = $item['url'];
                    $html = $this->_getHtml($temp_url);
                }
                $values = $this->_getData($html, $item['left'], $item['right'], $item['left2']);
                if($values != ''){
                    $contents[$key]['values'] = $values;
                }else{
                    unset($contents[$key]);
                }
            }

            $temp_arr = array('source', 'information', 'market', 'values');
            foreach($contents as $key => $content){
                foreach($content as $key2 => $item){
                    if(!in_array($key2, $temp_arr)){
                        unset($contents[$key][$key2]);
                    }
                }
            }
            /*foreach($data as $key => $item){
                if(is_array($ticker)){
                    foreach($ticker as $code){
                        $url = str_replace('<<TICKER>>', $code['code'], $temp_url);
                        $url = str_replace('<<ticker>>', strtolower($code['code']), $temp_url);
                        if($url != $temp_url){
                            $temp_url = $url;                            
                            $html = $this->_getHtml($url);
                        }
                        // $url = str_replace('<<TICKER>>', $code['code'], $item['url']);
                        // $url = str_replace('<<ticker>>', strtolower($code['code']), $item['url']);
                        $value = $this->_getData($html, $item['left'], $item['right'], $item['left2']);
                        if($value != ''){
                            $contents[] = array(
                                'source' => $item['source'],
                                'information' => $item['information'],
                                'market' => $item['market'],
                                'ticker' => $code['code'],
                                'values' => $value
                            );
                        }
                        //$contents[$key]['ticker'] = $code['code'];
                        //$contents[$key]['values'] = $this->_getData($url, $item['left'], $item['right'], $item['left2']);
                   }
                }else{
                    $url = str_replace('<<TICKER>>', $ticker, $temp_url);
                    $url = str_replace('<<ticker>>', strtolower($ticker), $temp_url);
                    if($url != $temp_url){
                        $temp_url = $url;                      
                        $html = $this->_getHtml($url);
                    }
                    // $url = str_replace('<<TICKER>>', $ticker, $item['url']);
                    // $url = str_replace('<<ticker>>', strtolower($ticker), $item['url']);
                    $value = $this->_getData($html, $item['left'], $item['right'], $item['left2']);
                    if($value != ''){
                        $contents[] = array(
                            'source' => $item['source'],
                            'information' => $item['information'],
                            'market' => $item['market'],
                            'ticker' => $ticker,
                            'values' => $value
                        );
                    }
                    //$contents[$key]['values'] = $this->_getData($url, $item['left'], $item['right'], $item['right2']);
                }
            }*/
            $this->mprofile->addResult($contents, 'result_view');
            //redirect(admin_url() . 'profile/result');
            //pre($data);
            
        }else{
        
            $this->data->tickers = $this->mprofile->listTicker();
            $this->data->markets = $this->mprofile->listInfo('market');
            $this->data->sources = $this->mprofile->listInfo('source');
            $this->data->infos = $this->mprofile->listInfo('information');

            $this->data->title = "Profile";
            $this->template->write_view('content', 'profile/index', $this->data);
            $this->template->write('title', 'Profile');
            $this->template->render();
        }
    }
    public function result(){
        $this->data->results = $this->mprofile->getResult();

        $this->data->title = "Results";
        $this->template->write_view('content', 'profile/result', $this->data);
        $this->template->write('title', 'Results');
        $this->template->render();
    }
    protected function _getData($html, $left, $right, $left2 = '', $right2 = ''){
        //return $url;
        $response = '';
        /*$curl = new curl();
        $html = $curl->makeRequest('get', $url, null);*/
        //$html = $this->_getHtml($url);
        $start = preg_quote($left, '/');
        $end = preg_quote($right, '/');
        $rule = "/(?<=$start).*(?=$end)/msU";
        preg_match($rule, $html, $result);
        if(!empty($result)){
            $response = $result[0];
        }
        if($left2 != '' && $response != ''){
            $start = preg_quote($left2, '/');
            $end = preg_quote($right2, '/');
            $rule = "/(?<=$start).*(?=$end)$/msU";
            preg_match($rule, $result[0], $result2);
            $response = $result2[0];
            pre($result2);
        }
        return $response;
    }
    protected function _getHtml($url){
        $curl = new curl();
        return $curl->makeRequest('get', $url, null);
    }

}