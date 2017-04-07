<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/* * ******************************************************************************************************************* *
 * 	 Author: Minh																										 *
 * * ******************************************************************************************************************* */

class Update_Indexes extends Admin {
    public function __construct() {
        parent::__construct();
    }
	public function update_indexes(){
		$dir = '\\\LOCAL\IFRCDATA\DOWNLOADS\VNDB\METASTOCK\INDEXES\\';
		//$dir = 'D:\DOWNLOADS\VNDB\METASTOCK\INDEXES\\';
		if (file_exists ($dir)) {
			if (is_dir ($dir)) {
				$dh = opendir ($dir) or die (" Directory Open failed !");
				while ($file = readdir ($dh)) {
					$sub_dir = '\\\LOCAL\IFRCDATA\DOWNLOADS\VNDB\METASTOCK\INDEXES\\'.$file.'\\';
					//$sub_dir = 'D:\DOWNLOADS\VNDB\METASTOCK\INDEXES\\'.$file.'\\';
					$files = glob($sub_dir . '*.txt');
					foreach($files as $sub_file)
					{
						$data = file_get_contents($sub_file);
						$data = rtrim($data);
						$ex_n = explode("\n",$data);
						foreach($ex_n as $k => $v){
							$ex_f = explode(",",$v);
							if($ex_f[0] != "<Ticker>"){
								if($ex_f[0] == "^HASTC"){
									$market = "HNX";
								}elseif($ex_f[0] == "^VNINDEX"){
									$market = "VNI";
								}
								$value = array(
									'sources' => $file, 
									'date' => $ex_f[1], 
									'market' => @$market, 
									'index' => $ex_f[0], 
									'close' => rtrim($ex_f[5]),
								);
								$query_s = mysql_query("SELECT * FROM vndb_indexes_dwl where sources = '".$file."' and date = '".$ex_f[1]."' and market = '".@$market."'");
								$row_s = mysql_num_rows($query_s);
								if($row_s == 0){
									$data_k = array_keys($value);
									$value_k = '(`'.implode('`,`',$data_k).'`)';
									$value_v = '(\''.implode('\',\'',$value).'\')';
									$query_b1 = mysql_query("INSERT INTO vndb_indexes_dwl ".$value_k." VALUES ".$value_v."");
								}
							}
							
						}
						
					}
				}
				Closedir ($dh);
			}
		}
		$query_b2 = mysql_query("SELECT date FROM `vndb_indexes_dwl` GROUP BY date ORDER BY date");
		$query_b3 = mysql_query("delete from  vndb_indexes");
		$query_b4 = mysql_query("insert into vndb_indexes (date) (SELECT date FROM `vndb_indexes_dwl`GROUP BY date ORDER BY date desc)");
		$query_b5 = mysql_query("update vndb_indexes, vndb_indexes_dwl set vndb_indexes.vni=vndb_indexes_dwl.`close` 
		where vndb_indexes_dwl.`date`=vndb_indexes.`date` and vndb_indexes_dwl.`index`='^VNINDEX'");
		$query_b6 = mysql_query("update vndb_indexes, vndb_indexes_dwl set vndb_indexes.hnx=vndb_indexes_dwl.`close` 
		where vndb_indexes_dwl.`date`=vndb_indexes.`date` and vndb_indexes_dwl.`index`='^HASTC'");
		redirect(admin_url());
	}
	public function update_calendar(){
		$query_b1 = mysql_query("delete from vndb_calendar");
		$query_b2 = mysql_query("insert into vndb_calendar (date,vni,hnx,upc) (SELECT date,vni,hnx,upc FROM `vndb_indexes`GROUP BY date ORDER BY date desc)");
		redirect(admin_url());
	}
}