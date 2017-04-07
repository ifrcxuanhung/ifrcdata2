<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Resource_model extends CI_Model {

    var $table = 'resource';

    function __construct() {
        parent::__construct();
    }

    //find resource by id
    public function find($id = NULL) {
        if (is_numeric($id)) {
            $this->db->where('resource_id', $id);
        }
        $query = $this->db->get($this->table);
        return $query->result_array();
    }

    //add resource
    public function add($data = NULL) {
        return $this->db->insert($this->table, $data);
    }

    //update resource
    public function update($data = NULL, $id = NULL) {
        if (!is_numeric($id)) {
            return FALSE;
        }
        $this->db->where('resource_id', $id);
        return $this->db->update($this->table, $data);
    }

    //delete resource
    public function delete($id = NULL) {
        if (!is_numeric($id)) {
            return FALSE;
        }
        $this->db->where('resource_id', $id);
        return $this->db->delete($this->table);
    }

    //check resource name exist
    public function check_name($module = NULL, $controller = NULL, $id = NULL) {
        if (!is_numeric($id)) {
            return FALSE;
        }
        $this->db->where('module', $module);
        $this->db->where('controller', $controller);
        $this->db->where('resource_id !=', $id);
        $query = $this->db->get($this->table);
        if ($query->num_rows() > 0)
            return FALSE;
        else
            return TRUE;
    }

}
