<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/* * ****************************************************************** */
/*     Client  Name  :  IFRC                                      */
/*     Project Name  :  cms v3.0                                     */
/*     Program Name  :  admin.php                                      */
/*     Entry Server  :                                               */
/*     Called By     :  System                                       */
/*     Notice        :  File Code is utf-8                           */
/*     Copyright     :  IFRC                                         */
/* ------------------------------------------------------------------- */
/*     Comment       :  controller admin                             */
/* ------------------------------------------------------------------- */
/*     History       :                                               */
/* ------------------------------------------------------------------- */
/*     Version V001  :  2012.08.14 (LongNguyen)        New Create      */
/* * ****************************************************************** */

class Admin extends MY_Controller {

    protected $data;
    // protected $_host = 'D:\IFRCDATA\\';
    protected $_host = '\\\LOCAL\\';

    function __construct() {
        parent::__construct();
		//echo "<pre>";print_r($this->router->fetch_class());exit; 
		
			if (!$this->ion_auth->logged_in()) {
				$this->session->set_userdata('url_login', current_url());
				$this->session->set_userdata('module_login', 'admin');
				redirect('auth/login', 'refresh');
			} elseif (!$this->ion_auth->is_admin()) {
				//redirect($this->config->item('base_url'), 'refresh');
			}
			$this->load->model('Language_model', 'language_model');
			$where = array('status' => 1);
			$langList = $this->language_model->find(NULL, $where);
			if (is_array($langList) == TRUE && count($langList) > 0) {
				foreach ($langList as $value) {
					$this->data->list_language[$value['code']] = $value;
				}
				$this->data->default_language = $langList[0];
			}
			unset($langList);
			$this->session->set_userdata('default_language', $this->data->default_language);
			if (!$this->session->userdata('curent_language')) {
				$this->session->set_userdata('curent_language', $this->data->default_language);
			}
			
			$this->data->curent_language = $this->session->userdata('curent_language');
			$this->data->setting = $this->registry->setting;
			$this->template->set_template('admin');
			// $this->output->enable_profiler(TRUE);
			$this->write_log();
		
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
    /*    M001 : New  2012.08.14 (LongNguyen)                            */
    /*     * ************************************************************** */

    public function index() {
        $this->zend->load('Zend_Acl');
        $this->template->write_view('content', 'dashboard');
        $this->template->write('title', 'Admin Panel ');
        $this->template->render();
    }

    /*     * ************************************************************** */
    /*    Name ： thumb                                                */
    /* --------------------------------------------------------------- */
    /*    Description  ：  thumb image                                    */
    /*                                                                 */
    /* --------------------------------------------------------------- */
    /*    Params  ：  None                                             */
    /* --------------------------------------------------------------- */
    /*    Return  ：                                                 */
    /* --------------------------------------------------------------- */
    /*    Warning ：                                                  */
    /* --------------------------------------------------------------- */
    /*    Copyright : IFRC                                         */
    /* --------------------------------------------------------------- */
    /*    M001 : New  2012.08.14 (LongNguyen)                            */
    /*     * ************************************************************** */

    public function _thumb(&$image = NULL) {
        $thumb = 'assets/images/no-image.jpg';
        $config = array();
        if ($image == NULL) {
            $image = $thumb;
            return $thumb;
        }
        if (isset($image) == TRUE && $image != '') {
            $image = substr($image, strpos($image, 'assets'));
            $thumb = trim(str_replace('assets/upload', 'assets/upload/thumbs', $image), '/');
            if (is_file(trim(PATH . $thumb)) != TRUE) {
                if (is_file(PATH . $image, '/') == TRUE) {
                    $config['image_library'] = 'gd2';
                    $config['source_image'] = PATH . trim($image, '/');
                    $config['new_image'] = PATH . $thumb;
                    $config['create_thumb'] = TRUE;
                    $config['thumb_marker'] = '';
                    $config['maintain_ratio'] = TRUE;
                    $config['width'] = 100;
                    $config['height'] = 75;
                    $this->load->library('image_lib', $config);
                    if (!$this->image_lib->resize()) {
                        echo $this->image_lib->display_errors();
                    }
                }
            }
        }
        return $thumb;
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
    /*    M001 : New  2012.08.14 (LongNguyen)                            */
    /*     * ************************************************************** */

    public function access_denied() {
        $data->message = $this->session->userdata('access');
        $this->template->write_view('content', 'access_denied', $data);
        $this->template->write('title', 'Admin Panel ');
        $this->template->render();
        return;
    }

    public function write_log() {
        $filename = './assets/log/user_log.txt';

        if (!file_exists($filename)) {
            $content = 'TIME' . chr(9) . 'USERNAME' . chr(9) . 'URL';
            $fp = fopen($filename, "wb");
            fwrite($fp, $content);
            fclose($fp);
        }

        if(isset($this->session->userdata['username'])){

            $user = $this->session->userdata['username'];

        }elseif(isset($this->session->userdata['email'])){

            $user = $this->session->userdata['email'];

        }else{

            $user = $this->session->userdata['user_id'];

        }

        $date_current = date('Y-m-d H:i:s');

        $url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

        $content = PHP_EOL;
        $content .= $date_current . chr(9) . $user . chr(9) . $url;

        $fp = fopen($filename, "a");
        fwrite($fp, $content);
        fclose($fp);
    }

}