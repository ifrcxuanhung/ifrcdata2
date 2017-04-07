<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/* * ****************************************************************** */
/*     Client  Name  :  IFRC                                      */
/*     Project Name  :  cms v3.0                                     */
/*     Program Name  :  import.php                                      */
/*     Entry Server  :                                               */
/*     Called By     :  System                                       */
/*     Notice        :  File Code is utf-8                           */
/*     Copyright     :  IFRC                                         */
/* ------------------------------------------------------------------- */
/*     Comment       :  controller import                             */
/* ------------------------------------------------------------------- */
/*     History       :                                               */
/* ------------------------------------------------------------------- */
/*     Version V001  :  2012.08.14 (LongNguyen)        New Create      */
/* * ****************************************************************** */

class Import extends Admin {

    function __construct() {
        parent::__construct();
        $this->load->model('Import_model', 'import_model');
    }

    /*     * **************************************************************
      /*    Name ： index
      /* ---------------------------------------------------------------
      /*    Description  ：  this function will be called automatically
      /*                   when the controller is called
      /* ---------------------------------------------------------------
      /*    Params  ：  None
      /* ---------------------------------------------------------------
      /*    Return  ：
      /* ---------------------------------------------------------------
      /*    Warning ：
      /* ---------------------------------------------------------------
      /*    Copyright : IFRC
      /* ---------------------------------------------------------------
      /*    M001 : New  2012.08.14 (LongNguyen)
      /*     * ************************************************************** */

    function index($title = NULL) {
        $this->load->helper('form');
        if ($title == NULL) {
            $title = 'Import >> Data';
        }
        $this->data->input = $this->input->post();
        $this->data->title = $title;
        $this->template->write_view('content', 'import/import_form', $this->data);
        $this->template->write('title', 'Import');
        $this->template->render();
    }

    /*     * ************************************************************** */
    /*    Name ： all                                                */
    /* --------------------------------------------------------------- */
    /*    Description  ：  call index with param title   */
    /* --------------------------------------------------------------- */
    /*    Params  ：  None                                             */
    /* --------------------------------------------------------------- */
    /*    Return  ：                                                 */
    /* --------------------------------------------------------------- */
    /*    Warning ：                                                  */
    /* --------------------------------------------------------------- */
    /*    Copyright : IFRC                                         */
    /* --------------------------------------------------------------- */
    /*    M001 : New  2012.09.12 (LongNguyen)                            */
    /*     * ************************************************************** */

    function all() {
        self::index('Import >> Data All');
    }

    /*     * ************************************************************** */
    /*    Name ： upload                                                */
    /* --------------------------------------------------------------- */
    /*    Description  ：  upload file txt|csv|png|zip to folder cache/upload   */
    /* --------------------------------------------------------------- */
    /*    Params  ：  None                                             */
    /* --------------------------------------------------------------- */
    /*    Return  ：                                                 */
    /* --------------------------------------------------------------- */
    /*    Warning ：                                                  */
    /* --------------------------------------------------------------- */
    /*    Copyright : IFRC                                         */
    /* --------------------------------------------------------------- */
    /*    M001 : New  2012.09.12 (LongNguyen)                            */
    /*     * ************************************************************** */

    function upload() {
        $config = NULL;
        $config['upload_path'] = './assets/cache/upload/';
        $config['allowed_types'] = 'txt|csv|png|zip';
        $config['max_size'] = 0;
        $config['overwrite'] = TRUE;
        $config['file_name'] = strtolower($_FILES['file']['name']);
        $this->load->library('upload', $config);
        if (!$this->upload->do_upload('file')) {
            $result = array('error' => $this->upload->display_errors());
        } else {
            $result = array('upload_data' => $this->upload->data());
        }
        $this->output->set_output(json_encode($result));
    }

    /*     * ***********************************************************************************************************
     * Name         ： listfile
     * -----------------------------------------------------------------------------------------------------------------
     * Description  ：list co active la 9 trong table idx_file
     * -----------------------------------------------------------------------------------------------------------------
     * Params       ：  None
     * -----------------------------------------------------------------------------------------------------------------
     * Return       ：json
     * -----------------------------------------------------------------------------------------------------------------
     * Warning      ：
     * -----------------------------------------------------------------------------------------------------------------
     * Copyright    ： IFRC
     * -----------------------------------------------------------------------------------------------------------------
     * M001         ： New  2012.09.12 (LongNguyen)
     * *************************************************************************************************************** */

    function listfile() {
        if ($this->input->is_ajax_request()) {
            $result = array();
            $list = $this->import_model->listUploadFile();
            if (is_array($list) == TRUE && count($list > 0)) {
                foreach ($list as $key => $value) {
                    $result[$key]['table'] = strtolower($value['table']);
                    $result[$key]['name'] = $value['file'];
                    $result[$key]['empty'] = $value['empty'];
                }
            }
            $this->output->set_output(json_encode($result));
        }
    }

    /*     * ***********************************************************************************************************
     * Name         ： checktable
     * -----------------------------------------------------------------------------------------------------------------
     * Description  ：check xem cac table can import co ton tai ko
     * -----------------------------------------------------------------------------------------------------------------
     * Params       ：  POST file , table , folder
     * -----------------------------------------------------------------------------------------------------------------
     * Return       ：json
     * -----------------------------------------------------------------------------------------------------------------
     * Warning      ：
     * -----------------------------------------------------------------------------------------------------------------
     * Copyright    ： IFRC
     * -----------------------------------------------------------------------------------------------------------------
     * M001         ： New  2012.09.12 (LongNguyen)
     * *************************************************************************************************************** */

    function checktable() {
        if ($this->input->is_ajax_request()) {
            $listFile = array();
            $file = $this->input->post('file');
            $table = strtolower($this->input->post('table'));
            $folder = strtolower($this->input->post('folder'));
            if (strpos($file, '.zip') !== false) {
                $file = "./assets/{$folder}/" . $file;
                if (file_exists($file)) {
                    // kiem tra xem co phai file zip ko , neyu phai thi un zip va gan $file = new file
                    if (strpos($file, '.zip') !== false) {
                        $zip = new ZipArchive;
                        if ($zip->open($file) === TRUE) {
                            $zip->extractTo('./assets/' . $folder . '/');
                            for ($i = 0; $i < $zip->numFiles; $i++) {
                                $listFile[] = $zip->getNameIndex($i);
                            }
                            $zip->close();
                        }
                    }
                }
            } else {
                $listFile[] = $file;
            }
            $result = array();
            foreach ($listFile as $key => $value) {
                $tableCheck = $table;
                if ($table == 'undefined') {
                    $tableCheck = substr($value, 0, (strrpos($value, ".")));
                }
                $result[$key]['name'] = $tableCheck;
                $result[$key]['file'] = $value;
                if ($this->import_model->checkTable($tableCheck) == TRUE) {
                    $result[$key]['status'] = 1;
                }
            }
            $this->output->set_output(json_encode($result));
        }
    }

    /*     * ***********************************************************************************************************
     * Name         ： insert
     * -----------------------------------------------------------------------------------------------------------------
     * Description  ：read file in folder and import to table
     * -----------------------------------------------------------------------------------------------------------------
     * Params       ：  POST file , table , empty . folder
     * -----------------------------------------------------------------------------------------------------------------
     * Return       ：json $result
     * -----------------------------------------------------------------------------------------------------------------
     * Warning      ：file txt chr(9) insert vaof table 1 lan 2000 rows
     * -----------------------------------------------------------------------------------------------------------------
     * Copyright    ： IFRC
     * -----------------------------------------------------------------------------------------------------------------
     * M001         ： New  2012.09.12 (LongNguyen)
     * *************************************************************************************************************** */

    function insert($input = NULL) {

        $results = array();
        $listFile = array();
        if ($this->input->is_ajax_request()) {
            if ($this->input->post('file')) {
                $input = NULL;
                // get param qua post
                $input['file'] = trim($this->input->post('file'));
                $input['table'] = trim($this->input->post('table'));
                $input['empty'] = trim($this->input->post('empty'));
                $input['folder'] = trim($this->input->post('folder'));
            }
        }
        if ($input != NULL) {
            //nếu ko folder ko có thì set folder default
            if ($input['folder'] == '') {
                $input['folder'] = 'cache/upload';
            }
            /* xu ly import */
            $file = "./assets/{$input['folder']}/" . $input['file'];
            if (file_exists($file)) {
                $listFile[0]['file'] = $file;
                $listFile[0]['table'] = $input['table'];
                // kiem tra xem file moi ton tai ko
                foreach ($listFile as $kfile => $files) {
                    $file = $files['file'];
                    $table = $files['table'];
                    if (file_exists($file)) {
                        $data = fopen($file, "r");
                        if ($input['empty'] == 'true') {
                            $this->import_model->emptyTable($table);
                        }
                        $num = 0;
                        $totalRows = 0;
                        $line = 0; //line
                        while (fgets($data) !== false) {
                            $totalRows++;
                        }
                        fclose($data);
                        // lấy ra tồng số rows hiện tại
                        $totalRowsTable = $this->import_model->getTotal($table);
                        $data = fopen($file, "r");
                        $arr_database = array();
                        while (!feof($data)) {
                            // neu la line 0
                            if ($line == 0) {
                                $row = fgets($data);
                                $col = explode(chr(9), $row);
                                $col2 = array();
                                foreach ($col as $value) {
                                    $col2[] = trim(strtolower($value));
                                }
                                $col = $col2; // cac fiels trong file upload
                                $feilds = $this->import_model->getFields($table);
                                $feilds = array_intersect($col, $feilds);
                                unset($row);
                            } else {
                                $dataOneRow = array();
                                // lay ra data cua 1 row
                                $row = str_getcsv(fgets($data), chr(9), '"', '\\');
                                // gan key vao value
                                foreach ($col as $key => $value) {
                                    $dataOneRow[$value] = isset($row[$key]) ? trim($row[$key]) : NULL;
                                }
                                // lay ra cac value co trong feilds cua table
                                foreach ($feilds as $key => $value) {
                                    $arr_database[$num][$value] = $dataOneRow[$value];
                                }
                                // kiem tra new $num = 2000 thi insert vo or het file
                                if ($num == 2000 || $line == ($totalRows - 1)) {
                                    $results[$kfile]['error'] = $this->import_model->insertData($table, $arr_database);
                                    if ($results[$kfile]['error'] != NULL) {
                                        break;
                                    }
                                    $arr_database = array();
                                    $num = 0;
                                }
                            }
                            $num++;
                            $line++;
                        }
                        fclose($data);
                        $results[$kfile]['numRows'] = $totalRows > 0 ? $totalRows - 1 : 0;
                        // lấy ra tồng số rows sau khi import
                        $results[$kfile]['totalTable'] = $this->import_model->getTotal($table);
                        $results[$kfile]['totalImport'] = $results[$kfile]['totalTable'] - $totalRowsTable;
                        $results[$kfile]['msg'] = 'Import success';
                        if ($results[$kfile]['error'] != NULL) {
                            $results[$kfile]['msg'] = 'Import Error';
                        }
                        $results[$kfile]['status'] = 1;
                    }
                }
            } else {
                $results[0]['status'] = 0;
                $results[0]['msg'] = 'file does not exists';
            }
        }
        if ($this->input->is_ajax_request()) {
            $this->output->set_output(json_encode($results));
        } else {
            return $results;
        }
    }

}