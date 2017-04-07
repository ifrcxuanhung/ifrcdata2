<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Etf_model extends CI_Model {

    function __construct() {
        parent::__construct();
        set_time_limit(0);
    }
    
    function load_data_country($path){
        $base_url = str_replace('/', '//', $path);
        $this->db->query("TRUNCATE TABLE ETFDB_COUNTRY");
        $this->db->query("LOAD DATA INFILE '{$base_url}' INTO TABLE ETFDB_COUNTRY FIELDS TERMINATED BY  '\t' IGNORE 1 LINES ()");
    }
    
    function load_data_screener($path){
        $base_url = str_replace('/', '//', $path);
        $this->db->query("TRUNCATE TABLE ETFDB_SCREENER");
        $this->db->query("LOAD DATA INFILE '{$base_url}' INTO TABLE ETFDB_SCREENER FIELDS TERMINATED BY  '\t' IGNORE 1 LINES ()");
    }
}
