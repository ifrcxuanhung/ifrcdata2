<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Equity extends Admin {

    protected $data;
    public $idx_equity_day = "idx_equity_day";
    public $idx_equity_month = "idx_equity_month";
    public $idx_equity_year = "idx_equity_year";

    function __construct() {
        parent::__construct();
        $this->load->helper(array('my_array_helper', 'form'));
    }

    function index() {
        set_time_limit(0);
    }

    function index_old() {
        if ($this->input->is_ajax_request()) {
            set_time_limit(0);
            $path = "\\\LOCAL\IFRCVN\VFDB\\DATA\\";
            $output = array(
                'err' => ''
            );
            $arr_database = array();
            if (is_dir($path)) {
                $list_table = array($this->idx_equity_day);
                foreach ($list_table as $item) {
                    if (!$this->db->truncate($item)) {
                        break;
                    }
                    /* get file import */
                    $idx_equity = $path . "idx_equity_day.txt";
                    $data = fopen($idx_equity, 'r');
                    while (!feof($data)) {
                        $countRows = 0;
                        $totalRows = 0;
                        $line = 0;
                        while (fgets($data) !== false) {
                            $totalRows++;
                        }
                    }
                    fclose($data);
                    $data = fopen($idx_equity, "r");
                    while (!feof($data)) {
                        /* no header in file txt */
                        $feilds = array("0" => "code", "1" => "date", "2" => "close");
                        $dataOneRow = array();
                        $row = str_getcsv(fgets($data), chr(9), '"', '\\');
                        foreach ($feilds as $key => $value) {
                            $arr_database[$countRows][$value] = isset($row[$key]) ? trim($row[$key]) : NULL;
                        }
                        if ($countRows == 2000 || $line == ($totalRows - 1)) {
                            $this->db->insert_batch($item, $arr_database);
                            $countRows = 0;
                        }
                        $countRows++;
                        $line++;
                    }
                    fclose($data);
                }
            } else {
                $output['err'] = trans("error", 1);
            }
            $this->output->set_output(json_encode($output));
        }
    }

    function import_idx_equity_day() {
        if ($this->input->is_ajax_request()) {
            $path = "\\\LOCAL\IFRCVN\VFDB\DATA\\";
            $output = array(
                'err' => ''
            );
            if (is_dir($path)) {
                $file = "{$path}idx_equity_day.txt";

                $sql = "TRUNCATE TABLE {$this->idx_equity_day};";
                $this->db->query($sql);

                $sql = "LOAD DATA LOCAL INFILE'" . str_replace("\\", "\\\\", $file) . "'
                        INTO TABLE {$this->idx_equity_day}
                        FIELDS TERMINATED BY '\t'
                        LINES TERMINATED BY '\r\n';";
                $this->db->query($sql);
            } else {
                $output['err'] = trans("folder_is_not_exists", 1);
            }
            $this->output->set_output(json_encode($output));
        }
    }

    function import_idx_equity_month() {
        if ($this->input->is_ajax_request()) {
            $output = array(
                'err' => ''
            );

            $sql = "TRUNCATE TABLE {$this->idx_equity_month};";
            $this->db->query($sql);

            $sql = "SELECT code
                    FROM {$this->idx_equity_day}
                    GROUP BY code;";
            $list_code = $this->db->query($sql)->result_array();
            if (is_array($list_code) && count($list_code) > 0) {
                foreach ($list_code as $key => $value) {
                    $sql = "INSERT INTO {$this->idx_equity_month} (code, yyyymm, date, close)
                            SELECT {$this->idx_equity_day}.code, CONCAT(YEAR(temp.maxdate), SUBSTR(temp.maxdate, 6, 2)) AS yyyymm, temp.maxdate AS date, {$this->idx_equity_day}.close
                            FROM {$this->idx_equity_day}, (SELECT code, MAX(date) AS maxdate
                            FROM {$this->idx_equity_day}
                            WHERE code = '{$value['code']}'
                            AND !ISNULL(date)
                            GROUP BY MONTH(date), YEAR(date)) AS temp
                            WHERE {$this->idx_equity_day}.code = temp.code
                            AND {$this->idx_equity_day}.date = temp.maxdate;";
                    $this->db->query($sql);
                }
            } else {
                $output['err'] = trans("import_error", 1);
            }
        }
        $this->output->set_output(json_encode($output));
    }

    function import_idx_equity_year() {
        if ($this->input->is_ajax_request()) {
            $output = array(
                'err' => ''
            );

            $sql = "TRUNCATE TABLE {$this->idx_equity_year};";
            $this->db->query($sql);

            $sql = "SELECT code, YEAR(MAX(date)) AS yyyy, MAX(date) AS date
                    FROM {$this->idx_equity_day}
                    WHERE !ISNULL(date)
                    GROUP BY code, YEAR(date);";
            $data = $this->db->query($sql)->result_array();
            $this->db->insert_batch($this->idx_equity_year, $data);
            unset($data);

            $sql = "UPDATE {$this->idx_equity_year}, {$this->idx_equity_day}
                    SET {$this->idx_equity_year}.close = {$this->idx_equity_day}.close
                    WHERE {$this->idx_equity_year}.code = {$this->idx_equity_day}.code
                    AND {$this->idx_equity_year}.date = {$this->idx_equity_day}.date;";
            $err = $this->db->query($sql);

            if (isset($err) && !$err) {
                $output['err'] = trans("import_error", 1);
            }
        }
        $this->output->set_output(json_encode($output));
    }

    function test() {
        //if ($this->input->is_ajax_request()) {
        $output = array(
            'err' => ''
        );
        $sql = "TRUNCATE TABLE {$this->idx_equity_year};";
        $this->db->query($sql);

        $sql = "SELECT code, YEAR(MAX(date)) AS yyyy, MAX(date) AS date
                    FROM {$this->idx_equity_day}
                    WHERE !ISNULL(date)
                    GROUP BY code, YEAR(date);";
        $data = $this->db->query($sql)->result_array();
        $this->db->insert_batch($this->idx_equity_year, $data);
        unset($data);

        $sql = "UPDATE {$this->idx_equity_year}, {$this->idx_equity_day}
					SET {$this->idx_equity_year}.close = {$this->idx_equity_day}.close
					WHERE {$this->idx_equity_year}.code = {$this->idx_equity_day}.code
					AND {$this->idx_equity_year}.date = {$this->idx_equity_day}.date;";
        $this->db->query($sql);
    }

    //$this->output->set_output(json_encode($output));
}