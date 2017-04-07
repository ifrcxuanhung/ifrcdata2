<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Quick_calculation_model extends CI_Model {

    public $table = 'category';
    public $category_description = 'category_description';
    private $cal_dates;

    /**     * ************************************************************* */
    /*    Name ： __construct                                                */
    /* --------------------------------------------------------------- */
    /*    Description  ： list articles by id (if exist) and language  */
    /* --------------------------------------------------------------- */
    /*    Params  ：                                               */
    /* --------------------------------------------------------------- */
    /*    Return  ：                        */
    /* --------------------------------------------------------------- */
    /*    Warning ：                                                     */
    /* --------------------------------------------------------------- */
    /*    Copyright : IFRC                                         */
    /* --------------------------------------------------------------- */
    /*    M001 : New  2012.09.05 (LongNguyen)                             */
    /*     * ************************************************************** */
    function __construct() {
        parent::__construct();
        $data = $this->registry->get('setting');
        $this->cal_dates = str_replace(' ', '', $data['calculation_dates']);
    }

    /**     * ************************************************************* */
    /*    Name ： del_indexes                                                */
    /* --------------------------------------------------------------- */
    /*    Description  ：   */
    /* --------------------------------------------------------------- */
    /*    Params  ：                                               */
    /* --------------------------------------------------------------- */
    /*    Return  ：                        */
    /* --------------------------------------------------------------- */
    /*    Warning ：                                                     */
    /* --------------------------------------------------------------- */
    /*    Copyright : IFRC                                         */
    /* --------------------------------------------------------------- */
    /*    M001 : New  2012.09.05 (LongNguyen)                             */
    /*     * ************************************************************** */
    function del_indexes($table) {
        $this->db->empty_table($table);
    }

    /**     * ************************************************************* */
    /*    Name ： insert_indexes                                                */
    /* --------------------------------------------------------------- */
    /*    Description  ：   */
    /* --------------------------------------------------------------- */
    /*    Params  ：                                               */
    /* --------------------------------------------------------------- */
    /*    Return  ：                        */
    /* --------------------------------------------------------------- */
    /*    Warning ：                                                     */
    /* --------------------------------------------------------------- */
    /*    Copyright : IFRC                                         */
    /* --------------------------------------------------------------- */
    /*    M001 : New  2012.09.05 (LongNguyen)                             */
    /*     * ************************************************************** */
    function insert_indexes($table, $arr) {
        if ($table == 'idx_specs') {
            if (isset($arr['idx_code']) == TRUE && $arr['idx_code'] != '')
                $this->db->insert($table, $arr);
        }else
            $this->db->insert($table, $arr);
    }

    /**     * ************************************************************* */
    /*    Name ： update_tables                                                */
    /* --------------------------------------------------------------- */
    /*    Description  ：   */
    /* --------------------------------------------------------------- */
    /*    Params  ：                                               */
    /* --------------------------------------------------------------- */
    /*    Return  ：                        */
    /* --------------------------------------------------------------- */
    /*    Warning ：                                                     */
    /* --------------------------------------------------------------- */
    /*    Copyright : IFRC                                         */
    /* --------------------------------------------------------------- */
    /*    M001 : New  2012.09.05 (LongNguyen)                             */
    /*     * ************************************************************** */
    function update_tables($table, $arr, $col) {
        $sql = "SELECT * FROM $table WHERE $col = '" . $arr[$col] . "'";
        $query = $this->db->query($sql);
        if ($query->num_rows() == 0) {
            $this->insert_indexes($table, $arr);
        }
        return 1;
    }

    /**     * ************************************************************* */
    /*    Name ： del_record                                                */
    /* --------------------------------------------------------------- */
    /*    Description  ：   */
    /* --------------------------------------------------------------- */
    /*    Params  ：                                               */
    /* --------------------------------------------------------------- */
    /*    Return  ：                        */
    /* --------------------------------------------------------------- */
    /*    Warning ：                                                     */
    /* --------------------------------------------------------------- */
    /*    Copyright : IFRC                                         */
    /* --------------------------------------------------------------- */
    /*    M001 : New  2012.09.05 (LongNguyen)                             */
    /*     * ************************************************************** */
    function del_record($table, $where) {
        $this->db->delete($table, $where);
    }

    /**     * ************************************************************* */
    /*    Name ： edit_tables                                                */
    /* --------------------------------------------------------------- */
    /*    Description  ：   */
    /* --------------------------------------------------------------- */
    /*    Params  ：                                               */
    /* --------------------------------------------------------------- */
    /*    Return  ：                        */
    /* --------------------------------------------------------------- */
    /*    Warning ：                                                     */
    /* --------------------------------------------------------------- */
    /*    Copyright : IFRC                                         */
    /* --------------------------------------------------------------- */
    /*    M001 : New  2012.09.05 (LongNguyen)                             */
    /*     * ************************************************************** */
    function edit_tables($table, $arr, $id) {
        $this->db->where('id', $id);
        $this->db->update($table, $arr);
    }

    /**     * ************************************************************* */
    /*    Name ： get_table_files                                                */
    /* --------------------------------------------------------------- */
    /*    Description  ：   */
    /* --------------------------------------------------------------- */
    /*    Params  ：                                               */
    /* --------------------------------------------------------------- */
    /*    Return  ：                        */
    /* --------------------------------------------------------------- */
    /*    Warning ：                                                     */
    /* --------------------------------------------------------------- */
    /*    Copyright : IFRC                                         */
    /* --------------------------------------------------------------- */
    /*    M001 : New  2012.09.05 (LongNguyen)                             */
    /*     * ************************************************************** */
    function get_table_files($where = NULL) {
        if ($where != NULL) {
            $sql = "SELECT * FROM idx_files WHERE $where";
        } else {
            $sql = "SELECT * FROM idx_files";
        }
        return $this->db->query($sql)->result_array();
    }

    /**     * ************************************************************* */
    /*    Name ： exportdata                                                */
    /* --------------------------------------------------------------- */
    /*    Description  ：   */
    /* --------------------------------------------------------------- */
    /*    Params  ：                                               */
    /* --------------------------------------------------------------- */
    /*    Return  ：                        */
    /* --------------------------------------------------------------- */
    /*    Warning ：                                                     */
    /* --------------------------------------------------------------- */
    /*    Copyright : IFRC                                         */
    /* --------------------------------------------------------------- */
    /*    M001 : New  2012.09.05 (LongNguyen)                             */
    /*     * ************************************************************** */
    function exportdata($table, $fields) {
        $sql = "SELECT $fields FROM $table ORDER BY id DESC";
        return $this->db->query($sql)->result_array();
    }

    /**     * ************************************************************* */
    /*    Name ： get_field_table                                                */
    /* --------------------------------------------------------------- */
    /*    Description  ：   */
    /* --------------------------------------------------------------- */
    /*    Params  ：                                               */
    /* --------------------------------------------------------------- */
    /*    Return  ：                        */
    /* --------------------------------------------------------------- */
    /*    Warning ：                                                     */
    /* --------------------------------------------------------------- */
    /*    Copyright : IFRC                                         */
    /* --------------------------------------------------------------- */
    /*    M001 : New  2012.09.05 (LongNguyen)                             */
    /*     * ************************************************************** */
    function get_field_table($table) {
        $data_fiels = $this->db->query("SHOW FIELDS FROM $table")->result_array();
        $fields = NULL;
        foreach ($data_fiels as $v) {
            $fields.=$v['Field'] . chr(9);
        }
        return $fields;
    }

    /**     * ************************************************************* */
    /*    Name ： showstructure                                                */
    /* --------------------------------------------------------------- */
    /*    Description  ：   */
    /* --------------------------------------------------------------- */
    /*    Params  ：                                               */
    /* --------------------------------------------------------------- */
    /*    Return  ：                        */
    /* --------------------------------------------------------------- */
    /*    Warning ：                                                     */
    /* --------------------------------------------------------------- */
    /*    Copyright : IFRC                                         */
    /* --------------------------------------------------------------- */
    /*    M001 : New  2012.09.05 (LongNguyen)                             */
    /*     * ************************************************************** */
    // show structure
    function showstructure($table) {
        $sql = "SHOW COLUMNS FROM $table";
        return $this->db->query($sql)->result_array();
    }

    /* phần tính cho Preparation--------------------------------------------------------------------------------------- */
    /*     * ***********************************************************************************************************
     * Name         ： inportDataIdx_compositionFromIdx_compo
     * -----------------------------------------------------------------------------------------------------------------
     * Description  ：import data table idx_composition from table idx_compo
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

    public function empty_Bkh_calcul($table) {
        self::del_indexes($table);
    }

    public function updateBkh_calcul_FromStk_history() {
        $sql = "INSERT INTO bkh_calcul (bkh_calcul.dates, bkh_calcul.stk_code, bkh_calcul.stk_shares,
                bkh_calcul.adjclose, bkh_calcul.`close`, bkh_calcul.divtr, bkh_calcul.divnr)
                SELECT stk_history.dates, stk_history.stk_code, stk_history.stk_shares,
                stk_history.adjclose, stk_history.`close`, stk_history.divtr, stk_history.divnr
                FROM stk_history
                INNER JOIN idx_list
                ON stk_history.stk_code = idx_list.stk_code
                AND idx_list.idx_code =
                (   SELECT qidx_ref.idx_code
                    FROM qidx_ref
                    WHERE qidx_ref.active = 1
                    limit 1
                )
                WHERE stk_history.dates >=
                (
                    SELECT qidx_ref.idx_start_date
                    FROM qidx_ref
                    WHERE qidx_ref.active = 1
                    limit 1
                )
                AND stk_history.dates <=
                (
                    SELECT qidx_ref.idx_end_date
                    FROM qidx_ref
                    WHERE qidx_ref.active = 1
                    limit 1
                ) ORDER BY stk_history.stk_code, stk_history.dates;";
        $this->db->query($sql);
        return TRUE;
    }

    public function updateCapi_Bkh_calcul() {
        $sql = "UPDATE bkh_calcul
                SET bkh_calcul.capi = bkh_calcul.stk_shares*bkh_calcul.`close`;";
        $this->db->query($sql);
    }

    public function importData_ScapiFromBkh_calcul() {
        $this->db->select('A3.dates, SUM(A3.capi) AS scapi');
        $this->db->group_by('A3.dates');
        $data = $this->db->get('bkh_calcul AS A3')->result_array();
        if (is_array($data) == TRUE && count($data) > 0) {
            return $this->db->insert_batch('_scapi', $data);
        }
        return TRUE;
    }

    public function updateWGTBkh_calcul() {
        $sql = "UPDATE bkh_calcul, _scapi
                SET bkh_calcul.wgt  = bkh_calcul.capi/_scapi.scapi
                WHERE _scapi.dates = bkh_calcul.dates;";
        $this->db->query($sql);
    }

    public function updatePdateBkh_calcul() {
        $sql = "UPDATE bkh_calcul, idx_calendar
                SET bkh_calcul.PDATE  = idx_calendar.prevdate
                WHERE bkh_calcul.DATES = idx_calendar.currdate;";
        $this->db->query($sql);
    }

    public function updatePcloseBkh_calcul() {
        $sql = "UPDATE bkh_calcul, bkh_calcul A2
                SET bkh_calcul.pclose = A2.adjclose
                WHERE bkh_calcul.pdate  = A2.dates
                AND bkh_calcul.stk_code = A2.stk_code;";
        $this->db->query($sql);
    }

    public function updateStk_tr_Bkh_calcul() {
        $sql = "UPDATE bkh_calcul
                SET bkh_calcul.stk_tr = 100*((bkh_calcul.adjclose/bkh_calcul.pclose)-1)
                WHERE (bkh_calcul.adjclose*bkh_calcul.pclose)<>0;";
        $this->db->query($sql);

        $sql = "UPDATE bkh_calcul
                SET bkh_calcul.stk_pr = bkh_calcul.stk_tr - (bkh_calcul.divtr/bkh_calcul.close)
                WHERE (bkh_calcul.adjclose*bkh_calcul.pclose)<>0;";
        $this->db->query($sql);

        $sql = "UPDATE bkh_calcul
                SET bkh_calcul.stk_nr = bkh_calcul.stk_pr + (bkh_calcul.divnr/bkh_calcul.close)
                WHERE (bkh_calcul.adjclose*bkh_calcul.pclose)<>0;";
        $this->db->query($sql);
    }

    public function importData_QidxCalculFromBkh_calcul() {
        self::del_indexes('qidx_calcul');
        $this->db->select('bkh_calcul.dates, bkh_calcul.close AS `close`,
                            SUM(bkh_calcul.wgt*bkh_calcul.stk_tr) AS `idx_tr`,
                            SUM(bkh_calcul.wgt*bkh_calcul.stk_pr) AS `idx_pr`,
                            SUM(bkh_calcul.wgt*bkh_calcul.stk_nr) AS `idx_nr`,
                            COUNT(bkh_calcul.dates) AS `nb`');
        $this->db->group_by('bkh_calcul.dates');
        $data = $this->db->get('bkh_calcul')->result_array();
        if (is_array($data) == TRUE && count($data) > 0) {
            return $this->db->insert_batch('qidx_calcul', $data);
        }
        return TRUE;
    }

    public function updateClose_QidxCalcul($table) {
        $arr = self::_get_array($table);
        $sum = self::_get_idx_base('qidx_ref');
        $cal1 = $cal2 = $cal3 = $sum[0]['idx_base'];
        //$cal = 50;

        for ($row = 0; $row < count($arr); $row++):
            if ($row == 0) {

                $arr[$row]['close_tr'] = $cal1;
                $arr[$row]['close_pr'] = $cal2;
                $arr[$row]['close_nr'] = $cal3;
                //$arr[$row]['close'] = $cal;
            } else {

                $arr[$row]['close_tr'] = $cal1 * (1 + ($arr[$row]['idx_tr'] / 100));
                $arr[$row]['close_pr'] = $cal2 * (1 + ($arr[$row]['idx_pr'] / 100));
                $arr[$row]['close_nr'] = $cal3 * (1 + ($arr[$row]['idx_nr'] / 100));
                //$arr[$row]['close'] = $cal * (1 + ($arr[$row]['idx_tr'] / 100));

                $cal1 = $arr[$row]['close_tr'];
                $cal2 = $arr[$row]['close_pr'];
                $cal3 = $arr[$row]['close_nr'];
                //$cal = $arr[$row]['close'];
            }
        endfor;

        self::del_indexes($table);
        self::_insertTable_array($table, $arr);
    }

    public function _get_array($table) {
        $this->db->select('*');
        $this->db->from($table);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function _get_idx_base($table) {
        $this->db->select('*');
        $this->db->from($table);
        $this->db->where('active = 1');
        $this->db->limit('1');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function _insertTable_array($table, $arr) {
        $this->db->insert_batch($table, $arr);
    }

}