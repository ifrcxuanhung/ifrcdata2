<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/* * ****************************************************************** */
/*     Client  Name  :  IFRC                                      */
/*     Project Name  :  cms v3.0                                     */
/*     Program Name  :  observatory.php                                      */
/*     Entry Server  :                                               */
/*     Called By     :  System                                       */
/*     Notice        :  File Code is utf-8                           */
/*     Copyright     :  IFRC                                         */
/* ------------------------------------------------------------------- */
/*     Comment       :  controller observatory                             */
/* ------------------------------------------------------------------- */
/*     History       :                                               */
/* ------------------------------------------------------------------- */
/*     Version V001  :  2012.08.30 (Tung) 		       New Create      */
/* * ****************************************************************** */

class Observatory extends Admin {

    public $cache;

    public function __construct() {
        parent::__construct();
        $this->load->library('curl');
        $this->load->library('simple_html_dom');
        $this->load->library('my_cache');
        $cache = new My_Cache;
        $this->cache = $cache->loadCache();
    }

    /*     * ************************************************************** */
    /*    Name ： refresh_sample                                                */
    /* --------------------------------------------------------------- */
    /*    Description  ： refresh idx_sample table in database               */
    /* --------------------------------------------------------------- */
    /*    Params  ： 					                                      */
    /* --------------------------------------------------------------- */
    /*    Return  ：   												  */
    /* --------------------------------------------------------------- */
    /*    Warning ：                                                     */
    /* --------------------------------------------------------------- */
    /*    Copyright : IFRC                                         */
    /* --------------------------------------------------------------- */
    /*    M001 : New  2012.08.30 (Tung)                             */
    /*     * ************************************************************** */

    /* public function refresh_sample(){
      $this->load->Model('observatory_model', 'mObs');
      $count = $this->mObs->upload_sample();
      if($count != FALSE){
      $this->data->count = $count;
      }else{
      $this->data->err = 'sample.csv does not exist in ' . $_SERVER['DOCUMENT_ROOT'] . '/ims/assets/upload/files/sample/';
      }
      $this->template->write_view('content', 'observatory/refresh_sample', $this->data);
      $this->template->write('title', 'Observatory ');
      $this->template->render();
      } */

    /*     * ************************************************************** */
    /*    Name ： download_reuters                                          */
    /* --------------------------------------------------------------- */
    /*    Description  ： get reuters index and save in a csv file         */
    /* --------------------------------------------------------------- */
    /*    Params  ： 					                                      */
    /* --------------------------------------------------------------- */
    /*    Return  ：   												  */
    /* --------------------------------------------------------------- */
    /*    Warning ：                                                     */
    /* --------------------------------------------------------------- */
    /*    Copyright : IFRC                                         */
    /* --------------------------------------------------------------- */
    /*    M001 : New  2012.08.30 (Tung)                             */
    /*     * ************************************************************** */

    /* public function download_reuters(){
      if($this->input->is_ajax_request()){

      $symbol = $this->input->get('symbol');
      $duration = $this->input->get('duration');
      if($duration == 1826){
      $freq = '1day';
      }
      if($duration == 7300){
      $freq = '1month';
      }

      if(!$datahtml = $this->cache->load(substr($symbol, 1) . $duration)){
      $html = file_get_contents('http://charts.reuters.com/reuters/enhancements/US/interactiveChart/chart.asp?symbol=' . $symbol);
      $start = '\"Ticker\"\:\"';
      $end = '\"';
      $rule = "/(?<=$start).*(?=$end)/msU";
      preg_match_all($rule, $html, $result);
      $symbol2 = $result[0][1];
      $code = $result[0][2];

      $input = base64_encode('{"events":[{"type":"events","name":"news","color":"e52600","symbol":"' . $code . '","symbolType":"WSODIssue"}],"symbol":"' . $symbol2 . '","WSODIssue":"' . $code . '","RIC":"' . $symbol . '","company":null,"duration":"' . $duration . '","frequency":"' . $freq . '","dMax":undefined,"dMin":undefined,"display":"mountain","scaling":"linear","reskin":true}');
      curl = new curl( );
      $datahtml = $curl->makeRequest('post', 'http://charts.reuters.com/reuters/enhancements/US/interactiveChart/api.asp', array('inputs=B64ENC' . $input, '..contenttype..=text%2Fjavascript', '..requester..=ContentBuffer'));
      $datahtml = substr($datahtml, strpos($datahtml, '['));
      $datahtml = substr($datahtml, 0, strpos($datahtml, ']') + 1);
      $datahtml = str_replace('\\', '', $datahtml);
      $datahtml = json_decode($datahtml, true);
      $this->cache->save($datahtml, substr($symbol, 1) . $duration);
      }
      $i = 0;
      foreach ($datahtml as $key => $item){
      $aaData[$key][] = ++$i;
      $aaData[$key][] = str_replace(',', '', $item['open']);
      $aaData[$key][] = str_replace(',', '', $item['high']);
      $aaData[$key][] = str_replace(',', '', $item['low']);
      $aaData[$key][] = str_replace(',', '', $item['close']);
      $aaData[$key][] = str_replace(',', '', $item['date']);
      }
      $output = array(
      'aaData' => $aaData
      );
      $this->output->set_output(json_encode($output));
      }else{
      if($this->input->post('export', TRUE)){
      $symbol = $this->input->post('symbol');
      $duration = $this->input->post('duration');
      if($datahtml = $this->cache->load(substr($symbol, 1) . $duration)){
      $content = 'OPEN,CLOSE,HIGH,LOW,DATE,RAWDATE,X,Y,VOLUME' . PHP_EOL;
      foreach($datahtml as $item){
      $content .= str_replace(',', '', $item['open']) . ',';
      $content .= str_replace(',', '', $item['close']) . ',';
      $content .= str_replace(',', '', $item['high']) . ',';
      $content .= str_replace(',', '', $item['low']) . ',';
      $content .= str_replace(',', '', $item['date']) . ',';
      $content .= $item['rawDate'] . ',';
      $content .= $item['x'] . ',';
      $content .= $item['y'] . ',';
      $content .= str_replace(',', '', $item['volume']) . PHP_EOL;
      }
      $now = getdate();
      $time = date('Ymd', $now[0]);
      echo $time;
      $file = fopen($_SERVER['DOCUMENT_ROOT'] . '/ims/assets/download/reuters/' . $time . $duration . $symbol . '.csv', 'w');
      fwrite($file, $content);
      }
      }
      $this->template->write_view('content', 'observatory/reuters');
      $this->template->write('title', 'Observatory ');
      $this->template->render();
      }
      } */

    /* function listdata() {
      $this->load->Model('category_model');
      $this->data->list_category = $this->category_model->list_category();
      if (isset($this->data->list_category) == TRUE && $this->data->list_category != '' && is_array($this->data->list_category) == TRUE) {
      $aaData = array();
      foreach ($this->data->list_category as $key => $value) {
      $value->thumb = $this->_thumb($value->image);
      $aaData[$key][] = $value->category_id;
      $aaData[$key][] = $value->name;
      $aaData[$key][] = $value->status == 1 ? 'Enable' : 'Disable';
      $aaData[$key][] = $value->sort_order;
      $aaData[$key][] = '<a class="fancybox" style="display: block;width: 35px" href="' . (isset($value->image) ? base_url() . $value->image : base_url() . 'assets/images/no-image.jpg') . '" title="' . $value->name . '">
      <img class="thumbnails " src="' . (isset($value->thumb) ? base_url() . $value->thumb : base_url() . 'assets/images/no-image.jpg') . '" alt="" /></a>';
      }
      $output = array(
      "aaData" => $aaData
      );
      $this->output->set_output(json_encode($output));
      }
      } */
    /*     * ********************************************************* */
    /*    Name ： import_currency                                          
      /* --------------------------------------------------------------- */
    /*    Description  ：         
      /* --------------------------------------------------------------- */
    /*    Params  ： 					                                     
      /* --------------------------------------------------------------- */
    /*    Return  ：   												  
      /* --------------------------------------------------------------- */
    /*    Warning ：                                                     
      /* --------------------------------------------------------------- */
    /*    Copyright : IFRC                                         
      /* --------------------------------------------------------------- */
    /*    M001 : New  2013.06.17 (PhanMinh)                             
      /*     * ********************************************************* */

    public function import_currency() {
        $this->template->write_view('content', 'observatory/import_currency', $this->data);
        $this->template->write('title', 'Import Currency');
        $this->template->render();
    }

    public function import_economics() {
        $this->template->write_view('content', 'observatory/import_economics', $this->data);
        $this->template->write('title', 'Import Economics');
        $this->template->render();
    }

    public function process_import_currency() {
        if ($this->input->is_ajax_request()) {
            $from = microtime(true);
            $this->load->model('Observatory_Model', 'observatory_model');
            $this->observatory_model->import_currency();
            $total = microtime(true) - $from;
            $result[0]['time'] = round($total, 2);
            $result[0]['task'] = 'Import Currency';
            echo json_encode($result);
        }
    }

    public function process_import_economics() {
        if ($this->input->is_ajax_request()) {
            $from = microtime(true);
            $this->load->model('Observatory_Model', 'observatory_model');
            $this->observatory_model->import_economics();
            $total = microtime(true) - $from;
            $result[0]['time'] = round($total, 2);
            $result[0]['task'] = 'Import Economics';
            echo json_encode($result);
        }
    }

}