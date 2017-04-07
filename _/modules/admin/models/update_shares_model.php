<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Update_shares_model extends CI_Model {

    function __construct() {
        parent::__construct();

    }

    function smooth_vndb_shares() {
        $this->db->query('SET innodb_lock_wait_timeout = 10000000');
        $sql = "SELECT `vndb_shares`.`ticker`, `vndb_shares`.`date`, `vndb_shares`.`shli_final`
                FROM `vndb_shares`
                ORDER BY `vndb_shares`.`ticker`, `vndb_shares`.`date`";
        $arr = $this->db->query($sql)->result_array();
        if (isset($arr) == TRUE && count($arr) > 0) {
            foreach ($arr as $key => $value) {
                if ($value['shli_final'] != 0) {
                    if (isset($temp_key) == FALSE) {
                        $temp_key = array('key' => $key,
                            'ticker' => $value['ticker'],
                            'value' => $value['shli_final']
                        );
                    } else {
                        if ($value['shli_final'] == $temp_key['value'] && $temp_key['ticker'] == $value['ticker']) {
                            for ($i = $temp_key['key']; $i <= $key; $i++) {
                                $arr[$i]['shli_final'] = $temp_key['value'];
                            }
                        } else {
                            $temp_key = array('key' => $key,
                                'ticker' => $value['ticker'],
                                'value' => $value['shli_final']
                            );
                        }
                    }
                }
            }
        }
        $sql = "DROP TABLE IF EXISTS `vndb_shares_tuananh_result`;";
        $this->db->simple_query($sql);
        $sql = "CREATE TABLE `vndb_shares_tuananh_result` (
                `ticker` char(8) DEFAULT NULL,
                `date` date DEFAULT NULL,
                `shli_final` double DEFAULT NULL,
                KEY `ticker` (`ticker`),
                KEY `date` (`date`)
                );";
        $this->db->simple_query($sql);
        $path = APPPATH . '../../assets/cache/upload/';
        $filename = 'vndb_shares_tuananh_result.txt';
        $path_file = $path . $filename;
        $headers = array('ticker', 'date', 'shli_final');
        $temp = '';
        foreach ($headers as $value) {
            $temp .= $value . chr(9);
        }
        $data[0] = trim($temp) . PHP_EOL;
        foreach ($arr as $key => $item) {
            $temp = '';
            foreach ($headers as $value) {
                $temp .= $item[$value] . chr(9);
            }
            $data[] = trim($temp) . PHP_EOL;
        }
        file_put_contents($path_file, $data);
    }

    function update_vndb_shares() {
        $this->db->query('SET innodb_lock_wait_timeout = 10000000');
        $sql = "UPDATE `vndb_shares`, `vndb_shares_tuananh_result`
                SET `vndb_shares`.`shli_final` = `vndb_shares_tuananh_result`.`shli_final`
                WHERE `vndb_shares`.`ticker`  = `vndb_shares_tuananh_result`.`ticker`
                AND `vndb_shares`.`date`  = `vndb_shares_tuananh_result`.`date`";
        $this->db->query($sql);
    }

}
