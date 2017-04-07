<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/* * ****************************************************************** */
/*     Client  Name  :  IFRC                                      */
/*     Project Name  :  cms v3.0                                     */
/*     Program Name  :  language.php                                      */
/*     Entry Server  :                                               */
/*     Called By     :  System                                       */
/*     Notice        :  File Code is utf-8                           */
/*     Copyright     :  IFRC                                         */
/* ------------------------------------------------------------------- */
/*     Comment       :  controller language                             */
/* ------------------------------------------------------------------- */
/*     History       :                                               */
/* ------------------------------------------------------------------- */
/*     Version V001  :  2012.08.14 (LongNguyen)        New Create      */
/* * ****************************************************************** */

class Language extends Admin {

    protected $data;

    function __construct() {
        parent::__construct();
        $this->load->model('Language_model', 'language_model');
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

    function index() {
        $this->data->title = 'List languages';
        $this->template->write_view('content', 'language/language_list', $this->data);
        $this->template->write('title', 'languages ');
        $this->template->render();
    }

    /*     * ************************************************************** */
    /*    Name ： add                                                */
    /* --------------------------------------------------------------- */
    /*    Description  ： add new language                             */
    /* --------------------------------------------------------------- */
    /*    Params  ：  None                                             */
    /* --------------------------------------------------------------- */
    /*    Return  ：   redirect backend/language when add language success  */
    /* --------------------------------------------------------------- */
    /*    Warning ：                                                     */
    /* --------------------------------------------------------------- */
    /*    Copyright : IFRC                                         */
    /* --------------------------------------------------------------- */
    /*    M001 : New  2012.08.14 (LongNguyen)                             */
    /*     * ************************************************************** */

    function listdata() {
        if ($this->input->is_ajax_request()) {
            $this->data->list_language = $this->language_model->find();
            if ((isset($this->data->list_language) == TRUE) && ($this->data->list_language != '') && (is_array($this->data->list_language) == TRUE)) {
                $aaData = array();
                foreach ($this->data->list_language as $key => $value) {
                    $aaData[$key][] = $value['language_id'];
                    $aaData[$key][] = $value['name'];
                    $aaData[$key][] = $value['code'];
                    $aaData[$key][] = $value['sort_order'];
                    if ($value['status'] == '1') {
                        $aaData[$key][] = '<a style="color: green;" class="language-active" language_id="' . $value['language_id'] . '" href="javascript: void(0);">Enable</a>';
                    } else {
                        $aaData[$key][] = '<a style="color: red;" class="language-active" language_id="' . $value['language_id'] . '" href="javascript: void(0);">Disable</a>';
                    }
                    $aaData[$key][] = '<ul class="keywords" style="text-align:center;"><li class="green-keyword"><a title="" class="with-tip" href="' . admin_url() . 'language/edit/' . $value['language_id'] . '">' . trans('bt_edit', 1) . '</a></li>
                                   <li class="red_fx_keyword"><a title="" class="with-tip action-delete " language_id="' . $value['language_id'] . '" href="javascript: void(0);">' . trans('bt_delete', 1) . '</a></li></ul>';
                }
                $output = array(
                    "aaData" => $aaData
                );
                $this->output->set_output(json_encode($output));
            }
        }
    }

    function add() {
        $this->load->helper('form');
        $this->data->input = $this->input->post();
        $this->data->title = 'Language - Add new';
        $this->load->library('form_validation');
        $this->form_validation->set_rules('name', 'name', 'trim|required');
        $this->form_validation->set_rules('code', 'code', 'trim|required');
        $this->form_validation->set_rules('sort_order', 'sort order', 'trim|required|numeric');
        $this->form_validation->set_rules('status', 'status', 'trim|required|numeric');
        if ($this->form_validation->run() == FALSE) :
            $this->data->error = validation_errors();
        else:
            if ($this->language_model->add($this->input->post()) == 1):
                redirect(admin_url() . 'language');
            else:
                $this->data->error = 'insert error';
            endif;
        endif;
        $this->template->write_view('content', 'language/language_form', $this->data);
        $this->template->write('title', trans('Add', 1));
        $this->template->render();
    }

    function active($langCode) {
        if (isset($this->data->list_language[$langCode]) == TRUE) {
            $this->session->set_userdata('curent_language', $this->data->list_language[$langCode]);
            $this->output->set_output(json_encode(array('result' => 1)));
        }
    }

    /*     * ************************************************************** */
    /*    Name ： edit                                                */
    /* --------------------------------------------------------------- */
    /*    Description  ： edit 1 language                             */
    /* --------------------------------------------------------------- */
    /*    Params  ：  $id category_id                                             */
    /* --------------------------------------------------------------- */
    /*    Return  ：   redirect backend/language when edit language success  */
    /* --------------------------------------------------------------- */
    /*    Warning ：                                                     */
    /* --------------------------------------------------------------- */
    /*    Copyright : IFRC                                         */
    /* --------------------------------------------------------------- */
    /*    M001 : New  2012.08.14 (LongNguyen)                             */
    /*     * ************************************************************** */

    function edit($id) {
        $this->load->helper('form');
        $this->data->id = $id;
        $this->data->input = $this->language_model->find($id);
        count($this->data->input) == 0 ? redirect(admin_url() . 'language') : $this->data->input = $this->data->input[0];
        $this->data->title = 'Language - Edit';
        $this->load->library('form_validation');
        $this->form_validation->set_rules('name', 'name', 'trim|required');
        $this->form_validation->set_rules('code', 'code', 'trim|required');
        $this->form_validation->set_rules('sort_order', 'sort order', 'trim|required|numeric');
        $this->form_validation->set_rules('status', 'status', 'trim|required|numeric');
        if ($this->form_validation->run() == FALSE) :
            $this->data->error = validation_errors();
        else :
            if (self::_code_check($this->input->post('code')) != FALSE) :
                if ($this->language_model->update($this->input->post(), $id) == 1):
                    redirect(admin_url() . 'language');
                else:
                    $this->data->error = 'update error';
                endif;
            endif;
        endif;
        $this->template->write_view('content', 'language/language_form', $this->data);
        $this->template->write('title', trans('Edit', 1));
        $this->template->render();
    }

    /**     * ************************************************************* */
    /*    Name ： delete                                                */
    /* --------------------------------------------------------------- */
    /*    Description  ： delete 1 language   call by ajax               */
    /* --------------------------------------------------------------- */
    /*    Params  ：  $_POST id                                             */
    /* --------------------------------------------------------------- */
    /*    Return  ：   return 0 when delete false return 1 when delete success  */
    /* --------------------------------------------------------------- */
    /*    Warning ：                                                     */
    /* --------------------------------------------------------------- */
    /*    Copyright : IFRC                                         */
    /* --------------------------------------------------------------- */
    /*    M001 : New  2012.08.14 (LongNguyen)                             */
    /*     * ************************************************************** */

    function delete() {
        $this->output->enable_profiler(FALSE);
        if ($this->input->is_ajax_request()) {
            echo $this->language_model->delete($this->input->post('id'));
        }
    }

    function chang_active() {
        $this->output->enable_profiler(FALSE);
        if ($this->input->is_ajax_request()) {
            echo $this->language_model->change_active($this->input->post('id'), $this->input->post('text'));
        }
    }

    /**     * ************************************************************* */
    /*    Name ： _code_check                                                */
    /* --------------------------------------------------------------- */
    /*    Description  ： check language if exists               */
    /* --------------------------------------------------------------- */
    /*    Params  ：  $_POST id                                             */
    /* --------------------------------------------------------------- */
    /*    Return  ：  true: not exist   false: exist and set error message */
    /* --------------------------------------------------------------- */
    /*    Warning ：                                                     */
    /* --------------------------------------------------------------- */
    /*    Copyright : IFRC                                         */
    /* --------------------------------------------------------------- */
    /*    M001 : New  2012.08.14 (LongNguyen)                             */
    /*     * ************************************************************** */

    private function _code_check($code) {
        if ($this->language_model->check_code($code, $this->data->id)) {
            return true;
        } else {
            $this->data->error = 'code already exists';
            return false;
        }
    }

}