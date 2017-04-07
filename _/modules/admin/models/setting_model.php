<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Setting_model extends CI_Model {

    var $table = 'setting';

    function __construct() {
        parent::__construct();
    }

    public function find($id = NULL) {
        if (is_numeric($id)) {
            $this->db->where('setting_id', $id);
        }
        $query = $this->db->get($this->table);
        return $query->result_array();
    }

    public function get_group($name) {
        if ($name == NULL)
            return FALSE;
        $this->db->where('group', $name);
        $query = $this->db->get($this->table);
        $data = $query->result();
        $temp = FALSE;
        if (is_array($data) == TRUE && count($data) > 0) {
            foreach ($data as $value) {
                $temp[$value->key] = $value->value;
            }
        }
        unset($data);
        return $temp;
    }

    public function add($data = NULL) {
        return $this->db->insert($this->table, $data);
    }

    public function update($data = NULL, $id = NULL) {
        if (!is_numeric($id)) {
            return FALSE;
        }
        $this->db->where('setting_id', $id);
        return $this->db->update($this->table, $data);
    }

    public function update_nxt_dates() {
        $sql = "UPDATE setting, idx_calendar, setting s
                SET setting.`value` = idx_calendar.currdate
                WHERE s.`key` = 'calculation_dates'
                AND s.`value` = idx_calendar.prevdate
                AND setting.`key` = 'nxt_date';";
        return $this->db->query($sql);
    }

    public function check_update_nxt_dates() {
        $sql = "SELECT idx_calendar.currdate
                FROM idx_calendar, setting
                WHERE setting.`key` = 'calculation_dates'
                AND setting.`value` = idx_calendar.prevdate LIMIT 1;";
        return $this->db->query($sql)->result_array();
    }

    public function check_update_nxt_dates_edit($calculation_dates) {
        $sql = "SELECT idx_calendar.currdate
                FROM idx_calendar
                WHERE idx_calendar.prevdate = '{$calculation_dates}' LIMIT 1;";
        return $this->db->query($sql)->result_array();
    }

    public function get_key_by_id($id) {
        $sql = "SELECT setting.`key`
                FROM setting
                WHERE setting.`setting_id` = '{$id}' LIMIT 1;";
        return $this->db->query($sql)->result_array();
    }

    public function get_min_max_idx_calender() {
        $sql = "SELECT MIN(idx_calendar.prevdate) as `min_date`,
                MAX(idx_calendar.currdate) as `max_date`
                FROM idx_calendar LIMIT 1;";
        return $this->db->query($sql)->result_array();
    }

    public function delete($id = NULL) {
        if (!is_numeric($id)) {
            return FALSE;
        }
        $this->db->where('setting_id', $id);
        return $this->db->delete($this->table);
    }

    public function check_key($key = NULL, $id = NULL) {
        if (!is_numeric($id)) {
            return FALSE;
        }
        $this->db->where('key', $key);
        $this->db->where('setting_id !=', $id);
        $query = $this->db->get($this->table);
        if ($query->num_rows() > 0)
            return FALSE;
        else
            return TRUE;
    }

}
