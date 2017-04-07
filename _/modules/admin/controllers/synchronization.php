<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/* * ****************************************************************** 
/*     Client  Name  :  IFRC                                      
/*     Project Name  :  cms v3.0                                     
/*     Program Name  :  Synchronization.php                                      
/*     Entry Server  :                                               
/*     Called By     :  System                                       
/*     Notice        :  File Code is utf-8                           
/*     Copyright     :  IFRC                                         
/* ------------------------------------------------------------------- 
/*     Comment       :  controller page                             
/* ------------------------------------------------------------------- 
/*     History       :                                               
/* ------------------------------------------------------------------- 
/*     Version V001  :  2013.08.06 (Minh Đẹp Trai)        New Create      
/* * ****************************************************************** */

class Synchronization extends Admin {

    function __construct() {
        parent::__construct();
        $this->load->Model('synchronization_model', 'msynch');
    }

    function index() {
        $this->data->info = $this->msynch->list_all();
        $this->template->write_view('content', 'synchronization/index', $this->data);
        $this->template->write('title', 'Synchronization');
        $this->template->render();
    }
    function synchronization_data() {
        if ($this->input->is_ajax_request()) {
        	$from = microtime(true);
            $data_id = $this->input->post('data_id');
            $arr_id = explode(',',$data_id);
            $host = '';
            $password = '';
            foreach($arr_id as $id){
                $data_search = $this->msynch->search($id);
                //if($data_search['type'] == ''){
                $this->msynch->duplicateTables($data_search);
                // }else{
                //     if($host != '' && $pass != ''){
                //         $this->update_encryption($host,$pass);
                //         $data_encryption = $this->encryption($data_search['type']);
                //     }else{
                //         $data_encryption = $this->encryption($data_search['type']);
                //         $host = $data_encryption[0];
                //         $pass = $data_encryption[2];
                //     }
                //     $this->msynch->duplicateTables_host($data_encryption,$data_search['from_db'],$data_search['to_db'],$data_search['from_tb'],$data_search['to_tb']);
                // }
            }
        	$total = microtime(true) - $from;
            $response[0]['time'] = round($total, 2);
            $response[0]['task'] = 'Synchronization';
        	echo json_encode($response);
        }
    }
    function check_table(){
        if ($this->input->is_ajax_request()) {
            $data_id = $this->input->post('data_id');
            $arr_id = explode(',',$data_id);
            $data['error'] = array();
            $data['position'] = array();
            $data['host'] = array();
            $host = '';
            foreach($arr_id as $id){
                $data_search = $this->msynch->search($id);
                //if($data_search['type'] == ''){
                    $count = $this->db->query('SELECT count(*) as count FROM information_schema.tables WHERE table_schema = "'.$data_search['from_db'].'" AND table_name = "'.$data_search['from_tb'].'" LIMIT 1;')->row_array();
                    if($count['count'] == 0){
                        $data['position'][] = $id;
                        $data['error'][] = 'Table "'.$data_search['from_tb'].'" doesn\'t exist';
                    }
                //}else{
                    if($data_search['type'] != $host){
                        $data['host'][] = $data_search['type'];
                    }
                    $host = $data_search['type'];
                //}
            }
            echo json_encode($data);
        }
    }
    // function check_password(){
    //     if ($this->input->is_ajax_request()) {
    //         $host = $this->input->post('host');
    //         $password = $this->input->post('password');
    //         $password_encryption = hash('sha512', $this->input->post('password'));
    //         $data_encryption = $this->encryption($host);
    //         if($password_encryption == $data_encryption[1]){
    //             $data['check'] = 1;
    //             $this->update_encryption($data_encryption[0],$password);
    //         }else{
    //             $data['check'] = 0;
    //         }
    //         echo json_encode($data);
    //     }
    // }
    // function encryption($host){
    //     $result_data = file('//LOCAL/IFRCVN/VNDB/SYNCHRONIZATION/vndb_account.txt');
    //     $header = $result_data[0];
    //     unset($result_data[0]);
    //     $data_write = array();
    //     $flag = '';
    //     foreach($result_data as $data){
    //         $arr_data = explode(chr(9),$data);
    //         $check = preg_match("#^[0-9a-f]{128}$#i", $arr_data[2]);
    //         if($check == 0){
    //             $arr_data[2] = base64_encode($arr_data[2]);
    //             $flag = 1;
    //         }
    //         if($host == $arr_data[0]){
    //             if(isset($arr_data[3])){
    //                 $data_password =  array($arr_data[0],$arr_data[1],$arr_data[3]);
    //             }else{
    //                 $data_password =  array($arr_data[0],$arr_data[2]);
    //             }
    //         }
    //         $data_write[] = implode(chr(9),$arr_data);
    //     }
    //     if($flag != ''){
    //         array_unshift($data_write, trim($header));
    //         $implode = implode("\r\n", $data_write);
    //         $filename = '//LOCAL/IFRCVN/VNDB/SYNCHRONIZATION/vndb_account.txt';
    //         $create = fopen($filename, "w");
    //         $write = fwrite($create, $implode);
    //         fclose($create);                
    //     }
    //     return $data_password;
    // }
    // function update_encryption($host,$password){
    //     $result_data = file('//LOCAL/IFRCVN/VNDB/SYNCHRONIZATION/vndb_account.txt');
    //     $header = trim($result_data[0]).chr(9).'raw_password';
    //     unset($result_data[0]);
    //     $data_write = array();
    //     foreach($result_data as $data){
    //         $arr_data = explode(chr(9),$data);
    //         if($arr_data[0] == $host){
    //             $data_write[] = trim(implode(chr(9),$arr_data)).chr(9).$password;
    //         }else{
    //             $data_write[] = trim(implode(chr(9),$arr_data)).chr(9);
    //         }
    //     }
    //     array_unshift($data_write, trim($header));
    //     $implode = implode("\r\n", $data_write);
    //     $filename = '//LOCAL/IFRCVN/VNDB/SYNCHRONIZATION/vndb_account.txt';
    //     $create = fopen($filename, "w");
    //     $write = fwrite($create, $implode);
    //     fclose($create);                
    // }
}