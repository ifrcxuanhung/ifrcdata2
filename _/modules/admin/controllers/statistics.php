<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/* * ****************************************************************** */
/*     Client  Name  :  IFRC                                      */
/*     Project Name  :  cms v3.0                                     */
/*     Program Name  :  article.php                                      */
/*     Entry Server  :                                               */
/*     Called By     :  System                                       */
/*     Notice        :  File Code is utf-8                           */
/*     Copyright     :  IFRC                                         */
/* ------------------------------------------------------------------- */
/*     Comment       :  controller article                             */
/* ------------------------------------------------------------------- */
/*     History       :                                               */
/* ------------------------------------------------------------------- */
/*     Version V001  :  2012.08.14 (LongNguyen)        New Create      */
/* * ****************************************************************** */

class Statistics extends Admin {

    protected $data;

    function __construct() {
        parent::__construct();
        // load model category
		set_time_limit(0);
        $this->load->model('statistics_model', 'mstat');
        $this->load->helper(array('my_array_helper', 'form'));
        $this->load->library('curl');
        $this->load->library('simple_html_dom');
        $this->load->Model('download_model', 'mdownload');
        date_default_timezone_set('Asia/Ho_Chi_Minh');
    }

    function index() {
        $this->mstat->shliStatistics();
        $this->data->title = 'List articles';
        $this->template->write_view('content', 'article/article_list', $this->data);
        $this->template->write('title', 'Articles ');
        $this->template->render();
    }
	
	function check_share(){
		$this->db->set('date');
		$this->db->where('status = ',1);
		$query = $this->db->get('shli_statistics')->result_array();
		pre($query);	
	}

    function shares_out(){
        $this->mstat->shouStatistics();
    }
	function daily_all(){
		$this->get_exc();	
		$this->update_daily();	
	}
	function monthly_all(){
		$this->get_exc_monthly();	
		$this->update_monthly();		
	}
	function yearly_all(){
		$this->get_exc_yearly();	
		$this->update_yearly();		
	}
	function update_daily(){
		set_time_limit(0);
		$this->db->query("DROP TABLE IF EXISTS TMP_STATS");
		$this->db->query("CREATE TABLE TMP_STATS (
		SELECT MARKET,YYYYMMDD, SUM(IF(LENGTH(TICKER) = 3,VLM,0)) AS SVLM_VNDB, SUM(VLM) AS SVLMKL_VNDB,
		SUM(IF(LENGTH(TICKER) = 3,TRN,0)) AS STRN_VNDB, SUM(TRN) AS STRNKL_VNDB, COUNT(TICKER) AS NB
		FROM vndb_prices_history
		GROUP BY MARKET,YYYYMMDD ORDER BY YYYYMMDD,MARKET
		)");
		$this->db->query("CREATE INDEX MARKETYYYYMMDD ON TMP_STATS (MARKET,YYYYMMDD) USING BTREE");
		$this->db->query("CREATE INDEX MARKETYYYYMMDD1 ON vndb_stats_daily (MARKET,YYYYMMDD) USING BTREE");
		
		
		$this->db->query("INSERT INTO vndb_stats_daily (YYYYMMDD, MARKET)
		(SELECT B.YYYYMMDD, B.MARKET FROM TMP_STATS AS B LEFT JOIN vndb_stats_daily AS A ON CONCAT(B.YYYYMMDD , B.MARKET) = CONCAT(A.YYYYMMDD , A.MARKET) 
		WHERE CONCAT(A.YYYYMMDD , A.MARKET) IS NULL)");
		
		$this->db->query("UPDATE vndb_stats_daily SET SVLM_VNDB = 0, STRN_VNDB = 0, SVLMKL_VNDB = 0, STRNKL_VNDB = 0");
		
		$this->db->query("UPDATE vndb_stats_daily A, TMP_STATS B SET A.DATE = NOW(), A.SVLM_VNDB = B.SVLM_VNDB, A.STRN_VNDB = B.STRN_VNDB, A.SVLMKL_VNDB = B.SVLMKL_VNDB, A.STRNKL_VNDB = B.STRNKL_VNDB, A.NB = B.NB
		WHERE A.MARKET = B.MARKET AND A.YYYYMMDD = B.YYYYMMDD");
		
		$this->db->query("UPDATE vndb_stats_daily A, vndb_stats_exc_day B SET 
		A.SVLM_EXC = IF(B.SVLM_EXC = 0 || B.SVLM_EXC IS NULL, A.SVLM_EXC, B.SVLM_EXC),
		A.STRN_EXC = IF(B.STRN_EXC = 0 || B.STRN_EXC IS NULL, A.STRN_EXC, B.STRN_EXC),
		A.SVLMKL_EXC = IF(B.SVLMKL_EXC = 0 || B.SVLMKL_EXC IS NULL, A.SVLMKL_EXC, B.SVLMKL_EXC),
		A.STRNKL_EXC = IF(B.STRNKL_EXC = 0 || B.STRNKL_EXC IS NULL , A.STRNKL_EXC, B.STRNKL_EXC)
		WHERE A.MARKET = B.MARKET AND A.YYYYMMDD = B.YYYYMMDD");
		
		$this->db->query("UPDATE vndb_stats_daily A, vndb_stats_exc_all B SET 
		A.SVLM_EXC = IF(B.SVLM_EXC = 0 || B.SVLM_EXC IS NULL, A.SVLM_EXC, B.SVLM_EXC), 
		A.STRN_EXC = IF(B.STRN_EXC = 0 || B.STRN_EXC IS NULL, A.STRN_EXC, B.STRN_EXC), 
		A.SVLMKL_EXC = IF(B.SVLMKL_EXC = 0 || B.SVLMKL_EXC IS NULL, A.SVLMKL_EXC, B.SVLMKL_EXC),
		A.STRNKL_EXC = IF(B.STRNKL_EXC = 0 || B.STRNKL_EXC IS NULL, A.STRNKL_EXC, B.STRNKL_EXC),
		A.SVLMTT_EXC = IF(B.SVLMTT_EXC = 0 || B.SVLMTT_EXC IS NULL, A.SVLMTT_EXC, B.SVLMTT_EXC),  
		A.STRNTT_EXC = IF(B.STRNTT_EXC = 0 || B.STRNTT_EXC IS NULL, A.STRNTT_EXC, B.STRNTT_EXC)
		WHERE B.PER = 'D' AND A.MARKET = B.MARKET AND A.YYYYMMDD = B.YYYYMMDD");
		
		$this->db->query("UPDATE vndb_stats_daily SET SVLM_DIFF = NULL, STRN_DIFF = NULL");
		
		$this->db->query("UPDATE vndb_stats_daily SET SVLM_DIFF = SVLM_VNDB - SVLM_EXC WHERE SVLM_EXC * SVLM_VNDB <> 0");
		
		$this->db->query("UPDATE vndb_stats_daily SET STRN_DIFF = STRN_VNDB - STRN_EXC WHERE STRN_EXC * STRN_VNDB <> 0");
		
		$this->db->query("UPDATE vndb_stats_daily SET SVLMKL_DIFF = SVLMKL_VNDB - SVLMKL_EXC WHERE SVLMKL_EXC * SVLMKL_VNDB <> 0");
		
		$this->db->query("UPDATE vndb_stats_daily SET STRNKL_DIFF = STRNKL_VNDB - STRNKL_EXC WHERE STRNKL_EXC * STRNKL_VNDB <> 0");
		
		$this->db->query("UPDATE vndb_stats_daily SET 
		correct_vlm = IF(SVLM_DIFF = 0,1,0), 
		correct_trn = IF(STRN_DIFF = 0,1,0), 
		correct_vlmkl = IF(SVLMKL_DIFF = 0,1,0), 
		correct_trnkl = IF(STRNKL_DIFF = 0,1,0)");
		
		$this->db->query("UPDATE vndb_stats_daily SET correct = NULL");
		$this->db->query("UPDATE vndb_stats_daily SET correct = IF(correct_vlm * correct_trn * correct_vlmkl * correct_trnkl = 1,1,0)");
		
		$this->db->query("UPDATE vndb_stats_daily SET YYYYMM = LEFT(YYYYMMDD,6), YYYY = LEFT(YYYYMMDD,4)");
		
		$this->db->query("DROP TABLE IF EXISTS TMP_STATS");
		$this->db->query("CREATE TABLE TMP_STATS (
		SELECT * FROM vndb_stats_daily 
		)");
		
		$this->db->query("DROP TABLE IF EXISTS vndb_stats_daily");
		$this->db->query("CREATE TABLE vndb_stats_daily (
		SELECT * FROM TMP_STATS ORDER BY YYYYMMDD DESC, MARKET
		)");
		
		$this->db->query("ALTER TABLE vndb_stats_daily DROP COLUMN id");
		$this->db->query("ALTER TABLE vndb_stats_daily ADD id INT (10) NOT NULL AUTO_INCREMENT PRIMARY KEY");
		
		$this->db->query("UPDATE vndb_stats_daily SET YYYYMMDD = REPLACE(DATE, '-', '') WHERE YYYYMMDD IS NULL");
		
		$this->db->query("INSERT INTO vndb_stats_exc_day (YYYYMMDD, MARKET)
		(SELECT B.YYYYMMDD, B.MARKET FROM vndb_stats_daily AS B LEFT JOIN vndb_stats_exc_day AS A ON CONCAT(B.YYYYMMDD , B.MARKET) = CONCAT(A.YYYYMMDD , A.MARKET) 
		WHERE CONCAT(A.YYYYMMDD , A.MARKET) IS NULL)");
		
		$this->db->query("DROP TABLE IF EXISTS TMP_STATS");
		$this->out_home();
	}
	function update_monthly(){
		set_time_limit(0);
		$this->db->query("DROP TABLE IF EXISTS TMP_STATS");
		$this->db->query("CREATE TABLE TMP_STATS (
SELECT MARKET,LEFT(YYYYMMDD,6) AS YYYYMM, SUM(IF(LENGTH(TICKER) = 3,VLM,0)) AS SVLM_VNDB, SUM(VLM) AS SVLMKL_VNDB,
SUM(IF(LENGTH(TICKER) = 3,TRN,0)) AS STRN_VNDB, SUM(TRN) AS STRNKL_VNDB, COUNT(TICKER) AS NB
FROM vndb_prices_history
GROUP BY MARKET,YYYYMM ORDER BY YYYYMM,MARKET
)");
		$this->db->query("CREATE INDEX MARKETYYYYMM ON TMP_STATS (MARKET,YYYYMM) USING BTREE");
		$this->db->query("CREATE INDEX MARKETYYYYMM ON vndb_stats_monthly (MARKET,YYYYMM) USING BTREE");

		$this->db->query("INSERT INTO vndb_stats_monthly (YYYYMM, MARKET)
(SELECT B.YYYYMM, B.MARKET FROM TMP_STATS AS B LEFT JOIN vndb_stats_monthly AS A ON CONCAT(B.YYYYMM , B.MARKET) = CONCAT(A.YYYYMM , A.MARKET) 
WHERE CONCAT(A.YYYYMM , A.MARKET) IS NULL)");

		$this->db->query("UPDATE vndb_stats_monthly SET SVLM_VNDB = 0, STRN_VNDB = 0, SVLMKL_VNDB = 0, STRNKL_VNDB = 0");

		$this->db->query("UPDATE vndb_stats_monthly A, TMP_STATS B SET A.DATE = NOW(), A.SVLM_VNDB = B.SVLM_VNDB, A.STRN_VNDB = B.STRN_VNDB, A.SVLMKL_VNDB = B.SVLMKL_VNDB, A.STRNKL_VNDB = B.STRNKL_VNDB, A.NB = B.NB
WHERE A.MARKET = B.MARKET AND A.YYYYMM = B.YYYYMM");

		$this->db->query("UPDATE vndb_stats_monthly A, vndb_stats_exc_month B SET A.SVLM_EXC = B.SVLM_EXC, A.STRN_EXC = B.STRN_EXC, A.SVLMKL_EXC = B.SVLMKL_EXC, A.STRNKL_EXC = B.STRNKL_EXC
WHERE A.MARKET = B.MARKET AND A.YYYYMM = B.YYYYMM");

		$this->db->query("UPDATE vndb_stats_monthly A, vndb_stats_exc_all B SET 
A.SVLM_EXC = IF(B.SVLM_EXC = 0, A.SVLM_EXC, B.SVLM_EXC), 
A.STRN_EXC = IF(B.STRN_EXC = 0, A.STRN_EXC, B.STRN_EXC), 
A.SVLMKL_EXC = IF(B.SVLMKL_EXC = 0, A.SVLMKL_EXC, B.SVLMKL_EXC),
A.STRNKL_EXC = IF(B.STRNKL_EXC = 0, A.STRNKL_EXC, B.STRNKL_EXC),
A.SVLMTT_EXC = IF(B.SVLMTT_EXC = 0, A.SVLMTT_EXC, B.SVLMTT_EXC),  
A.STRNTT_EXC = IF(B.STRNTT_EXC = 0, A.STRNTT_EXC, B.STRNTT_EXC)
WHERE B.PER = 'M' AND A.MARKET = B.MARKET AND A.YYYYMM = B.YYYYMM");

		$this->db->query("UPDATE vndb_stats_monthly SET SVLM_DIFF = NULL, STRN_DIFF = NULL");

		$this->db->query("UPDATE vndb_stats_monthly SET SVLM_DIFF = SVLM_VNDB - SVLM_EXC WHERE SVLM_EXC * SVLM_VNDB <> 0");

		$this->db->query("UPDATE vndb_stats_monthly SET STRN_DIFF = STRN_VNDB - STRN_EXC WHERE STRN_EXC * STRN_VNDB <> 0");

		$this->db->query("UPDATE vndb_stats_monthly SET SVLMKL_DIFF = SVLMKL_VNDB - SVLMKL_EXC WHERE SVLMKL_EXC * SVLMKL_VNDB <> 0");

		$this->db->query("UPDATE vndb_stats_monthly SET STRNKL_DIFF = STRNKL_VNDB - STRNKL_EXC WHERE STRNKL_EXC * STRNKL_VNDB <> 0");

		$this->db->query("UPDATE vndb_stats_monthly SET 
correct_vlm = IF(SVLM_DIFF = 0,1,0), 
correct_trn = IF(STRN_DIFF = 0,1,0), 
correct_vlmkl = IF(SVLMKL_DIFF = 0,1,0), 
correct_trnkl = IF(STRNKL_DIFF = 0,1,0)");

		$this->db->query("UPDATE vndb_stats_monthly SET correct = NULL");
		$this->db->query("UPDATE vndb_stats_monthly SET correct = IF(correct_vlm * correct_trn * correct_vlmkl * correct_trnkl = 1,1,0)");

		$this->db->query("DROP TABLE IF EXISTS TMP_STATS");
		$this->db->query("CREATE TABLE TMP_STATS (
SELECT * FROM vndb_stats_monthly 
)");

		$this->db->query("DROP TABLE IF EXISTS vndb_stats_monthly");
		$this->db->query("CREATE TABLE vndb_stats_monthly (
SELECT * FROM TMP_STATS ORDER BY YYYYMM DESC, MARKET
)");
		$this->db->query("DROP TABLE IF EXISTS TMP_STATS");

		$this->db->query("ALTER TABLE vndb_stats_monthly DROP COLUMN id");
		$this->db->query("ALTER TABLE vndb_stats_monthly ADD id INT (10) NOT NULL AUTO_INCREMENT PRIMARY KEY");


		$this->out_home();
	}
	function update_yearly(){
	  	set_time_limit(0);
		$this->db->query("DROP TABLE IF EXISTS TMP_STATS");
		$this->db->query("CREATE TABLE TMP_STATS (
SELECT MARKET,LEFT(YYYYMMDD,4) AS YYYY, SUM(IF(LENGTH(TICKER) = 3,VLM,0)) AS SVLM_VNDB, SUM(VLM) AS SVLMKL_VNDB,
SUM(IF(LENGTH(TICKER) = 3,TRN,0)) AS STRN_VNDB, SUM(TRN) AS STRNKL_VNDB, COUNT(TICKER) AS NB
FROM vndb_prices_history
GROUP BY MARKET,YYYY ORDER BY YYYY,MARKET
)");
		$this->db->query("CREATE INDEX MARKETYYYY ON TMP_STATS (MARKET,YYYY) USING BTREE");
		$this->db->query("CREATE INDEX MARKETYYYY ON vndb_stats_yearly (MARKET,YYYY) USING BTREE");

		$this->db->query("INSERT INTO vndb_stats_yearly (YYYY, MARKET)
(SELECT B.YYYY, B.MARKET FROM TMP_STATS AS B LEFT JOIN vndb_stats_yearly AS A ON CONCAT(B.YYYY , B.MARKET) = CONCAT(A.YYYY , A.MARKET) 
WHERE CONCAT(A.YYYY , A.MARKET) IS NULL)");

		$this->db->query("UPDATE vndb_stats_yearly SET SVLM_VNDB = 0, STRN_VNDB = 0, SVLMKL_VNDB = 0, STRNKL_VNDB = 0");

		$this->db->query("UPDATE vndb_stats_yearly A, TMP_STATS B SET A.DATE = NOW(), A.SVLM_VNDB = B.SVLM_VNDB, A.STRN_VNDB = B.STRN_VNDB, A.SVLMKL_VNDB = B.SVLMKL_VNDB, A.STRNKL_VNDB = B.STRNKL_VNDB , A.NB = B.NB
WHERE A.MARKET = B.MARKET AND A.YYYY = B.YYYY");

		$this->db->query("UPDATE vndb_stats_yearly A, vndb_stats_exc_year B SET A.SVLM_EXC = B.SVLM_EXC, A.STRN_EXC = B.STRN_EXC, A.SVLMKL_EXC = B.SVLMKL_EXC, A.STRNKL_EXC = B.STRNKL_EXC
WHERE A.MARKET = B.MARKET AND A.YYYY = B.YYYY");

		$this->db->query("UPDATE vndb_stats_yearly A, vndb_stats_exc_all B SET 
A.SVLM_EXC = IF(B.SVLM_EXC = 0, A.SVLM_EXC, B.SVLM_EXC), 
A.STRN_EXC = IF(B.STRN_EXC = 0, A.STRN_EXC, B.STRN_EXC), 
A.SVLMKL_EXC = IF(B.SVLMKL_EXC = 0, A.SVLMKL_EXC, B.SVLMKL_EXC),
A.STRNKL_EXC = IF(B.STRNKL_EXC = 0, A.STRNKL_EXC, B.STRNKL_EXC),
A.SVLMTT_EXC = IF(B.SVLMTT_EXC = 0, A.SVLMTT_EXC, B.SVLMTT_EXC),  
A.STRNTT_EXC = IF(B.STRNTT_EXC = 0, A.STRNTT_EXC, B.STRNTT_EXC)
WHERE B.PER = 'Y' AND A.MARKET = B.MARKET AND A.YYYY = B.YYYY");

		$this->db->query("UPDATE vndb_stats_yearly SET SVLM_DIFF = NULL, STRN_DIFF = NULL");

		$this->db->query("UPDATE vndb_stats_yearly SET SVLM_DIFF = SVLM_VNDB - SVLM_EXC WHERE SVLM_EXC * SVLM_VNDB <> 0");

		$this->db->query("UPDATE vndb_stats_yearly SET STRN_DIFF = STRN_VNDB - STRN_EXC WHERE STRN_EXC * STRN_VNDB <> 0");

		$this->db->query("UPDATE vndb_stats_yearly SET SVLMKL_DIFF = SVLMKL_VNDB - SVLMKL_EXC WHERE SVLMKL_EXC * SVLMKL_VNDB <> 0");

		$this->db->query("UPDATE vndb_stats_yearly SET STRNKL_DIFF = STRNKL_VNDB - STRNKL_EXC WHERE STRNKL_EXC * STRNKL_VNDB <> 0");

		$this->db->query("UPDATE vndb_stats_yearly SET 
correct_vlm = IF(SVLM_DIFF = 0,1,0), 
correct_trn = IF(STRN_DIFF = 0,1,0), 
correct_vlmkl = IF(SVLMKL_DIFF = 0,1,0), 
correct_trnkl = IF(STRNKL_DIFF = 0,1,0)");

		$this->db->query("UPDATE vndb_stats_yearly SET correct = NULL");
		$this->db->query("UPDATE vndb_stats_yearly SET correct = IF(correct_vlm * correct_trn * correct_vlmkl * correct_trnkl = 1,1,0)");

		$this->db->query("DROP TABLE IF EXISTS TMP_STATS");
		$this->db->query("CREATE TABLE TMP_STATS (
SELECT * FROM vndb_stats_yearly 
)");

		$this->db->query("DROP TABLE IF EXISTS vndb_stats_yearly");
		$this->db->query("CREATE TABLE vndb_stats_yearly (
SELECT * FROM TMP_STATS ORDER BY YYYY DESC, MARKET
)");
		$this->db->query("DROP TABLE IF EXISTS TMP_STATS");

		$this->db->query("ALTER TABLE vndb_stats_yearly DROP COLUMN id");
		$this->db->query("ALTER TABLE vndb_stats_yearly ADD id INT (10) NOT NULL AUTO_INCREMENT PRIMARY KEY");


		$this->out_home();
	}
	public function get_exc(){
        $now = time();
        $table = 'vndb_stats_daily';
        // get hsx
        $market = 'HSX';
        $this->load->library('curl');
        $curl = new curl;
        $url = 'http://www.hsx.vn/hsx/Modules/Giaodich/KQGDCN.aspx';
        $method = 'post';
        $post = NULL;
        $start = 'Cổ Phiếu / Stocks</span></td>';
        $end = '</td>';
        $value = download_exc($market, $url, $start, $end, $method, $post);

        $start = 'Chứng Chỉ Quỹ / IFCs</span></td>';
        $value2 = download_exc($market, $url, $start, $end, $method, $post);
    
        $svlm_exc = 0;
        $strn_exc = 0;
        $svlmkl_exc = 0;
        $strnkl_exc = 0;

        if(isset($value)){
            if(isset($value[0])){
                $svlm_exc = convertNumber2Us(trim(strip_tags(str_replace('""', '"', $value[0]))), 'vn') * 1;
                if(isset($value2[0])){
                    $svlmkl_exc = convertNumber2Us(trim(strip_tags(str_replace('""', '"', $value2[0]))), 'vn') * 1;
                    $svlmkl_exc += $svlm_exc;
                }
            }
            if(isset($value[1])){
                $strn_exc = convertNumber2Us(trim(strip_tags(str_replace('""', '"', $value[1]))), 'vn') * 1000;
                if(isset($value2[1])){
                    $strnkl_exc = convertNumber2Us(trim(strip_tags(str_replace('""', '"', $value2[1]))), 'vn') * 1000;
                    $strnkl_exc += $strn_exc;
                }
            }
            
            
            $data = array(
                'market' => $market,
                'date' => date('Y/m/d', $now),                    
                'yyyymmdd' => date('Ymd', $now),
                'yyyymm' => date('Ym', $now),
                'yyyy' => date('Y', $now),
                'svlm_exc' => $svlm_exc,
                'strn_exc' => $strn_exc,
                'svlmkl_exc' => $svlmkl_exc,
                'strnkl_exc' => $strnkl_exc
            );
            $where = array(
                'market' => $data['market'],
                'yyyymmdd' => $data['yyyymmdd']
            );
            $this->mdownload->update_exc($table, $data, $where);

            $file = '\\\LOCAL\IFRCVN\VNDB\METASTOCK\STATISTICS\DAY\STATS_HSX_' . $data['yyyymmdd'] . '.txt';
            $headers = array_keys($data);
            $content = implode(chr(9), $headers) . PHP_EOL;
            $content .= implode(chr(9), $data) . PHP_EOL;
            $f = fopen($file, 'w');
            fwrite($f, $content);
            fclose($f);
            
        }

        // get hnx
        $market = 'HNX';
        $this->load->library('curl');
        $curl = new curl;
        $post = 'p_p_id=gdtkkqgd_WAR_HnxIndexportlet&p_p_lifecycle=1&p_p_state=exclusive&p_p_mode=view&p_p_col_id=column-1&p_p_col_count=2&_gdtkkqgd_WAR_HnxIndexportlet_anchor=queryAction&_gdtkkqgd_WAR_HnxIndexportlet_cmd=&_gdtkkqgd_WAR_HnxIndexportlet_par_tk_type=0&_gdtkkqgd_WAR_HnxIndexportlet_par_ad_view=&_gdtkkqgd_WAR_HnxIndexportlet_par_idx=HNX_INDEX&_gdtkkqgd_WAR_HnxIndexportlet_par_chart_kl=&_gdtkkqgd_WAR_HnxIndexportlet_par_chart_gt=&_gdtkkqgd_WAR_HnxIndexportlet_rd_par_tk_type=0&_gdtkkqgd_WAR_HnxIndexportlet_rd_par_tk_type=1&_gdtkkqgd_WAR_HnxIndexportlet_rd_par_tk_type=2&_gdtkkqgd_WAR_HnxIndexportlet_rd_par_idx=HNX_INDEX&_gdtkkqgd_WAR_HnxIndexportlet_rd_par_idx=HNX30&as_fid=rlMD6I7rsttLF64BTe/G';
        $post = explode('&', $post);
        $method = 'post';
        $url = 'http://hnx.vn/web/guest/ket-qua-giao-dich';

        $start = '<tr class="odd">';
        $end = '<td>';

        $value = download_exc($market, $url, $start, $end, $method, $post);

        $svlm_exc = 0;
        $strn_exc = 0;
        $svlmtt_exc = 0;
        $strntt_exc = 0;
        if(isset($value)){
            if(isset($value[0])){
                $date = trim(strip_tags($value[0]));
                $date = strtotime($date);
                $date = date('Y-m-d', $date);
            }
            if($date == date('Y-m-d', $now)){
                if(isset($value[1])){
                    $svlm_exc = convertNumber2Us(trim(strip_tags(str_replace('""', '"', $value[1]))), 'vn') * 1;
                }
                if(isset($value[2])){
                    $strn_exc = convertNumber2Us(trim(strip_tags(str_replace('""', '"', $value[2]))), 'vn') * 1000;
                }  
                if(isset($value[5])){
                    $svlmtt_exc = convertNumber2Us(trim(strip_tags(str_replace('""', '"', $value[5]))), 'vn') * 1;
                }
                if(isset($value[6])){
                    $strntt_exc = convertNumber2Us(trim(strip_tags(str_replace('""', '"', $value[6]))), 'vn') * 1000;
                }
                $data = array(
                    'market' => $market,
                    'date' => date('Y/m/d', $now),                    
                    'yyyymmdd' => date('Ymd', $now),
                    'yyyymm' => date('Ym', $now),
                    'yyyy' => date('Y', $now),
                    'svlm_exc' => $svlm_exc,
                    'strn_exc' => $strn_exc,
                    'svlmkl_exc' => $svlm_exc,
                    'strnkl_exc' => $strn_exc,
                    'svlmtt_exc' => $svlmtt_exc,
                    'strntt_exc' => $strntt_exc
                );
                
                $where = array(
                    'market' => $data['market'],
                    'yyyymmdd' => $data['yyyymmdd']
                );
                $this->mdownload->update_exc($table, $data, $where);

                $file = '\\\LOCAL\IFRCVN\VNDB\METASTOCK\STATISTICS\DAY\STATS_HNX_' . $data['yyyymmdd'] . '.txt';
                $headers = array_keys($data);
                $content = implode(chr(9), $headers) . PHP_EOL;
                $content .= implode(chr(9), $data) . PHP_EOL;
                $f = fopen($file, 'w');
                fwrite($f, $content);
                fclose($f);
            }
        }

        //get upc
        $market = 'UPC';
        $this->load->library('curl');
        $curl = new curl;
        $post = 'p_p_id=gdtkkqgd_WAR_HnxIndexportlet&p_p_lifecycle=1&p_p_state=exclusive&p_p_mode=view&p_p_col_id=column-1&p_p_col_count=1&_gdtkkqgd_WAR_HnxIndexportlet_anchor=queryAction&_gdtkkqgd_WAR_HnxIndexportlet_cmd=&_gdtkkqgd_WAR_HnxIndexportlet_par_tk_type=0&_gdtkkqgd_WAR_HnxIndexportlet_par_ad_view=&_gdtkkqgd_WAR_HnxIndexportlet_par_idx=UPCOM_INDEX&_gdtkkqgd_WAR_HnxIndexportlet_par_chart_kl=&_gdtkkqgd_WAR_HnxIndexportlet_par_chart_gt=&_gdtkkqgd_WAR_HnxIndexportlet_rd_par_tk_type=0&_gdtkkqgd_WAR_HnxIndexportlet_rd_par_tk_type=1&_gdtkkqgd_WAR_HnxIndexportlet_rd_par_tk_type=2&_gdtkkqgd_WAR_HnxIndexportlet_rd_par_idx=UPCOM_INDEX&_gdtkkqgd_WAR_HnxIndexportlet_rd_par_idx=HNX30&as_fid=Kqrm0ZM5htjGvZ+oxs38';
        $post = explode('&', $post);
        $method = 'post';
        $url = 'http://hnx.vn/web/guest/ket-qua-giao-dich2';

        $start = '<tr class="odd">';
        $end = '<td>';
        $value = download_exc($market, $url, $start, $end, $method, $post);
        $svlm_exc = 0;
        $strn_exc = 0;
        $svlmtt_exc = 0;
        $strntt_exc = 0;
        if(isset($value)){
            if(isset($value[0])){
                $date = trim(strip_tags($value[0]));
                $date = strtotime($date);
                $date = date('Y-m-d', $date);
            }
            if($date == date('Y-m-d', $now)){
                if(isset($value[1])){
                    $svlm_exc = convertNumber2Us(trim(strip_tags(str_replace('""', '"', $value[1]))), 'vn') * 1;
                }
                if(isset($value[2])){
                    $strn_exc = convertNumber2Us(trim(strip_tags(str_replace('""', '"', $value[2]))), 'vn') * 1000;
                }
                if(isset($value[5])){
                    $svlmtt_exc = convertNumber2Us(trim(strip_tags(str_replace('""', '"', $value[5]))), 'vn') * 1;
                }
                if(isset($value[6])){
                    $strntt_exc = convertNumber2Us(trim(strip_tags(str_replace('""', '"', $value[6]))), 'vn') * 1000;
                }
                $data = array(
                    'market' => $market,
                    'date' => date('Y/m/d', $now),                    
                    'yyyymmdd' => date('Ymd', $now),
                    'yyyymm' => date('Ym', $now),
                    'yyyy' => date('Y', $now),
                    'svlm_exc' => $svlm_exc,
                    'strn_exc' => $strn_exc,
                    'svlmkl_exc' => $svlm_exc,
                    'strnkl_exc' => $strn_exc,
                    'svlmtt_exc' => $svlmtt_exc,
                    'strntt_exc' => $strntt_exc
                );

                $where = array(
                    'market' => $data['market'],
                    'yyyymmdd' => $data['yyyymmdd']
                );
                $this->mdownload->update_exc($table, $data, $where);

                $file = '\\\LOCAL\IFRCVN\VNDB\METASTOCK\STATISTICS\DAY\STATS_UPC_' . $data['yyyymmdd'] . '.txt';
                $headers = array_keys($data);
                $content = implode(chr(9), $headers) . PHP_EOL;
                $content .= implode(chr(9), $data) . PHP_EOL;
                $f = fopen($file, 'w');
                fwrite($f, $content);
                fclose($f);
                
            }

        }
        $this->mdownload->order_table('vndb_stats_daily', array('date'=>'DESC', 'market'=>'ASC'));
        
    }

    public function get_exc_monthly(){
        $this->load->Model('exchange_model', 'mexchange');

        $now = time();
        $path = '\\\LOCAL\IFRCVN\VNDB\METASTOCK\STATISTICS\MONTH\\';
        $array = array(
            array(
                'code_dwl' => 'EXCSTAMHNX',
                'market' => 'HNX',
            ),
            array(
                'code_dwl' => 'EXCSTAMUPC',
                'market' => 'UPC',
            ),
            array(
                'code_dwl' => 'EXCSTAMHSX1',
                'market' => 'HSX1',
            ),
            array(
                'code_dwl' => 'EXCSTAMHSX3',
                'market' => 'HSX3',
            ),
        );

        foreach($array as $item){
            $file = $path . 'STATS_' . $item['market'] . '_' . date('Ym', $now) . '.txt';
            $info = '';
            $info = $this->mdownload->listInfo(array('code_dwl' => $item['code_dwl']));
            $info = $info[0];
            $options = $this->mexchange->getOption($item['code_dwl']);
            $format = $this->mexchange->getMetaFormat($info['output']);
            $market = $item['market'];
            $this->load->library('curl');
            $curl = new curl;
            $post = NULL;
            $url = str_replace('<<_date_yyyymm>>', date('Ym', $now), $info['url']);
            $html = $curl->makeRequest($info['vfpgetpost'], $url, $post);
            $len = '';
            $from = strpos($html, $info['left']);
            if(strpos($html, $info['right'], $from) != ''){
                $len = strpos($html, $info['right'], $from) - ($from + strlen($info['left']));
            }
            if($len == ''){
                $html = substr($html, $from);
            }else{
                $html = substr($html, $from, $len);
            }
            if($info['del_bllef'] == ''){
                $info['del_bllef'] = '<tr';
            }
            $result[0] = explode($info['del_bllef'], $html);
            array_shift($result[0]);
            if(isset($format['per'])){
                unset($format['per']);
            }
            if($item['market'] == 'HSX1'){
                unset($format['svlmkl_exc']);
                unset($format['strnkl_exc']);
                unset($format['svlmtt_exc']);
                unset($format['strntt_exc']);
            }
            if($item['market'] == 'HSX3'){
                unset($format['svlm_exc']);
                unset($format['strn_exc']);
            }

            unset($format['yyyymmdd']);
            $data = convertMetaStock($result[0], $format, $options, $info);
            $headers = array_keys($format);
            $headers = implode(chr(9), $headers) . PHP_EOL;

            array_pop($data);
            foreach($data as $key => $item){
                if($item['market'] == 'HSX'){
                    $delimitor = '-';
                    if(isset($item['svlmtt_exc']) || isset($item['strntt_exc'])){
                        $item['svlmtt_exc'] += $item['svlmkl_exc'];
                        $item['strntt_exc'] += $item['strnkl_exc'];
                    }
                }else{
                    $delimitor = '/';
                }
                $dtemp = explode($delimitor, $item['yyyymm']);
                $item['yyyymm'] = $dtemp[1] . '-' . $dtemp[0];
                // pre($item);
                $date = strtotime($item['yyyymm']);                
                $item['yyyymm'] = date('Ym', $date);
                $item['yyyy'] = date('Y', $date);
                $item['date'] = date('Y-m-d');
                $table = 'vndb_stats_monthly';
                $where = array(
                    'market' => $item['market'],
                    'yyyymm' => $item['yyyymm']
                );
                $this->mdownload->update_exc($table, $item, $where);
                $data[$key] = $item;
            }
            pre($data);
            $content = $headers;
            foreach($data as $item){
                $content .= implode(chr(9), $item) . PHP_EOL;
            }
            $f = fopen($file, 'w');
            fwrite($f, $content);
            fclose($f);
        }
        // $this->mdownload->order_table('vndb_stats_daily', array('date'=>'DESC', 'market'=>'ASC'));
    }

    public function get_exc_yearly(){
        $this->load->Model('exchange_model', 'mexchange');

        $now = time();
        $path = '\\\LOCAL\IFRCVN\VNDB\METASTOCK\STATISTICS\YEAR\\';
        $array = array(
            array(
                'code_dwl' => 'EXCSTAYHNX',
                'market' => 'HNX',
            ),
            array(
                'code_dwl' => 'EXCSTAYUPC',
                'market' => 'UPC',
            ),
        );

        foreach($array as $item){
            $file = $path . 'STATS_' . $item['market'] . '_' . date('Ym', $now) . '.txt';
            $info = '';
            $info = $this->mdownload->listInfo(array('code_dwl' => $item['code_dwl']));
            $info = $info[0];
            $options = $this->mexchange->getOption($item['code_dwl']);
            $format = $this->mexchange->getMetaFormat($info['output']);
            $market = $item['market'];
            $this->load->library('curl');
            $curl = new curl;
            $post = NULL;
            $url = str_replace('<<_date_yyyy>>', date('Y', $now), $info['url']);
            $html = $curl->makeRequest($info['vfpgetpost'], $url, $post);
            $len = '';
            $from = 0;
            if($info['left'] != ''){
                $from = strpos($html, $info['left']);
            }
            if(strpos($html, $info['right'], $from) != ''){
                $len = strpos($html, $info['right'], $from) - ($from + strlen($info['left']));
            }
            
            if($len == ''){
                $html = substr($html, $from);
            }else{
                $html = substr($html, $from, $len);
            }
            if($info['del_bllef'] == ''){
                $info['del_bllef'] = '<tr';
            }
            $result[0] = explode($info['del_bllef'], $html);
            array_shift($result[0]);
            if(isset($format['per'])){
                unset($format['per']);
            }
            unset($format['yyyymmdd']);
            unset($format['yyyymm']);
            $data = convertMetaStock($result[0], $format, $options, $info);
            $headers = array_keys($format);
            $headers = implode(chr(9), $headers) . PHP_EOL;
            array_pop($data);
            foreach($data as $key => $item){                
                $item['date'] = date('Y-m-d');
                $table = 'vndb_stats_yearly';
                $where = array(
                    'market' => $item['market'],
                    'yyyy' => $item['yyyy']
                );
                $this->mdownload->update_exc($table, $item, $where);
                $data[$key] = $item;
            }
            $content = $headers;
            foreach($data as $item){
                $content .= implode(chr(9), $item) . PHP_EOL;
            }
            $f = fopen($file, 'w');
            fwrite($f, $content);
            fclose($f);
        }
        // $this->mdownload->order_table('vndb_stats_daily', array('date'=>'DESC', 'market'=>'ASC'));
    }
	function out_home(){
		redirect(admin_url());
	}
	
	public function upload_day(){
		$this->code_upload_day();
		$this->out_home();
	}
	
	public function upload_month(){
		$this->code_upload_month();
		$this->out_home();
	}
	
	public function upload_year(){
		$this->code_upload_year();
		$this->out_home();
	}
	
	public function upload_all(){
		$this->code_upload_day();
		$this->code_upload_month();
		$this->code_upload_year();
		$this->db->query("TRUNCATE TABLE VNDB_STATS_EXC_ALL");
		
		$col = 'MARKET,PER,DATE,YYYYMMDD,YYYYMM,YYYY,SVLM_EXC,STRN_EXC,SVLMKL_EXC,STRNKL_EXC,SVLMTT_EXC,STRNTT_EXC';
		
		$this->db->query("INSERT INTO VNDB_STATS_EXC_ALL ($col) SELECT $col FROM VNDB_STATS_EXC_DAY");
		$this->db->query("INSERT INTO VNDB_STATS_EXC_ALL ($col) SELECT $col FROM VNDB_STATS_EXC_MONTH");
		$this->db->query("INSERT INTO VNDB_STATS_EXC_ALL ($col) SELECT $col FROM VNDB_STATS_EXC_YEAR");
		$this->out_home();
	}
	
	public function code_upload_day(){
		$this->db->query("TRUNCATE TABLE VNDB_STATS_EXC_DAY");
		$path = '\\\LOCAL\IFRCVN\VNDB\METASTOCK\STATISTICS\MANUAL\\';
		$path_files = glob($path . '*.txt');
		foreach ($path_files as $file) {
			$filename = basename($file, ".txt");
			if($filename == 'VNDB_STATS_EXC_DAY'){
				$base_url = str_replace("\\", "\\\\", $file);
				$this->db->query("LOAD DATA INFILE '".$base_url."' INTO TABLE VNDB_STATS_EXC_DAY FIELDS TERMINATED BY '\t' IGNORE 1 LINES (MARKET,PER,DATE,YYYYMMDD,YYYYMM,YYYY,SVLM_EXC,STRN_EXC,SVLMKL_EXC,STRNKL_EXC,SVLMTT_EXC,STRNTT_EXC)");
			}
		}
	}
	
	public function code_upload_month(){
		$this->db->query("TRUNCATE TABLE VNDB_STATS_EXC_MONTH");
		$path = '\\\LOCAL\IFRCVN\VNDB\METASTOCK\STATISTICS\MANUAL\\';
		$path_files = glob($path . '*.txt');
		foreach ($path_files as $file) {
			$filename = basename($file, ".txt");
			if($filename == 'VNDB_STATS_EXC_MONTH'){
				$base_url = str_replace("\\", "\\\\", $file);
				$this->db->query("LOAD DATA INFILE '".$base_url."' INTO TABLE VNDB_STATS_EXC_MONTH FIELDS TERMINATED BY '\t' IGNORE 1 LINES (MARKET,PER,DATE,YYYYMMDD,YYYYMM,YYYY,SVLM_EXC,STRN_EXC,SVLMKL_EXC,STRNKL_EXC,SVLMTT_EXC,STRNTT_EXC)");
			}
		}
	}
	
	public function code_upload_year(){
		$this->db->query("TRUNCATE TABLE VNDB_STATS_EXC_YEAR");
		$path = '\\\LOCAL\IFRCVN\VNDB\METASTOCK\STATISTICS\MANUAL\\';
		$path_files = glob($path . '*.txt');
		foreach ($path_files as $file) {
			$filename = basename($file, ".txt");
			if($filename == 'VNDB_STATS_EXC_YEAR'){
				$base_url = str_replace("\\", "\\\\", $file);
				$this->db->query("LOAD DATA INFILE '".$base_url."' INTO TABLE VNDB_STATS_EXC_YEAR FIELDS TERMINATED BY '\t' IGNORE 1 LINES (MARKET,PER,DATE,YYYYMMDD,YYYYMM,YYYY,SVLM_EXC,STRN_EXC,SVLMKL_EXC,STRNKL_EXC,SVLMTT_EXC,STRNTT_EXC)");
			}
		}
	}
}