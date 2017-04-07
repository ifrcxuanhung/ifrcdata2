<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/* * ****************************************************************** */
/*     Client  Name  :  IFRC                                      */
/*     Project Name  :  IMS v3.0                                     */
/*     Program Name  :  caculation.php                                      */
/*     Entry Server  :                                               */
/*     Called By     :  System                                       */
/*     Notice        :  File Code is utf-8                           */
/*     Copyright     :  IFRC                                         */
/* ------------------------------------------------------------------- */
/*     Comment       :  controller caculation                             */
/* ------------------------------------------------------------------- */
/*     History       :                                               */
/* ------------------------------------------------------------------- */
/*     Version V001  :  2012.09.04 (LongNguyen)        New Create      */
/* * ****************************************************************** */

class Backhistory_extractdata extends Admin {

    protected $data;
    private $cal_dates;

    function __construct() {
        parent::__construct();
        $this->load->model('Indexes_model', 'indexes_model');
        $this->load->model('Composition_model', 'composition_model');
        $this->load->model('Import_model', 'import_model');
        $this->load->model('Quick_calculation_model', 'quick_calculation_model');
    }

    /*     * ***********************************************************************************************************
     * Name         ： index
     * -----------------------------------------------------------------------------------------------------------------
     * Description  ：
     * -----------------------------------------------------------------------------------------------------------------
     * Params       ：
     * -----------------------------------------------------------------------------------------------------------------
     * Return       ：
     * -----------------------------------------------------------------------------------------------------------------
     * Warning      ：
     * -----------------------------------------------------------------------------------------------------------------
     * Copyright    ： IFRC
     * -----------------------------------------------------------------------------------------------------------------
     * M001         ： New  2012.09.12 (LongNguyen)
     * *************************************************************************************************************** */

    function index($render = TRUE) {
        $result = array();

        $this->quick_calculation_model->updateBkh_calcul_FromStk_history('bkh_calcul');

        if ($this->input->is_ajax_request()) {
            $this->output->set_output(json_encode($result));
        } else {
            if ($render == TRUE) {
                $this->template->write_view('content', 'backhistory_extractdata/backhistory_extractdata_index', $this->data);
                $this->template->write('title', 'Back History Extract Data');
                $this->template->render();
            }
        }
    }

}