<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Database extends Admin {

    protected $data;

    function __construct() {
        parent::__construct();
        $this->load->model('Database_model', 'mdatabase');
        date_default_timezone_set("Asia/Ho_Chi_Minh"); 
    }

/* * *******************************************************************************************************************
 * Client  Name ：  IFRC
 * ---------------------------------------------------------------------------------------------------------------------
 * Project Name ：  IFRCDATA
 * ---------------------------------------------------------------------------------------------------------------------
 * Program Name ：  database.php
 * ---------------------------------------------------------------------------------------------------------------------
 * Entry Server ：
 * ---------------------------------------------------------------------------------------------------------------------
 * Called By    ：  System
 * ---------------------------------------------------------------------------------------------------------------------
 * Notice       ：
 * ---------------------------------------------------------------------------------------------------------------------
 * Copyright    ：  IFRC
 * ---------------------------------------------------------------------------------------------------------------------
 * Comment      ：
 * ---------------------------------------------------------------------------------------------------------------------
 * History      ：
 * ---------------------------------------------------------------------------------------------------------------------
 * Version V001 ：  2013.10.04 (Minh Đẹp Trai)        New Create
 * ******************************************************************************************************************* */

    function index(){
    	$this->template->write_view('content', 'database/index', $this->data);
        $this->template->write('title', 'Index');
        $this->template->render();
    }

    function view(){
        $mainPath = '\\\LOCAL\IFRCVN\VNDB\STRUCTURE\CORRECT\\';
        $filenameStructure = 'structure_database.txt';
        $filenameIndex = 'index_database.txt';

        $this->db->query("TRUNCATE TABLE database_structure");
        $this->db->query("TRUNCATE TABLE database_index");

        $baseUrlStructure = str_replace("\\", "\\\\", $mainPath.$filenameStructure);
        $baseUrlIndex = str_replace("\\", "\\\\", $mainPath.$filenameIndex);

        $this->db->query("LOAD DATA LOCAL INFILE '" . $baseUrlStructure . "' INTO TABLE database_structure FIELDS TERMINATED BY '\t' IGNORE 1 LINES");
        $this->db->query("LOAD DATA LOCAL INFILE '" . $baseUrlIndex . "' INTO TABLE database_index FIELDS TERMINATED BY '\t' IGNORE 1 LINES");

        $this->data->infoStructure = $this->mdatabase->getStructure();
        $this->data->infoIndex = $this->mdatabase->getIndex();
    	$this->template->write_view('content', 'database/view', $this->data);
        $this->template->write('title', 'View');
        $this->template->render();
    }

    function download(){
    	if ($this->input->is_ajax_request()) {
            $from = microtime(true);
	        $mainPath = '\\\LOCAL\IFRCVN\VNDB\STRUCTURE\\';
            $filenameStructureFinal = 'structure_database.txt';
            $filenameIndexFinal = 'index_database.txt';
            $filenameLog = 'log.txt';
            $numTableExist = 0;
            $numTableIndex = 0;
            $result[0]['success'] = array();
            $result[0]['error'] = array();
            $databases = $this->db->query("SHOW DATABASES")->result_array();
            if(count($databases) > 0){

				$create = fopen($mainPath.'DOWNLOAD\\'.$filenameStructureFinal, "w");
				$header = 'Database'.chr(9).'Table'.chr(9).'Field'.chr(9).'Type'.chr(9).'Null'.chr(9).'Key'.chr(9).'Default'.chr(9).'Extra'."\r\n";
	            $write = fwrite($create, $header);
	            fclose($create);

                $create = fopen($mainPath.'DOWNLOAD\\'.$filenameIndexFinal, "w");
                $header = 'Database'.chr(9).'Table'.chr(9).'Group'.chr(9).'Item'."\r\n";
                $write = fwrite($create, $header);
                fclose($create);

            	$data_chooseDatabase = file($mainPath.'choose_database.txt');
                write_log($mainPath.'DOWNLOAD\\', $filenameLog, "Data database of file choose_database.txt have ".count($data_chooseDatabase)." database");
            	foreach ($databases as $database) {
            		$database = trim($database['Database']);
            		foreach($data_chooseDatabase as $databaseFile){
            			if(trim($databaseFile) == $database){
            				$pathChooseTable = $mainPath.'choose_table_'.$database.'.txt';
				            if (!file_exists($pathChooseTable)) {   
				                $chooseTables = array();                         
				            }else{
				                $chooseTables = file($pathChooseTable);
				            }
				            if(count($chooseTables) > 0){
				                foreach($chooseTables as $table){
				                    $table = trim($table);
                                    $count = $this->db->query('SELECT * FROM information_schema.tables WHERE table_schema = "'.$database.'" AND table_name = "'.$table.'" LIMIT 1')->num_rows();
                                    if($count == 0){
                                        $numTableExist++;
                                        write_log($mainPath.'DOWNLOAD\\', $filenameLog, 'Table "'.$table.'" doesn\'t exist');
                                    }else{
				                        $this->exportStructure($database, $table, $mainPath.'DOWNLOAD\\'.$filenameStructureFinal);
                                        $respone = $this->exportIndex($database, $table, $mainPath.'DOWNLOAD\\'.$filenameIndexFinal);
                                        if($respone != NULL){
                                            $numTableIndex++;
                                            write_log($mainPath.'DOWNLOAD\\', $filenameLog, 'Table "'.$table.'" doesn\'t index');
                                        }
                                    }
				                }
				            }else{
				                $tablesDatabase = $this->db->query('SHOW TABLES FROM '.$database)->result_array();
				                foreach($tablesDatabase as $tableDatabase){
				                    $this->exportStructure($database, $tableDatabase['Tables_in_'.$database], $mainPath.'DOWNLOAD\\'.$filenameStructureFinal);
                                    $respone = $this->exportIndex($database, $tableDatabase['Tables_in_'.$database], $mainPath.'DOWNLOAD\\'.$filenameIndexFinal);
                                    if($respone != NULL){
                                        $numTableIndex++;
                                        write_log($mainPath.'DOWNLOAD\\', $filenameLog, 'Table "'.$tableDatabase['Tables_in_'.$database].'" doesn\'t index');
                                    }
				                }
				            }
            			}
            		}
            	}
            	$result[0]['success'] = "Done";
                write_log($mainPath.'DOWNLOAD\\', $filenameLog, "Notificate: Done");
                $file = 'example.txt';
                $newfile = 'example.txt.bak';

                if (!copy($mainPath.'DOWNLOAD\\'.$filenameStructureFinal, $mainPath.'CORRECT\\'.$filenameStructureFinal)) {
                    write_log($mainPath.'DOWNLOAD\\', $filenameLog, 'Error: failed to copy '.$mainPath.'DOWNLOAD\\'.$filenameStructureFinal);
                }
                if (!copy($mainPath.'DOWNLOAD\\'.$filenameIndexFinal, $mainPath.'CORRECT\\'.$filenameIndexFinal)) {
                    write_log($mainPath.'DOWNLOAD\\', $filenameLog, 'Error: failed to copy '.$mainPath.'DOWNLOAD\\'.$filenameIndexFinal);
                }
            }else{
            	$result[0]['error'][] = "Don't find database!";
                write_log($mainPath.'DOWNLOAD\\', $filenameLog, "Error: Don't find database!");
            }
            write_log($mainPath.'DOWNLOAD\\', $filenameLog, 'Report: '.$numTableExist.' table doesn\'t exist');
            write_log($mainPath.'DOWNLOAD\\', $filenameLog, 'Report: '.$numTableIndex.' table doesn\'t index');
            $total = microtime(true) - $from;
            write_log($mainPath.'DOWNLOAD\\', $filenameLog, 'Time: '.round($total, 2).' seconds');
            $result[0]['time'] = round($total, 2);
            $result[0]['task'] = 'Time';
            $result[0]['report'] = array(
                $numTableExist.' table doesn\'t exist',
                $numTableIndex.' table doesn\'t index'
            );
            echo json_encode($result);
        }
    }

    public function correct(){
        if ($this->input->is_ajax_request()) {
            $from = microtime(true);
            $mainPath = '\\\LOCAL\IFRCVN\VNDB\STRUCTURE\CORRECT\\';
            $filenameLog = 'log.txt';
            $result[0]['success'] = array();
            $result[0]['error'] = array();
            $dataCorrect = $this->db->get('database_correct')->result_array();
            $numFieldChange = 0;
            if(count($dataCorrect) > 0){

                foreach($dataCorrect as $data){
                    $dataFile = file($mainPath.'structure_database.txt');
                    $dataWriteAgain = array();
                    foreach($dataFile as $data2){
                        if($data2 != ""){
                            $arrData2 = explode("\t",trim($data2));
                            if(count($arrData2) > 0 && is_array($arrData2)){
                                if(strtolower($arrData2[2]) == strtolower($data['field'])){
                                    $arrData2[3] = $data['type'];
                                    write_log($mainPath, $filenameLog, "Database ".$arrData2[0]." , Table ".$arrData2[1]." change type of column ".$arrData2[2]);
                                    $numFieldChange++;
                                }
                                $dataWriteAgain[] = implode("\t",$arrData2);
                            }
                        }
                    }
                    $create = fopen($mainPath.'structure_database.txt', "w");
                    $write = fwrite($create, implode("\r\n",$dataWriteAgain));
                    fclose($create);
                }
                $result[0]['success'] = "Done";
                write_log($mainPath, $filenameLog, "Notificate: Done");
            }else{
                write_log($mainPath, $filenameLog, "Error: Database 'database_correct' haven't record");
                $result[0]['error'][] = "Database 'database_correct' haven't record";
            }
            write_log($mainPath, $filenameLog, 'Report: '.$numFieldChange.' field had changed');
            $total = microtime(true) - $from;
            write_log($mainPath, $filenameLog, 'Time: '.round($total, 2).' seconds');
            $result[0]['time'] = round($total, 2);
            $result[0]['task'] = 'Time';
            $result[0]['report'] = array(
                $numFieldChange.' fields had changed'
            );
            echo json_encode($result);
        }
    }

    public function exportStructure($database, $table, $filename){
        $columnsTable = $this->db->query('SHOW COLUMNS FROM `'.$database.'`.`'.$table.'`')->result_array();
        $data_column = array();
        foreach($columnsTable as $column){
            $data_column[] = $database.chr(9).$table.chr(9).implode("\t",$column);
        }
        $create = fopen($filename, "a");
        $write = fwrite($create, implode("\r\n",$data_column)."\r\n");
        fclose($create);
    }

    public function exportIndex($database, $table, $filename){
        $data_field = $this->db->query('SHOW INDEX FROM `'.$database.'`.`'.$table.'`')->result_array();
        
        $data_table = array();
        foreach($data_field as $field){
            $title_key_name = $field['Key_name'];
            if($field['Key_name'] == $title_key_name){
                $data_table[$title_key_name][] =  $field['Column_name'];
            }  
        }
        $data_final = array();
        foreach($data_table as $key => $value){
            if($key != 'PRIMARY'){
                $data_final[] = $database.chr(9).$table.chr(9).$key.chr(9).implode(",",$value);
            }
        }
        if(count($data_final) > 0){
            $create = fopen($filename, "a");
            $write = fwrite($create, implode("\r\n",$data_final)."\r\n");
            fclose($create);
            return null;
        }else{
            return $table;
        }
    }

    public function cleanStruture(){
        if ($this->input->is_ajax_request()) {
            $from = microtime(true);
            set_time_limit(0);
            $mainPath = '\\\LOCAL\IFRCVN\VNDB\STRUCTURE\CLEAN\\';
            $filenameLog = 'log_structure.txt';
            $data = $this->db->get('database_structure')->result_array();
            $numColumnAdd = 0;
            $numColumnModify = 0;
            $dataColumnPrimary = array();
            $result[0]['error'] = array();
            foreach($data as $items){
                $column = $items['field'];
                $database = $items['database'];
                $table = $items['table'];
                if($items['key'] == 'PRI'){
                    $dataColumnPrimary[] = array_merge($items, array("check"=>"nothing"));
                }
                $checkColumn = $this->db->query('SHOW COLUMNS FROM `'.$database.'`.`'.$table.'` WHERE field = "'.$column.'"')->row_array();
                if($checkColumn['Null'] == 'NO'){
                    $checkColumn['Null'] = 'NOT NULL';
                }else{
                    $checkColumn['Null'] = 'NULL';
                }
                if(count($checkColumn) == 0){
                    if($items['type'] == ''){
                        $result[0]['error'][] = 'Column '.$column.' of table '.$table.' missing type';
                        write_log($mainPath, $filenameLog, 'Error: Column '.$column.' of table '.$table.' missing type');
                    }else{
                        $flag=1;
                        $optionAdd = $items['type'];
                        if($items['null'] == 'NO'){
                            $optionAdd .= ' NOT NULL ';
                        }else{
                            $optionAdd .= ' NULL ';
                        }
                        if($items['key'] == 'PRI'){
                            $flag=0;
                            $valueCorrect = substr($items['type'],0,3);
                            if($valueCorrect != 'int'){
                                $result[0]['error'][] = 'Column '.$column.' of table '.$table.' have '.$items['type'].' can\'t set Primary Key';
                                write_log($mainPath, $filenameLog, 'Error: Column '.$column.' of table '.$table.' have '.$items['type'].' can\'t set Primary Key');
                            }else{
                                $dataColumnPrimary[] = array_merge($items, array("check"=>"add"));
                            }
                        }
                        if($items['default'] != ''){
                            $optionAdd .= ' DEFAULT "'.trim($items['default']).'" ';
                        }
                        if($items['extra'] != ''){
                            $optionAdd .= trim($items['extra']);
                        }
                        if($flag == 1){
                            $this->db->query('ALTER TABLE `'.$database.'`.`'.$table.'` ADD COLUMN `'.$column.'` '.$optionAdd);
                            write_log($mainPath, $filenameLog, 'Add column '.$column.' with option '.$optionAdd.' of database '.$database.' & table '.$table);
                            $numColumnAdd++;
                        }
                    }
                }else{
                    $flagResult = 1;
                    $dataResult = array();
                    foreach($items as $key => $item){
                        if($key != 'database' && $key != 'table' && $key != 'column' && $key != 'id'){
                            if($key != 'default'){
                                $where = 'AND `'.$key.'` = "'.trim($item).'"';
                            }else{
                                if($item == ''){
                                    $where = 'AND `'.$key.'` IS NULL';
                                }
                            }
                            $checkField = $this->db->query('SHOW COLUMNS FROM `'.$database.'`.`'.$table.'` WHERE field = "'.$column.'" '.$where)->num_rows();
                            $flagResult = $flagResult * $checkField;
                            $dataResult[] = trim($item);
                        }
                    }
                    if($flagResult == 0){
                        $flag=1;
                        $dataResult[0] = '`'.$dataResult[0].'`';
                        if($dataResult[2] == 'NO'){
                            $dataResult[2] = 'NOT NULL';
                        }else{
                            $dataResult[2] = 'NULL';
                        }
                        if($dataResult[3] == 'PRI'){
                            $flag=0;
                            array_unshift($dataResult, $table);
                            array_unshift($dataResult, $database);
                            $dataColumnPrimary[] = array_merge($items, array("check"=>"modify"));
                        }else{
                            unset($dataResult[3]);
                        }
                        if($dataResult[4] == '' || $dataResult[4] == 'NULL'){
                            
                        }else {
                            if(strpos($dataResult[1], 'int')){
                                if(is_numeric($dataResult[4])){
                                    $dataResult[4] = 'DEFAULT "'.$dataResult[4].'"';
                                }else{
                                    unset($dataResult[4]);
                                }
                            }else{
                                $dataResult[4] = 'DEFAULT "'.$dataResult[4].'"';
                            }
                            
                        }
                        if($dataResult[5] == ''){
                            unset($dataResult[5]);
                        }
                        if($flag == 1){
                            $this->db->query('ALTER TABLE `'.$database.'`.`'.$table.'` MODIFY COLUMN '.implode(' ',$dataResult));
                            unset($dataResult[0]);
                            write_log($mainPath, $filenameLog, 'Modify column '.$checkColumn['Field'].' from '.$checkColumn['Type'].' '.$checkColumn['Null'].' to '.implode(' ',$dataResult).' of database '.$database.' & table '.$table);
                            $numColumnModify++;
                        }
                    }
                }
            }
            $dataColumnUnione = array();
            foreach($dataColumnPrimary as $items){
                $database = $items['database'];
                $table = $items['table'];
                unset($items['database'],$items['table']);
                $dataColumnUnione[$database][$table][$items['field']] = $items;
            }
            foreach($dataColumnUnione as $database => $arrDatabase){
                $i=0;
                foreach($arrDatabase as $table => $arrTable){
                    $maxTable = count($dataColumnUnione[$database][$table]);
                    $dataColumn = array();
                    foreach($arrTable as $column => $Optcolumn){
                        $i++;
                        $flagadd=0;
                        $flagmodify=0;
                        if($Optcolumn['check'] == 'add'){
                            $flagadd=1;
                            $dataColumnAdd[] = $column;
                        }elseif($Optcolumn['check'] == 'modify'){
                            $flagmodify=1;
                        }
                        $dataColumn[] = $column;
                        if($i == $maxTable){
                            $dataColumn = array_unique($dataColumn);
                            if($flagadd !=0 || $flagmodify !=0){
                                if($flagadd == 1){
                                    foreach($dataColumnAdd as $tableAdd){
                                        $option = '`'.$dataColumnUnione[$database][$table][$tableAdd]['field'].'`';
                                        $option .= ' '.$dataColumnUnione[$database][$table][$tableAdd]['type'];
                                        $null = $dataColumnUnione[$database][$table][$tableAdd]['null'];
                                        if($null == 'NO'){
                                            $option .= ' NOT NULL';
                                        }else{
                                            $option .= ' NULL';
                                        }
                                        $this->db->query('ALTER TABLE `'.$database.'`.`'.$table.'` ADD COLUMN '.$option);
                                        write_log($mainPath, $filenameLog, 'Add column '.$option.' of database '.$database.' & table '.$table);
                                        $numColumnAdd++;
                                    }
                                }
                                $this->db->query('ALTER TABLE `'.$database.'`.`'.$table.'` DROP PRIMARY KEY, ADD PRIMARY KEY('.implode(',',$dataColumn).')');
                                write_log($mainPath, $filenameLog, 'Drop and add new primary key for ('.implode(',',$dataColumn).') of database '.$database.' & table '.$table);
                            }
                        }
                    }
                }
                
            }
            write_log($mainPath, $filenameLog, 'Notificate: Done');
            write_log($mainPath, $filenameLog, 'Report: '.$numColumnAdd.' columns have been add.');
            write_log($mainPath, $filenameLog, 'Report: '.$numColumnModify.' columns have been modify.');
            $total = microtime(true) - $from;
            write_log($mainPath, $filenameLog, 'Time: '.round($total, 2).' seconds');
            $result[0]['time'] = round($total, 2);
            $result[0]['task'] = 'Time';
            $result[0]['success'] = array(
                $numColumnAdd.' columns have been add.',
                $numColumnModify.' columns have been modify.'
            );
            echo json_encode($result);
        }
    }

    public function cleanIndex(){
        if ($this->input->is_ajax_request()) {
            $from = microtime(true);
            set_time_limit(0);
            $mainPath = '\\\LOCAL\IFRCVN\VNDB\STRUCTURE\CLEAN\\';
            $filenameLog = 'log_index.txt';
            $data = $this->db->get('database_index')->result_array();
            $numIndexAdd = 0;
            foreach($data as $items){
                $database = $items['database'];
                $table = $items['table'];
                $group = $items['group'];
                $array_item = explode(',',trim($items['item']));
                $checkItems = $this->db->query('SHOW INDEX FROM `'.$database.'`.`'.$table.'` WHERE Key_name = "'.$group.'"')->result_array();
                if(count($checkItems) == 0){
                    $this->db->query('ALTER TABLE `'.$database.'`.`'.$table.'` ADD INDEX `'.$group.'` (`'.implode('`,`',$array_item).'`)');
                    write_log($mainPath, $filenameLog, 'Add index '.$group.' include '.implode(',',$array_item));
                    $numIndexAdd++;
                }else{
                    $data_table = array();
                    foreach($checkItems as $field){
                        if($field['Key_name'] != "PRIMARY"){
                            $data_table[] =  $field['Column_name'];
                        }
                    }
                    $compare = array_diff_assoc($array_item, $data_table);
                    if(count($compare) > 0){
                        $this->db->query('ALTER TABLE `'.$database.'`.`'.$table.'` DROP INDEX `'.$group.'`');
                        write_log($mainPath, $filenameLog, 'Drop index '.$group.' of database '.$database.' & table '.$table);
                        $this->db->query('ALTER TABLE `'.$database.'`.`'.$table.'` ADD INDEX `'.$group.'` (`'.implode('`,`',$array_item).'`)');
                        write_log($mainPath, $filenameLog, 'Add index '.$group.' include '.implode(',',$array_item));
                        $numIndexAdd++;
                    }
                }

            }
            write_log($mainPath, $filenameLog, 'Notificate: Done');
            write_log($mainPath, $filenameLog, 'Report: '.$numIndexAdd.' index have been add.');
            $total = microtime(true) - $from;
            write_log($mainPath, $filenameLog, 'Time: '.round($total, 2).' seconds');
            $result[0]['time'] = round($total, 2);
            $result[0]['task'] = 'Time';
            $result[0]['success'] = array(
                $numIndexAdd.' index have been add.'
            );
            echo json_encode($result);
        }
    }

    public function review_database(){
        $database = 'pvn';
        $mainPath = '\\\LOCAL\IFRCVN\VNDB\STRUCTURE\\';
        $filenameReview = 'review_database_'.$database.'.txt';
        //$this->write_review($mainPath, $filenameReview, $data);
        $dataTable = $this->db->query('SHOW TABLES FROM '.$database)->result_array();
        $dataNumColumn = array();
        foreach($dataTable as $table){
            $dataNumColumn[] = $this->db->query('SHOW COLUMNS FROM `'.$database.'`.`'.$table['Tables_in_'.$database].'`')->num_rows();
        }
        $maxNum = max($dataNumColumn);
        $dataHeader = array('Database','Table','Option');
        for ($i=1; $i <= $maxNum; $i++) { 
            $dataHeader[] = 'Field '.$i;
        }
        $this->write_review($mainPath, $filenameReview, implode("\t",$dataHeader));
        foreach($dataTable as $table){
            $dataColumn = $this->db->query('SHOW COLUMNS FROM `'.$database.'`.`'.$table['Tables_in_'.$database].'`')->result_array();
            $dataColumnField = array();
            $dataColumnType = array();
            $where = '1=1';
            foreach($dataColumn as $column){
                $dataColumnField[] = $column['Field'];
                $dataColumnType[] = $column['Type'];
                $where .= ' OR (`'.$column['Field'].'` != "" OR `'.$column['Field'].'` IS NOT NULL)';
            }
            $dataValue = $this->db->query('SELECT * FROM `'.$database.'`.`'.$table['Tables_in_'.$database].'` WHERE '.$where.' limit 1')->row_array();
            $dataDefault = $database.chr(9).$table['Tables_in_'.$database].chr(9);
            $this->write_review($mainPath, $filenameReview, $dataDefault.'Field'.chr(9).trim(implode("\t",$dataColumnField)));
            $this->write_review($mainPath, $filenameReview, $dataDefault.'Type'.chr(9).trim(implode("\t",$dataColumnType)));
            $this->write_review($mainPath, $filenameReview, $dataDefault.'Value'.chr(9).trim(implode("\t",$dataValue)));
        }
    }

    public function write_review($path = "", $filename = "", $data = "") {
        $create = fopen($path.$filename, "a");
        $write = fwrite($create, $data."\r\n");
        fclose($create);
    }

    public function review_all(){
        $path = '\\\LOCAL\IFRCVN\VNDB\STRUCTURE\\';
        $filename = 'review_all.txt';
        $create = fopen($path.$filename, "w");
        $write = fwrite($create, '');
        fclose($create);
        $databases = $this->db->query("SHOW DATABASES")->result_array();
        $arrDatabaseChoose = array(
            // 'ectn',
            // 'ifrcdata_db',
            // 'ifrcedu',
            // 'ifrcnews_db',
            'ims',
            // 'ims_production',
            // 'imslong',
            // 'index_ifrc',
            // 'pvn',
            // 'ueldb'
        );
        $dataColumnsAll = array();
        $dataNameColumn = array();
        $dataTable = array();
        foreach($databases as $database){
            $database = $database['Database'];
            if(in_array($database, $arrDatabaseChoose)){
                $tables = $this->db->query('SHOW TABLES FROM '.$database)->result_array();
                foreach($tables as $table){
                    $table = $table['Tables_in_'.$database];
                    $dataTable[] = $database.'-'.$table;
                    $columns = $this->db->query('SHOW COLUMNS FROM `'.$database.'`.`'.$table.'`')->result_array();
                    foreach($columns as $column){
                        $column['Field'] = trim(preg_replace('/\s+/', '', $column['Field']));
                        $column['Field'] = strtolower($column['Field']);
                        $dataNameColumn[] = $column['Field'];
                        $dataColumnsAll[] = $database.chr(9).$table.chr(9).implode("\t",$column);
                    }
                }
            }
        }
        // $dataAccount = file($path.'..\\SYNCHRONIZATION\vndb_account.txt');
        // unset($dataAccount[0]);
        // foreach($dataAccount as $account){
        //     $arrAccount = explode("\t",$account);
        //     $arrAccount[2] = base64_decode($arrAccount[2]);
        //     $connect_host = mysql_connect($arrAccount[0], $arrAccount[1], $arrAccount[2], true);
        //     if ($connect_host){
        //         $queryDatabase = mysql_query("SHOW DATABASES");
        //         while($databases = mysql_fetch_assoc($queryDatabase)){
        //             $database = $databases['Database'];
        //             if($database != 'information_schema'){
        //                 $queryTables = mysql_query('SHOW TABLES FROM '.$database);
        //                 while($tables = mysql_fetch_assoc($queryTables)){
        //                     $table = $tables['Tables_in_'.$database];
        //                     $dataTable[] = $database.'-'.$table;
        //                     $queryColumn = mysql_query('SHOW COLUMNS FROM `'.$database.'`.`'.$table.'`');
        //                     while($column = mysql_fetch_assoc($queryColumn)){
        //                         $column['Field'] = trim(preg_replace('/\s+/', '', $column['Field']));
        //                         $column['Field'] = strtolower($column['Field']);
        //                         $dataNameColumn[] = $column['Field'];
        //                         $dataColumnsAll[] = $arrAccount[0].chr(9).$database.chr(9).$table.chr(9).implode("\t",$column);
        //                     }
        //                 }
        //             }
        //         }
        //         mysql_close($connect_host);
        //     }
        // }
        $dataColumnUni = array_unique($dataNameColumn);
        $dataFinal = array();
        foreach($dataColumnsAll as $columnAll){
            $arrColumnAll = explode("\t",$columnAll);
            if(in_array($arrColumnAll[2], $dataColumnUni)){
                $dataFinal[$arrColumnAll[2]]["database"][]=$arrColumnAll[0].'-'.$arrColumnAll[1];
                unset($arrColumnAll[0],$arrColumnAll[1],$arrColumnAll[4],$arrColumnAll[5],$arrColumnAll[7]);
                $dataFinal[$arrColumnAll[2]]["field"] = $arrColumnAll;
            }
        }
        $dataWrite = array();
        $maxTable = 0;
        foreach($dataFinal as $column => $data){
            $dataWriteTmp = $column;
            $default = $data['field'][6];
            unset($data['field'][2],$data['field'][6]);
            foreach($data['field'] as $itemField){
                // $arrField = preg_match('/(.+)\((.+)\)/', $itemField, $match);
                $arrField = explode(" ",$itemField);
                if(count($arrField) == 1){
                    if(strpos($arrField[0], '(')){
                        preg_match('/(.+)\((.+)\)/', $arrField[0], $match);
                        $dataWriteTmp .= chr(9);
                        $dataWriteTmp .= $match[1]; // Type
                        $arrMatch = explode(',',$match[2]);
                        if(count($arrMatch) == 2){
                            foreach($arrMatch as $am){
                                $dataWriteTmp .= chr(9);
                                $dataWriteTmp .= $am; // Length & Decimal
                            }
                            $dataWriteTmp .= chr(9);
                            $dataWriteTmp .= ""; // Special
                        }else{
                            $dataWriteTmp .= chr(9);
                            $dataWriteTmp .= $arrMatch[0]; // Length
                            $dataWriteTmp .= chr(9);
                            $dataWriteTmp .= ""; // Decimal
                            $dataWriteTmp .= chr(9);
                            $dataWriteTmp .= ""; // Special
                        }
                    }else{
                        $dataWriteTmp .= chr(9);
                        $dataWriteTmp .= $arrField[0]; //Type
                        $dataWriteTmp .= chr(9);
                        $dataWriteTmp .= ""; // Length
                        $dataWriteTmp .= chr(9);
                        $dataWriteTmp .= ""; // Decimal
                        $dataWriteTmp .= chr(9);
                        $dataWriteTmp .= ""; // Special
                    }
                }else{
                    if(strpos($arrField[0], '(')){
                        preg_match('/(.+)\((.+)\)/', $arrField[0], $match);
                        $dataWriteTmp .= chr(9);
                        $dataWriteTmp .= $match[1]; // Type
                        $arrMatch = explode(',',$match[2]);
                        if(count($arrMatch) == 2){
                            foreach($arrMatch as $am){
                                $dataWriteTmp .= chr(9);
                                $dataWriteTmp .= $am; // Length & Decimal
                            }
                            $dataWriteTmp .= chr(9);
                            $dataWriteTmp .= $arrField[1]; // Special
                        }else{
                            $dataWriteTmp .= chr(9);
                            $dataWriteTmp .= $arrMatch[0]; // Length
                            $dataWriteTmp .= chr(9);
                            $dataWriteTmp .= ""; // Decimal
                            $dataWriteTmp .= chr(9);
                            $dataWriteTmp .= $arrField[1]; // Special
                        }
                    }else{
                        $dataWriteTmp .= chr(9);
                        $dataWriteTmp .= $arrField[0];
                        $dataWriteTmp .= chr(9);
                        $dataWriteTmp .= ""; // Length
                        $dataWriteTmp .= chr(9);
                        $dataWriteTmp .= ""; // Decimal
                        $dataWriteTmp .= chr(9);
                        $dataWriteTmp .= $arrField[1]; // Special
                    }
                }
                $dataWriteTmp .= chr(9);
                $dataWriteTmp .= $default; // Default 
                $dataWriteTmp .= chr(9);
                $dataWriteTmp .= ""; // New Type
                $dataWriteTmp .= chr(9);
                $dataWriteTmp .= ""; // New Length
                $dataWriteTmp .= chr(9);
                $dataWriteTmp .= ""; // New Decimal
                $dataWriteTmp .= chr(9);
                $dataWriteTmp .= ""; // New Special
                $dataWriteTmp .= chr(9);
                $dataWriteTmp .= ""; // New Default
            }

            $dataResult = array();
            foreach($dataTable as $itemDatabase){
                if(in_array($itemDatabase, $data['database'])){
                    $dataResult[] = 1;
                }else{
                    $dataResult[] = '';
                }
            }
            $dataWrite[] = $dataWriteTmp.chr(9).implode("\t",$dataResult);
        }
        $header = 'Column'.chr(9).'Type'.chr(9).'Length'.chr(9).'Decimal'.chr(9).'Special'.chr(9).'Default'.chr(9).'New Type'.chr(9).'New Length'.chr(9).'New Decimal'.chr(9).'New Special'.chr(9).'New Default'.chr(9).implode("\t",$dataTable);
        array_unshift($dataWrite, $header);
        $create = fopen($path.$filename, "a");
        $write = fwrite($create, implode("\r\n", $dataWrite));
        fclose($create);
    }

    public function reserve_table(){
        $from = microtime(true);
        set_time_limit(0);
        $mainPath = '\\\LOCAL\IFRCVN\VNDB\STRUCTURE\RESERVE\\';
        $filenameLog = 'log_reserve.txt';
        write_log($mainPath, $filenameLog, ' *** Start Function Reserve Table ******************* ');
        write_log($mainPath, $filenameLog, ' * -------------------------------------------------- ');
        $path = '\\\LOCAL\IFRCVN\VNDB\STRUCTURE\\';
        $filename = 'review_all.txt';
        $numColumnAdd = 0;
        $numColumnModify = 0;
        $dataFile = file($path.$filename);
        $header = $dataFile[0];
        $arrHeader = explode("\t",$header);
        unset($dataFile[0]);
        $dataTmp = array();
        foreach($dataFile as $data){
            $arrData = explode("\t",$data);
            $dataFix = array();
            foreach($arrData as $key => $value){
                if($key != 0 && $key != 1 && $key != 2 && $key != 3 && $key != 4 && $key != 5 && $key != 6 && $key != 7 && $key != 8 && $key != 9 && $key != 10){
                    if($arrData[$key] == 1){
                        $dataFix[] = $arrHeader[$key];
                    }
                }else{
                    $dataFix[] = $arrData[$key];
                }
            }
            $column = $dataFix[0];
            if($dataFix[7] != '' && $dataFix[8] != ''){
                $typeNew = $dataFix[6].'('.$dataFix[7].','.$dataFix[8].')';
            }elseif($dataFix[7] != '' && $dataFix[8] == ''){
                $typeNew = $dataFix[6].'('.$dataFix[7].')';
            }else{
                $typeNew = $dataFix[6];
            }
            $typeNew .= ' '.$dataFix[9];
            $setDefault = '';
            if($dataFix[5] != ''){
                if($dataFix[10] == ''){
                    $setDefault = 'DROP DEFAULT'; 
                }else{
                    if($dataFix[5] != $dataFix[10]){
                         $setDefault = 'SET DEFAULT "'.$dataFix[10].'"';
                    }    
                }
            }else{
                if($dataFix[10] != ''){
                    $setDefault = 'SET DEFAULT "'.$dataFix[10].'"';
                }
            }
            unset($dataFix[0],$dataFix[1],$dataFix[2],$dataFix[3],$dataFix[4],$dataFix[5],$dataFix[6],$dataFix[7],$dataFix[8],$dataFix[9],$dataFix[10]);
            foreach($dataFix as $dtFix){
                $arrFix = explode('-',$dtFix);
                $database = trim($arrFix[0]);
                $table = trim($arrFix[1]);
                $dataTmp[$database][$table][] = array(
                    'column' => $column,
                    'type' => $typeNew,
                    'default' => $setDefault
                );
            }
        }
        foreach($dataTmp as $database => $tables){
            if($database == 'ectn'){
                foreach($tables as $table => $columns){
                    if($table == 'article'){
                        $numColumn = count($columns);
                        $check = 1;
                        for($i=0;$i<$numColumn;$i++){
                            if(trim($columns[$i]['type']) != ''){
                                $checkField = $this->db->query('SHOW COLUMNS FROM `'.$database.'`.`'.$table.'` WHERE field = "'.$columns[$i]['column'].'"')->row_array();
                                similar_text($checkField['Type'], trim($columns[$i]['type']),$similarity_pst);
                                if($similarity_pst == 100){
                                    $check = $check * 1;
                                }else{
                                    $check = $check * 0;
                                }
                            }else{
                                $check = $check * 1;
                            }
                        }
                        if($check == 0){
                            write_log($mainPath, $filenameLog, ' * | Database: '.$database);
                            write_log($mainPath, $filenameLog, ' * | - Table: '.$table);
                            $status = $this->db->query('SELECT * FROM information_schema.TABLES WHERE TABLE_NAME = "'.$table.'" AND TABLE_SCHEMA = "'.$database.'"')->row_array();
                            write_log($mainPath, $filenameLog, ' * | - Rows: '.$status['TABLE_ROWS']);
                            write_log($mainPath, $filenameLog, ' * | ------------------------------------------------ ');
                            foreach($columns as $column){

                                $column['type'] = trim($column['type']);
                                if($column['type'] != ''){
                                    $checkField = $this->db->query('SHOW COLUMNS FROM `'.$database.'`.`'.$table.'` WHERE field = "'.$column['column'].'"')->row_array();
                                    if(count($checkField) == 0){
                                        write_log($mainPath, $filenameLog, ' * | - Column '.$column['column'].' doesn\'t exists');
                                        //$this->db->query('ALTER TABLE `'.$database.'`.`'.$table.'` ADD COLUMN `'.$column['column'].'` '.$column['type']);
                                        $numColumnAdd++;
                                        write_log($mainPath, $filenameLog, ' * | - Add column '.$column['column'].' with '.$column['type']);
                                    }else{
                                        $checkType = $this->db->query('SHOW COLUMNS FROM `'.$database.'`.`'.$table.'` WHERE Field = "'.$column['column'].'" AND Type="'.$column['type'].'"')->num_rows();
                                        // pre('SHOW COLUMNS FROM `'.$database.'`.`'.$table.'` WHERE Field = "'.$column['column'].'" AND Type="'.$column['type'].'"');
                                        if($checkType == 0){
                                            if($checkField['Null'] == 'NO'){
                                                $checkField['Null'] = 'NOT NULL';
                                            }else{
                                                $checkField['Null'] = 'NULL';
                                            }
                                            if($checkField['Extra'] != ''){
                                                //$this->db->query('ALTER TABLE `'.$database.'`.`'.$table.'` MODIFY COLUMN `'.$column['column'].'` '.$column['type'].' '.$checkField['Extra'].' '.$checkField['Null']);
                                                pre('ALTER TABLE `'.$database.'`.`'.$table.'` MODIFY COLUMN `'.$column['column'].'` '.$column['type'].' '.$checkField['Extra'].' '.$checkField['Null']);
                                                $numColumnModify++;
                                                write_log($mainPath, $filenameLog, ' * | - Modify column '.$column['column'].' from '.$checkField['Type'].' '.$checkField['Extra'].' '.$checkField['Null'].' to '.$column['type'].' '.$checkField['Extra'].' '.$checkField['Null']);
                                            }else{
                                                if($checkField['Key'] == 'PRI'){
                                                    //$this->db->query('ALTER TABLE `'.$database.'`.`'.$table.'` MODIFY COLUMN `'.$column['column'].'` '.$column['type'].' auto_increment '.$checkField['Null']);
                                                    pre('ALTER TABLE `'.$database.'`.`'.$table.'` MODIFY COLUMN `'.$column['column'].'` '.$column['type'].' auto_increment '.$checkField['Null']);
                                                    $numColumnModify++;
                                                    write_log($mainPath, $filenameLog, ' * | - Modify column '.$column['column'].' from '.$checkField['Type'].' auto_increment '.$checkField['Null'].' to '.$column['type'].' auto_increment '.$checkField['Null']);
                                                }else{
                                                    //$this->db->query('ALTER TABLE `'.$database.'`.`'.$table.'` MODIFY COLUMN `'.$column['column'].'` '.$column['type'].' '.$checkField['Null']);
                                                    pre('ALTER TABLE `'.$database.'`.`'.$table.'` MODIFY COLUMN `'.$column['column'].'` '.$column['type'].' '.$checkField['Null']);
                                                    $numColumnModify++;
                                                    write_log($mainPath, $filenameLog, ' * | - Modify column '.$column['column'].' from '.$checkField['Type'].' '.$checkField['Null'].' to '.$column['type'].' '.$checkField['Null']);
                                                }
                                            }
                                        }
                                    }
                                    if($column['default'] != ''){
                                        //$this->db->query('ALTER TABLE `'.$database.'`.`'.$table.'` ALTER COLUMN `'.$column['column'].'` '.$column['default']);
                                        pre('ALTER TABLE `'.$database.'`.`'.$table.'` ALTER COLUMN `'.$column['column'].'` '.$column['default']);
                                        $numColumnModify++;
                                        write_log($mainPath, $filenameLog, ' * | - Alter column '.$column['column'].' '.$column['default']);
                                    }
                                }
                            }
                            write_log($mainPath, $filenameLog, ' * -------------------------------------------------- ');
                        }
                    }
                }
            }
        }
        $total = microtime(true) - $from;
        write_log($mainPath, $filenameLog, ' *** REPORT ***************************************** ');
        write_log($mainPath, $filenameLog, ' * - Time: '.round($total, 2).' seconds');
        write_log($mainPath, $filenameLog, ' * - '.$numColumnAdd.' columns have been add.');
        write_log($mainPath, $filenameLog, ' * - '.$numColumnModify.' columns have been modify.');
        write_log($mainPath, $filenameLog, ' *** End Function Reserve Table ********************* ');
    }
}
