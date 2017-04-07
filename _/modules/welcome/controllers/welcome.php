<?php

class Welcome extends MY_Controller {

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     * 		http://example.com/index.php/welcome
     * 	- or -
     * 		http://example.com/index.php/welcome/index
     * 	- or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see http://codeigniter.com/user_guide/general/urls.html
     */
    function __construct() {
        parent::__construct();
        $this->load->Model('admin/download_model', 'mdownload');
    }

    public function index() {
        echo 'welcome';
    }

    public function download_cafef_yesterday() {
        set_time_limit(0);
        $yesterday_y = date('Ymd', strtotime(date('Y-m-d') . ' -1 day'));
        $yesterday_d = date('dmY', strtotime(date('Y-m-d') . ' -1 day'));
        $arrDownload = array();
        $arrDownload[] = 'http://images1.cafef.vn/data/' . $yesterday_y . '/CafeF.SolieuGD.' . $yesterday_d . '.zip'; /* ok */
        $arrDownload[] = 'http://images1.cafef.vn/data/' . $yesterday_y . '/CafeF.SolieuGD.Upto' . $yesterday_d . '.zip'; /* ok */
        $arrDownload[] = 'http://images1.cafef.vn/data/' . $yesterday_y . '/CafeF.SolieuGD.Raw.' . $yesterday_d . '.zip'; /* ok */
        $arrDownload[] = 'http://images1.cafef.vn/data/' . $yesterday_y . '/CafeF.SolieuGD.Raw.Upto' . $yesterday_d . '.zip';
        $arrDownload[] = 'http://images1.cafef.vn/data/' . $yesterday_y . '/CafeF.Index.' . $yesterday_d . '.zip';
        $arrDownload[] = 'http://images1.cafef.vn/data/' . $yesterday_y . '/CafeF.Index.Upto' . $yesterday_d . '.zip';
        $arrDownload[] = 'http://images1.cafef.vn/data/' . $yesterday_y . '/CafeF.CCNN.' . $yesterday_d . '.zip';
        $arrDownload[] = 'http://images1.cafef.vn/data/' . $yesterday_y . '/CafeF.CCNN.Upto' . $yesterday_d . '.zip';
        $arrDownload[] = 'http://images1.cafef.vn/data/' . $yesterday_y . '/CafeF.CCNN.Index.' . $yesterday_d . '.zip';
        $arrDownload[] = 'http://images1.cafef.vn/data/' . $yesterday_y . '/CafeF.CCNN.Index.Upto' . $yesterday_d . '.zip';

        foreach ($arrDownload as $value) {
            if (url_exists($value)) {
                $file = end(explode('/', $value));
                $f = fopen('\\\LOCAL\IFRCVN\VNDB\METASTOCK\CAFEF\ZIP\\' . $file, 'w');
                $data = file_get_contents($value);
                fwrite($f, $data);
                fclose($f);
                $zip = new ZipArchive;
                $res = $zip->open('\\\LOCAL\IFRCVN\VNDB\METASTOCK\CAFEF\ZIP\\' . $file);
                if ($res === TRUE) {
                    $zip->extractTo('\\\LOCAL\IFRCVN\VNDB\METASTOCK\CAFEF\TXT\\');
                    $zip->close();
                }
            }
        }
    }

    public function upload_backup_vndb_ftp() {
        set_time_limit(0);
        $this->load->library('ftp');
        $config = array();
        $config['hostname'] = 'indexifrc.com.vn';
        $config['username'] = 'indexifr';
        $config['password'] = 'iFrCfEB2012';
        $config['debug'] = TRUE;

        $this->ftp->connect($config);
        $list = list_file_vndb('\\\LOCAL\IFRCVN\VNDB\METASTOCK\ARCHIVES', '', '.zip|.ZIP');
        $list_file_ftp = $this->ftp->list_files('/public_html/vndb/');
        if (is_array($list)) {
            foreach ($list as $key => $value) {
                $name = end(explode('/', $value));
                if (in_array($name, $list_file_ftp) == FALSE) {
                    echo $name . '<br/>';
                    $this->ftp->upload($value, '/public_html/vndb/' . $name, 'auto', 0775);
                }
            }
        }
        $this->ftp->close();
    }

    public function download_cafef_yesterday2() {
        set_time_limit(0);
        $yesterday_y = date('Ymd', strtotime(date('Y-m-d') . ' -1 day'));
        $yesterday_d = date('dmY', strtotime(date('Y-m-d') . ' -1 day'));
        $arrDownload = array();
        $arrDownload[] = 'http://images1.cafef.vn/data/' . $yesterday_y . '/CafeF.SolieuGD.' . $yesterday_d . '.zip'; /* ok */
        $arrDownload[] = 'http://images1.cafef.vn/data/' . $yesterday_y . '/CafeF.SolieuGD.Upto' . $yesterday_d . '.zip'; /* ok */
        $arrDownload[] = 'http://images1.cafef.vn/data/' . $yesterday_y . '/CafeF.SolieuGD.Raw.' . $yesterday_d . '.zip'; /* ok */
        $arrDownload[] = 'http://images1.cafef.vn/data/' . $yesterday_y . '/CafeF.SolieuGD.Raw.Upto' . $yesterday_d . '.zip';
        $arrDownload[] = 'http://images1.cafef.vn/data/' . $yesterday_y . '/CafeF.Index.' . $yesterday_d . '.zip';
        $arrDownload[] = 'http://images1.cafef.vn/data/' . $yesterday_y . '/CafeF.Index.Upto' . $yesterday_d . '.zip';
        $arrDownload[] = 'http://images1.cafef.vn/data/' . $yesterday_y . '/CafeF.CCNN.' . $yesterday_d . '.zip';
        $arrDownload[] = 'http://images1.cafef.vn/data/' . $yesterday_y . '/CafeF.CCNN.Upto' . $yesterday_d . '.zip';
        $arrDownload[] = 'http://images1.cafef.vn/data/' . $yesterday_y . '/CafeF.CCNN.Index.' . $yesterday_d . '.zip';
        $arrDownload[] = 'http://images1.cafef.vn/data/' . $yesterday_y . '/CafeF.CCNN.Index.Upto' . $yesterday_d . '.zip';

        foreach ($arrDownload as $value) {
            if (url_exists($value)) {
                $file = end(explode('/', $value));
                $f = fopen('E:/xampp/htdocs/test/' . $file, 'w');
                $data = file_get_contents($value);
                fwrite($f, $data);
                fclose($f);
                $zip = new ZipArchive;
                $res = $zip->open('E:/xampp/htdocs/test/' . $file);
                if ($res === TRUE) {
                    $zip->extractTo('E:/xampp/htdocs/test/');
                    $zip->close();
                }
            }
        }
    }

    public function get_shares_caf() {
        $now = time();
        $source = 'CAF';
        $this->load->library('curl');
        $this->load->Model('admin/exchange_model', 'mexchange');
        $curl = new curl;
        $tickers = $this->mexchange->getTicker();
        // $tickers = array(array('code' => 'AAA'));
        $temp_url = 'http://s.cafef.vn/Ajax/CongTy/BanLanhDao.aspx?sym=<<ticker>>';
        $f = fopen('\\\LOCAL\IFRCVN\VNDB\METASTOCK\REFERENCE\CAF\CAF_' . date('Ymd', $now) . '.txt', 'w');
        $data[0] = array('sources', 'ticker', 'name', 'market', 'date', 'yyyymmdd', 'ipo', 'ipo_shli', 'ipo_shou', 'ftrd', 'ftrd_cls', 'shli', 'shou', 'shfn', 'capi', 'capi_fora', 'capi_forn', 'capi_stat');
        $content = implode(chr(9), end($data)) . PHP_EOL;
        fwrite($f, $content);
        foreach ($tickers as $ticker) {
            $ticker = $ticker['code'];
            $url = str_replace('<<ticker>>', $ticker, $temp_url);
            $html = $curl->makeRequest('get', $url, NULL);
            $start = 'KL CP đang ';
            $end = 'cp';
            $rule = "/(?<=$start).*(?=$end)/msU";
            preg_match_all($rule, $html, $result);
            if (!empty($result)) {
                $market = $this->mdownload->getMarket($ticker);
                $data[] = array(
                    'sources' => $source,
                    'ticker' => $ticker,
                    'name' => '',
                    'market' => $market,
                    'date' => date('Y/m/d', $now),
                    'yyyymmdd' => str_replace('/', '', date('Y/m/d', $now)),
                    'ipo' => '',
                    'ipo_shli' => '',
                    'ipo_shou' => '',
                    'ftrd' => '',
                    'ftrd_cls' => '',
                    'shli' => trim(str_replace('&nbsp;', '', str_replace(',', '', str_replace('niêm yết :', '', $result[0][0])))) * 1,
                    'shou' => trim(str_replace('&nbsp;', '', str_replace(',', '', str_replace('lưu hành :', '', $result[0][1])))) * 1,
                    'shfn' => '',
                    'capi' => '',
                    'capi_fora' => '',
                    'capi_forn' => '',
                    'capi_stat' => ''
                );
                $content = implode(chr(9), end($data)) . PHP_EOL;
                fwrite($f, $content);
            }
        }
        fclose($f);
    }

    public function get_hnx() {
        header('Content-Type: text/html; charset=utf-8');
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        // you wanna follow stuff like meta and location headers
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        // you want all the data back to test it for errors
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // probably unecessary, but cookies may be needed to
        $url = 'http://www.hnx.vn/web/guest/tin-niem-yet?p_p_id=newnyuc_WAR_HnxIndexportlet&p_p_lifecycle=2&p_p_state=normal&p_p_mode=view&p_p_cacheability=cacheLevelPage&p_p_col_id=column-1&p_p_col_count=2&_newnyuc_WAR_HnxIndexportlet_anchor=newsAction';
        $result = array();
        $arr_num = array('3', '8', '25', '34', '41');
        foreach ($arr_num as $num) {
            echo "Catalogue: " + $num . "<br />";
            $i = 0;
            while (1) {
                $i = $i + 10;
                echo "i: " + $i . "<br />";
                $filter = 'sEcho=1&iColumns=6&sColumns=&iDisplayStart=' . $i . '&iDisplayLength=10&mDataProp_0=0&mDataProp_1=1&mDataProp_2=2&mDataProp_3=3&mDataProp_4=4&mDataProp_5=5&_newnyuc_WAR_HnxIndexportlet_code=&_newnyuc_WAR_HnxIndexportlet_type_lists=' . $num . '&_newnyuc_WAR_HnxIndexportlet_news_ops_s_date=&_newnyuc_WAR_HnxIndexportlet_news_ops_e_date=&_newnyuc_WAR_HnxIndexportlet_content_search=';
                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $filter);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $result_json = curl_exec($ch);
                $result = json_decode($result_json, 1);
                $data_insert = array();
                $array_date_ann = array();
                $count = count($result['aaData']);
                foreach ($result['aaData'] as $item) {
                    $url_2 = 'http://www.hnx.vn/web/guest/tin-niem-yet?p_p_id=newnyuc_WAR_HnxIndexportlet&p_p_lifecycle=1&p_p_state=exclusive&p_p_mode=view&p_p_col_id=column-1&p_p_col_count=2&_newnyuc_WAR_HnxIndexportlet_anchor=viewAction&_newnyuc_WAR_HnxIndexportlet_cmd=viewContent&_newnyuc_WAR_HnxIndexportlet_news_id=' . $item[6] . '&_newnyuc_WAR_HnxIndexportlet_exist_file=1';
                    curl_setopt($ch, CURLOPT_URL, $url_2);
                    $html = curl_exec($ch);
                    $start = '<div class="div_row" align="left">';
                    $end = '</table>';
                    $start = preg_quote($start, '/t');
                    $end = preg_quote($end, '/t');
                    $rule = "/(?<=$start).*(?=$end)/msU";
                    $result_2 = array();
                    preg_match_all($rule, $html, $result_2);
                    $data = trim(strip_tags($result_2[0][0], '<p><a>'));
                    $array_date_ann = explode('/', $item[0]);
                    $date_ann = substr($array_date_ann[2], 0, 4) . '/' . $array_date_ann[1] . '/' . $array_date_ann[0];
                    $data_insert = array(
                        'ticker' => $item[1],
                        'market' => 'HNX',
                        'date_ann' => $date_ann,
                        'event_type' => '',
                        'evname' => $item[3],
                        'content' => $data,
                        'status' => ''
                    );
                    $check = $this->check_data_hnx($item[1], $date_ann);
                    if ($check['flag'] == 'FALSE') {
                        $this->db->where('id', $check['id']);
                        $this->db->update('vndb_events_day', $data_insert);
                    } else {
                        $this->db->insert('vndb_events_day', $data_insert);
                    }
                }
                if ($count == 0) {
                    break;
                }
            }
        }
    }

    public function check_data_hnx($ticker, $date_ann) {
        $this->db->select('count(*) as row, id');
        $this->db->where('ticker', $ticker);
        $this->db->where('date_ann', $date_ann);
        $result = $this->db->get('vndb_events_day')->row_array();
        if ($result['row'] != 0) {
            $final['flag'] = 'FALSE';
            $final['id'] = $result['id'];
        } else {
            $final['flag'] = 'TRUE';
        }
        return $final;
    }

}

/* End of file welcome.php */
    /* Location: ./application/controllers/welcome.php */