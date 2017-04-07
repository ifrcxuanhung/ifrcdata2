<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Calendar extends Admin {

    protected $data;

    function __construct() {
        parent::__construct();
        $this->load->model('download_model', 'mdownload');
    }

    function index($time = '') {
        $now = time();
        // $dir = $this->_host . 'IFRCVN\VNDB\METASTOCK\CURRENCY\\';
        $query = "SELECT DISTINCT(`date`) AS `date` FROM vndb_reference_daily";
        $data = $this->db->query($query)->result_array();
        // pre($data);die();
        foreach($data as $key => $item){
            $date[$key]['date'] = $item['date'];
            if($key >= 1){
                $date[$key]['prv_date'] = $data[$key - 1]['date'];
            }
            if($key < count($data) - 1){
                $date[$key]['nxt_date'] = $data[$key + 1]['date'];
            }
        }
        foreach($date as $item){
            $where['date'] = $item['date'];
            $this->mdownload->update_exc('bkh_calendar', $item, $where);
        }
        $this->export();
        echo time() - $now;
    }

    function export(){
        // $now = time();
        $file = '\\\LOCAL\IFRCVN\IMS\IMSTXT\IDX_CALENDAR.VN.txt';
        $data = $this->db->get('bkh_calendar')->result_array();
        $headers = array_keys(current($data));
        array_pop($headers);
        $contents = implode(chr(9), $headers) . PHP_EOL;
        unset($headers);
        foreach($data as $item){
            array_pop($item);
            $value = str_replace('-', '/', $item);
            $contents .= implode(chr(9), $value) . PHP_EOL;
        }
        unset($data);
        $f = fopen($file, 'w');
        fwrite($f, $contents);
        fclose($f);
        redirect(admin_url());
        // echo $contents;die();
        // echo time() - $now;
    }
}
