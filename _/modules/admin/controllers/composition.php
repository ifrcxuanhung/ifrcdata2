<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/* * ****************************************************************** */
/*     Client  Name  :  IFRC                                      */
/*     Project Name  :  IMS v2.0                                     */
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

class Composition extends Admin {

    protected $data;

    function __construct() {
        parent::__construct();
        $this->load->model('Composition_model', 'composition_model');
    }

    /*     * ************************************************************** */
    /*    Name ： index                                                */
    /* --------------------------------------------------------------- */
    /*    Description  ：  this function will be called automatically  */
    /*                   when the controller is called               */
    /* --------------------------------------------------------------- */
    /*    Params  ：  None                                             */
    /* --------------------------------------------------------------- */
    /*    Return  ：                                                 */
    /* --------------------------------------------------------------- */
    /*    Warning ：                                                  */
    /* --------------------------------------------------------------- */
    /*    Copyright : IFRC                                         */
    /* --------------------------------------------------------------- */
    /*    M001 : New  2012.09.04 (LongNguyen)                            */
    /*     * ************************************************************** */

    function index() {
        $table = "idx_specs";
        $table2 = "idx_composition";
        $this->data->specs = $this->sysformat_model->Show_Jtable($table, "show", "headers", "overview", "page_specs", "list_specs", "T2Ajax", "&table=idx_specs");
        $this->data->composition = $this->sysformat_model->Show_Jtable($table2, "show", "headers", "idx_composition", "page_composition", "list_composition", "", "&table=idx_composition");
        $this->template->write_view('content', 'indexes/indexes_index', $this->data);
        $this->template->write('title', 'Indexes');
        $this->template->render();
    }

    function calculs() {
        $cache = $this->composition_model->get_idx_specs();
        $this->composition_model->cal_composition($cache, $_GET['h'], $_GET['m'], $_GET['s']);
        $this->composition_model->ins_intraday_compo();

    }

}