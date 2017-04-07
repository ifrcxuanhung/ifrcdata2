<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');


class synchronization_model extends CI_Model {

	 public $_table = "synchronization";

    function __construct() {
        parent::__construct();

    }

    function list_all(){
        $this->db->query('TRUNCATE TABLE synchronization');
        $path = '\\\LOCAL\IFRCVN\VNDB\SYNCHRONIZATION\vndb_synchronization.txt';
        $base_url = str_replace("\\", "\\\\", $path);
        $this->db->query("LOAD DATA LOCAL INFILE '".$base_url."' 
            INTO TABLE synchronization FIELDS TERMINATED BY '\t' LINES TERMINATED BY '\r\n' 
            IGNORE 1 LINES (from_db, from_tb, to_db, to_tb, type)");
    	return $this->db->get($this->_table)->result_array();
    }

    function search($id){
    	$this->db->where('id',$id);
    	return $this->db->get($this->_table)->row_array();
    }

    function duplicateTables($data) {
        if($data['type'] == ''){
            $result_data = file('//LOCAL/IFRCVN/VNDB/SYNCHRONIZATION/vndb_account.txt');
            $this->db->query('DROP TABLE IF EXISTS `' . $data['to_db'] . '`.`' . $data['to_tb'] . '`');
            $this->db->query('CREATE TABLE `' . $data['to_db'] . '`.`' . $data['to_tb'] . '` LIKE `' . $data['from_db'] . '`.`' . $data['from_tb'] . '`');
            $this->db->query('INSERT INTO `' . $data['to_db'] . '`.`' . $data['to_tb'] . '` SELECT * FROM `' . $data['from_db'] . '`.`' . $data['from_tb'] . '`');
            $this->db->query('OPTIMIZE TABLE `' . $data['to_db'] . '`.`' . $data['to_tb'] . '`');
        }else{
            $result_data = file('//LOCAL/IFRCVN/VNDB/SYNCHRONIZATION/vndb_account.txt');
            unset($result_data[0]);
            foreach($result_data as $rs_data){
                $arr_rs = explode(chr(9),$rs_data);
                if($arr_rs[0] == $data['type']){
                    $this->duplicateTables_host($arr_rs, $data['from_db'], $data['to_db'], $data['from_tb'], $data['to_tb']);
                }
            }
        }
            
    }

    function duplicateTables_host($connect, $source_database, $destination_database, $source_table, $destination_table) {
        set_time_limit(0);
        // Localhost
        $connect_local = mysql_connect("local","local","ifrcvn") or die(mysql_error());
        mysql_select_db( $source_database, $connect_local ) or die(mysql_error());

        //Host
        $password = base64_decode($connect[2]);
        $connect_host = mysql_connect($connect[0], $connect[1], $password, true) or die(mysql_error());
        mysql_select_db($destination_database, $connect_host ) or die(mysql_error());

        mysql_query("DROP TABLE IF EXISTS `".$destination_database."`.`".$destination_table."`",$connect_host) or die(mysql_error());

        $result = mysql_query("SELECT * FROM `".$source_database."`.`".$source_table."` limit 0",$connect_local);
        $fields = mysql_num_fields($result);
        $data_field = array();
        $data_primary = array();
        for ($i=0; $i < $fields; $i++) {
            $type  = mysql_field_type($result, $i);
            $name  = mysql_field_name($result, $i);
            $len   = mysql_field_len($result, $i);
            $flags = mysql_field_flags($result, $i);
            $type  = str_replace('string','varchar',$type);
            $type  = str_replace('real','double',$type);
            $type  = str_replace('blob','longtext',$type);
            $type  = strtolower($type);
            $flags = str_replace('not_null','not null',$flags);
            if(strrpos($flags,"primary_key")){
                $data_primary[] = $name;
            }
            $flags = str_replace('primary_key','',$flags);
            $flags = str_replace('multiple_key','',$flags);
            $flags = str_replace('unsigned','',$flags);
            $flags = str_replace('binary','',$flags);
            if($type == 'double' || $type == 'date' || $type == 'time' || $type == 'longtext'){
                if($type == 'longtext'){
                    $data_final[] = '`'.$name.'` '.$type;
                }else{
                    $data_final[] = '`'.$name.'` '.$type.' '.$flags;
                }
            }else{
                $data_final[] = '`'.$name.'` '.$type.'('.$len.') '.$flags;
            }
        }
        mysql_free_result($result);
        $field_final = implode(',',$data_final);
        if(count($data_primary) != 0){
            $fiels_primary = implode(',',$data_primary);
            mysql_query("CREATE TABLE `".$destination_database."`.`".$destination_table."` (".$field_final.",PRIMARY KEY (".$fiels_primary."))" ,$connect_host) or die(mysql_error());
        }else{
            mysql_query("CREATE TABLE `".$destination_database."`.`".$destination_table."` (".$field_final.")" ,$connect_host) or die(mysql_error());
        }
        //

        $data_field_index = mysql_query("SHOW INDEX FROM `".$source_database."`.`".$source_table."`",$connect_local);
        $data_index = array();
        while ($row = mysql_fetch_row($data_field_index)) {
            if($row[1] == 1){
                $key_name = $row[2];
                if($key_name == $row[2]){
                    $data_index[$key_name][] =  $row[4];
                }
            }
            
        }
        foreach($data_index as $key_index => $value_index){
            $count_index = count($value_index);
            $indexes_name = $key_index;
            if($count_index > 0){
                if($count_index > 1){
                    $indexes_colum = implode(',',$value_index);
                }else{
                    $indexes_colum = trim($value_index[0]);
                }
                mysql_query('CREATE INDEX `'.$indexes_name.'` ON `'.$destination_database.'`.`'.$destination_table.'` ('.$indexes_colum.') USING BTREE',$connect_host);
            } 
        }
        
        //
        $result = mysql_query("SELECT * FROM `".$source_database."`.`".$source_table."`",$connect_local);
        $fh = fopen("//LOCAL/IFRCVN/VNDB/SYNCHRONIZATION/{$source_table}.txt", "w+"); // Write value into file
        while ($row = mysql_fetch_row($result)) {
            fputs($fh, implode("\t", $row)."\r\n");
        }
        fclose ($fh);

        $path = '\\\LOCAL\IFRCVN\VNDB\SYNCHRONIZATION\\'.$source_table.'.txt';
        $base_url = str_replace("\\", "\\\\", $path);
        mysql_query("LOAD DATA LOCAL INFILE '".$base_url."' INTO TABLE `".$destination_database."`.`".$destination_table."` CHARACTER SET utf8 FIELDS TERMINATED BY '\t' LINES TERMINATED BY '\r\n'",$connect_host) or die(mysql_error());

        unlink('\\\LOCAL\IFRCVN\VNDB\SYNCHRONIZATION\\'.$source_table.'.txt');

        mysql_query('OPTIMIZE TABLE `' . $destination_database . '`.`' . $destination_table . '`',$connect_host) or die(mysql_error());

        mysql_close($connect_host);           
    }
}
?>