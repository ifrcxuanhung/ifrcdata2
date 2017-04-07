<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/* * ****************************************************************** */
/*     Client  Name  :  IFRC                                      */
/*     Project Name  :  cms v3.0                                     */
/*     Program Name  :  xls.php                                      */
/*     Entry Server  :                                               */
/*     Called By     :  System                                       */
/*     Notice        :  File Code is utf-8                           */
/*     Copyright     :  IFRC                                         */
/* ------------------------------------------------------------------- */
/*     Comment       :  controller xls                            */
/* ------------------------------------------------------------------- */
/*     History       :                                               */
/* ------------------------------------------------------------------- */
/*     Version V001  :  2012.08.14 (Tung)        New Create      */
/* * ****************************************************************** */

class Xls extends Admin {

    protected $data;

    function __construct() {
        parent::__construct();
        $this->load->helper(array('my_array_helper', 'form'));
    }

    /*     * ************************************************************** */
    /*    Name ： index                                                */
    /* --------------------------------------------------------------- */
    /*    Description  ：  this function will be called automatically  */
    /*                   when the controller is called               */
    /* --------------------------------------------------------------- */
    /*    Params  ：  None                                             */
    /* --------------------------------------------------------------- */
    /*    Return  ：                                                 */
    /* --------------------------------------------------------------- */
    /*    Warning ：                                                  */
    /* --------------------------------------------------------------- */
    /*    Copyright : IFRC                                         */
    /* --------------------------------------------------------------- */
    /*    M001 : New  2012.08.14 (Tung)                            */
    /*     * ************************************************************** */

    function index() {
        $this->load->Model('exchange_model', 'mexchange');
        $this->load->library('curl');
        $curl = new curl();
        $code_info = $this->mexchange->getCodeInfoByCodeDwl('VSTSTATHM');
        $tickers = $this->mexchange->getTicker();
        foreach($tickers as $ticker){
            $ticker = $ticker['code'];
            $name = 'VST_HISTORY_' . $ticker . '.txt';
            $file = '\\\LOCAL\IFRCDATA\DOWNLOADS\VNDB\METASTOCK\SHARES\VST\\' . $name;
            if(!is_file($file)){
                $options = $this->mexchange->getOption();
                $format = $this->mexchange->getMetaFormat($code_info['output']);
                $path = str_replace('file:/', '', $code_info['url']);
                $path = str_replace('<<TICKER>>', strtoupper($ticker), $path);
                $path = str_replace('<<ticker>>', strtolower($ticker), $path);
               // echo $path . '<br />';
                if(is_file($path)){
                    $html = file_get_contents($path);
                    $html = substr($html, strpos($html, '<table', 1));
                    $rule = "/\<tr.*\>(.*)\<\/tr\>/msU";
                    preg_match_all($rule, $html, $result);
                    array_shift($result[0]);

                    $headers = array_keys($format);
                    $contents = strtoupper(implode(chr(9) ,$headers)) . PHP_EOL;
                    if(!empty($result[0])){
                        $data = convertMetaStock($result[0], $format, $options, $code_info);
                        foreach($data as $key => $item){
                            $data[$key]['ticker'] = $ticker;
                        }
                        foreach($data as $key => $item){
                            // $item['date'] = explode('/', $item['date']);
                            // $item['date'] = $item['date'][2] . '/' . $item['date'][1] . '/' . $item['date'][0];
                            // $data[$key]['date'] = $item['date'];
                            $data[$key]['yyyymmdd'] = str_replace('/', '', $item['date']);
                        }
                        foreach($data as $item){
                            $values = '';
                            foreach($item as $key => $value){
                                if($key == 'source'){
                                    $values[] = 'VSTX';
                                }else{
                                    $values[] = $value;
                                }        
                            }
                            $contents .= implode(chr(9), $values);
                            $contents .= PHP_EOL;
                        }
                    }
                    $file = fopen('\\\LOCAL\IFRCDATA\DOWNLOADS\VNDB\METASTOCK\SHARES\VST\\' . $name, 'w');
                    fwrite($file, $contents);
                    //pre($data);
                }
            }
        }
        $this->data->title = "HNX";
        $this->template->write_view('content', 'hnx/hnx_index', $this->data);
        $this->template->write('title', 'HNX');
        $this->template->render();
    }

}