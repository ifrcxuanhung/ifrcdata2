<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Group_model extends CI_Model {

    var $table = 'group';

    function __construct() {
        parent::__construct();
    }

    public function find($id = NULL) {
        if (is_numeric($id)) {
            $this->db->where('id', $id);
        }
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
        $this->db->where('id', $id);
        return $this->db->update($this->table, $data);
    }

    public function delete($id = NULL) {
        if (!is_numeric($id)) {
            return FALSE;
        }
        $this->db->where('id', $id);
        return $this->db->delete($this->table);
    }

    public function check_name($key = NULL, $id = NULL) {
        if (!is_numeric($id)) {
            return FALSE;
        }
        $this->db->where('name', $key);
        $this->db->where('id !=', $id);
        $query = $this->db->get($this->table);
        if ($query->num_rows() > 0)
            return FALSE;
        else
            return TRUE;
    }

    public function get_user_group($id = NULL) {
        if (!is_numeric($id)) {
            return FALSE;
        }
        $this->db->where('user_id', $id);
        $data = $this->db->get('user_group');
        return $data->row();
    }

}
