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

class Indexes extends Admin {

    protected $data;

    function __construct() {
        parent::__construct();
        $this->load->model('Sysformat_model', 'sysformat_model');
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
//        $this->data->specs = $this->sysformat_model->Show_Jtable($table, "show", "headers", "overview", "page_specs", "list_specs", "T2Ajax", "&table=idx_specs");
//        $this->data->composition = $this->sysformat_model->Show_Jtable($table2, "show", "headers", "idx_composition", "page_composition", "list_composition", "", "&table=idx_composition");
        $this->template->write_view('content', 'indexes/indexes_index', $this->data);
        $this->template->write('title', 'Indexes');
        $this->template->render();
    }

    /*     * ************************************************************** */
    /*    Name ： download_file_csv                                                */
    /* --------------------------------------------------------------- */
    /*    Description  ：  this function will be called automatically  */
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

    function download_file_csv($table, $fields, $addtime, $path) {

        $data = $this->exportdata($table, $fields);
        ///name

        if ($fields == '*') {
            $data_fiels = $this->fetch("SHOW FIELDS FROM $table");
            unset($fields);
            foreach ($data_fiels as $v) {
                $fields.=$v['Field'] . chr(9);
            }
        }
        $csv_output .=$fields;
        $fields = explode(chr(9), $fields);

        // end name
        $csv_output .= "\n";
        foreach ($data as $v) {
            foreach ($fields as $f) {
                $csv_output.=$v[$f] . chr(9);
            }
            $csv_output .="\n";
        }
        $filename = "export_" . $table;
        if ($addtime != 0) {
            $addtime = "_" . date("Ymd-His");
        } else {
            $addtime = NULL;
        }
        $myFile = $path . $table . $addtime . ".txt";
        $fh = fopen($myFile, 'w') or die("can't open file");
        ;
        fwrite($fh, $csv_output);
        fclose($fh);
    }

    /*     * ************************************************************** */
    /*    Name ： exportstructure                                                */
    /* --------------------------------------------------------------- */
    /*    Description  ：  this function will be called automatically  */
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

    function exportstructure($table, $path) {
        $data = $this->showstructure($table);
        foreach ($data[0] as $k => $v) {
            $fields.=$k . chr(9);
        }
        ///name
        $csv_output .=$fields;
        $fields = explode(chr(9), $fields);
        // end name
        $csv_output .= "\n";
        foreach ($data as $v) {
            foreach ($fields as $f) {
                $csv_output.=$v[$f] . chr(9);
            }
            $csv_output .="\n";
        }
        $filename = "structure_";
        $myFile = $path . $filename . $table . ".txt";
        $fh = fopen($myFile, 'w') or die("can't open file");
        ;
        fwrite($fh, $csv_output);
        fclose($fh);
    }

}