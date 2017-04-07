<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Woman extends Admin {
    public function __construct() {
        parent::__construct();
        set_time_limit(0);
    }
    
    public function get_data(){
        $ch = curl_init();
    	curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
    	// you wanna follow stuff like meta and location headers
    	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    	// you want all the data back to test it for errors
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    	// probably unecessary, but cookies may be needed to
        
        $date = date('Ymd',time());
        
        $filename_stb = "//LOCAL/IFRCDATA/TESTS/WOMAN/VNDB_WOMAN_STB_{$date}.txt";
        $ticker_stb = $this->db->query("SELECT DISTINCT(CODE) FROM VNDB_COMPANY")->result_array();
        $data_file_stb = 'TICKER'."\t".'NAME_VN'."\t".'NAME_EN'."\t".'POSITIONS_VN'."\n";
        $create = fopen($filename_stb, "w");
        fwrite($create, $data_file_stb);
        fclose($create);
        $data_file_stb = '';
        foreach($ticker_stb as $tk_stb){
            $url_stb = "http://www.stockbiz.vn/Stocks/{$tk_stb['CODE']}/CompanyOfficers.aspx";
            curl_setopt($ch, CURLOPT_URL, $url_stb);
            $html_stb = curl_exec($ch);
            $start_stb = '<table width="100%" cellspacing="0" cellpadding="2" border="0" class="dataTable">
                    <tbody>';
            $end_stb = '</tbody> </table>';
            
            $start_stb = preg_quote($start_stb, '/t');
            $end_stb = preg_quote($end_stb, '/t');
            
            $rule_stb = "/(?<=$start_stb).*(?=$end_stb)/msU";
            preg_match_all($rule_stb, $html_stb, $result_stb);
            $data_stb = explode('</tr>', $result_stb[0][0]);
            unset($data_stb[0]);
            array_pop($data_stb);
            $data_stb_2 = array();
            
            foreach($data_stb as $key_stb => $item_stb){
                $item_stb = explode('</td>', $item_stb);
                $data_stb_1 = array();
                foreach($item_stb as $item_stb_1){
                        $item_stb_1 = trim(strip_tags($item_stb_1));
                        $data_stb_1[] = $item_stb_1;
                }
                array_pop($data_stb_1);
                $name_en = utf8_convert_url($data_stb_1[1],' ');
                $data_stb_2[] = $tk_stb['CODE']."\t".$data_stb_1[1]."\t".$name_en."\t".$data_stb_1[0]."\n";
            }
            $data_final_stb[$tk_stb['CODE']] = $data_stb_2;
        }
       
        foreach($data_final_stb as $item_final_stb){
            $item_final_stb = implode("",$item_final_stb);
            $create = fopen($filename_stb, "a");
            fwrite($create, $item_final_stb);
            fclose($create);
        }
        
            $ticker_vst = $this->db->query("SELECT DISTINCT(CODE) FROM VNDB_COMPANY")->result_array();

            $filename_vst = "//LOCAL/IFRCDATA/TESTS/WOMAN/VNDB_WOMAN_VST_{$date}.txt";
            $data_file_vst = 'TICKER'."\t".'FROM_DATE'."\t".'NAME_VN'."\t".'NAME_EN'."\t".'POSITIONS_VN'."\t".'BIRTHDAY'."\t".'SPECIALITY'."\t".'SHOU'."\t".'WORKSINCE'."\n";
            $create = fopen($filename_vst, "w");
            fwrite($create, $data_file_vst);
            fclose($create);
            $data_file_vst = '';

            $data_final_vst = array();
            foreach($ticker_vst as $tk_vst){
                $url_vst = "http://finance.vietstock.vn/{$tk_vst['CODE']}/ban-lanh-dao.htm";
                curl_setopt($ch, CURLOPT_URL, $url_vst);
                $html_vst = curl_exec($ch);
                $start_vst = '<table border="0" cellpadding="0" style="border-collapse: collapse" width="100%"
                        id="table370">';
                $end_vst = '</table>';
                $start_vst = preg_quote($start_vst, '/t');
                $end_vst = preg_quote($end_vst, '/t');
                $rule_vst = "/(?<=$start_vst).*(?=$end_vst)/msU";
                preg_match_all($rule_vst, $html_vst, $result_total);
                foreach($result_total as $result){
                    foreach($result as $data_final_vst){
                        $data_final_vst = explode('</tr>', $data_final_vst);
                        unset($data_final_vst[0]);
                        array_pop($data_final_vst);
                        $data_file_vst = array();
                        foreach($data_final_vst as $key_vst => $item_vst){
                            $item_vst = explode('</td>', $item_vst);
                            $data_vst = array();
                            foreach($item_vst as $item_vst_1){
                                    $item_vst_1 = trim(strip_tags($item_vst_1));
                                    $data_vst[] = $item_vst_1;
                            }
                            array_pop($data_vst);
                            if(count($data_vst) == 7){
                                    $arr_vst = explode('/',$data_vst[0]);
                                    $from_date = $arr_vst[2].'/'.$arr_vst[1].'/'.$arr_vst[0];
                                    $data_vst[0] = $from_date;
                            }
                            if(count($data_vst) < 7){
                                    array_unshift($data_vst,$from_date);
                            }
                            array_unshift($data_vst,$tk_vst['CODE']);
                            $data_vst[2] = str_replace('Ông ','',$data_vst[2]);
                            $data_vst[2] = str_replace('Bà  ','',$data_vst[2]);
                            $data_vst[6] = str_replace(',','',$data_vst[6]);
                            $data_vst[4] = str_replace('-- N/A --','',$data_vst[4]);
                            $data_vst[5] = str_replace('N/a','',$data_vst[5]);
                            $data_vst[7] = str_replace('Độc lập','1975',$data_vst[7]);
                            $data_vst[7] = str_replace('n/a','',$data_vst[7]);
                            $name_en = utf8_convert_url($data_vst[2],' ');
                            array_splice($data_vst, 3, 0, $name_en);
                            $data_file_vst[] = implode("\t",$data_vst);
                        }
                        $item_file_vst = implode("\n",$data_file_vst);
                        $item_file_vst .= "\n";
                        $create = fopen($filename_vst, "a");
                        fwrite($create, $item_file_vst);
                        fclose($create);
                    }
                }
            }
            
            $this->db->query("DROP TABLE IF EXISTS TMP_ID");
            $this->db->query("CREATE TABLE TMP_ID SELECT TICKER, ID_TICKER_STP, ID_TICKER_FPTS FROM VNDB_WOMAN WHERE NAME_VN = '0'");
            $this->db->query("ALTER TABLE TMP_ID ADD ID INT (11) NOT NULL AUTO_INCREMENT PRIMARY KEY");

            for ($i = 1; $i <= 55; $i++) {
            $url = "http://stoxplus.com/doanh_nghiep_AZ.asp?MenuID=4&subMenuID=13&ticker=&CompanyName=&IndustryID=0&CityID=&ExchangeID=100&cptvID=1&OrderBy=1&page={$i}";
                curl_setopt($ch, CURLOPT_URL, $url);
                $html = curl_exec($ch);
                $start = ' style="text-align:left;">';
                $end = '</td>';
                $start = preg_quote($start, '/t');
                $end = preg_quote($end, '/t');
                $rule = "/(?<=$start).*(?=$end)/msU";
                preg_match_all($rule, $html, $result);
                foreach($result[0] as $k => $v){
                    if($k%2){
                        unset($result[0][$k]);	
                    }
                }
                foreach($result[0] as $rs){
                    $ticker = trim(strip_tags($rs));
                    $url = preg_match('/<a href="(.+)">/', $rs, $match);
                    $info = parse_url($match[1]);
                    $id = explode('=',$info['query']);
                    $this->db->query("INSERT INTO TMP_ID (`TICKER`, `ID_TICKER_STP`) VALUES ('{$ticker}','{$id[4]}')");
                }
            }
            
            $url_fpts = 'http://ezsearch.fpts.com.vn/DataService/DataService.asmx/GetProductList';
            $result_fpts = array();
            for ($j = 0; $j <= 1070; $j = $j + 25) {
                $data_fpts = array(
                    'start' => $j,
                    'max' => 25,
                    'sortColumn' => 'stock_code',
                    'sortOrder' => 'ASC',
                    'strStockCode' => 'ALL',
                    'strExchange' => '-1', 
                    'strMinistryID' => '-1'
                );
                $json = json_encode($data_fpts);

                curl_setopt($ch, CURLOPT_URL, $url_fpts);		                                                                    
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
                curl_setopt($ch, CURLOPT_POSTFIELDS, $json);                                                                  
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
                        'Content-Type: application/json',                                                                                
                        'Content-Length: ' . strlen($json))                                                                       
                );                                                                                                                   

                $result_json = curl_exec($ch);
                $result_fpts = json_decode($result_json,1);
                foreach($result_fpts['Rows'] as $it){
                        $id_fpts = $it['CpnyID'];
                        $ticker_fpts = trim(strip_tags($it['stock_code']));
                        $this->db->query("UPDATE TMP_ID SET ID_TICKER_FPTS = '{$id_fpts}' WHERE TICKER = '{$ticker_fpts}'");
                }
            }
			
            $filename_stp = "//LOCAL/IFRCDATA/TESTS/WOMAN/VNDB_WOMAN_STP_{$date}.txt";
            $data_file_stp = 'FROM_DATE'."\t".'TICKER'."\t".'NAME_EN'."\t".'POSITIONS_EN'."\t".'SHOU'."\t".'OWNERSHIP'."\n";
            $create = fopen($filename_stp, "w");
            fwrite($create, $data_file_stp);
            fclose($create);
            $data_file_stp = '';
            $result_data_stp = $this->db->query("SELECT TICKER, ID_TICKER_STP FROM TMP_ID WHERE LENGTH(TICKER) = 3 GROUP BY TICKER")->result_array();
            foreach($result_data_stp as $item_stp){
                $url_stp = "http://stoxplus.com/stoxpage/stoxpage.asp?action=company&companyID={$item_stp['ID_TICKER_STP']}&tab_business_info=3";
                curl_setopt($ch, CURLOPT_URL, $url_stp);
                $html_stp = curl_exec($ch);
                $start_stp = '<table width="100%" border="0" cellpadding="3" cellspacing="0">';
                $end_stp = '</table>';
                $start_stp = preg_quote($start_stp, '/t');
                $end_stp = preg_quote($end_stp, '/t');
                $rule_stp = "/(?<=$start_stp).*(?=$end_stp)/msU";
                preg_match_all($rule_stp, $html_stp, $result_total_stp);
                foreach($result_total_stp as $rs_total_stp){
                    foreach($rs_total_stp as $rs_stp){
                        $rs_stp = explode('</tr>', $rs_stp);
                        array_pop($rs_stp);
                        unset($rs_stp[0]);
                        if(count($rs_stp) != 0){
                            $data_final_stp = array();
                            foreach($rs_stp as $key_stp => $item_stp_1){
                                $item_stp_1 = explode('</td>', $item_stp_1);
                                $data_stp = array();
                                foreach($item_stp_1 as $item_stp_2){
                                    $item_stp_2 = trim(strip_tags($item_stp_2));
                                    $item_stp_2 = str_replace('&nbsp;','',$item_stp_2);
                                    $data_stp[] = $item_stp_2;
                                }
                                $data_stp[2] = str_replace(',','',$data_stp[2]);
                                $data_stp[2] = str_replace('-','0',$data_stp[2]);
                                $data_stp[3] = trim($data_stp[3]);
                                $data_stp[4] = trim($data_stp[4]);
                                $data_stp[3] = str_replace('%','',$data_stp[3]);
                                $data_stp[3] = str_replace('-','0',$data_stp[3]);
                                $data_stp[4] = trim($data_stp[4]);
                                $arr_stp = explode('/',$data_stp[4]);
                                $from_date = $arr_stp[2].'/'.$arr_stp[1].'/'.$arr_stp[0];
                                array_unshift($data_stp, $from_date);
                                unset($data_stp[5]);
                                array_unshift($data_stp,$item_stp['TICKER']);
                                $data_final_stp[] = implode("\t",$data_stp);

                            }
                            $item_file_stp = implode("\n",$data_final_stp);
                            $item_file_stp .= "\n";
                            $create = fopen($filename_stp, "a");
                            fwrite($create, $item_file_stp);
                            fclose($create);
                       }
                   }
                }
            }
        $filename_fpts = "//LOCAL/IFRCDATA/TESTS/WOWAN/VNDB_WOMAN_FPT_{$date}.txt";
        $data_file_fpts = 'TICKER'."\t".'NAME_VN'."\t".'POSITIONS_VN'."\t".'BIRTHDAY'."\t".'NATIONALITY'."\t".'EDUCATION'."\t".'SPECIALITY'."\n";
        $create = fopen($filename_fpts, "w");
        fwrite($create, $data_file_fpts);
        fclose($create);
        $data_file_fpts = '';
        $result_data_fpts = $this->db->query("SELECT TICKER, ID_TICKER_FPTS FROM TMP_ID WHERE LENGTH(TICKER) = 3 AND ID_TICKER_FPTS <> '' GROUP BY TICKER")->result_array();

        foreach($result_data_fpts as $item_fpts){
                $url_fpts = "http://ezsearch.fpts.com.vn/Services/EzData/ProcessLoadRuntime.aspx?s={$item_fpts['ID_TICKER_FPTS']}&cGroup=Overview&cPath=Services/EzData/OverviewInfoManage";
                curl_setopt($ch, CURLOPT_URL, $url_fpts);
                $html_fpts = curl_exec($ch);
                $start_fpts = ' <table cellspacing="0" border="0" id="_ctl0_DataGrid1" style="border-collapse:collapse;">';
                $end_fpts = '</table>';
                $start_fpts = preg_quote($start_fpts, '/t');
                $end_fpts = preg_quote($end_fpts, '/t');
                $rule_fpts = "/(?<=$start_fpts).*(?=$end_fpts)/msU";
                preg_match_all($rule_fpts, $html_fpts, $result_fpts);
                $data_fpts = explode('</tr>', $result_fpts[0][0]);
                unset($data_fpts[0]);
                array_pop($data_fpts);
                $data_final_fpts = array();
                if(count($data_fpts) != 0){
                    foreach($data_fpts as $key_fpts => $fpts){
                        $fpts = explode('</td>', $fpts);
                        $data_fpts2 = array();
                        foreach($fpts as $item_fpts_2){
                            $item_fpts_2 = trim(strip_tags($item_fpts_2));
                            $item_fpts_2 = str_replace('&nbsp;','',$item_fpts_2);
                            $data_fpts2[] = $item_fpts_2;
                        }
                        $data_fpts2[0] = str_replace('Ông ','',$data_fpts2[0]);
                        $data_fpts2[0] = str_replace('Bà ','',$data_fpts2[0]);
                        if($data_fpts2[2] != ''){
                            $check = strpos($data_fpts2[2], '-');
                            if ($check !== false) {
                                $data_fpts2[2] = str_replace('-','/',$data_fpts2[2]);
                            }
                            $array_date = explode('/',$data_fpts2[2]);
                            if(isset($array_date[1]) && isset($array_date[2])){
                                $data_fpts2[2] = $array_date[2].'/'.$array_date[1].'/'.$array_date[0];
                            }else{
                                $data_fpts2[2] = $array_date[0];
                            }
                        }else{
                            $data_fpts2[2] = '';
                        }
                        array_unshift($data_fpts2, $item_fpts['TICKER']);
                        unset($data_fpts2[7],$data_fpts2[8]);
                        $data_final_fpts[] =  implode("\t",$data_fpts2);

                    }
                    $item_file_fpts = implode("\n",$data_final_fpts);
                    $item_file_fpts .= "\n";
                    $create = fopen($filename_fpts, "a");
                    fwrite($create, $item_file_fpts);
                    fclose($create);
                }
            }
            $this->load_data();
    }

    public function load_data(){
        $path = '\\\LOCAL\IFRCDATA\TESTS\WOMAN\\';
        $data_files = glob("$path{*.txt,*.TXT}", GLOB_BRACE);
        $dateupd = date('Y-m-d',time());
        foreach($data_files as $file) {
            $filename = pathinfo($file, PATHINFO_FILENAME);
            $arr_filename = explode('_', $filename);
            $base_url = str_replace("\\", "\\\\", $file);
            switch ($arr_filename[2]) {
                case 'FPT':
                    $table = 'TMP_WOMAN_FPT';
                    $this->db->query("DROP TABLES IF EXISTS {$table}");
                    $this->db->query("CREATE TABLE {$table} SELECT * FROM VNDB_WOMAN WHERE TICKER = 0");
                    $this->db->query("ALTER TABLE {$table} DROP COLUMN id");
                    $this->db->query("LOAD DATA INFILE '{$base_url}' INTO TABLE {$table}  FIELDS TERMINATED BY '\t' IGNORE 1 LINES (ticker, name_vn, positions_vn, birthday, nationality, education, speciality) set sources = '{$arr_filename[2]}', dateupd = '{$dateupd}'");
                    $this->export_file($table,$path,$dateupd);
                    break;
                case 'STB':
                    $table = 'TMP_WOMAN_STB';
                    $this->db->query("DROP TABLES IF EXISTS {$table}");
                    $this->db->query("CREATE TABLE {$table} SELECT * FROM VNDB_WOMAN WHERE TICKER = 0");
                    $this->db->query("ALTER TABLE {$table} DROP COLUMN id");
                    $this->db->query("LOAD DATA INFILE '{$base_url}' INTO TABLE {$table}  FIELDS TERMINATED BY '\t' IGNORE 1 LINES (ticker, name_vn, name_en, positions_vn) set sources = '{$arr_filename[2]}', dateupd = '{$dateupd}'");
                    $this->export_file($table,$path,$dateupd);
                    break;
                case 'STP':
                    $table = 'TMP_WOMAN_STP';
                    $this->db->query("DROP TABLES IF EXISTS {$table}");
                    $this->db->query("CREATE TABLE {$table} SELECT * FROM VNDB_WOMAN WHERE TICKER = 0");
                    $this->db->query("ALTER TABLE {$table} DROP COLUMN id");
                    $this->db->query("LOAD DATA INFILE '{$base_url}' INTO TABLE {$table}  FIELDS TERMINATED BY '\t' IGNORE 1 LINES (ticker, date_ann, name_en, positions_en, shou, ownership) set sources = '{$arr_filename[2]}', dateupd = '{$dateupd}'");
                    $this->export_file($table,$path,$dateupd);
                    break;
                case 'VST':
                    $table = 'TMP_WOMAN_VST';
                    $this->db->query("DROP TABLES IF EXISTS {$table}");
                    $this->db->query("CREATE TABLE {$table} SELECT * FROM VNDB_WOMAN WHERE TICKER = 0");
                    $this->db->query("ALTER TABLE {$table} DROP COLUMN id");
                    $this->db->query("LOAD DATA INFILE '{$base_url}' INTO TABLE {$table}  FIELDS TERMINATED BY '\t' IGNORE 1 LINES (ticker, date_ann, name_vn, name_en, positions_vn, birthday, speciality, shou, worksince) set sources = '{$arr_filename[2]}', dateupd = '{$dateupd}'");
                    $this->export_file($table,$path,$dateupd);
                    break;
            }
        }
    }
    
    public function export_file($table,$path,$dateupd){
        $name = substr($table,4);
        $dateupd = str_replace('-','',$dateupd);
        $data = $this->db->query("select * from {$table}")->result_array();
        $implode = array();
        foreach ($data as $item) {
            $header = array_keys($item);
            $item['date_ann'] = str_replace('-','/', $item['date_ann']);
            $item['dateupd'] = str_replace('-','/', $item['dateupd']);
            $item['birthday'] = str_replace('-','/', $item['birthday']);
            $content[] = implode("\t", $item);
        }
        $header = implode("\t", $header);
        $content = implode("\r\n", $content);
        $file = $header . "\r\n";
        $file .= $content;
        $filename = $path."FINAL\\{$name}_{$dateupd}.txt";
        $create = fopen($filename, "w");
        $write = fwrite($create, $file);
        fclose($create);
        $this->db->query("DROP TABLES IF EXISTS {$table}");
    }

    public function get_ceo_all() {
        $this->template->write_view('content', 'woman/get_ceo_all', $this->data);
        $this->template->write('title', 'Download CEO');
        $this->template->render();
    }

    public function download_ceo() {
        $this->template->write_view('content', 'woman/download_ceo', $this->data);
        $this->template->write('title', 'Download CEO');
        $this->template->render();
    }    

    public function compare_ceo() {
        $this->template->write_view('content', 'woman/compare_ceo', $this->data);
        $this->template->write('title', 'Compare CEO');
        $this->template->render();
    }

    public function import_ceo() {
        $this->template->write_view('content', 'woman/import_ceo', $this->data);
        $this->template->write('title', 'Import CEO');
        $this->template->render();
    }

    public function process_download_ceo(){
        if ($this->input->is_ajax_request()) {
            $from = microtime(true);
            $date = date('Y-m-d');
            $this->download_ceo_part($date);

            $total = microtime(true) - $from;
            $return[0]['time'] = round($total, 2);
            $return[0]['task'] = 'Time';
            echo json_encode($return);
        }
    }

    public function process_compare_ceo(){
        if ($this->input->is_ajax_request()) {
            $from = microtime(true);
            $return[0]['report'] = array();
            $return[0]['report'] = $this->compare_ceo_part($return[0]['report']);
            
            $total = microtime(true) - $from;
            $return[0]['time'] = round($total, 2);
            $return[0]['task'] = 'Time';
            echo json_encode($return);
        }
    }

    public function process_import_ceo(){
        if ($this->input->is_ajax_request()) {
            $from = microtime(true);
            $date = date('Y-m-d');
            $this->import_ceo_part($date);
            
            $total = microtime(true) - $from;
            $return[0]['time'] = round($total, 2);
            $return[0]['task'] = 'Time';
            echo json_encode($return);
        }
    }

    public function process_get_ceo_all(){
        if ($this->input->is_ajax_request()) {
            $from = microtime(true);
            $date = date('Y-m-d');
            $this->download_ceo_part($date);
            $return[0]['report'] = array();
            $return[0]['report'] = $this->compare_ceo_part($return[0]['report']);
            //$this->import_ceo_part($date);

            $total = microtime(true) - $from;
            $return[0]['time'] = round($total, 2);
            $return[0]['task'] = 'Time';
            echo json_encode($return);
        }
    }

    public function download_ceo_part($date){
        header('Content-Type: text/html; charset=utf-8');
        $this->db->query('TRUNCATE TABLE all_women_caf_tmp');
        //$this->db->query('INSERT INTO all_women_caf_tmp (ticker,en_name,market) (SELECT ticker, `name`, market FROM `vndb_reference_daily` WHERE date = (SELECT max(date) as date FROM `vndb_reference_daily`))');
        //$i=0;
        $options = array(
            CURLOPT_RETURNTRANSFER => true, // return web page
            CURLOPT_FOLLOWLOCATION => true, // follow redirects
            CURLOPT_USERAGENT => "minh", // who am i
            CURLOPT_AUTOREFERER => true, // set referer on redirect
            CURLOPT_CONNECTTIMEOUT => 120, // timeout on connect
            CURLOPT_TIMEOUT => 120, // timeout on response
            CURLOPT_MAXREDIRS => 10, // stop after 10 redirects
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        // you wanna follow stuff like meta and location headers
        curl_setopt_array( $ch, $options );
        
        //while(1){
            //$j = $i+500;
            //$dataTicker = $this->db->query('SELECT * FROM all_women_caf LIMIT '.$i.','.$j)->result_array();
            //$dataTicker = $this->db->query('SELECT * FROM all_women_caf_tmp')->result_array();
            $dataTicker = $this->db->query('SELECT ticker, `name`, market FROM `vndb_reference_daily` WHERE date = (SELECT max(date) as date FROM `vndb_reference_daily`)')->result_array();
            if(count($dataTicker) == 0){
                break;
            }else{
                $dataUpdate = array();
                foreach($dataTicker as $ticker){
                    $url = 'http://s.cafef.vn/hastc/'.$ticker['ticker'].'/ban-lanh-dao.chn';
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                    curl_setopt($ch, CURLOPT_VERBOSE, TRUE);
                    $html = curl_exec($ch);
                    $start = '<table class="cl_ceo" cellspacing="0" cellpadding="0" border="0" style="width: 100%">';
                    $end = '</table>';
                    $start1 = preg_quote($start, '/t');
                    $end1 = preg_quote($end, '/t');
                    $rule = "/(?<=$start1).*(?=$end1)/msU";
                    $result = array();
                    preg_match_all($rule, $html, $result);
                    if(count($result[0]) >= 3){
                        $dataNeed = explode('</tr>',$result[0][2]);
                        $dataNeed = $dataNeed[1];
                        $dataNeed = explode('</td>',$dataNeed);
                        $dataFinal = array();
                        foreach($dataNeed as $dN){
                            $dN = strip_tags($dN);
                            $dataFinal[] = preg_replace('/\s+/', ' ', trim($dN));
                        }
                        if(isset($dataFinal[1])){
                            $ceo = $dataFinal[1];
                        }else{
                            $ceo = '';
                        }
                        // $dataUpdate[] = array(
                        //     'id' => $ticker['id'],
                        //     'position' => $dataFinal[0],
                        //     'CEO' => $ceo,
                        //     'date' => $date
                        // );
                        $dataInsert = array(
                            'ticker' => $ticker['ticker'],
                            'market' => $ticker['market'],
                            'en_name' => $ticker['name'],
                            'position' => $dataFinal[0],
                            'CEO' => $ceo,
                            'date' => $date
                        );
                        //pre($dataUpdate);die();
                        $this->db->insert('all_women_caf_tmp',$dataInsert);
                    }else{
                        // $dataUpdate[] = array(
                        //     'id' => $ticker['id'],
                        //     'date' => $date
                        // );
                        $dataInsert = array(
                            'ticker' => $ticker['ticker'],
                            'market' => $ticker['market'],
                            'en_name' => $ticker['name'],
                            'date' => $date
                        );
                        // @$this->db->update('all_women_caf_tmp',$dataUpdate,'id');
                        $this->db->insert('all_women_caf_tmp',$dataInsert);
                        continue;
                    }
                }
                //pre($dataUpdate);
                // if(count($dataUpdate) != 0){
                //     @$this->db->update_batch('all_women_caf_tmp',$dataUpdate,'id');
                // }
                //die();
            }
            //pre($dataUpdate);
            //$i = $j;
        //}
    }

    public function compare_ceo_part($array){
        $status = $this->db->query('SELECT * FROM information_schema.TABLES WHERE TABLE_NAME = "all_women_caf" AND TABLE_SCHEMA = "ifrcdata_db"')->row_array();
        //$return[0]['report'] = array();
        if($status['TABLE_ROWS'] != 0){
            $dataCompare = $this->db->query('SELECT t1.id, t1.ticker, t2.CEO, t1.CEO as CEO_tmp FROM all_women_caf_tmp as t1 left join all_women_caf as t2 on t1.ticker = t2.ticker WHERE t1.CEO <> t2.CEO  AND t2.`date` = (SELECT MAX(`date`) as `date` FROM all_women_caf)')->result_array();
            $array = array(
                count($dataCompare).' row change.'
            );
            foreach($dataCompare as $compare){
                array_push($array, 'CEO of '.$compare['ticker'].' change from '.$compare['CEO'].' to '.$compare['CEO_tmp']);
            }
        }else{
            $this->db->query('INSERT INTO all_women_caf (ticker,en_name,market,position,ceo,`date`) (SELECT ticker,en_name,market,position,ceo,`date` FROM `all_women_caf_tmp`)');               
        }
        return $array;
    }

    public function import_ceo_part($date){
        $numDate = $this->db->query('SELECT count(DISTINCT(`date`)) as numDate, MIN(`date`) as minDate, MAX(`date`) as maxDate FROM all_women_caf')->row_array();
        if(strtotime($date) != strtotime($numDate['maxDate'])){
            $this->db->query('INSERT INTO all_women_caf (ticker,en_name,market,position,ceo,`date`) (SELECT ticker,en_name,market,position,ceo,`date` FROM `all_women_caf_tmp`)');               
            if($numDate['numDate'] >= 2){
                $this->db->query('DELETE FROM all_women_caf WHERE `date` = "'.$numDate['minDate'].'"');
            }
        }
    }
}