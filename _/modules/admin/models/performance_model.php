<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Performance_model extends CI_Model {

    public $_destination_database = 'ifrcdata_db';
    public $_source_database_local = 'ims_production';
    public $_source_database_host = 'admin_ims';

    public $_table_daily_temp = 'q_index_vnx_daily_temp';
    public $_table_daily = 'q_index_vnx_daily';

    function __construct() {
        parent::__construct();        
    }

    public function check_table_exists($data_table){

        $connect_host = mysql_connect('ifrcims.com', 'admin_ims1', 'VietnamIfrc1', true) or die(mysql_error());
        mysql_select_db($this->_source_database_host, $connect_host ) or die(mysql_error());

        $data_final = array();
        foreach ($data_table['table'] as $table) {
            foreach ($data_table['database'] as $connected => $database) {
                if($connected == 'local'){
                    $result = $this->db->query("SELECT * FROM information_schema.tables WHERE table_schema = '{$database}' AND table_name = '{$table}' LIMIT 1")->num_rows();
                }else{
                    $respone = mysql_query("SELECT * FROM information_schema.tables WHERE table_schema = '{$database}' AND table_name = '{$table}' LIMIT 1",$connect_host);
                    $result = mysql_num_rows($respone);
                }
                $data_final[] = array(
                    'connected' => $connected,
                    'table' => $table,
                    'exists' => $result
                );
            }
        }
        mysql_close($connect_host);
        return $data_final;
    }

    public function get_table_daily($data_table){
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        $connect_host = mysql_connect('ifrcims.com', 'admin_ims1', 'VietnamIfrc1', true) or die(mysql_error());
        mysql_select_db($this->_source_database_host, $connect_host ) or die(mysql_error());

        $this->db->query('DROP TABLE IF EXISTS `' . $this->_destination_database . '`.`' . $this->_table_daily_temp . '`');
        $this->db->query('DROP TABLE IF EXISTS `' . $this->_destination_database . '`.`' . $this->_table_daily . '`');

        $arr_data_table = explode(',',$data_table);

        $data_local = array();
        $data_host = array();

        $i = 0;
        foreach($arr_data_table as $data){
            $i++;
            $arr_data = explode('|',$data);
            if($arr_data[1] == 'local'){
                if($i == 1){
                    $this->db->query('CREATE TABLE `' . $this->_destination_database . '`.`' . $this->_table_daily_temp . '` LIKE `' . $this->_source_database_local . '`.`' . $arr_data[0] . '`');
                    $this->db->query('CREATE TABLE `' . $this->_destination_database . '`.`' . $this->_table_daily . '` LIKE `' . $this->_source_database_local . '`.`' . $arr_data[0] . '`');
                }
                $data_local[] = array(
                    'connected' => $arr_data[1],
                    'table' => $arr_data[0]
                );
                
            }else{
                if($i == 1){
                    mysql_query('CREATE TABLE `' . $this->_destination_database . '`.`' . $this->_table_daily_temp . '` LIKE `' . $this->_source_database_host . '`.`' . $arr_data[0] . '`',$connect_host);
                    mysql_query('CREATE TABLE `' . $this->_destination_database . '`.`' . $this->_table_daily . '` LIKE `' . $this->_source_database_host . '`.`' . $arr_data[0] . '`',$connect_host);
                }
                $data_host[] = array(
                    'connected' => $arr_data[1],
                    'table' => $arr_data[0]
                );
            }
        }
        if(count($data_local) != 0){
            $this->write_txt_local($data_local);
        }
        if(count($data_host) != 0){
            $this->write_txt_host($data_host);
        }

        mysql_close($connect_host);

        $path = APPPATH."../../assets/temp/";
        $files = glob($path . '*.txt');
        if(count($files) != 0){
            foreach($files as $file){
                $filename = basename($file, ".txt");
                $arr_filename = explode('-',$filename);
                $this->db->query("LOAD DATA LOCAL INFILE '".$file."' INTO TABLE `".$this->_destination_database."`.`".$this->_table_daily_temp."` FIELDS TERMINATED BY '\t' LINES TERMINATED BY '\r\n' (idx_code, date, close)");
                unlink($file);
            }
        }

        $path_final = APPPATH."../../assets/temp/".$this->_table_daily.".txt";

        $connect_local = mysql_connect('local', 'local', 'ifrcvn', true) or die(mysql_error());
        mysql_select_db($this->_destination_database, $connect_local ) or die(mysql_error());

        $fh = fopen($path_final, "w+"); // Write value into file

        $result = mysql_query('SELECT * FROM `' . $this->_destination_database . '`.`' . $this->_table_daily_temp . '` GROUP BY idx_code, date',$connect_local) or die(mysql_error());    

        while ($row = mysql_fetch_row($result)) {
            fputs($fh, implode("\t", $row)."\r\n");
        }
        fclose ($fh);
        mysql_free_result($result);

        mysql_close($connect_local);

        $this->db->query('ALTER TABLE `' . $this->_destination_database . '`.`' . $this->_table_daily . '` ADD COLUMN perform DOUBLE');

        $this->db->query("LOAD DATA LOCAL INFILE '".$path_final."' INTO TABLE `".$this->_destination_database."`.`".$this->_table_daily."` FIELDS TERMINATED BY '\t' LINES TERMINATED BY '\r\n'");

        unlink($path_final);

        $this->calculate_perform($this->_table_daily);
        $this->create_table_monthly($this->_table_daily);
        $this->create_table_yearly($this->_table_daily);
        $this->calculate_perform($this->_table_daily);
    }

    public function write_txt_host($data_table){

        $connect_host = mysql_connect('ifrcims.com', 'admin_ims1', 'VietnamIfrc1', true) or die(mysql_error());
        mysql_select_db($this->_source_database_host, $connect_host ) or die(mysql_error());

        foreach($data_table as $table){

            $path = APPPATH."../../assets/temp/".$table['table']."-".$table['connected'].".txt";
            if($table['table'] == $this->_table_daily){
                $result = mysql_query('SELECT idx_code, date, close  FROM `' . $this->_source_database_host . '`.`' . $table['table'] . '`',$connect_host);    
            }else{
                $result = mysql_query('SELECT idx_code, date, idx_close  FROM `' . $this->_source_database_host . '`.`' . $table['table'] . '`',$connect_host);    
            }
            $fh = fopen($path, "w+"); // Write value into file

            while ($row = mysql_fetch_row($result)) {
                fputs($fh, implode("\t", $row)."\r\n");
            }
            fclose ($fh);
            mysql_free_result($result);

        }

        mysql_close($connect_host);
    }

    public function write_txt_local($data_table){

        $connect_local = mysql_connect('local', 'local', 'ifrcvn', true) or die(mysql_error());
        mysql_select_db($this->_source_database_local, $connect_local ) or die(mysql_error());

        foreach($data_table as $table){
            $path = APPPATH."../../assets/temp/".$table['table']."-".$table['connected'].".txt";
            if($table['table'] == $this->_table_daily){
                $result = mysql_query('SELECT idx_code, date, close FROM `' . $this->_source_database_local . '`.`' . $table['table'] . '`',$connect_local);
            }else{
                $result = mysql_query('SELECT idx_code, date, idx_close FROM `' . $this->_source_database_local . '`.`' . $table['table'] . '`',$connect_local);
            }
            $fh = fopen($path, "w+"); // Write value into file
            while ($row = mysql_fetch_row($result)) {
                fputs($fh, implode("\t", $row)."\r\n");
            }
            fclose ($fh);
            mysql_free_result($result);
        }
        
        mysql_close($connect_local);
    }

    public function create_table_monthly($table_daily){
        $table_monthly = str_replace('daily','monthly',$table_daily);
        $this->db->query('DROP TABLE IF EXISTS `' . $this->_destination_database . '`.`' . $table_monthly . '`');
        $this->db->query('CREATE TABLE `' . $this->_destination_database . '`.`' . $table_monthly . '` LIKE `'.$table_daily.'`');
        $this->db->query('DROP TABLE IF EXISTS TMP');
        $this->db->query('CREATE TABLE TMP (SELECT * FROM '.$table_daily.' ORDER BY idx_code, date DESC)');
        $this->db->query('INSERT INTO '.$table_monthly.' (IDX_CODE, DATE, IDX_MOTHER, CLOSE, CAPI,DIVISOR, IDX_CURR, PERFORM) (SELECT IDX_CODE, MAX(DATE) AS DATE, IDX_MOTHER, CLOSE, CAPI,DIVISOR, IDX_CURR, PERFORM FROM TMP GROUP BY YEAR(DATE), MONTH(DATE), idx_code ORDER BY idx_code, DATE)');
        $this->calculate_perform($table_monthly);
    }

    public function create_table_yearly($table_daily){
        $table_yearly = str_replace('daily','yearly',$table_daily);
        $this->db->query('DROP TABLE IF EXISTS `' . $this->_destination_database . '`.`' . $table_yearly . '`');
        $this->db->query('CREATE TABLE `' . $this->_destination_database . '`.`' . $table_yearly . '` LIKE `'.$table_daily.'`');
        $this->db->query('DROP TABLE IF EXISTS TMP');
        $this->db->query('CREATE TABLE TMP (SELECT * FROM '.$table_daily.' ORDER BY idx_code, date DESC)');
        $this->db->query('INSERT INTO '.$table_yearly.' (IDX_CODE, DATE, IDX_MOTHER, CLOSE, CAPI,DIVISOR, IDX_CURR, PERFORM) (SELECT IDX_CODE, MAX(DATE) AS DATE, IDX_MOTHER, CLOSE, CAPI,DIVISOR, IDX_CURR, PERFORM FROM TMP GROUP BY YEAR(DATE), idx_code ORDER BY idx_code, DATE)');
        $this->calculate_perform($table_yearly);
    }

    public function calculate_perform($table){
        $table_temp = $table.'_temp';
        $this->db->query('DROP TABLE IF EXISTS '.$table_temp);

        $this->db->query('CREATE TABLE ' . $table_temp . ' SELECT * FROM ' . $table . ' ORDER BY IDX_CODE, DATE ASC');

        $this->db->query('DROP TABLE IF EXISTS TMP');

        $this->db->query('CREATE TABLE TMP SELECT IDX_CODE, DATE, CLOSE, IF(@plr = IDX_CODE,(@runtot :=  (a.CLOSE-@runtot)/@runtot), null) AS rt, (@runtot := a.CLOSE ) ne, @plr := IDX_CODE AS dummy FROM ' . $table_temp . ' a, (SELECT @runtot:=0) c');

        $this->db->query('UPDATE ' . $table . ' A, TMP B SET A.PERFORM = B.RT WHERE A.IDX_CODE = B.IDX_CODE AND A.DATE = B.DATE');
    }

    public function get_code(){
        return $this->db->query('SELECT idx_mother FROM q_index_vnx_daily GROUP BY idx_mother')->result_array();
    }

    public function get_currency(){
        return $this->db->query('SELECT idx_curr FROM q_index_vnx_daily GROUP BY idx_curr')->result_array();
    }

    public function get_type(){
        return $this->db->query('SELECT SUBSTRING(idx_code FROM -5 FOR 2) AS type FROM q_index_vnx_daily GROUP BY type')->result_array();
    }

    public function listTable($table,$code_mother,$currency,$type){        
        $sSortDir = $this->input->get_post('sSortDir_0', true);
        
        /* Array of database columns which should be read and sent back to DataTables. Use a space where
         * you want to insert a non-database field (for example a counter or static image)
         */
        $sTable = $table;
         
        $aColumns = array("{$sTable}.idx_mother", "{$sTable}.date", "{$sTable}.close", "{$sTable}.perform");
        
        $aColumns2 = array('idx_mother', 'date', 'close', 'perform');

        $this->db->where('idx_mother',$code_mother);
        $this->db->where('idx_curr',$currency);
        $this->db->where('SUBSTRING(idx_code FROM -5 FOR 2) = ',$type);
        
        // DB table to use
    

        $iDisplayStart = $this->input->get_post('iDisplayStart', true);
        $iDisplayLength = $this->input->get_post('iDisplayLength', true);
        $iSortCol_0 = $this->input->get_post('iSortCol_0', true);
        $iSortingCols = $this->input->get_post('iSortingCols', true);
        $sSearch = $this->input->get_post('sSearch', true);
        $sEcho = $this->input->get_post('sEcho', true);
        $myFilter = $this->input->get_post('myFilter', true);
    
        // Paging
        if(isset($iDisplayStart) && $iDisplayLength != '-1')
        {
            $this->db->limit($this->db->escape_str($iDisplayLength), $this->db->escape_str($iDisplayStart));
        }
        
        // Ordering
        if(isset($iSortCol_0))
        {
            for($i=0; $i<intval($iSortingCols); $i++)
            {
                $iSortCol = $this->input->get_post('iSortCol_'.$i, true);
                $bSortable = $this->input->get_post('bSortable_'.intval($iSortCol), true);
                $sSortDir = $this->input->get_post('sSortDir_'.$i, true);
    
                if($bSortable == 'true')
                {
                    $this->db->order_by($aColumns[intval($this->db->escape_str($iSortCol)) + 1], $this->db->escape_str($sSortDir));
                }
            }
        }
        
        /* 
         * Filtering
         * NOTE this does not match the built-in DataTables filtering which does it
         * word by word on any field. It's possible to do here, but concerned about efficiency
         * on very large tables, and MySQL's regex functionality is very limited
         */
        if(isset($sSearch) && !empty($sSearch))
        {
            for($i=0; $i<count($aColumns); $i++)
            {
                $bSearchable = $this->input->get_post('bSearchable_'.$i, true);
                
                // Individual column filtering
                if(isset($bSearchable) && $bSearchable == 'true')
                {
                    $this->db->or_like($aColumns[$i], $this->db->escape_like_str($sSearch));
                }
            }
        }

        // my filter
        if(isset($myFilter) && !empty($myFilter)){
            $myFilter = json_decode($myFilter);
            foreach($myFilter as $filter => $keyword){
                if($keyword != 'all'){
                    $this->db->where($filter, $keyword);
                }
            }
        }
        
        // Select Data
        $this->db->select('SQL_CALC_FOUND_ROWS '.str_replace(' , ', ' ', implode(', ', $aColumns)), false);
        $rResult = $this->db->get($sTable);
    
        // Data set length after filtering
        $this->db->select('FOUND_ROWS() AS found_rows');
        $iFilteredTotal = $this->db->get()->row()->found_rows;
    
        // Total data set length
        $iTotal = $this->db->count_all($sTable);
    
        // Output
        $output = array(
            'sEcho' => intval($sEcho),
            'iTotalRecords' => $iTotal,
            'iTotalDisplayRecords' => $iFilteredTotal,
            'aaData' => array()
        );
        
        foreach($rResult->result_array() as $aRow)
        {
            $row = array();
            
            foreach($aColumns2 as $col)
            {
                if(!in_array($col, array('id'))){
                    $value = '';
                    $value = $aRow[$col];
                    if($col == 'close'){
                        $value = round($value, 2);
                    }
                    if($col == 'perform'){
                        $value =  round($value, 6);
                        if($value >= 0){
                            if($value == 0){
                                $value = '-';
                            }else{
                                $value = '<span style="color:#02BB00">'.$value.'</span>';
                            }
                        }else{
                            $value = '<span style="color:#FF0000">'.$value.'</span>';
                        }
                    }
                    $row[] = $value;
                }
            }
            
            $output['aaData'][] = $row;
            //$output['my_id'][] = $aRow['user_id'];
        }
        return $output;
    }
}
