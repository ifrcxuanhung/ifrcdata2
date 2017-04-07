<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class Hnx_ref extends Admin {
    protected $data;
    private $cal_dates;
    protected $_option;

    public function __construct() {
        parent::__construct();
        set_time_limit(0);
        $this->load->library('curl');
        $this->load->library('simple_html_dom');
    }
    public function index(){
	if (!isset($data['err']) || count($data['err']) == 0) {
        $start = 0;  // Download d? li?u t? ngày
		$end = 400;
        $date = date('d/m/Y');
		$content = 'source,ticker,name,market,date,yyyymmdd,ipo,ipo_shli,ipo_shou,ftrd,ftrd_cls,shli,shou,shfn,capi,capi_fora,capi_forn,capi_stat' . PHP_EOL;;
        $content = str_replace(',', chr(9), $content);
		while ($start <= $end) {
			$curl = new curl( );
           $datahtml = $curl->makeRequest('post','http://hnx.vn/en/web/guest/dang-niem-yet',array('iColumns=10','iDisplayLength=100','iDisplayStart=200','iSortCol_0=1','iSortingCols=1','loaick=','mDataProp_0=0','mDataProp_1=1','mDataProp_2=2',
			'nganh=','p_p_cacheability=cacheLevelPage','p_p_col_id=column-1','p_p_id=hsnydkgdjson_WAR_HnxIndexportlet',
			'p_p_lifecycle=2','quymony=','sColumns=','sEcho=5'));
		   $datahtml1 = substr($datahtml, strpos($datahtml, '{'));
			//print_r($datahtml);
			$datahtml = json_decode($datahtml1, 1);
			//print_r($datahtml);
           foreach($datahtml['aaData'] as $item){
			$source='EXC';
			$market='HNX';
			$date1= date('Y/m/d');
			$content .= $source .chr(9);
				$ticker = substr($item[1], strpos($item[1], '>') + 1);
				$ticker = str_replace('</a>', '', $ticker);
				$content .= $ticker . chr(9);
				$name = substr($item[2], strpos($item[2], '>') + 1);
				$name = str_replace('</a>', '', $name);
				$content .= $name . chr(9);
				$content .= $market .chr(9);
				$content .= $date1 .chr(9);
				//$date = explode('/', $date);
				//$date=$date[2] . $date[1] . $date[0];
				$content .= str_replace('/', '', $date1). chr(9);
				$lcdate1 = explode('/', $item[3]);
				$lcdate = $lcdate1[2] .'/'. $lcdate1[1] .'/'. $lcdate1[0];
				$content .=chr(9);
				$content .=chr(9);
				$content .=chr(9);
				$content .= $lcdate . chr(9);
				$content .=chr(9);
				$content .= str_replace(',', '', $item[4]) . chr(9);
				$content .=chr(9);
				$content .=chr(9);
				$content .= str_replace(',', '', $item[6]);
				//$content .= 1000*str_replace('.', '', $item[7]) . chr(9);
				$content .=chr(9);
				$content .=chr(9);
				$content .=chr(9);
				$content .= PHP_EOL;
			}
			//print_r($datahtml);
			//pre($datahtml);die();
			$html = str_get_html($datahtml1);
			$date2 = explode('/', $date);
			$date3 = $date2[2] . $date2[1] . $date2[0];
			$start = $start +100;
    }
	$file = fopen('\\\LOCAL\IFRCVN\VNDB\METASTOCK\REFERENCE\EXC\DAY\REF_HNX_' .$date3. '.txt', 'w');
	fwrite($file, $content);
	 }
	//print_r(date('d/m/Y'));
	$this->data->title = "HNX";
    $this->template->write_view('content', 'hsx_daily/index', $this->data);
    $this->template->write('title', 'HNX');
    $this->template->render();
}
}


