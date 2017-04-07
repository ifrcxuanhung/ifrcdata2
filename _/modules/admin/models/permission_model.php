<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Permission_model extends CI_Model {

    var $table = 'permission';

    function __construct() {
        parent::__construct();
    }

    //find resource by id
    public function find($id = NULL) {
        if (is_numeric($id)) {
            $this->db->where('role_id', $id);
        }
        $query = $this->db->get($this->table);
        return $query->row_array();
    }

    public function add($data = NULL) {
        return $this->db->insert($this->table, $data);
    }

    //update resource
    public function update($id = NULL, $data = NULL) {
        if (!is_numeric($id)) {
            return FALSE;
        }
        $this->db->where('role_id', $id);
        return $this->db->update($this->table, $data);
    }

    //delete resource
    public function delete($id = NULL) {
        if (!is_numeric($id)) {
            return FALSE;
        }
        $this->db->where('permission_id', $id);
        return $this->db->delete($this->table);
    }

    //check resource name exist
    public function check_exist($role_id = NULL) {
        if (!is_numeric($role_id)) {
            return FALSE;
        }
        $this->db->where('role_id =', $role_id);
        $query = $this->db->get($this->table);
        if ($query->num_rows() > 0)
            return TRUE;
        else
            return FALSE;
    }

}
