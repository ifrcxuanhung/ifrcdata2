<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');
define('PATH', str_replace("\\", "/", FCPATH));
/* load the MX_Router class */
require APPPATH . "third_party/MX/Controller.php";

class MY_Controller extends MX_Controller {

    protected $data;

    function __construct() {
        parent::__construct();
        // $this->db->cache_delete($this->router->fetch_class(), $this->router->fetch_method());
        // $this->db->simple_query('SET NAMES \'utf-8\'');
        $this->load->model('admin/Setting_model', 'setting_model');
        $this->registry->set('setting', $this->setting_model->get_group('setting'));
    }

}