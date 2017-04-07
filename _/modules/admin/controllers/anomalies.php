<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/* * ****************************************************************** */
/*     Client  Name  :  IFRC                                      */
/*     Project Name  :  cms v3.0                                     */
/*     Program Name  :  article.php                                      */
/*     Entry Server  :                                               */
/*     Called By     :  System                                       */
/*     Notice        :  File Code is utf-8                           */
/*     Copyright     :  IFRC                                         */
/* ------------------------------------------------------------------- */
/*     Comment       :  controller article                             */
/* ------------------------------------------------------------------- */
/*     History       :                                               */
/* ------------------------------------------------------------------- */
/*     Version V001  :  2012.01.07 (Tung)        New Create      */
/* * ****************************************************************** */

class Anomalies extends Admin {

    protected $data;

    function __construct() {
        parent::__construct();

        $this->load->Model('anomalies_model', 'manomalies');
        $this->load->helper(array('my_array_helper', 'form'));
    }

    function index() {
        if ($this->input->post()) {
            $table = $this->input->post('table');
            $column = $this->input->post('column');
            return json_encode($this->manomalies->excute($table, $column));
        } else {
            $this->data->title = 'Anomalies';
            $this->template->write_view('content', 'anomalies/index', $this->data);
            $this->template->write('title', 'Anomalies');
            $this->template->render();
        }
    }

    function test() {
        

    }

}