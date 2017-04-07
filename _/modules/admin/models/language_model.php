<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Language_model extends CI_Model {

    var $table = 'language';

    function __construct() {
        parent::__construct();
    }

    public function find($id = NULL, $where = NULL) {
        if (is_numeric($id)) {
            $this->db->where($this->table . '.language_id', $id);
        }
        if ($where != NULL && is_array($where) == TRUE) {
            $this->db->where($where);
        }
        $this->db->order_by($this->table . '.sort_order', 'asc');
        $query = $this->db->get($this->table);
        return $query->result_array();
    }

    public function add($data = NULL) {
        return $this->db->insert($this->table, $data);
    }

    public function update($data = NULL, $id = NULL) {
        if (!is_numeric($id)) {
            return FALSE;
        }
        $this->db->where('language_id', $id);
        return $this->db->update($this->table, $data);
    }

    public function delete($id = NULL) {
        if (!is_numeric($id)) {
            return FALSE;
        }
        $this->db->where('language_id', $id);
        return $this->db->delete($this->table);
    }

    public function change_active($id = NULL, $text = NULL) {
        if (!is_numeric($id)) {
            return FALSE;
        }
        $sql = "UPDATE `language`
                SET `language`.`status` = IF (`language`.`status` = 1, 0, 1)
                WHERE `language`.`language_id` = '{$id}'";
        $this->db->query($sql);
        $text = ($text == "Enable") ? "Disable" : "Enable";
        return $text;
    }

    public function check_code($key = NULL, $id = NULL) {
        if (!is_numeric($id)) {
            return FALSE;
        }
        $this->db->where('code', $key);
        $this->db->where('language_id !=', $id);
        $query = $this->db->get($this->table);
        if ($query->num_rows() > 0)
            return FALSE;
        else
            return TRUE;
    }

}
