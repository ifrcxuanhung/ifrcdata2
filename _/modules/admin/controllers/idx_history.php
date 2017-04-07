<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/* * ****************************************************************** */
/*     Client  Name  :  IFRC                                      */
/*     Project Name  :  cms v3.0                                     */
/*     Program Name  :  home.php                                      */
/*     Entry Server  :                                               */
/*     Called By     :  System                                       */
/*     Notice        :  File Code is utf-8                           */
/*     Copyright     :  IFRC                                         */
/* ------------------------------------------------------------------- */
/*     Comment       :  controller home                             */
/* ------------------------------------------------------------------- */
/*     History       :                                               */
/* ------------------------------------------------------------------- */
/*     Version V001  :  2012.09.28 (Tung)        New Create      		 */
/* * ****************************************************************** */

class Idx_history extends Admin {

    public function __construct() {
        parent::__construct();
        //$this->load->Model('Idx_histoday_model', 'idx_histoday_model');
    }

    public function index() {
        echo 123;

        $this->data->title = 'Index Histoday';
        $this->template->write('title', 'Index Histoday');

        $this->template->write_view('content', 'idx_histoday/idx_histoday_index', $this->data);
        $this->template->render();
    }

}