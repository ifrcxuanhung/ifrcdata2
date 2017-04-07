<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Database_model extends CI_Model {

    public $tableStructure = 'database_structure';
    public $tableIndex = 'database_index';

    function __construct() {
        parent::__construct();
    }

    public function getStructure(){
        return $this->db->get($this->tableStructure)->result_array();
    }

    public function getIndex(){
        return $this->db->get($this->tableIndex)->result_array();
    }

}