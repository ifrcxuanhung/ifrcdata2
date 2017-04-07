<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Update_shares extends Admin {

    protected $data;

    function __construct() {
        parent::__construct();

        $this->load->model('Import_model', 'import_model');
        $this->load->model('Update_shares_model', 'updates_shares_model');
        $this->load->helper(array('my_array_helper', 'form'));
    }

    function index() {
        if ($this->input->is_ajax_request()) {
            set_time_limit(0);
            $start = microtime_float();
            $output = array();
            $data = array();
            //smooth
            $this->updates_shares_model->smooth_vndb_shares();
            //end smooth
            $output[0] = array('task' => trans('step_smooth_vndb_shares', 1), 'time' => number_format(microtime_float() - $start, 2));
            //import data
            $path = APPPATH . '../../assets/cache/upload/';
            $file = $path . 'vndb_shares_tuananh_result.txt';
            $table = 'vndb_shares_tuananh_result';
            if (file_exists($file)) {
                $data = fopen($file, "r");
                $this->import_model->emptyTable($table);
                $num = 0;
                $totalRows = 0;
                $line = 0; //line
                while (fgets($data) !== false) {
                    $totalRows++;
                }
                fclose($data);
                // lấy ra tồng số rows hiện tại
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
                            $this->import_model->insertData($table, $arr_database);
                            $arr_database = array();
                            $num = 0;
                        }
                    }
                    $num++;
                    $line++;
                }
                fclose($data);
            }
            //end import data
            $output[1] = array('task' => trans('step_import_data_vndb_shares', 1), 'time' => number_format(microtime_float() - $start, 2));
            //update column `shli_final` in table vndb_shares
            $this->updates_shares_model->update_vndb_shares();
            //end update
            $output[2] = array('task' => trans('step_update_data_vndb_shares', 1), 'time' => number_format(microtime_float() - $start, 2));
            $this->output->set_output(json_encode($output));
        }
    }

}