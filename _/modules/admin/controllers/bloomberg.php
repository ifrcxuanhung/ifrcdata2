<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/* * ****************************************************************** */
/*     Client  Name  :  IFRC                                      */
/*     Project Name  :  cms v3.0                                     */
/*     Program Name  :  bloomberg.php                                      */
/*     Entry Server  :                                               */
/*     Called By     :  System                                       */
/*     Notice        :  File Code is utf-8                           */
/*     Copyright     :  IFRC                                         */
/* ------------------------------------------------------------------- */
/*     Comment       :  controller sys format                             */
/* ------------------------------------------------------------------- */
/*     History       :                                               */
/* ------------------------------------------------------------------- */
/*     Version V001  :  2013.03.01 (Tung)        New Create      */
/* * ****************************************************************** */

class Bloomberg extends Admin {

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
        $this->load->library('curl');
        set_time_limit(0);
    }

    public function index(){
        if($this->input->is_ajax_request()){
            $now = time();
            $response = array(
                'report' => ''
            );
            $this->db->select('code');
            $this->db->where('id <=' , 6);
            $codes = $this->db->get('cur_ref')->result_array();        
            $headers = $this->db->list_fields('vndb_currency');
            $titles = implode(chr(9), $headers) . PHP_EOL;
            $i = 0;
            $check = 0;
            $curl = new curl;
            foreach($codes as $code){
                if($check == 0){
                    $m = 'w';
                    $contents = $titles;
                }else{
                    $m = 'a';
                    $contents = '';
                }
                $data = array();
                $code = $code['code'];
                echo $code . '<br />';
                $url = 'http://www.bloomberg.com/apps/data?pid=webpxta&Securities='. $code . ':CUR&TimePeriod=5Y&Outfields=HDATE,PR004-H,PR005-H,PR006-H,PR007-H,PR008-H,PR013-H%22';
                $html = $curl->makeRequest('get', $url, NULL);
                $data = explode("\n", $html);
                for($a = 0; $a < 11; $a++){
                    array_shift($data);
                }
                array_pop($data);
                array_pop($data);
                foreach($data as $key => $item){
                    $data[$key] = trim($item);
                    $item = substr($item, 0, strlen($item) - 2);
                    $data[$key] = str_replace(array('" "', '"'), chr(9), $item);
                    $yyyymmdd =  substr($data[$key], 0, 8);
                    $data[$key] = date('Y-m-d', $now) . chr(9) . $code . chr(9) . $data[$key];
                    $temp = '';
                    $temp = explode(chr(9), $data[$key]);
                    if($code == 'JPYVND'){
                        $temp[3] /= 100;
                        $temp[4] /= 100;
                        $temp[5] /= 100;
                        $temp[6] /= 100;
                        $data[$key] = implode(chr(9), $temp);
                    }
                    $contents .= $data[$key] . PHP_EOL;
                    foreach($headers as $k => $header){
                        $data_insert[$i][$header] = $temp[$k];
                    }
                    $i++;
                    $this->db->where(array('code' => $code, 'yyyymmdd' => $yyyymmdd));
                    $this->db->delete('vndb_currency');
                }
                $path = $this->_host . 'IFRCVN\VNDB\METASTOCK\CURRENCY\BLOOMBERG\VNDB_CURRENCY_' . date('Ymd', $now) . '.txt';
                $f = fopen($path, $m);
                fwrite($f, $contents);
                fclose($f);
                $check++;
            }
            $this->db->insert_batch('vndb_currency', $data_insert);
            $path = $this->_host . 'IFRCDATA\IFRCVN\VNDB\METASTOCK\CURRENCY\BLOOMBERG\VNDB_CURRENCY_' . date('Ymd', $now) . '.txt';
            $f = fopen($path, 'w');
            fwrite($f, $contents);
            fclose($f);
            $response['report'][0]['task'] = 'Download';
            $response['report'][0]['time'] = time() - $now;
            $this->output->set_output(json_encode($response));
        }
        // $this->$this->template->write_view('content', 'bloomberg/index', $this->data);
        // $this->template->write('title', 'Back History - Action list');
        // $this->template->render();
    }

}