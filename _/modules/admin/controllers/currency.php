<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Currency extends Admin {

    protected $data;

    function __construct() {
        parent::__construct();
        $this->load->model('download_model', 'mdownload');
    }

    // function index($time = '') {
    //     $now = time();
    //     $dir = $this->_host . 'IFRCVN\VNDB\METASTOCK\CURRENCY\\';
    //     $files = glob($dir . 'VNDB_CURRENCY_*.txt');
    //     arsort($files);
    //     $files = array_shift($files);        


    //     $files = str_replace('\\', '\\\\', $files);
    //     $this->db->truncate('idx_currency_daily');
    //     $this->db->query('LOAD DATA LOCAL INFILE "' . $files . '" INTO TABLE idx_currency_daily IGNORE 1 LINES');
    //     // $where = array(
    //     //     'date' => $date
    //     // );
    //     $query = "SELECT `yyyymmdd` AS `date`, `code` AS `cur_code`, `popn` AS `open`, `phgh` AS `high`, `plow` AS `low`, `pcls` AS `close`, LEFT(code, 3) AS `cur_from` FROM idx_currency_daily";
    //     $data = $this->db->query($query)->result_array();
    //     $limit = 0;
    //     // pre($data);die();
    //     $count = count($data);
    //     $i = 0;
    //     while($limit <= $count){
    //         $this->db->close();
    //         $this->db->initialize();
    //         if(!empty($data)){
    //             $check = 0;
    //             foreach($data as $key => $item){
    //                 $i++;
    //                 if($check == 1000) break;
    //                 $check++;
    //                 $date = $item['date'];
    //                 $date = substr($date, 0, 4) . '-' . substr($date, 4, 2) . '-' . substr($date, 6, 2);
    //                 $where = array(
    //                     'date' => $date,
    //                     'cur_code' => $item['cur_code']
    //                 );
    //                 $this->mdownload->update_exc('idx_currency', $item, $where);
    //                 echo $i . '<br />';
    //                 unset($data[$key]);
    //             }
    //         }

    //         $limit += 1000;
    //     }
    //     $this->export();
    //     echo time() - $now;
    //     // $this->load->view()
    // }

    function index($time = '') {
        $now = time();
        $dir = $this->_host . 'IFRCVN\VNDB\METASTOCK\CURRENCY\\';
        $files = glob($dir . 'VNDB_CURRENCY_*.txt');
        arsort($files);
        $files = array_shift($files);        


        $files = str_replace('\\', '\\\\', $files);
        $this->db->truncate('idx_currency_daily');
        $this->db->query('LOAD DATA LOCAL INFILE "' . $files . '" INTO TABLE idx_currency_daily IGNORE 1 LINES');
        $this->data->count = $this->db->count_all_results('idx_currency_daily');
        $this->template->write_view('content', 'currency/index', $this->data);
        $this->template->render();
        
    }

    public function import_table(){
        $now = time();
        $start = $this->input->post('start');
        $offset = $this->input->post('offset');
        $query = "SELECT `cur_code`, `date`,`open`, `high`,`low`, `close`, LEFT(cur_code, 3) AS `cur_from` FROM idx_currency_daily LIMIT $start, $offset";
        $data = $this->db->query($query)->result_array();
        $i = 0;
        if(!empty($data)){
            foreach($data as $key => $item){
                $i++;
                $date = $item['date'];
                pre($date);
                $date = substr($date, 0, 4) . '-' . substr($date, 5, 2) . '-' . substr($date, 8, 2);
                $where = array(
                    'date' => $date,
                    'cur_code' => $item['cur_code']
                );
                $this->mdownload->update_exc('idx_currency', $item, $where);
				
                unset($data[$key]);
                if($i == 100) break;
            }
			$query="update idx_currency set cur_to= RIGHT(cur_code, 3)";
			$this->db->query($query);
        }
        //$this->output->set_output($i);

    }
    public function export(){
        // $now = time();
        $file = '\\\LOCAL\IFRCVN\IMS\IMSTXT\idx_currency.vn.txt';
        $data = $this->db->get('idx_currency')->result_array();
        $header = array_keys(current($data));
        array_pop($header);
        $contents = implode(chr(9), $header) . PHP_EOL;
        foreach($data as $item){
            array_pop($item);            
            $item['date'] = str_replace('-', '/', $item['date']);
            $contents .= implode(chr(9), $item) . PHP_EOL;
        }
        $f = fopen($file, 'w');
        fwrite($f, $contents);
        fclose($f);
        redirect(admin_url());
        // echo time() - $now;
    }

}
