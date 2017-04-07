<?php

class Task extends MY_Controller {

    function __construct() {
        parent::__construct();
        date_default_timezone_set("Asia/Ho_Chi_Minh"); 
    }

    function synchorize_daily(){
        $this->process_synchorize('DAILY','//LOCAL/IFRCVN/VNDB/SYNCHRONIZATION/DAILY/');
    }

    function synchorize_weekly(){
        $this->process_synchorize('WEEKLY','//LOCAL/IFRCVN/VNDB/SYNCHRONIZATION/WEEKLY/');
    }

    function synchorize_monthly(){
        $this->process_synchorize('MONTHLY','//LOCAL/IFRCVN/VNDB/SYNCHRONIZATION/MONTHLY/');
    }

    function process_synchorize($type, $path){
        $from = microtime(true);
        set_time_limit(0);
        ini_set("memory_limit","-1");
        
        $mainPath = $path;
        $pathLog = $mainPath.'LOG/';
        $filenameLog = 'log_'.date("Ymd").'.txt';
        $numDatabase = 0;
        $numTableDump = 0;
        $numRows = 0;
        write_log($pathLog, $filenameLog, ' *** START SYNCHRONIZATION '.$type.' ******************* ');
        write_log($pathLog, $filenameLog, ' * -------------------------------------------------- ');
        write_log($pathLog, $filenameLog, ' * | PATH: '.$mainPath);

        $dataAccount = file($mainPath.'../vndb_account.txt');
        unset($dataAccount[0]);
        $dataAccountFinal = array();
        foreach($dataAccount as $account){
            $arrAccount = explode("\t",$account);
            $dataAccountFinal[$arrAccount[0]] = array(
                'username' => $arrAccount[1],
                'password' => base64_decode($arrAccount[2])
            );
        }
        $dataFile = file($mainPath.'list_sync.txt');
        unset($dataFile[0]);
        $dataDomain = array();
        foreach($dataFile as $data){
            $arrData = explode("\t",$data);
            $host = trim($arrData[4]);
            $dataDomain[] = $host;
            if($host != $arrData[4]){
                $dataData[$host][] = array(
                    'from_db' => $arrData[0],
                    'from_tb' => $arrData[1],
                    'to_db' => $arrData[2],
                    'to_tb' => $arrData[3]
                );
            }   
        }
        $dataDomain = array_unique($dataDomain);
        foreach($dataDomain as $domain){
            $numDatabase++;
            $dataDataAction = $dataData[$domain];
            $dataAccountAction = $dataAccountFinal[$domain];
            //Host
            $connect_host = mysql_connect($domain, $dataAccountAction['username'], $dataAccountAction['password'], true);
            if($connect_host){
                write_log($pathLog, $filenameLog, ' * | Hosting: '.$domain);
                foreach($dataDataAction as $action){
                    $source_database = $action['from_db'];
                    $destination_database = $action['to_db'];

                    $source_table = $action['from_tb'];
                    $destination_table = $action['to_tb'];
                    write_log($pathLog, $filenameLog, ' * | ------------------------------------------------ ');
                    $checkTable = $this->db->query('SELECT * FROM information_schema.tables WHERE table_schema = "'.$source_database.'" AND table_name = "'.$destination_table.'" LIMIT 1')->num_rows();
                    if($checkTable != 0){

                        exec('C:/xampp/mysql/bin/mysqldump -h local -u local -pifrcvn '. $source_database .' '. $source_table .'  > '.$mainPath.$source_table.'.sql');
                        $numTableDump++;
                        $status = $this->db->query('SELECT * FROM information_schema.TABLES WHERE TABLE_NAME = "'.$destination_table.'" AND TABLE_SCHEMA = "'.$source_database.'"')->row_array();
                        $numRows = $numRows + $status['TABLE_ROWS'];
                        write_log($pathLog, $filenameLog, ' * | - Export table '.$source_table.' '.$status['TABLE_ROWS'].' rows to '.$source_table.'.sql');

                        $db_selected = mysql_select_db($destination_database, $connect_host );
                        if ($db_selected) {
                            write_log($pathLog, $filenameLog, ' * | - Import '.$source_table.'.sql to table '.$destination_table);

                            $templine = '';

                            $lines = file($mainPath.$source_table.".sql");

                            foreach ($lines as $line){

                                if (substr($line, 0, 2) == '--' || $line == ''){
                                    continue;
                                }
                             
                                $templine .= $line;

                                if (substr(trim($line), -1, 1) == ';'){

                                    $query = mysql_query($templine,$connect_host);
                                    if(!$query){
                                        write_log($pathLog, $filenameLog, ' * | - Run Query: False. '.mysql_error());
                                    }
                                    $templine = '';
                                    
                                }
                            }
                            unlink($mainPath.$source_table.".sql"); 
                            write_log($pathLog, $filenameLog, ' * | - Delete '.$source_table.'.sql');
                        }else{
                            write_log($pathLog, $filenameLog, ' * | - Select Database: '.$destination_database.' False. '.mysql_error());
                        }
                    }else{
                        write_log($pathLog, $filenameLog, ' * | - Table: '.$destination_table.' haven\'t exists. ');
                    }
                }
            }else{
                write_log($pathLog, $filenameLog, ' * | Connect Hosting: '.$domain.' False. '.mysql_error());
            }
            write_log($pathLog, $filenameLog, ' * -------------------------------------------------- ');
        }
        $total = microtime(true) - $from;
        write_log($pathLog, $filenameLog, ' *** REPORT ***************************************** ');
        write_log($pathLog, $filenameLog, ' * - TIME: '.round($total, 2).' SECONDS');
        write_log($pathLog, $filenameLog, ' * - DATABASE: '.$numDatabase);
        write_log($pathLog, $filenameLog, ' * - EXPORT & IMPORT: '.$numTableDump.' TABLES');
        write_log($pathLog, $filenameLog, ' * - TOTAL ROWS: '.$numRows);
        write_log($pathLog, $filenameLog, ' *** END SYNCHRONIZATION '.$type.' ********************** ');
    }
}