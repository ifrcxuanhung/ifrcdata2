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

class Check_dowload extends Admin {

    protected $data;

    function __construct() {
        parent::__construct();
        $this->load->helper(array('my_array_helper', 'form'));
    }

    function index() {
        if ($this->input->post()) {
            $data_post = array();
            if ($_FILES['param']['tmp_name'] != '') {
                $data_post = file($_FILES['param']['tmp_name']);
                foreach ($data_post as $key => $value) {
                    $data_post[$key] = str_replace(chr(9), '=', trim($value));
                }
            }
            check_download($this->input->post('url'), $this->input->post('post'), $data_post, $this->input->post('path_output'));
            $this->data->result = 'done';
        }
        $this->data->title = trans('Check Dowload', TRUE);
        $this->template->write_view('content', 'check_dowload/index', $this->data);
        $this->template->write('title', $this->data->title);
        $this->template->render();
    }

}