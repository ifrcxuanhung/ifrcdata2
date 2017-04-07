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

class Quick_caculation extends Admin {

    protected $data;
    private $cal_dates;

    function __construct() {
        parent::__construct();
        $this->load->model('Indexes_model', 'indexes_model');
        $this->load->model('Composition_model', 'composition_model');
        $this->load->model('Import_model', 'import_model');
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

        //$this->indexes_model->calculateStk_wgtOnIdx_composition();
        if ($render == TRUE) {
            $this->template->write_view('content', 'quick_caculation/quick_caculation_index', $this->data);
            $this->template->write('title', 'Quick Caculation ');
            $this->template->render();
        }
    }

}