<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/* * ******************************************************************************************************************* *
 * 	 Author: Minh 123			 																					     *
 * * ******************************************************************************************************************* */

class Fpts extends Admin {
    public function __construct() {
        parent::__construct();
    }
	public function index(){
		$this->template->write_view('content', 'fpts/index', $this->data);
        $this->template->write('title', 'Convert Excel');
        $this->template->render();
	}
	public function convert_excel(){
		set_time_limit(0);
		error_reporting(E_ERROR | E_WARNING | E_PARSE);
		require_once  BASEPATH. 'libraries/excel_reader2.php';
		$query = mysql_query("SELECT * FROM dms_metafield where type = 'PRICES'");
		while($data = mysql_fetch_assoc($query)){
			$result[] = $data['field'];
		}
		$header = implode(chr(9),$result);
		$dir = '\\\LOCAL\IFRCVN\VNDB\HTM\SHARES\FPT\ALL\SOURCE\\';
		$output = '\\\LOCAL\IFRCVN\VNDB\METASTOCK\SHARES\FPT\\';
		if (is_dir($dir)) {
			if ($dh = opendir($dir)) {
				while (($file = readdir($dh)) !== false) {
					if($file != '.' && $file != '..'){
						$sub_dir = "\\\LOCAL\IFRCVN\VNDB\HTM\SHARES\FPT\ALL\SOURCE\\$file\\";
						$num = basename($sub_dir);
						$files_xls = glob($sub_dir . '*.xls');
						$files_txt = glob($output . '*.txt');
						foreach($files_xls as $base_xls)
						{
							$file_name = basename($base_xls,".xls");
							$exp_name = explode('_',$file_name);
							$y = substr($exp_name[2],0,4);
							$m = substr($exp_name[2],-4,2);
							$d = substr($exp_name[2],6,8);
							$source = 'FPT';
							$date = $y."/".$m."/".$d;
							$content = $header.PHP_EOL;
							$datas = new Spreadsheet_Excel_Reader($base_xls,true,"UTF-8"); 
							$rowsnum = $datas->rowcount($sheet_index=0);
							$colsnum =  $datas->colcount($sheet_index=0); 	
							unset($main);
							$main=array();
							for ($i=$num;$i<=$rowsnum;$i++){
								if(is_numeric($datas->val($i,1))){
									if($exp_name[1] == 'HSX'){
										$shli = str_replace(',','',$datas->val($i,17));
										$shou = str_replace(',','',$datas->val($i,18));
									}elseif($exp_name[1] == 'UPC'){
										$shli = str_replace(array('*',','),'',$datas->val($i,3));
										$shou = str_replace(array('*',','),'',$datas->val($i,4));
									}else{
										$shli = str_replace(',','',$datas->val($i,3));
										$shou = str_replace(',','',$datas->val($i,4));
									}
									$main[] = $source.chr(9).$datas->val($i,2).chr(9).$exp_name[1].chr(9).$date.chr(9).$exp_name[2].chr(9).$shli.chr(9).$shou.chr(9).chr(9).chr(9).chr(9).chr(9).chr(9).chr(9).chr(9).chr(9).chr(9).chr(9).chr(9).chr(9).chr(9);
								}
							}
							$main = implode(PHP_EOL,$main);
							$content .= $main;
							$file_name = $source."_".$exp_name[1]."_".$exp_name[2].".txt";
							$create = fopen($output.$file_name, "w");
							$write = fwrite($create, $content);
							$close = fclose($create);
						}
					}
				}
				closedir($dh);
			}
		}
	}
}