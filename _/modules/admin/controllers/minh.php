<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/* * ******************************************************************************************************************* *
 * 	 Author: Minh Rất Đẹp Trai Hehe 																					 *
 * 	 Description: Controller để test tất cả action			 															 *
 * * ******************************************************************************************************************* */

class Minh extends Admin {

    public function __construct() {
        parent::__construct();
        $this->load->library('curl');
        $this->load->library('simple_html_dom');
        set_time_limit(0);
    }

    public function merge_dividend() {
        $dir = '\\\LOCAL\IFRCVN\VNDB\METASTOCK\DIVIDEND\FINAL\\';
        $now_date = date('Ymd', time());
        if (is_dir($dir)) {
            $dh = opendir($dir) or die(" Directory Open failed !");
            while ($file = readdir($dh)) {
                if ($file == 'STP') {
                    $dir_source = $dir . $file . '\\';
                    $filename = $dir . 'DIV_' . $file . '.txt';
                    $files = glob($dir_source . '*.txt');
                    $data = array();
                    foreach ($files as $base) {
                        $file_name = basename($base, ".txt");
                        $file_name = explode('_', $file_name);
                        $data = file_get_contents($base, FILE_USE_INCLUDE_PATH);
                        $data = explode("\r\n", trim($data));
                        if ($file_name[1] != 'AAA') {
                            unset($data[0]);
                        }
                        if (empty($data)) {
                            unset($data);
                        } else {
                            $data = implode("\r\n", $data);
                            $data .= "\r\n";
                            $file = fopen($filename, "a");
                            $write = fwrite($file, $data);
                            fclose($file);
                        }
                    }
                }
            }
        }
        $files = glob($dir . '*.txt');
        $this->db->query('DROP TABLE IF EXISTS TMP');
        $this->db->query('CREATE TABLE TMP (
            source VARCHAR(5),
            ticker varchar(6),
            `date` date,
            date_ex date,
            date_rec date,
            date_pay date,
            pay_method varchar(100),
            pay_year varchar(100),
            pay_period varchar(100),
            dividend double,
            yield	double,
            price_exd DOUBLE
        )');
        foreach ($files as $base) {
            $filename = basename($base, ".txt");
            if ($filename == 'DIV_STP') {
                $base_url = str_replace("\\", "\\\\", $base);
                $this->db->query("LOAD DATA LOCAL INFILE '" . $base_url . "' INTO TABLE tmp FIELDS TERMINATED BY '\t' IGNORE 1 LINES");
            }
        }
        $this->db->query('DROP TABLE IF EXISTS TMP_2');
        $this->db->query('CREATE TABLE TMP_2 SELECT * FROM TMP ORDER BY DATE_EX DESC');

        $data = $this->db->query("SELECT * FROM TMP_2")->result_array();
        $implode = array();
        foreach ($data as $item) {
            $header = array_keys($item);
            $implode[] = implode("\t", $item);
        }
        $header = implode("\t", $header);
        $implode = implode("\n", $implode);
        $file = $header . "\r\n";
        $file .= $implode;
        $filename = $dir . 'DIV_STP.txt';
        $create = fopen($filename, "w");
        $write = fwrite($create, $file);
        fclose($create);
    }


    public function loadFileInData(){
        $this->db->query("TRUNCATE TABLE vndb_files");
        $path = '\\\LOCAL\IFRCVN\VNDB\TESTS\vndb_files.txt';
        $data_file = file($path);
        $data_header = $data_file[0];
        $arr_header = explode("\t",$data_header);
        unset($data_file[0]);
        $data_final = array();
        foreach($data_file as $data){
            $arr_data = explode("\t",$data);
            $data_final_item = array();
            foreach($arr_data as $key => $item){
                $data_final_item[trim($arr_header[$key])] = trim($item);
            }
            $data_final[] = $data_final_item;
        }
        $this->db->insert_batch('vndb_files',$data_final);
    }

    public function get_data_caf() {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        // you wanna follow stuff like meta and location headers
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        // you want all the data back to test it for errors
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // probably unecessary, but cookies may be needed to
        $base_url = 'http://s.cafef.vn';
        $ticker = $this->db->query("SELECT DISTINCT TICKER FROM qidx_mdata_year")->result_array();
        $data_tab = array(
            '1' => 'TINH_HINH_SXKD',
            '2' => 'TRA_CO_TUC',
            '3' => 'THAY_DOI_NHAN_SU',
            '4' => 'TANG_VON',
            '5' => 'GD_CO_DONG_LON'
        );
        foreach ($ticker as $tk) {
            foreach ($data_tab as $tab_k => $tab_v) {
                $path = "//LOCAL/IFRCVN/VNDB/WEBSITE/" . $tab_v . "/";
                $name = "CAF_{$tk['TICKER']}.txt";
                $filename = $path . $name;
                if (!is_file($filename)) {
                    $header = 'TICKER' . "\t" . 'MARKET' . "\t" . 'DATE_ANN' . "\t" . 'EVNAME' . "\t" . 'LINK' . "\t" . 'CONTENT' . "\t" . 'LINK_PDF';
                    $header .= "\r\n";
                    $create = fopen($filename, "w");
                    fwrite($create, $header);
                    fclose($create);
                    $i = 0;
                    while (1) {
                        ++$i;
                        $url = "http://s.cafef.vn/Ajax/Events_RelatedNews_New.aspx?symbol={$tk['TICKER']}&floorID=2&configID={$tab_k}&PageIndex={$i}&PageSize=30&Type=2";
                        curl_setopt($ch, CURLOPT_URL, $url);
                        $html = curl_exec($ch);
                        $start = '<li style="list-style-type: none;border-bottom: 1px solid #D4E0E0;line-height:30px;background:none';
                        $end = '</li>';
                        $start1 = preg_quote($start, '/t');
                        $end1 = preg_quote($end, '/t');
                        $rule = "/(?<=$start1).*(?=$end1)/msU";
                        $result = array();
                        preg_match_all($rule, $html, $result);
                        $count_row = count($result[0]);
                        foreach ($result as $rs) {
                            foreach ($rs as $rs_sub) {
                                $date_ann = array();
                                preg_match('/<span class="timeTitle">([^\"]*)<\/span>/', $rs_sub, $date_ann);
                                $data_link = array();
                                preg_match('/<a class=\'docnhanhTitle\' href="([^\"]*)" title="([^\"]*)">(.*)<\/a>/', $rs_sub, $data_link);
                                $url_1 = $base_url . $data_link['1'];
                                curl_setopt($ch, CURLOPT_URL, $url_1);
                                $html_1 = curl_exec($ch);
                                $start_intro = '<h2 class="intro">';
                                $end_intro = '</h2>';
                                $start1_intro = preg_quote($start_intro, '/t');
                                $end1_intro = preg_quote($end_intro, '/t');
                                $rule_intro = "/(?<=$start1_intro).*(?=$end1_intro)/msU";
                                $result_intro = array();
                                preg_match_all($rule_intro, $html_1, $result_intro);
                                $start_content = '<div id="newscontent">';
                                $end_content = '</div> <div style="clear: both;">';
                                $start1_content = preg_quote($start_content, '/t');
                                $end1_content = preg_quote($end_content, '/t');
                                $rule_content = "/(?<=$start1_content).*(?=$end1_content)/msU";
                                $result_content = array();
                                preg_match_all($rule_content, $html_1, $result_content);
                                $data_content['intro'] = $result_intro[0][0];
                                //$result_content[0][0] = preg_replace('/<div align="right"><strong>(.*)<\/strong><br><\/div><\/div><\/div><\/div><p align=\'right\' id=\'pSignature\'><em>(.*)<\/em><\/p>/','', $result_content[0][0]);
                                if (strpos($result_content[0][0], 'File đính kèm :')) {
                                    $data_link_pdf = array();
                                    preg_match('/<a href="([^\"]*)">(.*)<\/a>/', $result_content[0][0], $data_link_pdf);
                                    //$result_content[0][0] = preg_replace('/<div style="TEXT-ALIGN: right"> <span style="font-size: 10pt; font-family: Arial; font-weight: normal; font-style: italic;">(.*)<\/span><\/div>/','', $result_content[0][0]);
                                    $data_content['link_pdf'] = $data_link_pdf[1];
                                } else {
                                    $data_content['link_pdf'] = '';
                                }
                                $result_content[0][0] = preg_replace("/&#?[a-z0-9]+;/i", " ", $result_content[0][0]);
                                $result_content[0][0] = str_replace(array("\r\n", "\r", "\n"), ' ', trim($result_content[0][0]));
                                $data_content['content'] = strip_tags(ltrim($result_content[0][0]));
                                $filename_1 = "//LOCAL/IFRCVN/VNDB/WEBSITE/$tab_v/CAF_{$tk['TICKER']}.txt";
                                $data_import = $tk['TICKER'] . "\t" . '' . "\t" . $date_ann[1] . "\t" . $data_link[3] . "\t" . $url_1 . "\t" . $data_content['content'] . "\t" . $data_content['link_pdf'];
                                $data_import .= "\r\n";
                                $create = fopen($filename_1, "a");
                                fwrite($create, $data_import);
                                fclose($create);
                            }
                        }
                        if ($count_row == 0) {
                            break;
                        }
                    }
                }
            }
        }
    }

    public function get_data_ticker() {
        $path = "//LOCAL/IFRCVN/VNDB/WEBSITE/";
        $data_file = file($path . 'LIST_PVN.txt');
        array_shift($data_file);
        $ticker = array();
        foreach ($data_file as $df) {
            $arr_df = explode("\t", $df);
            $ticker[] = $arr_df[0];
        }
        $data_tab = array('TINH_HINH_SXKD', 'TRA_CO_TUC', 'THAY_DOI_NHAN_SU', 'TANG_VON', 'GD_CO_DONG_LON');
        foreach ($data_tab as $folder) {
            $file_name = $path . $folder . '.txt';
            $header = 'TICKER' . "\t" . 'MARKET' . "\t" . 'DATE_ANN' . "\t" . 'EVNAME' . "\t" . 'LINK' . "\t" . 'CONTENT' . "\t" . 'LINK_PDF';
            $header .= "\r\n";
            $create = fopen($file_name, "w");
            fwrite($create, $header);
            fclose($create);
            foreach ($ticker as $tk) {
                $files = glob($path . $folder . '/' . '*.txt');
                foreach ($files as $file) {
                    $filename = basename($file, '.txt');
                    $arr_filename = explode('_', $filename);
                    if ($arr_filename[1] == $tk) {
                        $data_file2 = file($file);
                        array_shift($data_file2);
                        $data_import = implode("", $data_file2);
                        $create = fopen($file_name, "a");
                        fwrite($create, $data_import);
                        fclose($create);
                    }
                }
            }
        }
    }

    public function merge_pvn() {
        $path = "//LOCAL/IFRCVN/VNDB/WEBSITE/PVN/";
        $header = 'TICKER' . "\t" . 'MARKET' . "\t" . 'DATE_ANN' . "\t" . 'EVNAME' . "\t" . 'LINK' . "\t" . 'CONTENT' . "\t" . 'LINK_PDF' . "\t" . 'TYPE';
        $header .= "\r\n";
        $file_name = $path . '../' . 'PVN_FINAL.txt';
        $create = fopen($file_name, "w");
        fwrite($create, $header);
        fclose($create);
        $files = glob($path . '*.txt');
        foreach ($files as $file) {
            $type = basename($file, '.txt');
            if ($type != 'VNDB_EVENT_DAY' && $type != 'CPH') {
                $data_file = file($file);
                array_shift($data_file);
                $data_df = array();
                foreach ($data_file as $df) {
                    $arr_df = explode("\t", $df);
                    array_push($arr_df, trim($type));
                    $arr_df[6] = trim($arr_df[6]);
                    $arr_df[5] = trim($arr_df[5]);
                    $time = $arr_df[2];
                    $arr_time = explode(" ", $time);
                    $year = substr($arr_time[0], -4);
                    $month = substr($arr_time[0], 3, 2);
                    $day = substr($arr_time[0], 0, 2);
                    $time_data = $year . '/' . $month . '/' . $day;
                    $arr_df[2] = $time_data;
                    $from = '2011/01/01';
                    $to = date('Y/m/d', time());
                    if ($time_data >= $from && $time_data <= $to) {
                        $data_df[] = implode("\t", $arr_df);
                    }
                }
                $data_import = implode("\r\n", $data_df);
                $create = fopen($file_name, "a");
                fwrite($create, $data_import);
                fclose($create);
            }
        }
        $base_url = '\\\LOCAL\IFRCVN\VNDB\WEBSITE\PVN_FINAL.txt';
        $base_url = str_replace('\\', '\\\\', $base_url);
        $this->db->query('TRUNCATE TABLE vndb_pvn_tmp');
        $this->db->query("LOAD DATA LOCAL INFILE '{$base_url}' INTO TABLE vndb_pvn_tmp FIELDS TERMINATED BY '\t' IGNORE 1 LINES");
        $this->db->query("DROP TABLE IF EXISTS TMP");
        $this->db->query("CREATE TABLE TMP SELECT * FROM vndb_pvn_tmp ORDER BY date_ann DESC");

        $this->db->query("ALTER TABLE TMP DROP COLUMN ID");
        $this->db->query("ALTER TABLE TMP ADD ID INT(11) NOT NULL primary KEY AUTO_INCREMENT");

        $this->db->query("TRUNCATE TABLE vndb_pvn_tmp");
        $this->db->query("INSERT INTO vndb_pvn_tmp SELECT * FROM TMP");

        $data_new = $this->db->get('vndb_pvn_tmp')->result_array();
        $data_file_new = array();
        foreach ($data_new as $dn) {
            array_pop($dn);
            $dn['content'] = trim($dn['content']);
            $dn['link_pdf'] = trim($dn['link_pdf']);
            $dn['type'] = trim($dn['type']);
            $data_file_new[] = implode("\t", $dn);
        }
        $data_file = implode("\r\n", $data_file_new);
        $header .= $data_file;
        $file_name = '//LOCAL/IFRCVN/VNDB/WEBSITE/PVN_FINAL.txt';
        $create = fopen($file_name, "w");
        fwrite($create, $header);
        fclose($create);
    }

    public function merge_pvn2() {
        $path = "//LOCAL/IFRCVN/VNDB/WEBSITE/PVN/";
        $data_file = file($path . '../LIST_PVN.txt');
        array_shift($data_file);
        $ticker = array();
        foreach ($data_file as $df) {
            $arr_df = explode("\t", $df);
            $ticker[] = $arr_df[0];
        }
        $header = 'TICKER' . "\t" . 'MARKET' . "\t" . 'DATE_ANN' . "\t" . 'EVNAME' . "\t" . 'LINK' . "\t" . 'CONTENT';
        $header .= "\r\n";
        $file_name = $path . '../' . 'VNDB_EVENT_DAY_PVN.txt';
        $create = fopen($file_name, "w");
        fwrite($create, $header);
        fclose($create);
        $files = glob($path . '*.txt');
        foreach ($files as $file) {
            $type = basename($file, '.txt');
            if ($type == 'VNDB_EVENT_DAY') {
                $data_file2 = file($file);
                array_shift($data_file2);
                foreach ($data_file2 as $df2) {
                    $data_df2 = array();
                    foreach ($ticker as $tk) {
                        $arr_df2 = explode("\t", $df2);
                        if ($arr_df2[0] == $tk) {
                            $data_df2[] = $df2;
                        }
                    }
                    $data_import = implode("\r\n", $data_df2);
                    $create = fopen($file_name, "a");
                    fwrite($create, $data_import);
                    fclose($create);
                }
            }
        }
        $base_url = '//LOCAL/IFRCVN/VNDB/WEBSITE/VNDB_EVENT_DAY.txt';
        $base_url = str_replace('/', '//', $base_url);
        $this->db->query('TRUNCATE TABLE vndb_pvn_tmp2');
        $this->db->query("LOAD DATA LOCAL INFILE '{$base_url}' INTO TABLE vndb_pvn_tmp2 FIELDS TERMINATED BY '/t' IGNORE 1 LINES");
        $this->db->query("DROP TABLE IF EXISTS TMP");
        $this->db->query("CREATE TABLE TMP SELECT * FROM vndb_pvn_tmp2 ORDER BY date_ann DESC");

        $this->db->query("ALTER TABLE TMP DROP COLUMN ID");
        $this->db->query("ALTER TABLE TMP ADD ID INT(11) NOT NULL primary KEY AUTO_INCREMENT");

        $this->db->query("TRUNCATE TABLE vndb_pvn_tmp2");
        $this->db->query("INSERT INTO vndb_pvn_tmp2 SELECT * FROM TMP");

        $data_new = $this->db->get('vndb_pvn_tmp2')->result_array();
        $data_file_new = array();
        foreach ($data_new as $dn) {
            array_pop($dn);
            $dn['link'] = trim($dn['link']);
            $data_file_new[] = implode("\t", $dn);
        }
        $data_file = implode("\r\n", $data_file_new);
        $header .= $data_file;
        $file_name = '//LOCAL/IFRCVN/VNDB/WEBSITE/VNDB_EVENT_DAY_PVN.txt';
        $create = fopen($file_name, "w");
        fwrite($create, $header);
        fclose($create);
    }

    public function get_data_cph() {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        // you wanna follow stuff like meta and location headers
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        // you want all the data back to test it for errors
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // probably unecessary, but cookies may be needed to
        $path = "//LOCAL/IFRCVN/VNDB/WEBSITE/PVN/";
        $data_ticker = file($path . '../LIST_PVN.txt');
        array_shift($data_ticker);
        $filename = $path . "CPH.txt";
        $header = 'TICKER' . "\t" . 'EVNAME' . "\t" . 'DATE_EX' . "\t" . 'DATE_PAY' . "\t" . 'RATIO' . "\t" . 'DIVIDEND' . "\t" . 'PREF' . "\t" . 'CONTENT';
        $header .= "\r\n";
        $create = fopen($filename, "w");
        fwrite($create, $header);
        fclose($create);
        foreach ($data_ticker as $dt) {
            $arr_dt = explode("\t", $dt);
            $url = 'http://www.cophieu68.com/events.php?stockname=' . $arr_dt[0];
            curl_setopt($ch, CURLOPT_URL, $url);
            $html = curl_exec($ch);
            $start = '<p><div class="paginator" style="text-align:right">';
            $end = '</div></p>';
            $start1 = preg_quote($start, '/t');
            $end1 = preg_quote($end, '/t');
            $rule = "/(?<=$start1).*(?=$end1)/msU";
            $result = array();
            preg_match_all($rule, $html, $result);
            $data_page = explode(" ", strip_tags($result[0][0]));
            $total_page = $data_page[8];
            $count_row = ceil($total_page / 15);
            for ($i = 1; $i <= $count_row; $i++) {
                $url_1 = 'http://www.cophieu68.com/events.php?stockname=' . $arr_dt[0] . '&currentPage=' . $i;
                curl_setopt($ch, CURLOPT_URL, $url_1);
                $html_1 = curl_exec($ch);
                $start_1 = '<table width="100%" cellpadding="4" cellspacing="0" bgcolor="#E2EFFE">';
                $end_1 = '</table>';
                $start1_1 = preg_quote($start_1, '/t');
                $end1_1 = preg_quote($end_1, '/t');
                $rule_1 = "/(?<=$start1_1).*(?=$end1_1)/msU";
                $result_1 = array();
                preg_match_all($rule_1, $html_1, $result_1);
                $data_rs1 = explode("</tr>", $result_1[0][0]);
                array_shift($data_rs1);
                foreach ($data_rs1 as $drs1) {
                    $arr_drs1 = explode("</td>", $drs1);
                    if (count($arr_drs1) == 8) {
                        $data_ardrs1 = array();
                        foreach ($arr_drs1 as $ardrs1) {
                            if (strpos($ardrs1, '<hr noshade="noshade" size="1px" color="#CCCCCC" />')) {
                                $arr_ardrs1 = explode('<hr noshade="noshade" size="1px" color="#CCCCCC" />', $ardrs1);
                                $data_aadr1 = array();
                                foreach ($arr_ardrs1 as $aadr1) {
                                    $aadr1 = str_replace(array("\t", "\r\n", "\n"), '', $aadr1);
                                    $aadr1 = strip_tags(trim($aadr1));
                                    if (strpos($aadr1, 'đồng/cổ phiếu') || strpos($aadr1, 'GDKHQ')) {
                                        if (strpos($aadr1, 'đồng/cổ phiếu')) {
                                            $aadr1 = str_replace(array('đồng/cổ phiếu', ','), '', $aadr1);
                                            $data_aadr1[] = trim($aadr1);
                                        } elseif (strpos($aadr1, 'GDKHQ')) {
                                            $aadr1 = preg_replace('/(.*)GDKHQ:/', '', $aadr1);
                                            $aadr1 = str_replace(',', '', $aadr1);
                                            $data_aadr1[] = trim($aadr1);
                                        }
                                    } else {
                                        $data_aadr1[] = '';
                                    }
                                }
                                $ardrs1 = implode("|", $data_aadr1);
                            } elseif (strpos($ardrs1, 'Khối lượng phát hành:')) {
                                $data_aadr1 = array('', '');
                                $ardrs1 = implode("|", $data_aadr1);
                            }
                            $ardrs1 = preg_replace("/([0-9]{2})\/([0-9]{2})\/([0-9]{4})/", "$3/$2/$1", $ardrs1);
                            $ardrs1 = str_replace(array("\t", "\r\n", "\n", "%"), '', $ardrs1);
                            $data_ardrs1[] = strip_tags(trim($ardrs1));
                        }
                    } else {
                        foreach ($arr_drs1 as $ardrs1) {
                            $ardrs1 = str_replace(array("\t", "\r\n", "\n"), '', $ardrs1);
                            $ardrs1 = html_entity_decode($ardrs1, ENT_NOQUOTES, 'UTF-8');
                            array_push($data_ardrs1, strip_tags(trim($ardrs1)));
                        }
                    }
                    if (count($data_ardrs1) == 10) {
                        $data_price = explode("|", $data_ardrs1[6]);
                        $data_import = $data_ardrs1[1] . "\t" . $data_ardrs1[2] . "\t" . $data_ardrs1[3] . "\t" . $data_ardrs1[4] . "\t" . $data_ardrs1[5] . "\t" . $data_price[0] . "\t" . $data_price[1] . "\t" . $data_ardrs1[8];
                        $data_import .= "\r\n";
                        $create = fopen($filename, "a");
                        fwrite($create, $data_import);
                        fclose($create);
                    }
                }
            }
        }
    }

    public function data_freefloat() {
        $this->db->query("DROP TABLE IF EXISTS TMP_FREEFLOAT");
        $this->db->query("CREATE TABLE TMP_FREEFLOAT (stk_code CHAR(6), stk_name VARCHAR(100), stk_curr CHAR(3), stk_shares DOUBLE, stk_float DOUBLE, stk_capp DOUBLE, idx_code CHAR(10), start_date DATE, end_date DATE)");
        $path_file = '\\\LOCAL\IFRCVN\VNDB\METASTOCK\FREE_FLOAT\LIST_GROUP.txt';
        $base_url = str_replace('\\', '\\\\', $path_file);
        $this->db->query("LOAD DATA LOCAL INFILE '{$base_url}' INTO TABLE TMP_FREEFLOAT FIELDS TERMINATED BY '\t' IGNORE 1 LINES");
    }

    public function update_ca_type() {
        //header('Content-Type: text/html; charset=utf-8');
        //$this->db->query("TRUNCATE TABLE PVN_CA_KEYWORDS");
        //$this->db->query("LOAD DATA LOCAL INFILE '//LOCAL/IFRCVN/WORKS/PVN/PVN_CA_KEYWORDS.txt' INTO TABLE PVN_CA_KEYWORDS FIELDS TERMINATED BY '\t' IGNORE 1 LINES");
        $data_result = $this->db->query("select * from vndb_news_day")->result_array();
        //$data_file = file("//LOCAL/IFRCVN/VNDB/WEBSITE/LIST_PVN.txt");
        //array_shift($data_file);
        // foreach ($data_file as $df) {
        //     $arr_df = explode("\t", $df);
        //     $ticker[] = $arr_df[0];
        // }
        foreach ($data_result as $dr) {
            $filter = $this->search_type($dr['evname']);
            if($filter == ''){
                $filter = 'OTHER';
            }
            $data_update[] = array(
                'id' => $dr['id'],
                'new_type' => $filter
            );
            //$this->db->query("UPDATE vndb_news_day SET new_type = '" . $filter . "' WHERE ID = '" . $dr['id'] . "'");
            // if (in_array($dr['ticker'], $ticker)) {
            //     $this->db->query("UPDATE pvn_ca_events SET PVN = 1 WHERE ID = '" . $dr['id'] . "'");
            // }    
        }
        //pre($data_update);
        //$this->db->update_batch('vndb_news_day',$data_update,'id');
    }

    public function search_type($content) {
        
        pre(mb_convert_encoding($content, 'UTF-8', 'UTF-8'));
        $this->db->where('ca_type <>','OTHER');
        $data_keywords = $this->db->get("pvn_ca_keywords")->result_array();
        foreach ($data_keywords as $dk) {
            $arr_dk = explode(',', $dk['ca_keywords']);
            $keywords = array();
            foreach ($arr_dk as $adk) {
                $keywords[][$dk['ca_type']] = trim($adk);
            }
            $data_keywords_final[] = $keywords;
        }
        foreach ($data_keywords_final as $dkf) {
            foreach ($dkf as $sub_dkf) {
                //$filter = array();
                foreach ($sub_dkf as $sd_key => $sd_value) {
                    if((strpos( $content, $sd_value) != '')){
                        $filter[] = $sd_key;
                    }
                }
            }
        }
        if (isset($filter) && !empty($filter)) {
            if (in_array("STOCK DIVIDEND", $filter) && in_array("CASH DIVIDEND", $filter)) {
                $filter[0] = 'CASH DIVIDEND';
            }
            return $filter[0];
        } else {
            return $filter = '';
        }
    }

    public function get_date() {
        $strDateFrom = '2012/01/01';
        $strDateTo = '2013/05/30';
        $aryRange = array();

        $iDateFrom = mktime(1, 0, 0, substr($strDateFrom, 5, 2), substr($strDateFrom, 8, 2), substr($strDateFrom, 0, 4));
        $iDateTo = mktime(1, 0, 0, substr($strDateTo, 5, 2), substr($strDateTo, 8, 2), substr($strDateTo, 0, 4));

        if ($iDateTo >= $iDateFrom) {
            array_push($aryRange, date('Y-m-d', $iDateFrom));

            while ($iDateFrom < $iDateTo) {
                $iDateFrom+=86400;
                array_push($aryRange, date('Y-m-d', $iDateFrom));
            }
        }
    }

    public function cut_event_pvn() {
        header('Content-Type: text/html; charset=utf-8');
        $id = $_GET['id'];
        $type = $_GET['type'];
        $data_cash = $this->db->query("select * from pvn_ca_events where ca_type = 'CASH DIVIDEND' and id={$id}")->result_array();
        $data_stock = $this->db->query("select * from pvn_ca_events where ca_type = 'STOCK DIVIDEND' and id={$id}")->result_array();
        if ($type == 'cash') {
            $result_cash = $this->update_cash($data_cash);
            pre($result_cash);
        } else {
            $result_stock = $this->update_stock($data_stock);
            pre($result_stock);
        }
        //$this->db->update_batch('pvn_ca_events',$result_cash,'id');
        //$this->db->update_batch('pvn_ca_events',$result_stock,'id');
    }

    public function update_cash($data_cash) {
        foreach ($data_cash as $dr) {
            $data_date_rec = '';
            $data_date_ex = '';
            $data_period = '';
            $data_year = '';
            $data_ratio = '';
            $data_divtr = '';
            $data_date_pay = '';
            if (strpos($dr['content'], "span") && strpos($dr['content'], "id=")) {
                $dr['content'] = html_entity_decode(htmlspecialchars_decode($dr['content']));
                pre($dr['content']);
                $dr['content'] = str_replace("&nbsp;", '', $dr['content']);
                $dr['content'] = html_entity_decode(htmlspecialchars_decode($dr['content']));
                $arr_dr = explode("</li>", $dr['content']);
                pre($arr_dr);
                //die();
                if (count($arr_dr) > 1) {
                    foreach ($arr_dr as $adr) {
                        $adr = strip_tags($adr);
                        $opt_date_rec = 'cuối cùng:';
                        $opt_date_rec_2 = 'cuối cùng :';
                        $opt_date_ex = 'Ngày giaodịch không hưởng quy�?n:';
                        $opt_date_ex_2 = 'Ngàygiao dịch không hưởng quy�?n :';
                        $opt_date_ex_3 = 'Ngày giaodịch không hưởng quy�?n :';
                        $opt_date_ex_4 = 'Ngày giao dịchkhông hưởng quy�?n:';
                        $opt_per = 'vàmục đích:';
                        $opt_ratio = 'lệthực hiện:';
                        $opt_ratio_2 = 'lệ thựchiện:';
                        $opt_ratio_3 = 'lệ trả cổ tức:';
                        $opt_date_pay = 'gianthực hiện';
                        $opt_date_pay_2 = 'gianthực hiện:';
                        $opt_date_pay_3 = 'gian thực hiện:';

                        if (strpos($adr, $opt_date_ex) || strpos($adr, $opt_date_ex_2) || strpos($adr, $opt_date_ex_3) || strpos($adr, $opt_date_ex_4)) {
                            //pre($adr);
                            pre($opt_date_ex);
                            $data_date_ex = trim(preg_replace('/(.*)(' . $opt_date_ex . '|' . $opt_date_ex_2 . '|' . $opt_date_ex_3 . ')/', '', $adr));
                            $data_date_ex = trim(preg_replace('/( |Ngày)(.*)/', '', $data_date_ex));
                            pre($data_date_ex);
                        }
                        if (strpos($adr, $opt_date_rec) || strpos($adr, $opt_date_rec_2)) {
                            //pre($adr);
                            $data_date_rec = trim(preg_replace('/(.*)(' . $opt_date_rec . '|' . $opt_date_rec_2 . ')/', '', $adr));
                            $arr_ddr = explode('/', $data_date_rec);
                            $arr_ddr[2] = substr($arr_ddr[2], 0, 4);
                            $data_date_rec = $arr_ddr[0] . '/' . $arr_ddr[1] . '/' . $arr_ddr[2];
                            //pre($data_date_rec);
                        }
                        if (strpos($adr, $opt_per)) {
                            if (strpos($adr, 'đợt')) {
                                $data_period = trim(preg_replace('/(.*) đợt/', '', $adr));
                                $data_period = trim(preg_replace('/( |\/|\.)(.*)/', '', $data_period));
                            } else {
                                $data_period = '';
                            }
                            if (strpos($adr, 'năm')) {
                                $data_year = trim(preg_replace('/(.*) năm/', '', $adr));
                                $data_year = substr($data_year, 0, 4);
                            } else {
                                $data_year = '';
                            }
                        }
                        if (strpos($adr, $opt_ratio) || strpos($adr, $opt_ratio_2) || strpos($adr, $opt_ratio_3)) {
                            //pre($adr);
                            $data_ratio = trim(preg_replace('/(.*):/', '', $adr));
                            $data_ratio = trim(preg_replace('/%(.*)/', '', $data_ratio));
                            //pre($data_ratio);
                            $data_divtr = trim(preg_replace('/(.*) (nhận|\()/', '', $adr));
                            $data_divtr = trim(preg_replace('/đồng(.*)/', '', $data_divtr));
                            $data_divtr = str_replace('.', '', $data_divtr);
                            // pre($data_divtr);
                        }
                        if (strpos($adr, $opt_date_pay) || strpos($adr, $opt_date_pay_2) || strpos($adr, $opt_date_pay_3)) {
                            //pre($adr);
                            $data_date_pay = trim(preg_replace('/(.*):/', '', $adr));
                            $data_date_pay = trim(preg_replace('/ (.*)/', '', $data_date_pay));
                            //pre($data_date_pay);
                        }
                    }
                } else {
                    $data_date_rec = '';
                    $data_date_ex = '';
                    $data_period = '';
                    $data_year = '';
                    $data_ratio = '';
                    $data_divtr = '';
                    $data_date_pay = '';
                }
            } elseif (strpos($dr['content'], "p") && strpos($dr['content'], "class=")) {
                $dr['content'] = html_entity_decode(htmlspecialchars_decode($dr['content']));
                pre($dr['content']);
                $dr['content'] = str_replace("&nbsp;", '', $dr['content']);
                $arr_dr = explode("</p>", $dr['content']);
                pre($arr_dr);
                foreach ($arr_dr as $adr) {
                    $adr = strip_tags($adr);
                    $opt_date_rec = 'cuối cùng:';
                    $opt_date_ex = 'Ngày giao dịch không hưởng quy�?n:';
                    $opt_per = 'và mục đích:';
                    $opt_ratio = 'lệ thực hiện:';
                    $opt_date_pay = 'gian thực hiện';
                    if (strpos($adr, $opt_date_ex)) {
                        //pre($adr);
                        $data_date_ex = trim(preg_replace('/(.*)' . $opt_date_ex . '/', '', $adr));
                        $data_date_ex = trim(preg_replace('/ (.*)/', '', $data_date_ex));
                        //pre($data_date_ex);
                    }
                    if (strpos($adr, $opt_date_rec)) {
                        //pre($adr);
                        $data_date_rec = trim(preg_replace('/(.*)(' . $opt_date_rec . ')/', '', $adr));
                        $arr_ddr = explode('/', $data_date_rec);
                        $arr_ddr[2] = substr($arr_ddr[2], 0, 4);
                        $data_date_rec = $arr_ddr[0] . '/' . $arr_ddr[1] . '/' . $arr_ddr[2];
                        //pre($data_date_rec);
                    }
                    if (strpos($adr, $opt_per)) {
                        //pre($adr);
                        if (strpos($adr, 'đợt')) {
                            $data_period = trim(preg_replace('/(.*) đợt/', '', $adr));
                            $data_period = trim(preg_replace('/( |\/|\.)(.*)/', '', $data_period));
                        } else {
                            $data_period = '';
                        }
                        //pre($data_period);
                        if (strpos($adr, 'năm')) {
                            $data_year = trim(preg_replace('/(.*) năm/', '', $adr));
                            $data_year = substr($data_year, 0, 4);
                        } else {
                            $data_year = '';
                        }
                        //pre($data_year);
                    }
                    if (strpos($adr, $opt_ratio)) {
                        //pre($adr);
                        $data_ratio = trim(preg_replace('/(.*):/', '', $adr));
                        $data_ratio = trim(preg_replace('/%(.*)/', '', $data_ratio));
                        //pre($data_ratio);
                        $data_divtr = trim(preg_replace('/(.*) (nhận|\()/', '', $adr));
                        $data_divtr = trim(preg_replace('/đồng(.*)/', '', $data_divtr));
                        $data_divtr = str_replace('.', '', $data_divtr);
                        //pre($data_divtr);
                    }
                    if (strpos($adr, $opt_date_pay)) {
                        //pre($adr);
                        $data_date_pay = trim(preg_replace('/(.*):/', '', $adr));
                        $data_date_pay = trim(preg_replace('/ (.*)/', '', $data_date_pay));
                        //pre($data_date_pay);
                    }
                }
            } else {
                $dr['content'] = html_entity_decode(htmlspecialchars_decode($dr['content']));
                pre($dr['content']);
                $dr['content'] = str_replace('&amp;nbsp;', '', $dr['content']);
                $arr_dr = explode('&lt;br/&gt;', $dr['content']);
                pre($arr_dr);
                //die();
                foreach ($arr_dr as $adr) {
                    $opt_date_rec = 'Ng&agrave;y đăng k&yacute; cuối c&ugrave;ng:';
                    $opt_date_ex = 'Ng&agrave;y giao dịch kh&ocirc;ng hưởng quy�?n:';
                    $opt_per = 'Tạm ứng cổ tức bằng ti�?n';
                    $opt_ratio = 'Tỷ lệ thực hiện:';
                    $opt_date_pay = 'Th�?i gian thực hiện:';
                    if (strpos($adr, $opt_date_rec)) {
                        $data_date_rec = trim(preg_replace('/(.*)' . $opt_date_rec . '/', '', $adr));
                    }
                    if (strpos($adr, $opt_date_ex)) {
                        $data_date_ex = trim(preg_replace('/(.*)' . $opt_date_ex . '/', '', $adr));
                    }
                    if (strpos($adr, $opt_per)) {
                        pre($adr);
                        $data_period = trim(preg_replace('/(.*) (' . $opt_per . '|đợt)/', '', $adr));
                        $data_period = trim(preg_replace('/( |\/|\.|năm)(.*)/', '', $data_period));
                        if ($data_period == 'cuối') {
                            $data_period = 'CN';
                        }
                        pre($data_period);
                        $data_year = trim(preg_replace('/(.*) (' . $opt_per . '|năm)/', '', $adr));
                        $data_year = trim(preg_replace('/(:)(.*)/', '', $data_year));
                        //$data_year = substr($data_year,0,4);
                        pre($data_year);
                    }
                    if (strpos($adr, $opt_ratio)) {
                        //pre($adr);
                        //pre($opt_ratio);
                        $data_ratio = trim(preg_replace('/(.*)(' . $opt_ratio . '|th&ocirc;ng:)/', '', $adr));
                        $data_ratio = trim(preg_replace('/%(.*)/', '', $data_ratio));
                        //pre($data_ratio);
                        $data_divtr = trim(preg_replace('/(.*) (nhận|\(|được|được|bằng|đương|phiếu\()/', '', $adr));
                        $data_divtr = trim(preg_replace('/(đồng|đ&ocirc;̀ng|đ\/cp|đ\/ cổ phiếu|đ\/cổ phiếu|đ|đồng\/cổ phiếu)(.*)/', '', $data_divtr));
                        $data_divtr = trim(preg_replace('/(.*) /', '', $data_divtr));
                        $data_divtr = str_replace('.', '', $data_divtr);
                        //pre($data_divtr);
                    }
                    if (strpos($adr, $opt_date_pay)) {
                        //pre($adr);
                        //pre($opt_date_pay);
                        $data_date_pay = trim(preg_replace('/(.*)(' . $opt_date_pay . '|Ng&agrave;y|Ngày|ng&agrave;y|ngày)/', '', $adr));
                        $data_date_pay = trim(str_replace(array(' th&aacute;ng ', ' năm '), '/', $data_date_pay));
                        //pre($data_date_pay);
                    }
                }
            }
            if ($data_date_rec != '') {
                list($day_rec, $month_rec, $year_rec) = explode("/", $data_date_rec);
                $date_rec = $year_rec . '/' . $month_rec . '/' . $day_rec;
            } else {
                $date_rec = '';
            }
            if ($data_date_ex != '') {
                list($day_ex, $month_ex, $year_ex) = explode("/", $data_date_ex);
                $date_ex = $year_ex . '/' . $month_ex . '/' . $day_ex;
            } else {
                $date_ex = '';
            }
            if ($data_period != '') {
                $period = $data_period;
            } else {
                $period = '';
            }
            if ($data_year != '') {
                $year = $data_year;
            } else {
                $year = '';
            }
            if ($data_ratio != '') {
                $ratio = $data_ratio;
            } else {
                $ratio = '';
            }
            if ($data_divtr != '') {
                $divtr = $data_divtr;
            } else {
                $divtr = '';
            }
            if ($data_date_pay != '') {
                list($day_pay, $month_pay, $year_pay) = explode("/", $data_date_pay);
                $date_pay = $year_pay . '/' . $month_pay . '/' . $day_pay;
            } else {
                $date_pay = '';
            }
            $result_array[] = array(
                'id' => $dr['id'],
                'date_rec' => $date_rec,
                'date_ex' => $date_ex,
                'period' => $period,
                'year' => $year,
                'ratio' => $ratio,
                'divtr' => $divtr,
                'date_pay' => $date_pay
            );
        }
        return $result_array;
    }

    public function update_stock($data_stock) {
        $result_array = array();
        foreach ($data_stock as $dr) {
            $data_date_rec = '';
            $data_date_ex = '';
            $data_period = '';
            $data_year = '';
            $data_ratio = '';
            $data_divtr = '';
            $data_date_pay = '';
            if (strpos($dr['content'], "span") && strpos($dr['content'], "id=")) {
                $dr['content'] = html_entity_decode(htmlspecialchars_decode($dr['content']));
                pre($dr['content']);
                $dr['content'] = str_replace("&nbsp;", '', $dr['content']);
                $arr_dr = explode("</li>", $dr['content']);
                pre($arr_dr);
                //die();
                if (count($arr_dr) > 1) {
                    foreach ($arr_dr as $adr) {
                        $adr = strip_tags($adr);
                        $opt_date_rec = 'cuối cùng:';
                        $opt_date_rec_2 = 'cuối cùng :';
                        $opt_date_ex = 'Ngày giaodịch không hưởng quy�?n:';
                        $opt_per = 'vàmục đích:';
                        $opt_ratio = 'lệthực hiện:';
                        $opt_date_pay = 'gianthực hiện';
                        if (strpos($adr, $opt_date_ex)) {
                            pre($adr);
                            pre($opt_date_ex);
                            $data_date_ex = trim(preg_replace('/(.*)' . $opt_date_ex . '/', '', $adr));
                            $data_date_ex = trim(preg_replace('/ (.*)/', '', $data_date_ex));
                            pre($data_date_ex);
                        }
                        if (strpos($adr, $opt_date_rec) || strpos($adr, $opt_date_rec_2)) {
                            //pre($adr);
                            $data_date_rec = trim(preg_replace('/(.*)(' . $opt_date_rec . '|' . $opt_date_rec_2 . ')/', '', $adr));
                            $arr_ddr = explode('/', $data_date_rec);
                            $arr_ddr[2] = substr($arr_ddr[2], 0, 4);
                            $data_date_rec = $arr_ddr[0] . '/' . $arr_ddr[1] . '/' . $arr_ddr[2];
                            //pre($data_date_rec);
                        }
                        if (strpos($adr, $opt_per)) {
                            if (strpos($adr, 'đợt')) {
                                $data_period = trim(preg_replace('/(.*) đợt/', '', $adr));
                                $data_period = trim(preg_replace('/( |\/|\.)(.*)/', '', $data_period));
                            } else {
                                $data_period = '';
                            }
                            if (strpos($adr, 'năm')) {
                                $data_year = trim(preg_replace('/(.*) năm/', '', $adr));
                                $data_year = substr($data_year, 0, 4);
                            } else {
                                $data_year = '';
                            }
                        }
                        if (strpos($adr, $opt_ratio)) {
                            $data_ratio = trim(preg_replace('/(.*):/', '', $adr));
                            $data_ratio = trim(preg_replace('/%(.*)/', '', $data_ratio));
                            $data_divtr = trim(preg_replace('/(.*) (nhận|\()/', '', $adr));
                            $data_divtr = trim(preg_replace('/đồng(.*)/', '', $data_divtr));
                            $data_divtr = str_replace('.', '', $data_divtr);
                        }
                        if (strpos($adr, $opt_date_pay)) {
                            //pre($adr);
                            $data_date_pay = trim(preg_replace('/(.*):/', '', $adr));
                            $data_date_pay = trim(preg_replace('/ (.*)/', '', $data_date_pay));
                            //pre($data_date_pay);
                        }
                    }
                }
            } elseif (strpos($dr['content'], "p") && strpos($dr['content'], "class=")) {
                $dr['content'] = html_entity_decode(htmlspecialchars_decode($dr['content']));
                pre($dr['content']);
                $dr['content'] = str_replace("&nbsp;", '', $dr['content']);
                $arr_dr = explode("</p>", $dr['content']);
                pre($arr_dr);
                foreach ($arr_dr as $adr) {
                    $adr = strip_tags($adr);
                    $opt_date_rec = 'cuối cùng:';
                    $opt_date_ex = 'Ngày giao dịch không hưởng quy�?n:';
                    $opt_per = 'và mục đích:';
                    $opt_ratio = 'thực hiện:';
                    $opt_date_pay = 'gian thực hiện';
                    if (strpos($adr, $opt_date_ex)) {
                        //pre($adr);
                        $data_date_ex = trim(preg_replace('/(.*)' . $opt_date_ex . '/', '', $adr));
                        $data_date_ex = trim(preg_replace('/ (.*)/', '', $data_date_ex));
                        //pre($data_date_ex);
                    }
                    if (strpos($adr, $opt_date_rec)) {
                        //pre($adr);
                        $data_date_rec = trim(preg_replace('/(.*)(' . $opt_date_rec . ')/', '', $adr));
                        $arr_ddr = explode('/', $data_date_rec);
                        $arr_ddr[2] = substr($arr_ddr[2], 0, 4);
                        $data_date_rec = $arr_ddr[0] . '/' . $arr_ddr[1] . '/' . $arr_ddr[2];
                        //pre($data_date_rec);
                    }
                    if (strpos($adr, $opt_per)) {
                        //pre($adr);
                        if (strpos($adr, 'đợt')) {
                            $data_period = trim(preg_replace('/(.*) đợt/', '', $adr));
                            $data_period = trim(preg_replace('/( |\/|\.)(.*)/', '', $data_period));
                        } else {
                            $data_period = '';
                        }
                        //pre($data_period);
                        if (strpos($adr, 'năm')) {
                            $data_year = trim(preg_replace('/(.*) năm/', '', $adr));
                            $data_year = substr($data_year, 0, 4);
                        } else {
                            $data_year = '';
                        }
                        //pre($data_year);
                    }
                    if (strpos($adr, $opt_ratio)) {
                        //pre($adr);
                        $data_ratio = trim(preg_replace('/(.*):/', '', $adr));
                        $data_ratio = trim(preg_replace('/%(.*)/', '', $data_ratio));
                        //pre($data_ratio);
                        $data_divtr = trim(preg_replace('/(.*) (nhận|\()/', '', $adr));
                        $data_divtr = trim(preg_replace('/đồng(.*)/', '', $data_divtr));
                        $data_divtr = str_replace('.', '', $data_divtr);
                        //pre($data_divtr);
                    }
                    if (strpos($adr, $opt_date_pay)) {
                        //pre($adr);
                        $data_date_pay = trim(preg_replace('/(.*):/', '', $adr));
                        $data_date_pay = trim(preg_replace('/ (.*)/', '', $data_date_pay));
                        //pre($data_date_pay);
                    }
                }
            } else {
                $dr['content'] = html_entity_decode(htmlspecialchars_decode($dr['content']));
                pre($dr['content']);
                $dr['content'] = str_replace('&amp;nbsp;', '', $dr['content']);
                $arr_dr = explode('&lt;br/&gt;', $dr['content']);
                pre($arr_dr);
                //die();
                foreach ($arr_dr as $adr) {
                    $opt_date_rec = 'Ng&agrave;y đăng k&yacute; cuối c&ugrave;ng:';
                    $opt_date_ex = 'Ng&agrave;y giao dịch kh&ocirc;ng hưởng quy�?n:';
                    $opt_per = 'Tạm ứng cổ tức';
                    $opt_per_2 = 'Thanh toán cổ tức';
                    $opt_per_3 = 'Trả cổ tức bằng ti�?n';
                    $opt_ratio = 'Tỷ lệ thực hiện:';
                    $opt_date_pay = 'Th�?i gian thực hiện:';
                    if (strpos($adr, $opt_date_rec)) {
                        $data_date_rec = trim(preg_replace('/(.*)' . $opt_date_rec . '/', '', $adr));
                    }
                    if (strpos($adr, $opt_date_ex)) {
                        $data_date_ex = trim(preg_replace('/(.*)' . $opt_date_ex . '/', '', $adr));
                    }
                    if (strpos($adr, $opt_per) || strpos($adr, $opt_per_2) || strpos($adr, $opt_per_3)) {
                        //pre($adr);
                        if (strpos($adr, 'đợt')) {
                            $data_period = trim(preg_replace('/(.*) đợt/', '', $adr));
                            $data_period = trim(preg_replace('/( |\/|\.|năm)(.*)/', '', $data_period));
                            if ($data_period == 'cuối') {
                                $data_period = 'CN';
                            }
                        } else {
                            $data_period = '';
                        }
                        //pre($data_period);
                        if (strpos($adr, 'năm')) {
                            $data_year = trim(preg_replace('/(.*) năm/', '', $adr));
                            $data_year = substr($data_year, 0, 4);
                        } else {
                            $data_year = '';
                        }
                        //pre($data_year);
                    }
                    if (strpos($adr, $opt_ratio)) {
                        //pre($adr);
                        //pre($opt_ratio);
                        $data_ratio = trim(preg_replace('/(.*)(' . $opt_ratio . '|th&ocirc;ng:)/', '', $adr));
                        $data_ratio = trim(preg_replace('/(%|\()(.*)/', '', $data_ratio));
                        //$data_ratio_2 = trim(preg_replace('/(.*)(\(|sở hữu)/','',$adr));
                        //$data_ratio_2 = trim(preg_replace('/cổ phiếu(.*)/','',$data_ratio_2));
                        if (!strpos($data_ratio, ':')) {
                            $data_ratio = '100/' . $data_ratio;
                        } else {
                            $data_ratio = str_replace(':', '/', $data_ratio);
                        }
                        //pre($data_ratio);
                        //pre($data_ratio_2);
                        $data_divtr = '';
                    }
                    if (strpos($adr, $opt_date_pay)) {
                        //pre($adr);
                        //pre($opt_date_pay);
                        $data_date_pay = trim(preg_replace('/(.*)(' . $opt_date_pay . '|Ng&agrave;y|Ngày|ng&agrave;y|ngày)/', '', $adr));
                        $data_date_pay = trim(str_replace(array(' th&aacute;ng ', ' năm '), '/', $data_date_pay));
                        //pre($data_date_pay);
                    }
                }
            }
            if ($data_date_rec != '') {
                list($day_rec, $month_rec, $year_rec) = explode("/", $data_date_rec);
                $date_rec = $year_rec . '/' . $month_rec . '/' . $day_rec;
            } else {
                $date_rec = '';
            }
            if ($data_date_ex != '') {
                list($day_ex, $month_ex, $year_ex) = explode("/", $data_date_ex);
                $date_ex = $year_ex . '/' . $month_ex . '/' . $day_ex;
            } else {
                $date_ex = '';
            }
            if ($data_period != '') {
                $period = $data_period;
            } else {
                $period = '';
            }
            if ($data_year != '') {
                $year = $data_year;
            } else {
                $year = '';
            }
            if ($data_ratio != '') {
                $ratio = $data_ratio;
            } else {
                $ratio = '';
            }
            if ($data_divtr != '') {
                $divtr = $data_divtr;
            } else {
                $divtr = '';
            }
            if ($data_date_pay != '') {
                list($day_pay, $month_pay, $year_pay) = explode("/", $data_date_pay);
                $date_pay = $year_pay . '/' . $month_pay . '/' . $day_pay;
            } else {
                $date_pay = '';
            }
            $result_array[] = array(
                'id' => $dr['id'],
                'date_rec' => $date_rec,
                'date_ex' => $date_ex,
                'period' => $period,
                'year' => $year,
                'ratio' => $ratio,
                'divtr' => $divtr,
                'date_pay' => $date_pay
            );
        }
        return $result_array;
    }

    public function update_pvn_test() {
        header('Content-Type: text/html; charset=utf-8');
        $data_file = file(APPPATH . '../../assets/rule/rule.txt');
        array_shift($data_file);
        $data_table = $this->db->query("select id,ca_type,content from pvn_ca_events")->result_array();
        $data_row = array();
        foreach ($data_table as $key => $dt) {
            if (strpos($dt['content'], "href=&quot;") && strpos($dt['content'], ".pdf")) {
                $link_left = preg_replace('/(.*)href=&quot;/', '', $dt['content']);
                $link_final = preg_replace('/&quot;(.*)/', '', $link_left);
                $link = rawurldecode(basename($link_final,'.pdf'));
                $path = '\\\LOCAL\IFRCVN\WORKS\PVN\PDF\DONE\\';
                $data_link = $this->pdf2text($path . $link.'.pdf');
                if(strlen($data_link) != 0){
                    $model = 'PDF';
                }else{
                    $model = 'TXT';
                    $path = '\\\LOCAL\IFRCVN\WORKS\PVN\PDF\DONE\TXT\\';
                    if (file_exists($path.$link.'.txt')) {
                        $data_link = file($path.$link.'.txt',FILE_IGNORE_NEW_LINES);
                        $data_link = implode('&nbsp;',$data_link);
                    }else{
                        $data_link = '';
                    }
                }
                $content = str_replace("\n", '', $data_link);
            } elseif (strpos($dt['content'], "span") && strpos($dt['content'], "id")) {
                $model = 'SPAN';
                $content = $dt['content'];
            } elseif (strpos($dt['content'], "&lt;/p&gt;")) {
                $model = 'P';
                $content = $dt['content'];
            } else {
                $model = 'NORMAL';
                $content = $dt['content'];
            }
			$content = html_entity_decode(htmlspecialchars_decode($content));
            foreach ($data_file as $df) {
                $arr_df = explode("\t", $df);
                if ($dt['ca_type'] == $arr_df[0]) {
                    if ($arr_df[1] == $model) {
                        $data_right = '';
                        $arr_df3 = explode('|', $arr_df[3]);
                        if (!isset($data[$key]['id'])) {
                            $data_row[$key]['id'] = $dt['id'];
                            $data_row[$key]['ca_type'] = $dt['ca_type'];
                            //$data_row[$key]['model'] = $model;
                        }
                        $arr_df3 = explode('|', $arr_df[3]);
                        if (count($arr_df3) > 1) {
                            $data_filter_left = implode('|', $arr_df3);
                            $data_filter_left_quote = trim(preg_quote($data_filter_left, "/"));
                            $data_filter_left_final = str_replace('\|', '|', $data_filter_left_quote);
                            $filter_left = '(' . $data_filter_left_final . ')';
                        } else {
                            $data_filter_left = implode('|', $arr_df3);
                            $data_filter_left_quote = trim(preg_quote($data_filter_left, "/"));
                            $filter_left = $data_filter_left_quote;
                        }

                        $data_left = preg_replace('/(.*)' . $filter_left . '/', '', $content);
                        $arr_df4 = explode('|', $arr_df[4]);
                        if (!empty($arr_df4) && count($arr_df4) > 1) {
                            $data_filter_right = implode('|', $arr_df4);
                            $data_filter_right_quote = trim(preg_quote($data_filter_right, "/"));
                            $data_filter_right_final = str_replace('\|', '|', $data_filter_right_quote);
                            $filter_right = '(' . $data_filter_right_final . ')';
                        } else {
                            $data_filter_right = implode('|', $arr_df4);
                            $data_filter_right_quote = trim(preg_quote($data_filter_right, "/"));
                            $filter_right = $data_filter_right_quote;
                        }
                        $data_right = trim(preg_replace('/' . $filter_right . '(.*)/', '', $data_left));
                        $data_right = strip_tags($data_right);
                        if ($arr_df[2] == 'date_ex') {
                            $data_right = str_replace(array('I','l'),array('/',1),$data_right);
                            $data_right = htmlentities($data_right, ENT_QUOTES | ENT_IGNORE, "UTF-8");
                            $data_right = str_replace(array('&nbsp;',':'),'',$data_right);
                            $arr_dr = explode('/', $data_right);
                            if (count($arr_dr) == 3) {
                                $arr_dr[0] = substr($arr_dr[0], -2);
                                $data_right = trim($arr_dr[0]) . '/' . trim($arr_dr[1]) . '/' . trim($arr_dr[2]);
                            }
                            if (preg_match("/^(\d{1,2})[- \/.](\d{1,2})[- \/.](\d{4})$/", $data_right, $data_date)) {
                                $data_right = $data_date[3] . '/' . $data_date[2] . '/' . $data_date[1];
                            } else {
                                $data_right = '';
                            }
                        }
                        if ($arr_df[2] == 'date_rec') {
                            $data_right = htmlentities($data_right, ENT_QUOTES | ENT_IGNORE, "UTF-8");
                            $data_right = str_replace(array('&nbsp;',':'),'',$data_right);
                            $arr_dr = explode('/', $data_right);
                            if (count($arr_dr) == 3) {
                                $arr_dr[0] = substr($arr_dr[0], -2);
                                $data_right = trim($arr_dr[0]) . '/' . trim($arr_dr[1]) . '/' . trim($arr_dr[2]);
                            }
                            if (preg_match("/^(\d{1,2})[- \/.](\d{1,2})[- \/.](\d{4})$/", $data_right, $data_date)) {
                                $data_right = $data_date[3] . '/' . $data_date[2] . '/' . $data_date[1];
                            } else {
                                $data_right = '';
                            }
                        }
                        if ($arr_df[2] == 'period') {
                            if ($data_right == 'cuối') {
                                $data_right = 'CN';
                            }elseif($data_right == 'I'){
                                $data_right = '1';
                            }else{
                                if (!is_numeric($data_right)) {
                                    $data_right = '';
                                }
                            }
                        }
                        if ($arr_df[2] == 'year') {
                            if (!is_numeric($data_right)) {
                                $data_right = ''; 
                            }
                        }
                        if ($arr_df[2] == 'ratio') {
                            $data_right = str_replace(',', '.', $data_right);
                            if ($arr_df[0] == 'CASH DIVIDEND') {
                                $data_right = htmlentities($data_right, ENT_QUOTES | ENT_IGNORE, "UTF-8");
                                $data_right = str_replace(array('&nbsp;',':'),'',$data_right);
                                $data_right = str_replace(array(':',' '), '', $data_right);
                                if (!is_numeric($data_right)) {
                                    $data_right = ''; 
                                }
                            }
                            if ($arr_df[0] == 'STOCK DIVIDEND') {
                                if (is_numeric($data_right)) {
                                    $data_right = '100/' . $data_right;
                                }else{
                                    if(strpos($data_right, ':')){
                                        $filter = ':';
                                    }elseif(strpos($data_right, '–')){
                                        $filter = '–';
                                    }elseif(strpos($data_right, '/')){
                                        $filter = '/';
                                    }elseif(strpos($data_right, '-')){
                                        $filter = '-';
                                    }elseif(strpos($data_right, 'hưởng')){
                                        $filter = 'hưởng';
                                    }else{
                                        $filter = ' ';
                                    }
                                    $data_arr_dr = explode($filter,$data_right);
                                    if(count($data_arr_dr) == 2){
                                        $arr_dr = array();
                                        foreach($data_arr_dr as $adr){
                                            $arr_dr[] = substr(trim($adr),0,2) * 1;
                                        }
                                        if($arr_dr[0] != 0){
                                            $data_right = implode('/',$arr_dr);
                                        }else{
                                            $data_right = '';
                                        }
                                    }else{
                                        $data_right = '';
                                    }
                                }
                            }
                        }
                        if ($arr_df[2] == 'divtr') {
                            $data_right = str_replace('.', '', $data_right);
                            if (!is_numeric($data_right)) {
                                $data_right = '';
                            }
                        }
                        if ($arr_df[2] == 'date_pay') {
                            $data_right = str_replace(array('Í','j','O'),array('/','/',0), $data_right);
                            $data_right = htmlentities($data_right, ENT_QUOTES | ENT_IGNORE, "UTF-8");
                            $data_right = str_replace(array('&nbsp;',':','&lt;o:p&gt;&lt'),'',$data_right);
                            if (strpos($data_right, 'đến') || strpos($data_right, '-')) {
                                $data_right = preg_replace('/(.*)(đến|-) /', '', $data_right);
                                $data_right = preg_replace('/\<\/span\>(.*) /', '', $data_right);
                            }
                            if (strpos($data_right, 'th&aacute;ng') && strpos($data_right, 'năm')) {
                                $data_right = str_replace(array(' th&aacute;ng ', ' năm '), '/', $data_right);
                            }
                            $arr_dr = explode('/', $data_right);
                            if (count($arr_dr) == 3) {
                                $arr_dr[0] = substr($arr_dr[0], -2);
                                $data_right = trim($arr_dr[0]) . '/' . trim($arr_dr[1]) . '/' . trim($arr_dr[2]);
                            }
                            if (preg_match("/^(\d{1,2})[- \/.](\d{1,2})[- \/.](\d{4})$/", $data_right, $data_date)) {
                                $data_right = $data_date[3] . '/' . $data_date[2] . '/' . $data_date[1];
                            }else{
                                $data_right = '';
                            }
                        }
                        if ($arr_df[2] == 'date_eff') {
                            $data_right = htmlentities($data_right, ENT_QUOTES | ENT_IGNORE, "UTF-8");
                            $data_right = str_replace(array('&nbsp;',':','&lt;o:p&gt;&lt'),'',$data_right);
                            $arr_dr = explode('/', $data_right);
                            if (count($arr_dr) == 3) {
                                $arr_dr[0] = substr($arr_dr[0], -2);
                                $data_right = trim($arr_dr[0]) . '/' . trim($arr_dr[1]) . '/' . trim($arr_dr[2]);
                            }
                            if (preg_match("/^(\d{1,2})[- \/.](\d{1,2})[- \/.](\d{4})$/", $data_right, $data_date)) {
                                $data_right = $data_date[3] . '/' . $data_date[2] . '/' . $data_date[1];
                            } else {
                                $data_right = '';
                            }
                        }
                        if ($arr_df[2] == 'date_trd') {
                            $data_right = htmlentities($data_right, ENT_QUOTES | ENT_IGNORE, "UTF-8");
                            $data_right = str_replace(array('&nbsp;',':','&lt;o:p&gt;&lt'),'',$data_right);
                            $arr_dr = explode('/', $data_right);
                            if (count($arr_dr) == 3) {
                                $arr_dr[0] = substr($arr_dr[0], -2);
                                $data_right = trim($arr_dr[0]) . '/' . trim($arr_dr[1]) . '/' . trim($arr_dr[2]);
                            }
                            if (preg_match("/^(\d{1,2})[- \/.](\d{1,2})[- \/.](\d{4})$/", $data_right, $data_date)) {
                                $data_right = $data_date[3] . '/' . $data_date[2] . '/' . $data_date[1];
                            } else {
                                $data_right = '';
                            }
                        }
                        if ($arr_df[2] == 'shares_add') {
                            $data_right = htmlentities($data_right, ENT_QUOTES | ENT_IGNORE, "UTF-8");
                            $data_right = str_replace(array('&nbsp;',':'),'',$data_right);
                            $arr_dr = explode('.',$data_right);
                            if(!empty($arr_dr) && count($arr_dr) <= 3){
                                $data_adr = array();
                                foreach($arr_dr as $adr_k => $adr_v){
                                    if(!strpos($adr_v,'/') && !strpos($adr_v,'năm')){
                                        $adr_v = substr(trim($adr_v),-3);
                                        $adr_v = str_replace(' ','',$adr_v);
                                        if(is_numeric($adr_v)){
                                            if($adr_k == 0){
                                                $data_adr[] = $adr_v;
                                            }else{
                                                if(strlen($adr_v) == 3){
                                                    $data_adr[] = $adr_v;
                                                }
                                            }
                                        }
                                    }
                                }
                                $data_right = trim(implode('',$data_adr));
                            }
                            if (!is_numeric($data_right)) {
                                $data_right = '';
                            }
                        }
                        $data_row[$key][$arr_df[2]] = $data_right;
                    }
                }
            }
        }
        pre($data_row);
        $this->db->update_batch('pvn_ca_events',$data_row,'id');
    }

    public function choose_pdf() {
        header('Content-Type: text/html; charset=utf-8');
        $path = '\\\LOCAL\IFRCVN\WORKS\PVN\PDF\\';
        $files = glob($path . '*.pdf');
        foreach ($files as $file) {
            $file_name = basename($file, '.pdf');
            $result_choose = $this->read_pdf($file);
            if ($result_choose == true) {
                if (!file_exists($path . 'DONE')) {
                    mkdir($path . 'DONE');
                }
                $new_filename = $path . 'DONE\\' . $file_name . '.pdf';
                rename($file, $new_filename);
            }
        }
    }

    public function read_pdf($file) {
        $result = $this->pdf2text($file);
        if (strlen($result) > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function decodeAsciiHex($input) {
        $output = "";

        $isOdd = true;
        $isComment = false;

        for ($i = 0, $codeHigh = -1; $i < strlen($input) && $input[$i] != '>'; $i++) {
            $c = $input[$i];

            if ($isComment) {
                if ($c == '\r' || $c == '\n')
                    $isComment = false;
                continue;
            }

            switch ($c) {
                case '\0': case '\t': case '\r': case '\f': case '\n': case ' ': break;
                case '%':
                    $isComment = true;
                    break;

                default:
                    $code = hexdec($c);
                    if ($code === 0 && $c != '0')
                        return "";

                    if ($isOdd)
                        $codeHigh = $code;
                    else
                        $output .= chr($codeHigh * 16 + $code);

                    $isOdd = !$isOdd;
                    break;
            }
        }

        if ($input[$i] != '>')
            return "";

        if ($isOdd)
            $output .= chr($codeHigh * 16);

        return $output;
    }

    public function decodeAscii85($input) {
        $output = "";

        $isComment = false;
        $ords = array();

        for ($i = 0, $state = 0; $i < strlen($input) && $input[$i] != '~'; $i++) {
            $c = $input[$i];

            if ($isComment) {
                if ($c == '\r' || $c == '\n')
                    $isComment = false;
                continue;
            }

            if ($c == '\0' || $c == '\t' || $c == '\r' || $c == '\f' || $c == '\n' || $c == ' ')
                continue;
            if ($c == '%') {
                $isComment = true;
                continue;
            }
            if ($c == 'z' && $state === 0) {
                $output .= str_repeat(chr(0), 4);
                continue;
            }
            if ($c < '!' || $c > 'u')
                return "";

            $code = ord($input[$i]) & 0xff;
            $ords[$state++] = $code - ord('!');

            if ($state == 5) {
                $state = 0;
                for ($sum = 0, $j = 0; $j < 5; $j++)
                    $sum = $sum * 85 + $ords[$j];
                for ($j = 3; $j >= 0; $j--)
                    $output .= chr($sum >> ($j * 8));
            }
        }
        if ($state === 1)
            return "";
        elseif ($state > 1) {
            for ($i = 0, $sum = 0; $i < $state; $i++)
                $sum += ($ords[$i] + ($i == $state - 1)) * pow(85, 4 - $i);
            for ($i = 0; $i < $state - 1; $i++)
                $ouput .= chr($sum >> ((3 - $i) * 8));
        }

        return $output;
    }

    public function decodeFlate($input) {
        return @gzuncompress($input);
    }

    public function getObjectOptions($object) {
        $options = array();
        if (preg_match("#<<(.*)>>#ismU", $object, $options)) {
            $options = explode("/", $options[1]);
            @array_shift($options);

            $o = array();
            for ($j = 0; $j < @count($options); $j++) {
                $options[$j] = preg_replace("#\s+#", " ", trim($options[$j]));
                if (strpos($options[$j], " ") !== false) {
                    $parts = explode(" ", $options[$j]);
                    $o[$parts[0]] = $parts[1];
                }
                else
                    $o[$options[$j]] = true;
            }
            $options = $o;
            unset($o);
        }

        return $options;
    }

    public function getDecodedStream($stream, $options) {
        $data = "";
        if (empty($options["Filter"]))
            $data = $stream;
        else {
            $length = !empty($options["Length"]) ? $options["Length"] : strlen($stream);
            $_stream = substr($stream, 0, $length);

            foreach ($options as $key => $value) {
                if ($key == "ASCIIHexDecode")
                    $_stream = $this->decodeAsciiHex($_stream);
                if ($key == "ASCII85Decode")
                    $_stream = $this->decodeAscii85($_stream);
                if ($key == "FlateDecode")
                    $_stream = $this->decodeFlate($_stream);
            }
            $data = $_stream;
        }
        return $data;
    }

    public function getDirtyTexts(&$texts, $textContainers) {
        for ($j = 0; $j < count($textContainers); $j++) {
            if (preg_match_all("#\[(.*)\]\s*TJ#ismU", $textContainers[$j], $parts))
                $texts = array_merge($texts, @$parts[1]);
            elseif (preg_match_all("#Td\s*(\(.*\))\s*Tj#ismU", $textContainers[$j], $parts))
                $texts = array_merge($texts, @$parts[1]);
        }
    }

    public function getCharTransformations(&$transformations, $stream) {
        preg_match_all("#([0-9]+)\s+beginbfchar(.*)endbfchar#ismU", $stream, $chars, PREG_SET_ORDER);
        preg_match_all("#([0-9]+)\s+beginbfrange(.*)endbfrange#ismU", $stream, $ranges, PREG_SET_ORDER);

        for ($j = 0; $j < count($chars); $j++) {
            $count = $chars[$j][1];
            $current = explode("\n", trim($chars[$j][2]));
            for ($k = 0; $k < $count && $k < count($current); $k++) {
                if (preg_match("#<([0-9a-f]{2,4})>\s+<([0-9a-f]{4,512})>#is", trim($current[$k]), $map))
                    $transformations[str_pad($map[1], 4, "0")] = $map[2];
            }
        }
        for ($j = 0; $j < count($ranges); $j++) {
            $count = $ranges[$j][1];
            $current = explode("\n", trim($ranges[$j][2]));
            for ($k = 0; $k < $count && $k < count($current); $k++) {
                if (preg_match("#<([0-9a-f]{4})>\s+<([0-9a-f]{4})>\s+<([0-9a-f]{4})>#is", trim($current[$k]), $map)) {
                    $from = hexdec($map[1]);
                    $to = hexdec($map[2]);
                    $_from = hexdec($map[3]);

                    for ($m = $from, $n = 0; $m <= $to; $m++, $n++)
                        $transformations[sprintf("%04X", $m)] = sprintf("%04X", $_from + $n);
                } elseif (preg_match("#<([0-9a-f]{4})>\s+<([0-9a-f]{4})>\s+\[(.*)\]#ismU", trim($current[$k]), $map)) {
                    $from = hexdec($map[1]);
                    $to = hexdec($map[2]);
                    $parts = preg_split("#\s+#", trim($map[3]));

                    for ($m = $from, $n = 0; $m <= $to && $n < count($parts); $m++, $n++)
                        $transformations[sprintf("%04X", $m)] = sprintf("%04X", hexdec($parts[$n]));
                }
            }
        }
    }

    public function getTextUsingTransformations($texts, $transformations) {
        $document = "";
        for ($i = 0; $i < count($texts); $i++) {
            $isHex = false;
            $isPlain = false;

            $hex = "";
            $plain = "";
            for ($j = 0; $j < strlen($texts[$i]); $j++) {
                $c = $texts[$i][$j];
                switch ($c) {
                    case "<":
                        $hex = "";
                        $isHex = true;
                        break;
                    case ">":
                        $hexs = str_split($hex, 4);
                        for ($k = 0; $k < count($hexs); $k++) {
                            $chex = str_pad($hexs[$k], 4, "0");
                            if (isset($transformations[$chex]))
                                $chex = $transformations[$chex];
                            $document .= html_entity_decode("&#x" . $chex . ";");
                        }
                        $isHex = false;
                        break;
                    case "(":
                        $plain = "";
                        $isPlain = true;
                        break;
                    case ")":
                        $document .= $plain;
                        $isPlain = false;
                        break;
                    case "\\":
                        $c2 = $texts[$i][$j + 1];
                        if (in_array($c2, array("\\", "(", ")")))
                            $plain .= $c2;
                        elseif ($c2 == "n")
                            $plain .= '\n';
                        elseif ($c2 == "r")
                            $plain .= '\r';
                        elseif ($c2 == "t")
                            $plain .= '\t';
                        elseif ($c2 == "b")
                            $plain .= '\b';
                        elseif ($c2 == "f")
                            $plain .= '\f';
                        elseif ($c2 >= '0' && $c2 <= '9') {
                            $oct = preg_replace("#[^0-9]#", "", substr($texts[$i], $j + 1, 3));
                            $j += strlen($oct) - 1;
                            $plain .= html_entity_decode("&#" . $this->octdec($oct) . ";");
                        }
                        $j++;
                        break;

                    default:
                        if ($isHex)
                            $hex .= $c;
                        if ($isPlain)
                            $plain .= $c;
                        break;
                }
            }
            $document .= "\n";
        }

        return $document;
    }

    public function pdf2text($filename) {
        $infile = @file_get_contents($filename, FILE_BINARY);
        if (empty($infile))
            return "";

        $transformations = array();
        $texts = array();

        preg_match_all("#obj(.*)endobj#ismU", $infile, $objects);
        $objects = @$objects[1];

        for ($i = 0; $i < count($objects); $i++) {
            $currentObject = $objects[$i];

            if (preg_match("#stream(.*)endstream#ismU", $currentObject, $stream)) {
                $stream = ltrim($stream[1]);

                $options = $this->getObjectOptions($currentObject);
                if (!(empty($options["Length1"]) && empty($options["Type"]) && empty($options["Subtype"])))
                    continue;

                $data = $this->getDecodedStream($stream, $options);
                if (strlen($data)) {
                    if (preg_match_all("#BT(.*)ET#ismU", $data, $textContainers)) {
                        $textContainers = @$textContainers[1];
                        $this->getDirtyTexts($texts, $textContainers);
                    }
                    else
                        $this->getCharTransformations($transformations, $data);
                }
            }
        }

        return $this->getTextUsingTransformations($texts, $transformations);
    }

    public function export_file() {
        $data = $this->db->query("select id,ticker,market,date_ann,pvn,ca_type,date_ex,ratio,divtr,date_rec,date_pay,period,year,date_eff,date_trd,shares_begin,shares_after,shares_add,shares_type from pvn_ca_events where ca_type in ('CASH DIVIDEND') and pvn = 1")->result_array();
        $implode = array();
        foreach ($data as $item) {
            $header = array_keys($item);
            $implode[] = implode("\t", $item);
        }
        $header = implode("\t", $header);
        $implode = implode("\r\n", $implode);
        $file = $header . "\r\n";
        $file .= $implode;
        $filename = '\\\LOCAL\IFRCVN\VNDB\WEBSITE\PVN_CA_EVENTS_CASH_2.txt';
        $create = fopen($filename, "w");
        $write = fwrite($create, $file);
        fclose($create);
    }

    public function cut_pvn_pdf() {
        header('Content-Type: text/html; charset=utf-8');
        $data_result = $this->db->query("SELECT ID,CA_TYPE,CONTENT FROM PVN_CA_EVENTS WHERE CA_TYPE IN ('STOCK DIVIDEND') AND PVN = 1")->result_array();
        $data_file_pdf = array();
        foreach ($data_result as $dr) {
            if (strpos($dr['CONTENT'], '.pdf')) {
                $content_left = preg_replace('/(.*)href=&quot;/', '', $dr['CONTENT']);
                $content_right = preg_replace('/&quot;(.*)/', '', $content_left);
                $content_right = str_replace('../../', '', $content_right);
                $link = rawurldecode(basename($content_right,'.pdf'));
                $path = '\\\LOCAL\IFRCVN\WORKS\PVN\PDF\DONE\\';
                $path_2 = '\\\LOCAL\IFRCVN\WORKS\PVN\PDF\DONE\TXT\\';
                if(file_exists($path.$link.'.pdf')){
                    //$data_file = $this->pdf2text($path . $link);
                }elseif(file_exists($path_2.$link.'.txt')){
                    pre($dr['ID'].' - '.$dr['CA_TYPE']);
                    pre($link);
                    $data_link = file($path_2.$link.'.txt',FILE_IGNORE_NEW_LINES);
                    $data_file = implode('&nbsp;',$data_link);
                    $data_file = str_replace("\n", '', $data_file);
                    $data_file = html_entity_decode(htmlspecialchars_decode($data_file));
                    pre($data_file);
                    pre('--------------------------');
                }else{
                    //pre($content_right);
                }
                // $data_file = str_replace("\n", '', $data_file);
                // $data_file = html_entity_decode(htmlspecialchars_decode($data_file));
                // if(strpos($data_file,'cổ phiếu')){
                //     echo $dr['ID'].','; 
                // }
                // pre($data_file);
                
//                pre($data_file);
                //die();
            }
        }
    }

    public function multiexplode ($delimiters,$string) {
        $ary = explode($delimiters[0],$string);
        array_shift($delimiters);
        if($delimiters != NULL) {
            foreach($ary as $key => $val) {
                 $ary[$key] = $this->multiexplode($delimiters, $val);
            }
        }
        return  $ary;
    }

	public function test_phuong() {
       // $dir = '\\\LOCAL\TRAINEES\ThucAnh\PRICES_CEO\\';
       $dir = 'D:\\ADJCLOSE\\IST';
        $now_date = date('Ymd', time());
        if (is_dir($dir)) {
            $dh = opendir($dir) or die(" Directory Open failed !");
            while ($file = readdir($dh)) {
                $dir_source = $dir . $file . '\\';
                $filename = $dir . 'HA_ALL.txt';
                $files = glob($dir_source . '*.txt');
                $data = array();
                foreach ($files as $base) {
                    $file_name = basename($base, ".txt");
                    $file_name = explode('_', $file_name);
                    $data = file_get_contents($base, FILE_USE_INCLUDE_PATH);
                    $data = explode("\r\n", trim($data));
                }
                if (empty($data)) {
                    unset($data);
                } else {
                    $data = implode("\r\n", $data);
                    $data .= "\r\n";
                    $file = fopen($filename, "a");
                    $write = fwrite($file, $data);
                    fclose($file);
                }
            }
              
        }
    }

    public function combinePriceWomanCEO(){
        $dir = '\\\LOCAL\IFRCVN\WORKS\TRAINEES\THUCANH\WOMEN_CEO\\';
        $files = glob($dir . '*.csv');
        foreach($files as $key => $file){
            $filename = explode('_', pathinfo($file, PATHINFO_FILENAME));
            $market = $filename[0];
            $ticker = $filename[1];
            unset($filename);
            $f_content = file($file);
            $header = array_shift($f_content);
            foreach($f_content as $key2 => $item){
                $f_content[$key2] = $market . ',' . $ticker . ',' . $item;
            }
            if($key == 0){
                $data = 'market,ticker,' . strtolower($header);
                array_unshift($f_content, $data);
                unset($data);
            }
            if(!is_dir($dir . 'output')){
                mkdir($dir . 'output');
            }
            $output_file = $dir . 'output\prices_ggwmceo.csv';
            if($key == 0){
                file_put_contents($output_file, $f_content, LOCK_EX);
            }else{
                file_put_contents($output_file, $f_content, FILE_APPEND | LOCK_EX);
            }
        }
        header("location: " . admin_url());
        exit();
    }
public function combinePhuongtest_old(){
        //$dir = '\\\LOCAL\IFRCVN\WORKS\TRAINEES\THUCANH\YAHOO\\';
        //$dir = '\\\LOCAL\IFRCVN\VNDB\METASTOCK\DOWNLOAD_TEST\HISTODAY\CAF\\';
        ini_set('memory_limit', '-1');
        //$dir = 'D:\\ADJCLOSE\\IST\\';
        $dir = 'D:\\TMP\\20150409\\';
        $files = glob($dir . '*.txt');
        foreach($files as $key => $file){
            $filename = explode('_', pathinfo($file, PATHINFO_FILENAME));
            $market = $filename[0];
            $ticker = $filename[1];
            unset($filename);
            $f_content = file($file);
            $header = array_shift($f_content);
            foreach($f_content as $key2 => $item){
                $f_content[$key2] = $market . ',' . $ticker . ',' . $item;
            }
            if($key == 0){
                $data = 'ticker,' . strtolower($header);
                array_unshift($f_content, $data);
                unset($data);
            }
            if(!is_dir($dir . 'output')){
                mkdir($dir . 'output');
            }
            $output_file = $dir . 'DATA_ALL.txt';
            if($key == 0){
                file_put_contents($output_file, $f_content, LOCK_EX);
            }else{
                file_put_contents($output_file, $f_content, FILE_APPEND | LOCK_EX);
            }
        }
        header("location: " . admin_url());
        exit();
    }
	public function combinePhuongtest(){
        //$dir = '\\\LOCAL\IFRCVN\WORKS\TRAINEES\THUCANH\YAHOO\\';
        //$dir = '\\\LOCAL\IFRCVN\VNDB\METASTOCK\DOWNLOAD_TEST\HISTODAY\CAF\\';
        ini_set('memory_limit', '-1');
        //$dir = 'D:\\ADJCLOSE\\IST\\';
        $dir = 'D:\\TMP\\20150409\\';
        $files = glob($dir . '*.txt');
        foreach($files as $key => $file){
            $filename =  pathinfo($file, PATHINFO_FILENAME);
            //$market = $filename[0];
            $ticker = $filename;
            unset($filename);
            $f_content = file($file);
            $header = array_shift($f_content);
            foreach($f_content as $key2 => $item){
                $f_content[$key2] = $ticker . '	' . $item;
            }
            if($key == 0){
                $data = 'ticker	' . strtolower($header);
                array_unshift($f_content, $data);
                unset($data);
            }
            if(!is_dir($dir . 'output')){
                mkdir($dir . 'output');
            }
            $output_file = $dir . 'DATA_ALL.txt';
            if($key == 0){
                file_put_contents($output_file, $f_content, LOCK_EX);
            }else{
                file_put_contents($output_file, $f_content, FILE_APPEND | LOCK_EX);
            }
        }
        header("location: " . admin_url());
        exit();
    }

    public function get_hnx(){
        // header('Content-Type: text/html; charset=utf-8');
        // $ch = curl_init();
        // curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        // you wanna follow stuff like meta and location headers
        // curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        // you want all the data back to test it for errors
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // probably unecessary, but cookies may be needed to
        // $url = 'http://www.hnx.vn/web/guest/tin-niem-yet?p_p_id=newnyuc_WAR_HnxIndexportlet&p_p_lifecycle=2&p_p_state=normal&p_p_mode=view&p_p_cacheability=cacheLevelPage&p_p_col_id=column-1&p_p_col_count=2&_newnyuc_WAR_HnxIndexportlet_anchor=newsAction';
        $url = 'http://hnx.vn/web/guest/tin-niem-yet?p_p_id=newnyuc_WAR_HnxIndexportlet&p_p_lifecycle=2&p_p_state=normal&p_p_mode=view&p_p_cacheability=cacheLevelPage&p_p_col_id=column-1&p_p_col_count=2&_newnyuc_WAR_HnxIndexportlet_anchor=newsAction';
        $result = array();
        $arr_num = array('','3','8','25','34','41','13');
		// $arr_num = array('','3');
        foreach($arr_num as $num){
            // echo "Catalogue: ".$num."<br />";
            $i = 0;
            while (1) {
                // echo "i: ".$i."<br />";                
                // $filter = 'sEcho=1&iColumns=6&sColumns=&iDisplayStart='.$i.'&iDisplayLength=10&mDataProp_0=0&mDataProp_1=1&mDataProp_2=2&mDataProp_3=3&mDataProp_4=4&mDataProp_5=5&_newnyuc_WAR_HnxIndexportlet_code=&_newnyuc_WAR_HnxIndexportlet_type_lists='.$num.'&_newnyuc_WAR_HnxIndexportlet_news_ops_s_date=&_newnyuc_WAR_HnxIndexportlet_news_ops_e_date=&_newnyuc_WAR_HnxIndexportlet_content_search=';
                $filter = 'sEcho=1&iColumns=9&sColumns=&iDisplayStart='.$i.'&iDisplayLength=10&mDataProp_0=0&mDataProp_1=1&mDataProp_2=2&mDataProp_3=3&mDataProp_4=4&mDataProp_5=5&mDataProp_6=6&mDataProp_7=7&mDataProp_8=8&_ThongTinCongBo_WAR_ThongTinCongBoportlet_INSTANCE_aO8s_s_page=ny&_ThongTinCongBo_WAR_ThongTinCongBoportlet_INSTANCE_aO8s_c_code=null&_ThongTinCongBo_WAR_ThongTinCongBoportlet_INSTANCE_aO8s_news_stock_code=&_ThongTinCongBo_WAR_ThongTinCongBoportlet_INSTANCE_aO8s_news_memeber_code=&_ThongTinCongBo_WAR_ThongTinCongBoportlet_INSTANCE_aO8s_news_bond_code=&_ThongTinCongBo_WAR_ThongTinCongBoportlet_INSTANCE_aO8s_type_lists=1&_ThongTinCongBo_WAR_ThongTinCongBoportlet_INSTANCE_aO8s_news_ops_s_date=&_ThongTinCongBo_WAR_ThongTinCongBoportlet_INSTANCE_aO8s_news_ops_e_date=&_ThongTinCongBo_WAR_ThongTinCongBoportlet_INSTANCE_aO8s_content_search=&_ThongTinCongBo_WAR_ThongTinCongBoportlet_INSTANCE_aO8s_rp_ryear=&_ThongTinCongBo_WAR_ThongTinCongBoportlet_INSTANCE_aO8s_rp_type='.$num;
                // $ch = curl_init($url);                                                                      
                // curl_setopt($ch, CURLOPT_POST, 1);                                                                    
                // curl_setopt($ch, CURLOPT_POSTFIELDS, $filter);                                                                  
                // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $curl = new curl(false);
                $html = $curl->makeRequest('post', $url, $filter);
                // $result_json = curl_exec($ch);
                // $result = json_decode($result_json,1);
                $result = json_decode($html, true);
                $data_insert = array();
                $array_date_ann = array();
                $count = count($result['aaData']);
                foreach($result['aaData'] as $item){
                    // $url_2 = 'http://www.hnx.vn/web/guest/tin-niem-yet?p_p_id=newnyuc_WAR_HnxIndexportlet&p_p_lifecycle=1&p_p_state=exclusive&p_p_mode=view&p_p_col_id=column-1&p_p_col_count=2&_newnyuc_WAR_HnxIndexportlet_anchor=viewAction&_newnyuc_WAR_HnxIndexportlet_cmd=viewContent&_newnyuc_WAR_HnxIndexportlet_news_id='.$item[6].'&_newnyuc_WAR_HnxIndexportlet_exist_file=1';
                    $url_2 = 'http://www.hnx.vn/web/guest/tin-niem-yet?p_auth=Mj8P2csZ&p_p_id=ThongTinCongBo_WAR_ThongTinCongBoportlet_INSTANCE_aO8s&p_p_lifecycle=1&p_p_state=exclusive&p_p_mode=view&p_p_col_id=column-1&p_p_col_count=1&_ThongTinCongBo_WAR_ThongTinCongBoportlet_INSTANCE_aO8s_anchor=viewAction&_ThongTinCongBo_WAR_ThongTinCongBoportlet_INSTANCE_aO8s_cmd=viewContent&_ThongTinCongBo_WAR_ThongTinCongBoportlet_INSTANCE_aO8s_news_id='.$item[6];
                    // curl_setopt($curl, CURLOPT_URL, $url_2);
                    // $html = curl_exec($curl);
                    $html = $curl->makeRequest('get', $url_2, null);
                    $html = str_get_html($html);
                    $table = $html->find('table', 2);
                    // $start = '<table width="100%"';
                    // $end = '</table>';
                    // $start = preg_quote($start, '/t');
                    // $end = preg_quote($end, '/t');
                    // $rule = "/(?<=$start).*(?=$end)/msU";
                    // $result_2 = array();
                    // preg_match_all($rule, $html, $result_2);
                    // print_r($result_2); exit();
                    // @$data = trim(strip_tags($result_2[0][0],'<p><a>'));
                    $array_date_ann = explode('/',$item[0]);
                    $date_ann = substr($array_date_ann[2],0,4).'-'.$array_date_ann[1].'-'.$array_date_ann[0];
                    $data_insert = array(
                        'ticker' => $item[1],
                        'market' => 'HNX',
                        'date_ann' => $date_ann,
                        'event_type' => '',
                        'evname' => $item[3],
                        'content' => trim(strip_tags($table, '<p><a><br><br />'))
                        //'status' => ''
                    );
                    $check = $this->check_data_hnx($item[1], $date_ann, $item[3]);
                    if($check['flag'] == 'FALSE'){
                        $this->db->where('id', $check['id']);
                        $this->db->update('vndb_events_day', $data_insert);
                    }else{
                        $this->db->insert('vndb_events_day', $data_insert);
                    }
                }
                if ($count == 0) {
                    break;
                }
                $i = $i + 10;
            }
        }
    }

 public function get_upc(){
        header('Content-Type: text/html; charset=utf-8');
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        // you wanna follow stuff like meta and location headers
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        // you want all the data back to test it for errors
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // probably unecessary, but cookies may be needed to
        $url = 'http://hnx.vn/web/guest/tin-upcom?p_p_id=newnyuc_WAR_HnxIndexportlet&p_p_lifecycle=2&p_p_state=normal&p_p_mode=view&p_p_cacheability=cacheLevelPage&p_p_col_id=column-1&p_p_col_count=2&_newnyuc_WAR_HnxIndexportlet_anchor=newsAction';
        $result = array();
        //$arr_num = array('','3','8','25','34','41','13');
		$arr_num = array('','106','99','68','73');
        foreach($arr_num as $num){
            echo "Catalogue: "+$num."<br />";
            $i = 0;
            while (1) {
                echo "i: "+$i."<br />";
                $filter = 'sEcho=3&iColumns=6&sColumns=&iDisplayStart='.$i.'&iDisplayLength=10&mDataProp_0=0&mDataProp_1=1&mDataProp_2=2&mDataProp_3=3&mDataProp_4=4&mDataProp_5=5&_newnyuc_WAR_HnxIndexportlet_code=&_newnyuc_WAR_HnxIndexportlet_type_lists='.$num.'&_newnyuc_WAR_HnxIndexportlet_news_ops_s_date=&_newnyuc_WAR_HnxIndexportlet_news_ops_e_date=&_newnyuc_WAR_HnxIndexportlet_content_search=';
                $ch = curl_init($url);                                                                      
                curl_setopt($ch, CURLOPT_POST, 1);                                                                    
                curl_setopt($ch, CURLOPT_POSTFIELDS, $filter);                                                                  
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $result_json = curl_exec($ch);
                $result = json_decode($result_json,1);
                $data_insert = array();
                $array_date_ann = array();
                $count = count($result['aaData']);
                foreach($result['aaData'] as $item){
                    $url_2 = 'http://hnx.vn/web/guest/tin-upcom?p_p_id=newnyuc_WAR_HnxIndexportlet&p_p_lifecycle=1&p_p_state=exclusive&p_p_mode=view&p_p_col_id=column-1&p_p_col_count=2&_newnyuc_WAR_HnxIndexportlet_anchor=viewAction&_newnyuc_WAR_HnxIndexportlet_cmd=viewContent&_newnyuc_WAR_HnxIndexportlet_news_id='.$item[6].'&_newnyuc_WAR_HnxIndexportlet_exist_file=1';
                    curl_setopt($ch, CURLOPT_URL, $url_2);
                    $html = curl_exec($ch);
                    $start = '<div class="div_row" align="left">';
                    $end = '</table>';
                    $start = preg_quote($start, '/t');
                    $end = preg_quote($end, '/t');
                    $rule = "/(?<=$start).*(?=$end)/msU";
                    $result_2 = array();
                    preg_match_all($rule, $html, $result_2);
                    //print_r($result_2); exit;
                    @$data = trim(strip_tags($result_2[0][0],'<p><a>'));
                    $array_date_ann = explode('/',$item[0]);
                    $date_ann = substr($array_date_ann[2],0,4).'/'.$array_date_ann[1].'/'.$array_date_ann[0];
                    $data_insert = array(
                        'ticker' => $item[1],
                        'market' => 'UPC',
                        'date_ann' => $date_ann,
                        'event_type' => '',
                        'evname' => $item[3],
                        'content' => $data
                        //'status' => ''
                    );
                    $check = $this->check_data_hnx($item[1],$date_ann,$item[3]);
                    if($check['flag'] == 'FALSE'){
                        $this->db->where('id',$check['id']);
                        $this->db->update('vndb_events_day',$data_insert);
                    }else{
                        $this->db->insert('vndb_events_day',$data_insert);
                    }
                }
                if ($count == 0) {
                    break;
                }
                $i = $i+10;
            }
        }
    }

    public function check_data_hnx($ticker, $date_ann,$evname){
        $this->db->select('count(*) as row, id');
        $this->db->where('ticker', $ticker);
        $this->db->where('date_ann', $date_ann);
        $this->db->where('evname', $evname);
        $result = $this->db->get('vndb_events_day')->row_array();
        if($result['row'] != 0){
            $final['flag'] = 'FALSE';
            $final['id'] = $result['id'];
        }else{
            $final['flag'] = 'TRUE';
        }
        return $final;
    }

    public function get_all_column(){
        $database = $this->input->get('db');
        $path = '\\\LOCAL\IFRCVN\VNDB\TESTS\\';
        if($database != ''){
            $connect = mysql_connect("local","local","ifrcvn") or die(mysql_error());
            $db_selected = mysql_select_db($database, $connect);
            if (!$db_selected) {
                print_r(mysql_error());
            }else{
                $filename = $path.'database_'.$database.'.txt';
                $create = fopen($filename, "w");
                $write = fwrite($create, 'Database: '.$database."\r\n");
                fclose($create);
                $path_file_collection = $path.$database.'.txt';
                if (!file_exists($path_file_collection)) {   
                    $table_collection = array();                         
                }else{
                    $table_collection = file($path_file_collection);
                    unset($table_collection[0]);
                }
                if(count($table_collection) != 0){
                    foreach($table_collection as $table){
                        $table = trim($table);
                        $this->write_table_column($database, $table, $filename);
                    }
                }else{
                    $query_table = mysql_query('SHOW TABLES FROM '.$database) or die(mysql_error());
                    $data_final = array();
                    while ($row_table = mysql_fetch_assoc($query_table)) {
                        $this->write_table_column($database, $row_table['Tables_in_'.$database], $filename);
                    }
                }
                print_r('Done! This is path filename: '.$filename);
            }
        }else{
            print_r('Add parameter on url. Example: ?db=abc');
        }
    }

    public function write_table_column($database, $table, $filename){

        $create = fopen($filename, "a");
        $write = fwrite($create, "\t".'Table: '.$table."\r\n");
        fclose($create);
        $query_column = mysql_query('SHOW COLUMNS FROM `'.$database.'`.`'.$table.'`') or die(mysql_error());
        while ($row_column = mysql_fetch_assoc($query_column)) {
            if($row_column['Null'] == 'NO'){
                $row_column['Null'] = 'NOT NULL';
            }else{
                unset($row_column['Null']);
            }
            if($row_column['Key'] == 'PRI'){
                $row_column['Key'] = 'PRIMARY';
            }else{
                unset($row_column['Key']);
            }
            if($row_column['Default'] == ''){
                unset($row_column['Default']);
            }else{
                $row_column['Default'] = 'Default('.$row_column['Default'].')';
            }
            if($row_column['Extra'] == ''){
                unset($row_column['Extra']);
            }
            $data_row_column = implode("\t",$row_column);
            $create = fopen($filename, "a");
            $write = fwrite($create, "\t\t".$data_row_column."\r\n");
            fclose($create);
        }

    }

    public function get_data_vsd(){
        header('Content-Type: text/html; charset=utf-8');
        $types = array('21','23');
        $urlMain = 'http://vsd.vn';
        $url = $urlMain.'/Ajax/action.ashx';
        $limit = 20;
        $pathMain = '//LOCAL/IFRCVN/VNDB/VSD';
        if (!is_dir($pathMain)) {
            mkdir($pathMain);
        }
        foreach($types as $type){
            $pathType = '//LOCAL/IFRCVN/VNDB/VSD/'.$type.'/';
            if (!is_dir($pathType)) {
                mkdir($pathType);
            }
            $dataMaxId = $this->db->query('SELECT max(ArticleId) as max_id FROM `data_vsd` WHERE Type = '.$type.' GROUP BY Type')->row_array();
            if($dataMaxId == ''){
                $numpage = $this->get_num_page_vsd($url,$type,$limit);
            }else{
                $numpage = 5;
            }
            for ($i=1; $i <= $numpage; $i++) {
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

                curl_setopt($ch,CURLOPT_URL, $url);

                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($ch, CURLOPT_VERBOSE, TRUE);
                curl_setopt($ch, CURLOPT_POSTFIELDS, '__aa=14&__catID='.$type.'&__pSize='.$limit.'&__pIndex='.$i.'&__date=');
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt_array( $ch, $options );
                $result = curl_exec($ch); 
                $header = curl_getinfo( $ch );

                curl_close($ch);
                
                $response = json_decode($result,1);
                if($response != ''){
                    $itemFinal = array();
                    foreach($response['d'] as $item){
                        if($item['ArticleId'] > $dataMaxId['max_id']){
                            $urlContent = $urlMain.$item['Href'];
                            $content = $this->get_content_vsd($urlContent);
                            $valueFinal = array();
                            $dateFolder = '';
                            foreach($item as $key => $value){
                                if($key == 'Date'){
                                    $dataDate = explode('/',$value);
                                    $value = $dataDate[2].'-'.$dataDate[1].'-'.$dataDate[0];
                                    $dateFolder = $dataDate[2].$dataDate[1].$dataDate[0];
                                }
                                if($key == 'Title'){
                                    $value = html_entity_decode(htmlspecialchars_decode($value));
                                }
                                $valueFinal['Content'] = strip_tags(html_entity_decode($content));
                                $valueFinal['Type'] = $type;
                                $valueFinal[$key] = $value;
                            }
                            $pathItem = '//LOCAL/IFRCVN/VNDB/VSD/'.$type.'/'.$dateFolder.'/';
                            if (!is_dir($pathItem)) {
                                mkdir($pathItem);
                            }

                            $titleFilename = str_replace(".","-",$valueFinal['Title']); 
                            $titleFilename = str_replace(":","-",$titleFilename);
                            $titleFilename = str_replace("*","-",$titleFilename);
                            $titleFilename = str_replace("/","-",$titleFilename);
                            $titleFilename = str_replace("<","-",$titleFilename);
                            $titleFilename = str_replace(">","-",$titleFilename);
                            $titleFilename = str_replace("\"","-",$titleFilename);

                            $content = $this->webpage2txt($valueFinal['Content']);
                            $create = fopen($pathItem.nv_EncString($titleFilename).'.txt', "w");
                            fwrite($create, html_entity_decode($content));
                            fclose($create);
                        }else{
                            continue;
                        }
                        $itemFinal[]  = $valueFinal;
                    }
                    if(count($itemFinal) != 0){
                        $this->db->insert_batch('data_vsd',$itemFinal);
                    }
                }
            }
        }        
    }

    public function get_num_page_vsd($url,$type,$limit){
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

        //set the url, number of POST vars, POST data
        curl_setopt($ch,CURLOPT_URL, $url);

        //$json=json_encode($fieldString);
        //for some diff
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_VERBOSE, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, '__aa=14&__catID='.$type.'&__pSize='.$limit.'&__pIndex=1&__date=');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt_array( $ch, $options );
        //execute post
        $result = curl_exec($ch); 
        $header = curl_getinfo( $ch );

        //close connection
        curl_close($ch);
        
        $response = json_decode($result,1);
        return $response['TotalPage'];
    }

    public function get_content_vsd($url){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        // you wanna follow stuff like meta and location headers
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        // you want all the data back to test it for errors
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // probably unecessary, but cookies may be needed to
        curl_setopt($ch, CURLOPT_URL, $url);
        $html = curl_exec($ch);
        $start = '<div class="w651content">';
        $end = '</div>';
        $start1 = preg_quote($start, '/t');
        $end1 = preg_quote($end, '/t');
        $rule = "/(?<=$start1).*(?=$end1)/msU";
        $result = array();
        preg_match_all($rule, $html, $result);
        if(isset($result[0][0])){
            $return = $result[0][0];
        }else{
            $return = '';
        }
        return $return;
    }

    public function webpage2txt($content){
        $search = array('@<script[^>]*?>.*?</script>@si',  // Strip out javascript
                        '@<style[^>]*?>.*?</style>@siU',    // Strip style tags properly
                        '@<[\/\!]*?[^<>]*?>@si',            // Strip out HTML tags
                        '@<![\s\S]*?–[ \t\n\r]*>@',         // Strip multi-line comments including CDATA
                        '/\s{2,}/');
        $content = html_entity_decode($content);
        $text = preg_replace($search, "\r\n", $content);

        $pat[0] = "/^\s+/";
        $pat[2] = "/\s+\$/";
        $rep[0] = "";
        $rep[2] = " ";

        $text = preg_replace($pat, $rep, trim($text)); 
        return $text;
    }

    public function update_vsd(){
        header('Content-Type: text/html; charset=utf-8');
        // $data_file = file(APPPATH . '../../assets/rule/rule.txt');
        // array_shift($data_file);
        $datasEvent = $this->db->query('SELECT * FROM vndb_events_list')->result_array();
        $filterFinal = array();
        $keyFinal = array();
        foreach($datasEvent as $dataEvent){
            $filterFinal[] = mb_strtolower($dataEvent['evname_vn']);
            $keyFinal[] = $dataEvent['evname_en'];
        }
        $i=0;
        while(1){
            $j = $i+1000;
            $datas = $this->db->query('SELECT * FROM data_vsd LIMIT '.$i.','.$j)->result_array();
            if(count($datas) == 0){
                break;
            }else{
                $dataFinal = array();
                foreach ($datas as $data) {
                    $title = $data['Title'];
                    $dataEvents = $this->strposa($title,$filterFinal,$keyFinal);
                    if($dataEvents == ''){
                        $dataFinal[] = array(
                            'ArticleId' => $data['ArticleId'],
                            'event_type' => 'OTHER'
                        );
                    }else{
                        $dataFinal[] = array(
                            'ArticleId' => $data['ArticleId'],
                            'event_type' => implode(' & ',$dataEvents)
                        );
                    }            
                }
                @$this->db->update_batch('data_vsd', $dataFinal, 'ArticleId');
            }
            $i = $j;
        }
    }

    public function strposa($haystack, $needles = array(), $keyneedles = array(), $offset = 0) {
        $chr = array();
        foreach ($needles as $key => $needle) {
            $res = strpos('' . $haystack . '', '' . $needle . '', $offset);

            if ($res !== false)
                $chr[] = $keyneedles[$key];
        }
        if (empty($chr))
            return false;
        return $chr;
    }

    public function get_woman_ceo(){
        header('Content-Type: text/html; charset=utf-8');
        $this->db->query('TRUNCATE TABLE all_women_caf');
        $this->db->query('INSERT INTO all_women_caf (ticker,en_name,market) (SELECT ticker, `name`, market FROM `vndb_reference_daily` WHERE date = (SELECT max(date) as date FROM `vndb_reference_daily`))');
        $i=0;
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
        while(1){
            $j = $i+500;
            $dataTicker = $this->db->query('SELECT * FROM all_women_caf LIMIT '.$i.','.$j)->result_array();
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
                        $dataUpdate[] = array(
                            'id' => $ticker['id'],
                            'position' => $dataFinal[0],
                            'CEO' => $dataFinal[1]
                        );
                    }else{
                        continue;
                    }
                }
                //pre($dataUpdate);
                if(count($dataUpdate) != 0){
                    @$this->db->update_batch('all_women_caf',$dataUpdate,'id');
                }
                //die();
            }
            //pre($dataUpdate);
            $i = $j;
        }
    }

    public function get_data_reuters(){
        $url = 'http://charts.reuters.com/reuters/enhancements/US/interactiveChart/api.asp';
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

        //set the url, number of POST vars, POST data
        curl_setopt($ch,CURLOPT_URL, $url);

        //$json=json_encode($fieldString);
        //for some diff
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_VERBOSE, TRUE);
        //curl_setopt($ch, CURLOPT_POSTFIELDS, 'inputs=B64ENCeyJldmVudHMiOlt7InR5cGUiOiJldmVudHMiLCJuYW1lIjoibmV3cyIsImNvbG9yIjoiZTUyNjAwIiwic3ltYm9sIjoiNzYzNzA3MiIsInN5bWJvbFR5cGUiOiJXU09ESXNzdWUifV0sIldTT0RJc3N1ZSI6Ijc2MzcwNzIiLCJSSUMiOiIuRlRGVlRUIiwiY29tcGFueSI6bnVsbCwiZHVyYXRpb24iOiIxODI2IiwiZnJlcXVlbmN5IjoiMWRheSIsImRNYXgiOnVuZGVmaW5lZCwiZE1pbiI6dW5kZWZpbmVkLCJkaXNwbGF5IjoibW91bnRhaW4iLCJzY2FsaW5nIjoibGluZWFyIiwicmVza2luIjp0cnVlfQ%3D%3D&..contenttype..=text%2Fjavascript&..requester..=ContentBuffer');
        curl_setopt($ch, CURLOPT_POSTFIELDS, 'inputs=B64ENCeyJldmVudHMiOlt7InR5cGUiOiJldmVudHMiLCJuYW1lIjoibmV3cyIsImNvbG9yIjoiZTUyNjAwIiwic3ltYm9sIjoiMjYxMDMwMTUiLCJzeW1ib2xUeXBlIjoiV1NPRElzc3VlIn1dLCJzeW1ib2wiOiJVUyZGTERWTiIsIldTT0RJc3N1ZSI6IjI2MTAzMDE1IiwiUklDIjoiLlRSWEZMRFZOUCIsImNvbXBhbnkiOm51bGwsImR1cmF0aW9uIjoiMTgyNiIsImZyZXF1ZW5jeSI6IjFkYXkiLCJkTWF4Ijp1bmRlZmluZWQsImRNaW4iOnVuZGVmaW5lZCwiZGlzcGxheSI6Im1vdW50YWluIiwic2NhbGluZyI6ImxpbmVhciIsInJlc2tpbiI6dHJ1ZX0%3D&..contenttype..=text%2Fjavascript&..requester..=ContentBuffer');
		curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt_array( $ch, $options );
        //execute post
        $result = curl_exec($ch);
        $result = str_replace('\\','',$result);
        $result = str_replace('//','',$result);
        $result = strip_tags($result);
        $dataResult = explode('{',$result);
        $dataFinalMaxter = array();
        foreach($dataResult as $data){
            $arrData = explode(",\"",$data);
            if(count($arrData) > 3){
                $dataFinal = array();
                foreach($arrData as $subdata){
                    $subdata = str_replace("\"", "", $subdata);
                    $subdata = str_replace("¤", "", $subdata);
                    $arrSub = explode(":",$subdata);
                    if($arrSub[0] != 'x' && $arrSub[0] != 'y' && $arrSub[0] != 'volume' && $arrSub[0] != 'asOfDate' && $arrSub[0] != 'news' && $arrSub[0] != 'rawDate'){
                        if($arrSub[0] == 'date'){
                            $arrSub[1] = date('Y/m/d',strtotime($arrSub[1]));
                        }
                        $dataFinal[$arrSub[0]] = $arrSub[1];
                    }
                }
                $dataFinalMaxter[] = $dataFinal;
            }        
        }
        $dataImport = array();
        foreach ($dataFinalMaxter as $key => $value) {
            $dataSubImport = array();
            $header = '';
            foreach($value as $sub_key => $vl){
                if($key == 0){
                    $header .= $sub_key.chr(9);
                }
                $dataSubImport[] = $vl;
            }
            if($key == 0){
                $dataImport[] = $header;
            }
            $dataImport[] = implode(chr(9),$dataSubImport);
        }

        $create = fopen('//LOCAL/IFRCVN/VNDB/TESTS/TRXFLDVNP.txt', "w");
        $write = fwrite($create, implode("\r\n",$dataImport));
        fclose($create);
    }

    public function get_data_123(){
        $url = "http://123.30.23.116:1001/wsr/IdxDat.asmx/GetSvrByNm?svrNm=stk_feed";
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

        //set the url, number of POST vars, POST data
        curl_setopt($ch,CURLOPT_URL, $url);

        //$json=json_encode($fieldString);
        //for some diff
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_VERBOSE, TRUE);
        curl_setopt_array( $ch, $options );
        //execute post
        $result = curl_exec($ch);

        if(curl_errno($ch)){
            print curl_error($ch);
        }

        curl_close($ch);
        pre($result);
    }

    
}