<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/* * *******************************************************************************************************************
 * Client  Name ：  IFRC
 * ---------------------------------------------------------------------------------------------------------------------
 * Project Name ：  ims
 * ---------------------------------------------------------------------------------------------------------------------
 * Program Name ：  testing_model.php
 * ---------------------------------------------------------------------------------------------------------------------
 * Entry Server ：
 * ---------------------------------------------------------------------------------------------------------------------
 * Called By    ：  System
 * ---------------------------------------------------------------------------------------------------------------------
 * Notice       ：
 * ---------------------------------------------------------------------------------------------------------------------
 * Copyright    ：  IFRC
 * ---------------------------------------------------------------------------------------------------------------------
 * Comment      ：  model testing
 * ---------------------------------------------------------------------------------------------------------------------
 * History      ：
 * ---------------------------------------------------------------------------------------------------------------------
 * Version V001 ：  2012.10.02 (LongNguyen)        New Create
 * ******************************************************************************************************************* */

class Testing_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    /*     * ***********************************************************************************************************
     * Name         ： compareIdx_specsIdx_specs_check
     * -----------------------------------------------------------------------------------------------------------------
     * Description  ：kiểm tra xem table có tồn tại chưa
     * -----------------------------------------------------------------------------------------------------------------
     * Params       ：  POST $nameTable
     * -----------------------------------------------------------------------------------------------------------------
     * Return       ：bool true on table exist or false if table not exist
     * -----------------------------------------------------------------------------------------------------------------
     * Warning      ：
     * -----------------------------------------------------------------------------------------------------------------
     * Copyright    ： IFRC
     * -----------------------------------------------------------------------------------------------------------------
     * M001         ： New  2012.09.12 (LongNguyen)
     * *************************************************************************************************************** */

    public function compareIdx_specsIdx_specs_check($folder) {
        $sql = "SELECT idx_code FROM `idx_specs` where idx_code not in (select idx_code from idx_specs_check)";
        $result = array();
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            echo 1;
            return $result['warning'] = 'idx_specs and idx_specs_check not match';
        }
        $sql = "SELECT idx_code FROM `idx_specs_check` where idx_code not in (select idx_code from idx_specs)";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            echo 2;
            return $result['warning'] = 'idx_specs and idx_specs_check not match';
        }
        if ($this->db->count_all('idx_specs_check') != $this->db->count_all('idx_specs')) {
            echo 3;
            return $result['warning'] = 'idx_specs and idx_specs_check not match';
        }
        $arr['folder'] = $folder;
        $sql = "SELECT max(idx_specs.`idx_last`-idx_specs_check.`idx_last`) as `max`,idx_specs.`idx_code` FROM `idx_specs`,idx_specs_check WHERE  idx_specs.`idx_code`=idx_specs_check.`idx_code` group by idx_specs.`idx_code` ORDER BY `max` DESC limit 1";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $result['idx_last']['idx_code'] = $query->row()->idx_code;
            $result['idx_last']['max'] = round($query->row()->max, 6);
            $result['idx_last']['status'] = round($query->row()->max, 6) > $this->registry->setting['epsilon_idx_last'] ? 1 : 0;
            $result['idx_last']['epsilon'] = $this->registry->setting['epsilon_idx_last'];
            $arr = $result['idx_last'];
            $arr['folder'] = $folder;
            $arr['field'] = 'idx_last';
            self::_insertReport($arr);
        }

        $sql = "SELECT max(idx_specs.`idx_mcap`-idx_specs_check.`idx_mcap`) as `max`,idx_specs.`idx_code` FROM `idx_specs`,idx_specs_check WHERE  idx_specs.`idx_code`=idx_specs_check.`idx_code` group by idx_specs.`idx_code` ORDER BY `max` DESC limit 1";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $result['idx_mcap']['idx_code'] = $query->row()->idx_code;
            $result['idx_mcap']['max'] = round($query->row()->max, 4);
            $result['idx_mcap']['status'] = round($query->row()->max, 4) > $this->registry->setting['epsilon_idx_mcap'] ? 1 : 0;
            $result['idx_mcap']['epsilon'] = $this->registry->setting['epsilon_idx_mcap'];
            $arr = $result['idx_mcap'];
            $arr['folder'] = $folder;
            $arr['field'] = 'idx_mcap';
            self::_insertReport($arr);
        }

        $sql = "SELECT max(idx_specs.`idx_dcap`-idx_specs_check.`idx_dcap`) as `max`,idx_specs.`idx_code` FROM `idx_specs`,idx_specs_check WHERE  idx_specs.`idx_code`=idx_specs_check.`idx_code` group by idx_specs.`idx_code` ORDER BY `max` DESC limit 1";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $result['idx_dcap']['idx_code'] = $query->row()->idx_code;
            $result['idx_dcap']['max'] = round($query->row()->max, 4);
            $result['idx_dcap']['status'] = round($query->row()->max, 4) > $this->registry->setting['epsilon_idx_dcap'] ? 1 : 0;
            $result['idx_dcap']['epsilon'] = $this->registry->setting['epsilon_idx_dcap'];

            $arr = $result['idx_dcap'];
            $arr['folder'] = $folder;
            $arr['field'] = 'idx_dcap';
            self::_insertReport($arr);
        }

        $sql = "SELECT max(idx_specs.`idx_dvar`-idx_specs_check.`idx_dvar`) as `max`,idx_specs.`idx_code` FROM `idx_specs`,idx_specs_check WHERE  idx_specs.`idx_code`=idx_specs_check.`idx_code` group by idx_specs.`idx_code` ORDER BY `max` DESC limit 1";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $result['idx_dvar']['idx_code'] = $query->row()->idx_code;
            $result['idx_dvar']['max'] = round($query->row()->max, 3);
            $result['idx_dvar']['status'] = round($query->row()->max, 3) > $this->registry->setting['epsilon_idx_dvar'] ? 1 : 0;
            $result['idx_dvar']['epsilon'] = $this->registry->setting['epsilon_idx_dvar'];

            $arr = $result['idx_dvar'];
            $arr['folder'] = $folder;
            $arr['field'] = 'idx_dvar';
            self::_insertReport($arr);
        }
        return $result;
    }

    public function _insertReport($data) {
        if (is_array($data) == TRUE) {
            $this->db->insert('report_testing', $data);
        }
    }

}
