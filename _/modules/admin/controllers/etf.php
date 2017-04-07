<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/* * ******************************************************************************************************************* *
 *   Author: Minh Đẹp Trai                                                                                               *
 * * ******************************************************************************************************************* */

class Etf extends Admin {

    public function __construct() {
        parent::__construct();
        $this->load->model('Etf_model', 'etf_model');
    }
    
    public function download_etf_country(){
        $this->template->write_view('content', 'etf/download_etf_country', $this->data);
        $this->template->write('title', 'Download ETF Country');
        $this->template->render(); 
    }
    
    public function download_etf_screener(){
        $this->template->write_view('content', 'etf/download_etf_screener', $this->data);
        $this->template->write('title', 'Download ETF Screener');
        $this->template->render(); 
    }
    
    public function process_download_etf_country(){
        if ($this->input->is_ajax_request()) {
            $from = microtime(true);
            ini_set('max_execution_time', 6000);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
            // you wanna follow stuff like meta and location headers
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            // you want all the data back to test it for errors
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            // probably unecessary, but cookies may be needed to
            curl_setopt($ch, CURLOPT_ENCODING, 'gzip,deflate');

            $url = "http://etfdb.com/tool/etf-country-exposure-tool/";
            curl_setopt($ch, CURLOPT_URL, $url);

            $html = curl_exec($ch);
            $start = '<div class = "types_inner';
            $end = '</div>';
            $start1 = preg_quote($start, '/t');
            $end1 = preg_quote($end, '/t');
            $rule = "/(?<=$start1).*(?=$end1)/msU";
            $result = array();
            preg_match_all($rule, $html, $result);
            $header = 'COUNTRY'."\t".'TICKER'."\t".'LINK'."\t".'ETF'."\t".'ETF_LINK'."\t".'CATEGORY'."\t".'EXP_RATIO'."\t".'WGT'."\t".'ISSUER'."\t".'ISSUER_LNK'."\t".'INCEPTION'."\t".'DESCRIPTN'."\t".'STRUCTURE'."\t".'CLASS'."\t".'SIZE'."\t".'STYLE'."\t".'REGION_GEN'."\t".'REGION_SPE'."\t".'CURRENCY'."\t".'PRICE'."\t".'UPDATE'."\t".'AUM';
            $file = $header . "\r\n";
            $filename = "//LOCAL/INDEXIFRC/IMS/IMSTXT/ETFDB/ETFCOUNTRY.txt";
            $create = fopen($filename, "w");
            $write = fwrite($create, $file);
            fclose($create);
            foreach($result[0] as $rs){
                $item = explode("<li>",$rs);
                array_shift($item);
                foreach($item as $it){
                    $country = strip_tags($it);
                    $data_country = explode(" ",$country);
                    $total_full = end($data_country);
                    $total_split = str_replace('(','',$total_full);
                    $total = str_replace(')','',$total_split);
                    array_pop($data_country);
                    $country = trim(implode(" ",$data_country));
                    $country = str_replace(',', '', $country);
                    $match = array();
                    preg_match('/"(.+)"/', $it, $match);
                    $link = parse_url($match[1]);
                    if($total > 25){
                        $total_page = ceil($total/25);
                        for($k=1;$k<=$total_page;$k++){
                            $url_contry = 'http://etfdb.com'.$link['path'].'/page/'.$k;
                            curl_setopt($ch, CURLOPT_URL, $url_contry);
                            $html_contry = curl_exec($ch);
                            $start = '<tr>';
                            $end = '</tr>';
                            $start1 = preg_quote($start, '/t');
                            $end1 = preg_quote($end, '/t');
                            $rule = "/(?<=$start1).*(?=$end1)/msU";
                            $result_contry = array();
                            preg_match_all($rule, $html_contry, $result_contry);
                            unset($result_contry[0][0]);
                            $data_content = array();
                            foreach($result_contry[0] as $rs_c){
                                $item2 = explode("</td>",$rs_c);
                                array_pop($item2);
                                $ticker = trim(strip_tags($item2[0]));
                                $category = trim(strip_tags($item2[2]));
                                $exprat = trim(strip_tags($item2[3]));
                                $etf = trim(strip_tags($item2[1]));
                                $wgt = trim(strip_tags($item2[4]));
                                $match2 = array();
                                preg_match('/"(.+)"/', $item2[0], $match2);
                                $link2 = parse_url($match2[1]);
                                $url_ticker = 'http://etfdb.com'.$link2['path'];
                                curl_setopt($ch, CURLOPT_URL, $url_ticker);
                                $html_ticker = curl_exec($ch);
                                $start_b1 = '<div class = "oneOfFourColumns">';
                                $end_b1 = '</ul>';
                                $start1_b1 = preg_quote($start_b1, '/t');
                                $end1_b1 = preg_quote($end_b1, '/t');
                                $rule_b1 = "/(?<=$start1_b1).*(?=$end1_b1)/msU";
                                $result_b1 = array();
                                preg_match_all($rule_b1, $html_ticker, $result_b1);
                                $data_1 = explode('<li>',$result_b1[0][0]);
                                array_shift($data_1);
                                $data_1_1 = array();
                                foreach($data_1 as $dt1){
                                    $left = preg_quote('<strong>', '/t');
                                    $right = preg_quote('</strong>', '/t');
                                    $rule_dt1 = "/(?<=$left).*(?=$right)/msU";
                                    $dt1 = preg_replace($rule_dt1,'',$dt1);
                                    $dt1 = str_replace('<strong></strong>','',$dt1);
                                    $dt1 = trim(str_replace('</li>','',$dt1));
                                    $data_1_1[] = $dt1;
                                }
                                $issuer = strip_tags($data_1_1[0]);
                                $match3 = array();
                                preg_match('/"(.+)"/', $data_1_1[0], $match3);
                                if(isset($match3[1])){
                                    $link_issuer = $match3[1];
                                }else{
                                    $link_issuer = $url_ticker;
                                }
                                $inception = $data_1_1[5];
                                $data_inception = explode("<span>",$inception);
                                $year = str_replace('</span>','',$data_inception[1]);
                                $month = str_replace('</span>','',$data_inception[3]);
                                $day = str_replace('</span>','',$data_inception[5]);
                                $inception = $year.'/'.$month.'/'.$day;
                                $start_b2 = '<div class = "threeOfFourColumns" style = "float: right;">';
                                $end_b2 = '</div>';
                                $start1_b2 = preg_quote($start_b2, '/t');
                                $end1_b2 = preg_quote($end_b2, '/t');
                                $rule_b2 = "/(?<=$start1_b2).*(?=$end1_b2)/msU";
                                $result_b2 = array();
                                preg_match_all($rule_b2, $html_ticker, $result_b2);
                                $data_2 = explode("\n",$result_b2[0][0]);
                                if(isset($data_2[4])){
                                    $data_des = $data_2[4];
                                    $left = preg_quote('<strong>', '/t');
                                    $right = preg_quote('</strong>', '/t');
                                    $rule_dt1 = "/(?<=$left).*(?=$right)/msU";
                                    $data_des = preg_replace($rule_dt1,'',$data_des);
                                    $data_des = str_replace('<strong></strong>','',$data_des);
                                    $data_des = trim(str_replace('<br />','',$data_des));
                                }else{
                                    $data_des = $data_2[2];
                                    $left = preg_quote('<strong>', '/t');
                                    $right = preg_quote('</strong>', '/t');
                                    $rule_dt1 = "/(?<=$left).*(?=$right)/msU";
                                    $data_des = preg_replace($rule_dt1,'',$data_des);
                                    $data_des = str_replace('<strong></strong>','',$data_des);
                                    $data_des = trim(str_replace('<br />','',$data_des));
                                }
                                $start_b3 = '<tr class="rnnRowEven">';
                                $end_b3 = '</tr>';
                                $start1_b3 = preg_quote($start_b3, '/t');
                                $end1_b3 = preg_quote($end_b3, '/t');
                                $rule_b3 = "/(?<=$start1_b3).*(?=$end1_b3)/msU";
                                $result_b3 = array();
                                preg_match_all($rule_b3, $html_ticker, $result_b3);
                                $data_3 = explode("\n",$result_b3[0][0]);
                                $data_aum = trim(str_replace('M','',strip_tags($data_3[2])));
                                $currency = substr($data_aum,0,1);
                                $aum = substr($data_aum,1)*1000000;
                                $data_content[] = $country."\t".$ticker."\t".$url_ticker."\t".$etf."\t".''."\t".$category."\t".$exprat."\t".$wgt."\t".$issuer."\t".$link_issuer."\t".$inception."\t".$data_des."\t".''."\t".''."\t".''."\t".''."\t".''."\t".''."\t".$currency."\t".''."\t".''."\t".$aum;
                            }
                            $content = implode("\n",$data_content);
                            $content .= "\n";
                            $create = fopen($filename, "a");
                            $write = fwrite($create, $content);
                            fclose($create);
                        }
                    }else{
                        $url_contry = 'http://etfdb.com'.$link['path'];
                        curl_setopt($ch, CURLOPT_URL, $url_contry);
                        $html_contry = curl_exec($ch);
                        $start = '<tr>';
                        $end = '</tr>';
                        $start1 = preg_quote($start, '/t');
                        $end1 = preg_quote($end, '/t');
                        $rule = "/(?<=$start1).*(?=$end1)/msU";
                        $result_contry = array();
                        preg_match_all($rule, $html_contry, $result_contry);
                        unset($result_contry[0][0]);
                        $data_content = array();
                        foreach($result_contry[0] as $rs_c){
                            $item2 = explode("</td>",$rs_c);
                            array_pop($item2);
                            $ticker = trim(strip_tags($item2[0]));
                            $category = trim(strip_tags($item2[2]));
                            $exprat = trim(strip_tags($item2[3]));
                            $etf = trim(strip_tags($item2[1]));
                            $wgt = trim(strip_tags($item2[4]));
                            $match2 = array();
                            preg_match('/"(.+)"/', $item2[0], $match2);
                            $link2 = parse_url($match2[1]);
                            $url_ticker = 'http://etfdb.com'.$link2['path'];
                            curl_setopt($ch, CURLOPT_URL, $url_ticker);
                            $html_ticker = curl_exec($ch);
                            $start_b1 = '<div class = "oneOfFourColumns">';
                            $end_b1 = '</ul>';
                            $start1_b1 = preg_quote($start_b1, '/t');
                            $end1_b1 = preg_quote($end_b1, '/t');
                            $rule_b1 = "/(?<=$start1_b1).*(?=$end1_b1)/msU";
                            $result_b1 = array();
                            preg_match_all($rule_b1, $html_ticker, $result_b1);
                            $data_1 = explode('<li>',$result_b1[0][0]);
                            array_shift($data_1);
                            $data_1_1 = array();
                            foreach($data_1 as $dt1){
                                $left = preg_quote('<strong>', '/t');
                                $right = preg_quote('</strong>', '/t');
                                $rule_dt1 = "/(?<=$left).*(?=$right)/msU";
                                $dt1 = preg_replace($rule_dt1,'',$dt1);
                                $dt1 = str_replace('<strong></strong>','',$dt1);
                                $dt1 = trim(str_replace('</li>','',$dt1));
                                $data_1_1[] = $dt1;
                            }
                            $issuer = strip_tags($data_1_1[0]);
                            $match3 = array();
                            preg_match('/"(.+)"/', $data_1_1[4], $match3);
                            $link_issuer = $match3[1];
                            $inception = $data_1_1[5];
                            $data_inception = explode("<span>",$inception);
                            $year = str_replace('</span>','',$data_inception[1]);
                            $month = str_replace('</span>','',$data_inception[3]);
                            $day = str_replace('</span>','',$data_inception[5]);
                            $inception = $year.'/'.$month.'/'.$day;
                            $start_b2 = '<div class = "threeOfFourColumns" style = "float: right;">';
                            $end_b2 = '</div>';
                            $start1_b2 = preg_quote($start_b2, '/t');
                            $end1_b2 = preg_quote($end_b2, '/t');
                            $rule_b2 = "/(?<=$start1_b2).*(?=$end1_b2)/msU";
                            $result_b2 = array();
                            preg_match_all($rule_b2, $html_ticker, $result_b2);
                            $data_2 = explode("\n",$result_b2[0][0]);
                            if(isset($data_2[4])){
                                $data_des = $data_2[4];
                                $left = preg_quote('<strong>', '/t');
                                $right = preg_quote('</strong>', '/t');
                                $rule_dt1 = "/(?<=$left).*(?=$right)/msU";
                                $data_des = preg_replace($rule_dt1,'',$data_des);
                                $data_des = str_replace('<strong></strong>','',$data_des);
                                $data_des = trim(str_replace('<br />','',$data_des));
                            }else{
                                $data_des = $data_2[2];
                                $left = preg_quote('<strong>', '/t');
                                $right = preg_quote('</strong>', '/t');
                                $rule_dt1 = "/(?<=$left).*(?=$right)/msU";
                                $data_des = preg_replace($rule_dt1,'',$data_des);
                                $data_des = str_replace('<strong></strong>','',$data_des);
                                $data_des = trim(str_replace('<br />','',$data_des));
                            }
                            $start_b3 = '<tr class="rnnRowEven">';
                            $end_b3 = '</tr>';
                            $start1_b3 = preg_quote($start_b3, '/t');
                            $end1_b3 = preg_quote($end_b3, '/t');
                            $rule_b3 = "/(?<=$start1_b3).*(?=$end1_b3)/msU";
                            $result_b3 = array();
                            preg_match_all($rule_b3, $html_ticker, $result_b3);
                            $data_3 = explode("\n",$result_b3[0][0]);
                            $data_aum = trim(str_replace('M','',strip_tags($data_3[2])));
                            $currency = substr($data_aum,0,1);
                            $aum = substr($data_aum,1)*1000000;
                            $data_content[] = $country."\t".$ticker."\t".$url_ticker."\t".$etf."\t".''."\t".$category."\t".$exprat."\t".$wgt."\t".$issuer."\t".$link_issuer."\t".$inception."\t".$data_des."\t".''."\t".''."\t".''."\t".''."\t".''."\t".''."\t".$currency."\t".''."\t".''."\t".$aum;
                        }
                        $content = implode("\n",$data_content);
                        $content .= "\n";
                        $create = fopen($filename, "a");
                        $write = fwrite($create, $content);
                        fclose($create);
                    }
                }
            }
            $this->etf_model->load_data_country($filename);
            $total = microtime(true) - $from;
            $result[0]['time'] = round($total, 2);
            $result[0]['task'] = 'Download ETF Country';
            echo json_encode($result);
         } 
    }
    
    public function process_download_etf_screener(){
        if ($this->input->is_ajax_request()) {
            $from = microtime(true);
            ini_set('max_execution_time', 6000);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
            // you wanna follow stuff like meta and location headers
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            // you want all the data back to test it for errors
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            // probably unecessary, but cookies may be needed to
            curl_setopt($ch, CURLOPT_ENCODING, 'gzip,deflate');

            $url = "http://etfdb.com/screener/";
            curl_setopt($ch, CURLOPT_URL, $url);

            $html = curl_exec($ch);
            $start = '<tr class=\'etf\'';
            $end = '</tr>';
            $start1 = preg_quote($start, '/t');
            $end1 = preg_quote($end, '/t');
            $rule = "/(?<=$start1).*(?=$end1)/msU";
            $result = array();
            preg_match_all($rule, $html, $result);
            $header = 'COUNTRY'."\t".'TICKER'."\t".'LINK'."\t".'ETF'."\t".'ETF_LINK'."\t".'CATEGORY'."\t".'EXP_RATIO'."\t".'WGT'."\t".'ISSUER'."\t".'ISSUER_LNK'."\t".'INCEPTION'."\t".'DESCRIPTN'."\t".'STRUCTURE'."\t".'CLASS'."\t".'SIZE'."\t".'STYLE'."\t".'REGION_GEN'."\t".'REGION_SPE'."\t".'CURRENCY'."\t".'PRICE'."\t".'UPDATE'."\t".'AUM';
            $file = $header . "\r\n";
            $filename = "//LOCAL/INDEXIFRC/IMS/IMSTXT/ETFDB/ETFSCREENER.txt";
            $create = fopen($filename, "w");
            $write = fwrite($create, $file);
            fclose($create);
            foreach($result[0] as $rs){
                $item = explode("</td>",$rs);
                array_pop($item);
                $data_price = strip_tags($item[2]);
                $currency = substr($data_price,0,1);
                $price = substr($data_price,1);
                $etf = strip_tags($item[1]);
                $match_ticker = array();
                preg_match('/<a href=\'([^\"]*)\'>(.*)<\/a>/', $item[0], $match_ticker);
                $url_ticker = 'http://etfdb.com'.$match_ticker[1];
                $ticker = $match_ticker[2];
                curl_setopt($ch, CURLOPT_URL, $url_ticker);
                $html_ticker = curl_exec($ch);
                $start_b1 = '<div class = "oneOfFourColumns">';
                $end_b1 = '</ul>';
                $start1_b1 = preg_quote($start_b1, '/t');
                $end1_b1 = preg_quote($end_b1, '/t');
                $rule_b1 = "/(?<=$start1_b1).*(?=$end1_b1)/msU";
                $result_b1 = array();
                preg_match_all($rule_b1, $html_ticker, $result_b1);
                $data_1 = explode('<li>',$result_b1[0][0]);
                array_shift($data_1);
                $data_1_1 = array();
                foreach($data_1 as $dt1){
                    if(strpos($dt1, "strong")){
                        $left = preg_quote('<strong>', '/t');
                        $right = preg_quote('</strong>', '/t');
                        $rule_dt1 = "/(?<=$left).*(?=$right)/msU";
                        preg_match("/$left(.+)$right/", $dt1, $match_dt1);
                        $dt1 = preg_replace($rule_dt1,'',$dt1);
                        $dt1 = str_replace('<strong></strong>','',$dt1);
                        $dt1 = trim(str_replace('</li>','',$dt1));
                        if(strpos($match_dt1[1],'ssuer')){
                            $match_issuer = array();
                            preg_match('/"(.+)"/', $dt1, $match_issuer);
                            $data_link_issuer = parse_url($match_issuer[1]);
                            $link_issuer = 'http://etfdb.com'.$data_link_issuer['path'];
                            $data_1_1['issuer'] = strip_tags($dt1);
                            $data_1_1['link_issuer'] = $link_issuer;
                        }
                        if(strpos($match_dt1[1],'tructure')){
                            $data_1_1['structure'] = $dt1;
                        }
                        if(strpos($match_dt1[1],'Ratio')){
                            $data_1_1['ratio'] = $dt1;
                        }
                        if(strpos($match_dt1[1],'Category')){
                            $data_1_1['category'] = strip_tags($dt1);
                        }
                        if(strpos($match_dt1[1],'nception')){
                            $data_date_inception = explode("<span>",$dt1);
                            $year = str_replace('</span>','',$data_date_inception[1]);
                            $month = str_replace('</span>','',$data_date_inception[3]);
                            $day = str_replace('</span>','',$data_date_inception[5]);
                            $data_inception = $year.'/'.$month.'/'.$day;
                            $data_1_1['inception'] = $data_inception;
                        }
                    }
                }
                if(isset($data_1_1['issuer'])){
                    $issuer = $data_1_1['issuer'];
                }else{
                    $issuer = '';
                }
                if(isset($data_1_1['link_issuer'])){
                    $link_issuer = $data_1_1['link_issuer'];
                }else{
                    $link_issuer = '';
                }
                if(isset($data_1_1['structure'])){
                    $structure = $data_1_1['structure'];
                }else{
                    $structure = '';
                }
                if(isset($data_1_1['ratio'])){
                    $exp_ratio = $data_1_1['ratio'];
                }else{
                    $exp_ratio = '';
                }
                if(isset($data_1_1['category'])){
                    $category = $data_1_1['category'];
                }else{
                    $category = '';
                }
                if(isset($data_1_1['inception'])){
                    $inception = $data_1_1['inception'];
                }else{
                    $inception = '';
                }
                $start_b2 = '<div class = "threeOfFourColumns" style = "float: right;">';
                $end_b2 = '</div>';
                $start1_b2 = preg_quote($start_b2, '/t');
                $end1_b2 = preg_quote($end_b2, '/t');
                $rule_b2 = "/(?<=$start1_b2).*(?=$end1_b2)/msU";
                $result_b2 = array();
                preg_match_all($rule_b2, $html_ticker, $result_b2);
                $data_2 = explode("\n",$result_b2[0][0]);
                $data_2_2 = array();
                foreach($data_2 as $dt2){
                    if(strpos($dt2, "<strong>")){
                        $left = preg_quote('<strong>','/t');
                        $right = preg_quote('</strong>','/t');
                        $rule_dt2 = "/(?<=$left).*(?=$right)/msU";
                        $match_dt2 = array();
                        preg_match("/$left(.+)$right/", $dt2, $match_dt2);
                        $dt2 = preg_replace($rule_dt2,'',$dt2);
                        $dt2 = str_replace('<strong></strong>','',$dt2);
                        $dt2 = trim(str_replace('<br />','',$dt2));
                        if(strpos($match_dt2[1],'Tracks This Index')){
                            $match_etf = array();
                            preg_match('/"(.+)"/', $dt2, $match_etf);
                            $link_etf = $match_etf[1];
                            $data_2_2['link_etf'] = $link_etf;
                        }
                        if(strpos($match_dt2[1],'scription')){
                            $data_2_2['description'] = $dt2;
                        }
                    }
                }
                if(isset($data_2_2['link_etf'])){
                    $link_etf = $data_2_2['link_etf'];
                }else{
                    $link_etf = '';
                }
                if(isset($data_2_2['description'])){
                    $description = $data_2_2['description'];
                }else{
                    $description = '';
                }
                $start_b3 = '<td class="span1b">';
                $end_b3 = '</td>';
                $start1_b3 = preg_quote($start_b3, '/t');
                $end1_b3 = preg_quote($end_b3, '/t');
                $rule_b3 = "/(?<=$start1_b3).*(?=$end1_b3)/msU";
                $result_b3 = array();
                preg_match_all($rule_b3, $html_ticker, $result_b3);
                $data_3 = explode("</li>",$result_b3[0][0]);
                $data_3_3 = array();
                foreach($data_3 as $dt3){
                    if(strpos($dt3, "<strong>")){
                        $left = preg_quote('<strong>','/t');
                        $right = preg_quote('</strong>','/t');
                        $match_dt3 = array();
                        preg_match("/$left(.+)$right/", $dt3, $match_dt3);
                        $rule_dt3 = "/(?<=$left).*(?=$right)/msU";
                        $dt3 = preg_replace($rule_dt3,'',$dt3);
                        $dt3 = str_replace('<strong></strong>','',$dt3);
                        $dt3 = trim(strip_tags($dt3));
                        if(strpos($match_dt3[1],'Class:')){
                            $data_3_3['class'] = $dt3;
                        }
                        if(strpos($match_dt3[1],'Size:')){
                            $data_3_3['size'] = $dt3;
                        }
                        if(strpos($match_dt3[1],'Style:')){
                            $data_3_3['style'] = $dt3;
                        }
                        if(strpos($match_dt3[1],'n (General')){
                            $data_3_3['general'] = $dt3;
                        }
                        if(strpos($match_dt3[1],'n (Specific')){
                            $data_3_3['specific'] = str_replace(array("\t","\n"),'',$dt3);
                        }
                    }
                }
                if(isset($data_3_3['class'])){
                    $class = $data_3_3['class'];
                }else{
                    $class = '';
                }
                if(isset($data_3_3['size'])){
                    $size = $data_3_3['size'];
                }else{
                    $size = '';
                }
                if(isset($data_3_3['style'])){
                    $style = $data_3_3['style'];
                }else{
                    $style = '';
                }
                if(isset($data_3_3['general'])){
                    $gen = $data_3_3['general'];
                }else{
                    $gen = '';
                }
                if(isset($data_3_3['specific'])){
                    $spe = $data_3_3['specific'];
                }else{
                    $spe = '';
                }
                $start_b4 = '<div itemscope itemtype="http://schema.org/WebPage">';
                $end_b4 = '</div>';
                $start1_b4 = preg_quote($start_b4, '/t');
                $end1_b4 = preg_quote($end_b4, '/t');
                $rule_b4 = "/(?<=$start1_b4).*(?=$end1_b4)/msU";
                $result_b4 = array();
                preg_match_all($rule_b4, $html_ticker, $result_b4);
                $data_update = explode("\n",$result_b4[0][0]);
                $update = str_replace('</time>', '', $data_update[3]);
                $update = str_replace('-', '/', $update);
                $update = trim($update);
                $content = $header = ''."\t".$ticker."\t".$url_ticker."\t".$etf."\t".$link_etf."\t".$category."\t".$exp_ratio."\t".''."\t".$issuer."\t".$link_issuer."\t".$inception."\t".$description."\t".$structure."\t".$class."\t".$size."\t".$style."\t".$gen."\t".$spe."\t".$currency."\t".$price."\t".$update."\t".'';
                $content .= "\r\n";
                $create = fopen($filename, "a");
                $write = fwrite($create, $content);
                fclose($create);
            }
            $this->etf_model->load_data_screener($filename);
            $total = microtime(true) - $from;
            $result[0]['time'] = round($total, 2);
            $result[0]['task'] = 'Download ETF Screener';
            echo json_encode($result);
         } 
    }
}
