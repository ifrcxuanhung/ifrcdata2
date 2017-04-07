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
/*     Comment       :  model article                             */
/* ------------------------------------------------------------------- */
/*     History       :                                               */
/* ------------------------------------------------------------------- */
/*     Version V001  :  2012.08.14 (Tung)        New Create      */
/* * ****************************************************************** */

class Statistics_model extends CI_Model {
    function __construct() {
        parent::__construct();
    }

    public function shliStatistics() {
        //if ($this->input->is_ajax_request()) {
            $from = microtime(true);
            //$f = fopen($_SERVER['DOCUMENT_ROOT'] . '/ifrcdata/assets/test/a.php', 'a');
            set_time_limit(0);
            $list_ticker = $this->db->query("select DISTINCT ticker from shli_statistics order by ticker asc")->result_array();
            foreach ($list_ticker as $vlist_ticker) {
                $data_update = array();
                $arr = $this->db->query("select id,FPT,VSTX,VSTH, shli from shli_statistics where ticker = '{$vlist_ticker['ticker']}'  ORDER BY `date` asc")->result_array();
                // $arr = $this->db->query("select id,VSTH from shli_statistics where ticker = '{$vlist_ticker['ticker']}'  ORDER BY `date` asc")->result_array();
               $arr_plus = array('VSTX', 'FPT', 'VSTH', 'shli');
                // $arr_plus = array('FPT');
                $max = count($arr);
                foreach($arr_plus as $value_arr_plus){
                    for ($i = 0; $i < $max - 1; $i++) {
                        //foreach ($arr_plus as $value_arr_plus) {
                            if ($arr[$i][$value_arr_plus] != 0 && $arr[$i][$value_arr_plus] != '') {
                                for ($j = $i + 1; $j < $max; $j++) {
                                    if ($arr[$j][$value_arr_plus] != 0 && $arr[$j][$value_arr_plus] != '') {
                                        if ($arr[$j][$value_arr_plus] == $arr[$i][$value_arr_plus]) {
                                            
                                            for ($k = $i + 1; $k < $j; $k++) {
                                                // $contents = $value_arr_plus . ' ' . $i . ' ' . $j . PHP_EOL;
                                                // fwrite($f, $contents);
                                                //$arr[$k][$value_arr_plus] = $arr[$i][$value_arr_plus];
                                                $data_update[$k]['id'] = $arr[$k]['id'];
                                                $data_update[$k][$value_arr_plus] = $arr[$i][$value_arr_plus];
                                                //pre($data_update[$k]);
                                                
                                            }
                                        }
                                        $i = $j - 1;
                                        break;
                                    }
                                }
                            }
                        //}
                    }
                }
                //pre($data_update);
                if(!empty($data_update)){
                    //pre($data_update);
                    $this->db->update_batch('shli_statistics', $data_update, 'id');
                }
            }
            $total = microtime(true) - $from;
            echo $total;
        //}
    }

    public function shouStatistics() {
        //if ($this->input->is_ajax_request()) {
            $from = microtime(true);
            //$f = fopen($_SERVER['DOCUMENT_ROOT'] . '/ifrcdata/assets/test/a.php', 'a');
            set_time_limit(0);
            $list_ticker = $this->db->query("select DISTINCT ticker from vndb_shares order by ticker asc")->result_array();
            foreach ($list_ticker as $vlist_ticker) {
                $data_update = array();
                // $arr = $this->db->query("select id,shou_fpt,shou_cafef,shou_vstx,shou_vsth,shou_stp from vndb_shares where ticker = '{$vlist_ticker['ticker']}'  ORDER BY `date` asc")->result_array();
                $arr = $this->db->query("select id,shli_fpt,shli_cafef,shli_vstx,shli_vsth,shli_exc,shli_final,shli_final,shou_cafef,shou_vstx,shou_vsth,shou_exc,shou_final from vndb_shares where ticker = '{$vlist_ticker['ticker']}'  ORDER BY `date` asc")->result_array();
                // $arr = $this->db->query("select id,VSTH from shli_statistics where ticker = '{$vlist_ticker['ticker']}'  ORDER BY `date` asc")->result_array();
                $arr_plus = array('shli_fpt','shli_cafef','shli_vstx','shli_vsth','shli_exc','shli_final');
                // $arr_plus = array('shli_fpt','shli_cafef','shli_vstx','shli_vsth','shli_exc','shli_final','shou_cafef','shou_vstx','shou_vsth','shou_exc','shou_final');
                // $arr_plus = array('FPT');
                $max = count($arr);
                foreach($arr_plus as $value_arr_plus){
                    for ($i = 0; $i < $max - 1; $i++) {
                        //foreach ($arr_plus as $value_arr_plus) {
                            if ($arr[$i][$value_arr_plus] != 0 && $arr[$i][$value_arr_plus] != '') {
                                for ($j = $i + 1; $j < $max; $j++) {
                                    if ($arr[$j][$value_arr_plus] != 0 && $arr[$j][$value_arr_plus] != '') {
                                        if ($arr[$j][$value_arr_plus] == $arr[$i][$value_arr_plus]) {
                                            
                                            for ($k = $i + 1; $k < $j; $k++) {
                                                // $contents = $value_arr_plus . ' ' . $i . ' ' . $j . PHP_EOL;
                                                // fwrite($f, $contents);
                                                //$arr[$k][$value_arr_plus] = $arr[$i][$value_arr_plus];
                                                $data_update[$k]['id'] = $arr[$k]['id'];
                                                $data_update[$k][$value_arr_plus] = $arr[$i][$value_arr_plus];
                                                //pre($data_update[$k]);
                                                
                                            }
                                        }
                                        $i = $j - 1;
                                        break;
                                    }
                                }
                            }
                        //}
                    }
                }
                //pre($data_update);
                if(!empty($data_update)){
                    //pre($data_update);
                    $this->db->update_batch('vndb_shares', $data_update, 'id');
                }
            }
            $total = microtime(true) - $from;
            echo $total;
        //}
    }


}