<?php

if (!function_exists('admin_url')) {

    function admin_url() {
        $CI = & get_instance();
        return base_url() . $CI->config->item('admin_url') . '/';
    }

}
if (!function_exists('db_to_array')) {

    function db_to_array($data) {
        if (is_array($data)) {
            $temp = array();
            $first = NULL;
            $result = NULL;
            $temp = $data;
            $first = array_shift($temp);
            $temp = $first->fields;
            foreach ($data as $key => $value) {
                foreach ($temp as $value2) {
                    $result[$key][$value2] = $value->$value2;
                }
            }
            return $result;
        }
        return;
    }

}
if (!function_exists('template_url')) {

    function template_url() {
        $CI = & get_instance();
        return base_url() . $CI->template->path();
    }

}
if (!function_exists('pre')) {

    function pre($data) {
        echo '<pre>';
        print_r($data);
        echo '</pre>';
    }

}
if (!function_exists('utf8_convert_url')) {

    function utf8_convert_url($str, $dau) {
        $search = array(
            '#(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)#',
            '#(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)#',
            '#(ì|í|ị|ỉ|ĩ)#',
            '#(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)#',
            '#(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)#',
            '#(ỳ|ý|ỵ|ỷ|ỹ)#',
            '#(đ)#',
            '#(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)#',
            '#(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)#',
            '#(Ì|Í|Ị|Ỉ|Ĩ)#',
            '#(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)#',
            '#(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)#',
            '#(Ỳ|Ý|Ỵ|Ỷ|Ỹ)#',
            '#(Đ)#',
            '#(039|&quot;)#',
            "/[^a-zA-Z0-9\-\_]/");
        $replace = array('a', 'e', 'i', 'o', 'u', 'y', 'd', 'A', 'E', 'I', 'O', 'U', 'Y', 'D', '', '-');
        $str = preg_replace($search, $replace, $str);
        $str = preg_replace('/(-)+/', $dau, $str);
        return $str;
    }

}
/* TUAN ANH */
if (!function_exists('cut_str')) {

    function cut_str( $str = '', $length = '', $chr = '[...]' ) {
        if ( $length == '' ) {
            $length = 30;
        }
        if ( strlen( $str ) < $length ) {
            return $str;
        } else {
            return substr( $str, 0, $length ) . $chr;
        }
    }

}
if (!function_exists('trans')) {

    function trans($word, $return = FALSE) {
        $CI = & get_instance();
        $CI->load->model('Translate_model', 'translate');
        $trans = $CI->translate->get($word);
        if ($return == FALSE) {
            echo $trans;
        } else {
            return $trans;
        }
    }

}
if (!function_exists('full_copy')) {

    function full_copy($source, $target) {
        if (is_dir($source)) {
            $d = dir($source);
            while (FALSE !== ( $entry = $d->read() )) {
                if ($entry == '.' || $entry == '..') {
                    continue;
                }
                $Entry = $source . '/' . $entry;
                if (is_dir($Entry)) {
                    @mkdir($Entry);
                    $this->full_copy($Entry, $target . '/' . $entry);
                    continue;
                }
                copy($Entry, $target . '/' . $entry);
            }

            $d->close();
        } else {
            copy($source, $target);
        }
    }

}
if (!function_exists('highlight_number')) {

    function highlight_number($num) {
        $format = 'none';
        if ($num > 0) {
            $format = 'green';
        }
        if ($num < 0) {
            $format = 'red';
        }
        return 'color: ' . $format;
    }

}
if (!function_exists('replaceValueNull')) {

    function replaceValueNull($curent, $default, $data = '') {
        if (empty($curent)) {
            return $default;
        }
        if (is_object($curent) == TRUE) {
            $curent = get_object_vars($curent);
        }
        if (is_object($default) == TRUE) {
            $default = get_object_vars($default);
        }
        foreach ($curent as $k => $v) {
            if (is_array($v) == FALSE && is_object($v) == FALSE) {
                if (strip_tags($v) == '') {
                    $v = $default[$k];
                }
            } else {
                $v = replaceValueNull($curent[$k], $default[$k]);
            }
            $data[$k] = $v;
        }
        return $data;
    }

}

function executeSqlFile($filePath) {
    $CI = & get_instance();
    $fileString = file_get_contents($filePath);
    $queryArray = explode(";", $fileString);
    foreach ($queryArray as $query) {
        if (strlen($query) > 0) {
            $CI->db->trans_begin();
            $CI->db->query($query);
            if ($CI->db->trans_status() === FALSE) {
                $CI->db->trans_rollback();
            } else {
                $CI->db->trans_commit();
            }
        }
    }
}

function microtime_float() {
    list($usec, $sec) = explode(" ", microtime());
    return ((float) $usec + (float) $sec);
}

/**
 * function convertMetastock
 *
 * convert normal format to metastock format
 *
 * @data   array of data
 * @format get from dms_metafield base on type
 * @options    get from dms_body base on code_dwl
 * @code_info  get from vndb_download
 * @date   created date
 */
function convertMetastock(array $data, array $format, array $options, array $code_info, $date = '', $ticker = '') {
    //print_r($data);exit();
    // $headers = array_keys($format);
    // $contents = strtoupper(implode(chr(9) ,$headers)) . PHP_EOL;
    //if ($code_info['code_dwl'] == 'CAFALLTW') {
    $result = '';
    if(!empty($data) && is_array($data)){
        if($code_info['startrow'] > 1){
            for($i = 2; $i <= $code_info['startrow']; $i++){
                array_shift($data);
            }
        }
        if(strlen(trim(end($data))) == 1){
            array_pop($data);
        }
        if ($code_info['code_dwl'] == 'CPHDIVCAST') {
            foreach ($data as $key => $item) {
                $item = explode('</td>', $item);
                if ($key % 2 == 0) {
                } else {
                    unset($data[$key]);
                }
            }
        }
        foreach ($data as $key => $item) {
            $item = $code_info['del_bllef'] . $item;
            if($code_info['del_fdleft'] == ''){
                $code_info['del_fdleft'] = '<td';
            }
            $item = explode($code_info['del_fdleft'], $item);
            $column = 0;
            if(count($item) == 1){
                $column = 1;
            }
            foreach ($item as $values) {
                if (isset($options[$column])) {
                    $values = $code_info['del_fdleft'] . $values;
                    foreach($options[$column] as $option){
                        $option['left'] = convertCHR($option['left']);
                        $option['right'] = convertCHR($option['right']);
                        $value = $values;
                        $value = preg_replace('/\<tr.*\>/', '', $value);
                        $value = str_replace('&nbsp;', '', $value);
                        $value = html_entity_decode($value, ENT_COMPAT, 'utf-8');
                        if($option['left'] != ''){
                            $from = findstr($value, $option['left'], $option['occurrence']);
                            if($from != ''){
                                $from += strlen($option['left']);
                                $length = 0;
                                if($option['right'] != ''){
                                    $length = strpos($value, $option['right'], $from);
                                    $length = $length - $from;
                                }
                                if($length != 0){
                                    $value = substr($value, $from, $length);
                                }else{
                                    $value = substr($value, $from);
                                }
                                if($option['datafield'] != 'name'){
                                    $value = explode(', ', $value);
                                    $value = end($value);
                                }
                            }else{
                                $value = 0;
                            }
                            
                        }
                        //test
                        $value = preg_replace('/[\r(\r\n)\n]/', '', str_replace('&nbsp;', '' ,trim(strip_tags($value))));
                        if ($option['type'] == 'N') {
                            if ($code_info['delimitor'] == 'VN') {
                                $value = str_replace('.', '', $value);
                                $value = str_replace(',', '.', $value);

                            } else {
                                $value = str_replace(',', '', $value);
                            }
                        }

                            

                        if ($value == '--') {
                            $value = '';
                        }

                        if($option['type'] == 'N'){
                            $value *= 1;
                            if ($option['mult'] != 0) {
                                $value *= $option['mult'];
                            }
                        }
                        if($option['type'] == 'D'){
                            if($value == 'N/A' || $value == 0){
                                $value = '';
                            }
                            if($value != ''){
                                $temp_value = explode('/', $value);
                                if(strlen($temp_value[0]) == 2){
                                    $value = $temp_value[2] . '/' . $temp_value[1] . '/' . $temp_value[0];
                                }

                        
                            }
                        }


                        $format[$option['datafield']] = $value;
                        if (isset($format['yyyymmdd'])) {
                            if ($date != '' && $format['yyyymmdd'] == '') {
                                $format['yyyymmdd'] = $date;
                            }else{
                                $format['yyyymmdd'] = str_replace('/', '', $format['yyyymmdd']);
                            }
                        }
                        if (isset($format['source'])) {
                            $format['source'] = $code_info['code_src'];
                        }
                        if (isset($format['market'])) {
                            $format['market'] = $code_info['market'];
                        }
                        if (isset($format['date'])) {
                            if ($date != '' && $format['date'] == '') {
                                $format['date'] = substr($date, 0, 4) . '/' . substr($date, 4, 2) . '/' . substr($date, 6);
                            }
                        }
                        if (isset($format['ticker'])) {
                            if ($ticker != '') {
                                $format['ticker'] = $ticker;
                            }
                        }
                    }
                }
                $column++;
            }
            $result[$key] = $format;
        }
        if(is_array($result)){
            if (count(end($result)) == 1) {
                array_pop($result);
            }
        }
    }
    return $result;
}

function convertMetastock2(array $data, $type, $date, $code, $source = '') {
    $CI = & get_instance();
    $CI->load->Model('exchange_model', 'mexchange');
    $format = $CI->mexchange->getMetaFormat($type);
    $temp_headers = array_keys($format);
    $headers = array_shift($data);
    foreach ($data as $key => $item) {
        foreach ($item as $k => $value) {
            if (in_array($headers[$k], $temp_headers)) {
                $temp[$headers[$k]] = strip_tags(trim($value));
            }
        }
        $data[$key] = $temp;
    }
    $temp = array();
    foreach ($data as $key => $item) {
        foreach ($item as $k => $value) {
            $format[$k] = $value;
            $format['source'] = $source;
            $format['market'] = $code;
            $format['yyyymmdd'] = $date;
            $format['date'] = substr($date, 0, 4) . '/' . substr($date, 4, 2) . '/' . substr($date, 6);
        }
        $data[$key] = $format;
    }
    return $data;
}

function getFilesFromDir($dir) {

    $files = array();
    if ($handle = opendir($dir)) {
        while (false !== ($file = readdir($handle))) {
            if ($file != "." && $file != "..") {
                if (is_dir($dir . '/' . $file)) {
                    $dir2 = $dir . '/' . $file;
                    $files[] = getFilesFromDir($dir2);
                } else {
                    $files[] = $dir . '/' . $file;
                }
            }
        }
        closedir($handle);
    }

    return array_flat($files);
}

function array_flat($array) {

    foreach ($array as $a) {
        if (is_array($a)) {
            $tmp = array_merge($tmp, array_flat($a));
        } else {
            $tmp[] = $a;
        }
    }

    return $tmp;
}

function url_exists($url) {
    $h = get_headers($url);
    $status = array();
    preg_match('/HTTP\/.* ([0-9]+) .*/', $h[0], $status);
    return ($status[1] == 200);
}

function load_data($dir, $extension, $table, $option, $column, $conditions) {
    $CI = & get_instance();
    /*     * *************************** *
     *   Author: Hà Phan Minh        *
     *   Created: 2013/01/10         *
     *   							 *
     * ***************************** */
//================================================================//
    //$option = '\t' (tab) ; or '\n' (Down the line) or ','
    //$extension = '*.jpg,*.JPG,*.png'; or '*.txt'; or '*.csv';
    //$conditions = array('date' => '20130101', 'year' => '2003', 'name' => 'ALL', 'market' => 'HNX');
    //FORMAT_FILE: NAME_MARKET_YYYYMMDD (Nếu sai cấu trúc file sẽ không chạy)
    if ($dir == '' or $extension == '') {
        printf('An errors occur, there is no dicrectory or extension');
        exit();
    } else {
        $all_file = glob("$dir{$extension}", GLOB_BRACE);
        foreach ($all_file as $base) {
            $filename = pathinfo($base, PATHINFO_FILENAME);
            $arr = explode('_', $filename);
            $base_url = str_replace("\\", "\\\\", $base);
			if ($table == '' && $option == '' && $column == '') {
				printf('An errors occur, there is no table or option or column');
				exit();
			} else {
				$query_load = "LOAD DATA INFILE '" . $base_url . "' INTO TABLE " . $table . " FIELDS TERMINATED BY '" . $option . "' IGNORE 1 LINES (" . $column . ")";
				$CI->db->query($query_load);
            }
        }
    }
}

/**
 * @name list_file_vndb
 * @author longNguyen
 * @todo show list name file in folder and sub folder
 *
 * @param
 * $path path directory
 * $mask : name file like ... ex: 'vndb...txt'
 * $exten extention ".txt|.TXT"
 */
function list_file_vndb($path, $mask = '', $exten = ".txt|.TXT", $sub = FALSE, $data = array()) {

    if (!is_dir($path))
        return false;

    $ex = explode('|', $exten);
    $list = scandir($path);
    unset($list[0]);
    unset($list[1]);

    foreach ($list as $item) {
        foreach ($ex as $value) {
            $pos = strpos($item, $value);
            if ($pos >= 0 && $pos != '') {
                if ($mask != '') {
                    $check_mask = strpos($item, $mask);
                    if ($check_mask >= 0 && $check_mask != '') {
                        $data[] = $path . '/' . $item;
                    }
                } else {
                    $data[] = $path . '/' . $item;
                }
                break;
            }
        }
        if ($sub == TRUE)
            if (strpos($item, '.') == false) {
                $data = list_image($path . '/' . $item, $mask, $exten, $sub, $data);
            }
    }
    return $data;
}

function line_of_file($data = '', $header = 0) {
    if ($data == '')
        return FALSE;
    $result = array();
    if (is_array($data)) {
        foreach ($data as $key => $value) {
            if (file_exists($value) == FALSE) {
                $rows = -1;
            } else {
                $rows = count(file($value));
                $rows = $header == 1 ? $rows - 1 : $rows;
            }
            $result[$key]['rows'] = $rows;
            $result[$key]['file'] = $value;
        }
        return $result;
    } else {
        if (file_exists($data) == FALSE) {
            $rows = -1;
        } else {
            $rows = count(file($data));
            $rows = $header == 1 ? $rows - 1 : $rows;
        }
        return $rows;
    }
}

function insert_from_file($file = '', $table = '', $empty = FALSE) {
    $CI = & get_instance();
    $CI->load->model('Import_model', 'import_model');
    if ($file == '')
        return FALSE;
    if ($table == '')
        return FALSE;
    $results = array();
    /* xu ly import */
    if (file_exists($file)) {
        $data = fopen($file, "r");
        if ($empty == TRUE) {
            $CI->import_model->emptyTable($table);
        }
        $num = 0;
        $totalRows = 0;
        $line = 0; //line
        while (fgets($data) !== false) {
            $totalRows++;
        }
        fclose($data);
        // lấy ra tồng số rows hiện tại
        $totalRowsTable = $CI->import_model->getTotal($table);
        $data = fopen($file, "r");
        $arr_database = array();
        while (!feof($data)) {
            // neu la line 0
            if ($line == 0) {
                $row = fgets($data);
                $col = explode(chr(9), $row);
                $col2 = array();
                foreach ($col as $value) {
                    $col2[] = trim(strtolower($value));
                }
                $col = $col2; // cac fiels trong file upload
                $feilds = $CI->import_model->getFields($table);
                $feilds = array_intersect($col, $feilds);
                unset($row);
            } else {
                $dataOneRow = array();
                // lay ra data cua 1 row
                $row = str_getcsv(fgets($data), chr(9), '"', '\\');
                // gan key vao value
                foreach ($col as $key => $value) {
                    $dataOneRow[$value] = isset($row[$key]) ? trim($row[$key]) : NULL;
                }
                // lay ra cac value co trong feilds cua table
                foreach ($feilds as $key => $value) {
                    $arr_database[$num][$value] = $dataOneRow[$value];
                }
                // kiem tra new $num = 2000 thi insert vo or het file
                if ($num == 2000 || $line == ($totalRows - 1)) {
                    $CI->import_model->insertData($table, $arr_database);
                    $arr_database = array();
                    $num = 0;
                }
            }
            $num++;
            $line++;
        }
        fclose($data);
        $results['numRows'] = $totalRows > 0 ? $totalRows - 1 : 0;
        // lấy ra tồng số rows sau khi import
        $results['totalTable'] = $CI->import_model->getTotal($table);
        $results['totalImport'] = $results['totalTable'] - $totalRowsTable;
        return $results;
    } else {
        return FALSE;
    }
}

if(!function_exists('replaceFile')){
    function replaceFile($content, $rContent, $path){
        if(!is_file($path))
            return FALSE;
        $files = file_get_contents($path);
        $files = str_replace($content, $rContent, $files);
        $f = file_put_contents($path, $files);
        return TRUE;


    }
}

function check_download($url = '', $post = 0, $param = '', $output = '') {
    if ($url == '')
        return FALSE;
    $CI = & get_instance();
    $CI->load->library('curl');
    $CI->load->helper('file');
    $CI->load->library('simple_html_dom');
    $curl = new curl();
    if ($post == 1) {
        $post = 'post';
    } else {
        $post = 'get';
        $param = '';
    }
    $datahtml = $curl->makeRequest($post, $url, $param);
    if ($output != '') {
        $output_path = substr($output, 0, strripos($output, "\\"));
        if (file_exists($output_path) == FALSE)
            mkdir($output_path, 0755, true);
        write_file($output, $datahtml, 'w');
    } else {
        return $datahtml;
    }
}

if(!function_exists('download_exc')){
    function download_exc($market, $url, $start = '', $end = '', $method = 'get', $post = NULL){
        $value = '';
        $CI =& get_instance();
        $CI->load->library('curl');
        $curl = new curl(false);
        $start = preg_quote($start, '/');
        $end = preg_quote($end, '/');
        $rule = "/(?<=$start).*$end.*(?=$end)/msU";
        $html = '';
        while(1){
            $html = $curl->makeRequest($method, $url, $post, 3);
            if($start != '' && $end != ''){
                preg_match_all($rule, $html, $result);           
                if(!empty($result)){
                    file_put_contents('\\\LOCAL\IFRCVN\VNDB\HTM\STATS\DAY\STATS_' . $market . '_' . date('Ymd') . '.htm', $html);
                    foreach($result[0] as $item){
                        $item = str_replace('"odd">', '', $item);
                        $item = str_replace('"even">', '', $item);
                        $value[] = explode('</td>', $item);
                    }
                    return $value;
                }
            }else{
                $html = json_decode($html, 1);
                return $html['aaData'];
            }
        }

    }
}

if(!function_exists('compare_files')){
    function compare_files($type, $nfile1, $nfile2, array $exclude = array()){
        if(is_file($nfile1) && is_file($nfile2)){
            switch($type){
                case 'reference': $table = 'vndb_reference_day'; break;
                case 'prices': $table = 'vndb_day'; break;
                default: $table = ''; break;
            }
            if($table != ''){
                $CI =& get_instance();
                $nfile1 = str_replace('\\', '\\\\', $nfile1);
                $nfile2 = str_replace('\\', '\\\\', $nfile2);
                
                $query = "DROP TABLE IF EXISTS `vndb_test1`;
                        CREATE TABLE `vndb_test1` (SELECT * FROM $table LIMIT 0, 1);
                        TRUNCATE `vndb_test1`;
                        DROP TABLE IF EXISTS `vndb_test2`;
                        CREATE TABLE `vndb_test2` (SELECT * FROM $table LIMIT 0, 1);
                        TRUNCATE `vndb_test2`;
                        LOAD DATA LOCAL INFILE '$nfile1' INTO TABLE vndb_test1 FIELDS TERMINATED BY  '\\t'  IGNORE 1 LINES;
                        LOAD DATA LOCAL INFILE '$nfile2' INTO TABLE vndb_test2 FIELDS TERMINATED BY  '\\t'  IGNORE 1 LINES";
                $query = explode(';', $query);
                foreach($query as $item){
                    $CI->db->query($item);
                }
                unset($query);
                if($CI->db->count_all('vndb_test1') != $CI->db->count_all('vndb_test2')){
                    return -1;
                }

                $columns = $CI->db->list_fields($table);
                if(!empty($exclude)){
                    foreach($columns as $k => $col){
                        if(in_array($col, $exclude)){
                            unset($columns[$k]);
                        }
                    }
                }
                $columns = implode(',', $columns);
                $query = "SELECT $columns
                        FROM
                         (
                          SELECT $columns
                          FROM vndb_test1
                          UNION ALL
                          SELECT $columns
                          FROM vndb_test2
                        )  AS alias_table
                        GROUP BY $columns
                        HAVING COUNT(*) = 1;";
                $data = $CI->db->query($query)->result_array();
                return $data;
            }
        }
    }
}

function findstr($str, $needle, $occ){
    $pos = '';
    $from = 0;
    $count = 1;
    while($count <= $occ){
        $pos = strpos($str, $needle, $from);
        $from = ($pos + strlen($needle));
        $count++;
    }
    return $pos;
}

function convertNumber2Us($number, $region='us'){
    $decimal = '.';
    $thousand = ',';
    if($region == 'vn'){
        $decimal = ',';
        $thousand = '.';
    }
    $number = str_replace($decimal, '.', str_replace($thousand, '', $number));
    return $number;
}

function normalFormat($number){
    if(is_numeric($number)){
        $broken_number = explode('.', $number);
        $broken_number[0] = number_format($broken_number[0]);
        return implode('.', $broken_number);
    }
    return FALSE;
}

function checkExpiredFile($file, $dur){
    $check = FALSE;
    $now = time();
    $mtime = filemtime($file);
    $limit = strtotime("+$dur day", $mtime);
    if($now >= $limit){
        $check = TRUE;
    }
    return $check;

}

function checkTicker($path, $code_dwl, $market='', $pos='1'){
    $remains = '';
    $tickers = '';
    $CI =& get_instance();
    $files = glob($path . $code_dwl . '_*.txt');
    $CI->load->Model('exchange_model', 'mexchange');
    $rows = $CI->mexchange->getTicker($market);
    if(count($files) == 1){
        $i = 0;
        $f = fopen($files[0], 'r');
        while($content = fgetcsv($f, 0, "\t")){
            if($i != 0){
                $tickers[] = $content[1];
            }
            $i++;
        }
    }else{
        foreach($files as $file){
            $file = pathinfo($file, PATHINFO_FILENAME);
            $file = explode('_', $file);
            $tickers[] = $file[$pos];
        }
    }
    if(!empty($tickers)){
        foreach($rows as $row){
            if(!in_array($row['code'], $tickers)){
                $remains[] = $row['code'];
            }
        }
    }
    return $remains;
}

function convertCHR($str){
    $str = preg_replace('/[C,c][H,h][R,r]\((\d+)\)/e', 'chr($1)', $str);
    return $str;
}

/* function export file
 * para: $path - location file export
 * para: $file - file name export
 * para: $header - column export
 * para: $arr - data export
 */
if (!function_exists('export_file')) {

    function export_file($path = "", $file_name = "", $headers = null, $arr = null) {
        $path_file = APPPATH . '../../assets/download/views/' . $file_name;
        $temp = '';
        foreach ($headers as $value) {
            $temp .= $value . chr(9);
        }
        $data[0] = trim($temp) . PHP_EOL;
        foreach ($arr as $key => $item) {
            $temp = '';
            foreach ($headers as $value) {
                $temp .= trim($item[$value]) . chr(9);
            }
            $data[] = $temp . PHP_EOL;
        }
        file_put_contents($path_file, $data);
        unset($data);
        if(is_file($path . $file_name)) {
            unlink($path . $file_name);
        }
        copy($path_file, $path . $file_name);
        unlink($path_file);
    }

}

if (!function_exists('load_data_mysql')) {

    function load_data_mysql($path = "", $file_name = "", $table_name = "", $arr_header = null) {
        $CI = & get_instance();
        //$CI->db->query("TRUNCATE TABLE ".$table_name);
        $path = $path.$file_name;
        $data_file = file($path);
        if($arr_header == null){
            $data_header = $data_file[0];
            $arr_header = explode("\t",$data_header);    
        }
        //print_r($data_file); exit;
        unset($data_file[0]);
        $data_final = array();
        foreach($data_file as $data){
            $arr_data = explode("\t",$data);
            $data_final_item = array();
            foreach($arr_header as $key => $item){
                $data_final_item[trim($item)] = trim(isset($arr_data[$key]) ? $arr_data[$key] : "");
            }
            $data_final[] = $data_final_item;
        }
        $CI->db->insert_batch($table_name,$data_final);
    }
}

if (!function_exists('write_log')) {
    function write_log($path = "", $filename = "", $data = "") {
        $create = fopen($path.$filename, "a");
        $write = fwrite($create, date('Y-m-d H:i:s',time()).chr(9).$data."\r\n");
        fclose($create);
    }
}   

function nv_EncString ( $text )
{
    $text = html_entity_decode( $text );
    //thay thế chữ thuong
    $text = preg_replace( "/(å|ä|ā|à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ|ä|ą)/u", 'a', $text );
    $text = preg_replace( "/(ß|ḃ)/", "b", $text );
    $text = preg_replace( "/(ç|ć|č|ĉ|ċ|¢|©)/", 'c', $text );
    $text = preg_replace( "/(đ|ď|ḋ|đ)/", 'd', $text );
    $text = preg_replace( "/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ|ę|ë|ě|ė)/", 'e', $text );
    $text = preg_replace( "/(ḟ|ƒ)/", "f", $text );
    $text = str_replace( "ķ", "k", $text );
    $text = preg_replace( "/(ħ|ĥ)/", "h", $text );
    $text = preg_replace( "/(ì|í|î|ị|ỉ|ĩ|ï|î|ī|¡|į)/", 'i', $text );
    $text = str_replace( "ĵ", "j", $text );
    $text = str_replace( "ṁ", "m", $text );
   
    $text = preg_replace( "/(ö|ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ|ö|ø|ō)/", 'o', $text );
    $text = str_replace( "ṗ", "p", $text );
    $text = preg_replace( "/(ġ|ģ|ğ|ĝ)/", "g", $text );
    $text = preg_replace( "/(ü|ù|ú|ū|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ|ü|ų|ů)/", 'u', $text );
    $text = preg_replace( "/(ỳ|ý|ỵ|ỷ|ỹ|ÿ)/", 'y', $text );
    $text = preg_replace( "/(ń|ñ|ň|ņ)/", 'n', $text );
    $text = preg_replace( "/(ŝ|š|ś|ṡ|ș|ş|³)/", 's', $text );
    $text = preg_replace( "/(ř|ŗ|ŕ)/", "r", $text );
    $text = preg_replace( "/(ṫ|ť|ț|ŧ|ţ)/", 't', $text );
   
    $text = preg_replace( "/(ź|ż|ž)/", 'z', $text );
    $text = preg_replace( "/(ł|ĺ|ļ|ľ)/", "l", $text );
   
    $text = preg_replace( "/(ẃ|ẅ)/", "w", $text );
   
    $text = str_replace( "æ", "ae", $text );
    $text = str_replace( "þ", "th", $text );
    $text = str_replace( "ð", "dh", $text );
    $text = str_replace( "£", "pound", $text );
    $text = str_replace( "¥", "yen", $text );
   
    $text = str_replace( "ª", "2", $text );
    $text = str_replace( "º", "0", $text );
    $text = str_replace( "¿", "?", $text );
   
    $text = str_replace( "µ", "mu", $text );
    $text = str_replace( "®", "r", $text );
   
    //thay thế chữ hoa
    $text = preg_replace( "/(Ä|À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ|Ą|Å|Ā)/", 'A', $text );
    $text = preg_replace( "/(Ḃ|B)/", 'B', $text );
    $text = preg_replace( "/(Ç|Ć|Ċ|Ĉ|Č)/", 'C', $text );
    $text = preg_replace( "/(Đ|Ď|Ḋ)/", 'D', $text );
    $text = preg_replace( "/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ|Ę|Ë|Ě|Ė|Ē)/", 'E', $text );
    $text = preg_replace( "/(Ḟ|Ƒ)/", "F", $text );
    $text = preg_replace( "/(Ì|Í|Ị|Ỉ|Ĩ|Ï|Į)/", 'I', $text );
    $text = preg_replace( "/(Ĵ|J)/", "J", $text );
   
    $text = preg_replace( "/(Ö|Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ|Ø)/", 'O', $text );
    $text = preg_replace( "/(Ü|Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ|Ū|Ų|Ů)/", 'U', $text );
    $text = preg_replace( "/(Ỳ|Ý|Ỵ|Ỷ|Ỹ|Ÿ)/", 'Y', $text );
    $text = str_replace( "Ł", "L", $text );
    $text = str_replace( "Þ", "Th", $text );
    $text = str_replace( "Ṁ", "M", $text );
   
    $text = preg_replace( "/(Ń|Ñ|Ň|Ņ)/", "N", $text );
    $text = preg_replace( "/(Ś|Š|Ŝ|Ṡ|Ș|Ş)/", "S", $text );
    $text = str_replace( "Æ", "AE", $text );
    $text = preg_replace( "/(Ź|Ż|Ž)/", 'Z', $text );
   
    $text = preg_replace( "/(Ř|R|Ŗ)/", 'R', $text );
    $text = preg_replace( "/(Ț|Ţ|T|Ť)/", 'T', $text );
    $text = preg_replace( "/(Ķ|K)/", 'K', $text );
    $text = preg_replace( "/(Ĺ|Ł|Ļ|Ľ)/", 'L', $text );
   
    $text = preg_replace( "/(Ħ|Ĥ)/", 'H', $text );
    $text = preg_replace( "/(Ṗ|P)/", 'P', $text );
    $text = preg_replace( "/(Ẁ|Ŵ|Ẃ|Ẅ)/", 'W', $text );
    $text = preg_replace( "/(Ģ|G|Ğ|Ĝ|Ġ)/", 'G', $text );
    $text = preg_replace( "/(Ŧ|Ṫ)/", 'T', $text );
   
    return $text;
}
?>
