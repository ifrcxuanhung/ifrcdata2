<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/* * ******************************************************************************************************************* *
 * 	 																													 *
 *																														 *
 *																														 *
 *													Author: Minh Đẹp Trai			 									 *
 *																														 *
 *																														 *
 *																														 *
 * * ******************************************************************************************************************* */
class Daily_action extends Admin {
    public function __construct() {
        parent::__construct();
    }
	public function index() {
        $this->template->write_view('content', 'daily_action/daily_rept', $this->data);
        $this->template->write('title', 'Report Month');
        $this->template->render();
    }

}