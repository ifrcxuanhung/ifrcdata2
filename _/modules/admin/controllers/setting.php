<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/* * ****************************************************************** */
/*     Client  Name  :  IFRC                                      */
/*     Project Name  :  cms v3.0                                     */
/*     Program Name  :  setting.php                                      */
/*     Entry Server  :                                               */
/*     Called By     :  System                                       */
/*     Notice        :  File Code is utf-8                           */
/*     Copyright     :  IFRC                                         */
/* ------------------------------------------------------------------- */
/*     Comment       :  controller setting                             */
/* ------------------------------------------------------------------- */
/*     History       :                                               */
/* ------------------------------------------------------------------- */
/*     Version V001  :  2012.08.14 (LongNguyen)        New Create      */
/* * ****************************************************************** */

class Setting extends Admin {

    protected $data;

    function __construct() {
        parent::__construct();
        $this->load->model('Setting_model', 'setting_model');
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
    /*    M001 : New  2012.08.14 (LongNguyen) | Tuan Anh 03-10-2012         */
    /*     * ************************************************************** */

    function index() {
//Tuan Anh update nxt_date table setting follow calculation_dates
        $this->data->nxt_dates = $this->setting_model->check_update_nxt_dates();
        if(isset($this->data->nxt_dates) && is_object($this->data->nxt_dates)):
            if ($this->data->nxt_dates[0]['currdate'] != '')
            $this->setting_model->update_nxt_dates();
        endif;


// ----------------- //
        $this->data->title = 'Setting';
        $this->data->list_setting = $this->setting_model->find();
        $this->template->write_view('content', 'setting/setting_list', $this->data);
        $this->template->write('title', 'Setting Manager');
        $this->template->render();
    }

    /*     * ************************************************************** */
    /*    Name ： add                                                */
    /* --------------------------------------------------------------- */
    /*    Description  ： add new setting                             */
    /* --------------------------------------------------------------- */
    /*    Params  ：  None                                             */
    /* --------------------------------------------------------------- */
    /*    Return  ：   redirect backend/setting when add setting success  */
    /* --------------------------------------------------------------- */
    /*    Warning ：                                                     */
    /* --------------------------------------------------------------- */
    /*    Copyright : IFRC                                         */
    /* --------------------------------------------------------------- */
    /*    M001 : New  2012.08.14 (LongNguyen)                             */
    /*     * ************************************************************** */

    function add() {
        $this->data->input = $this->input->post();
        $this->data->title = 'Setting - Add new';
        $this->load->library('form_validation');
        $this->form_validation->set_rules('group', 'group', 'trim|required');
        $this->form_validation->set_rules('key', 'key', 'trim|required|is_unique[setting.key]');
        $this->form_validation->set_rules('value', 'value', 'trim|required');
        if ($this->form_validation->run() == FALSE) :
            $this->data->error = validation_errors();
        else:
            if ($this->setting_model->add($this->input->post()) == 1):
                redirect(admin_url() . 'setting');
            else:
                $this->data->error = 'insert error';
            endif;
        endif;
        $this->template->write_view('content', 'setting/setting_form', $this->data);
        $this->template->write('title', 'Add new');
        $this->template->render();
    }

    /*     * ************************************************************** */
    /*    Name ： edit                                                */
    /* --------------------------------------------------------------- */
    /*    Description  ： edit 1 setting                             */
    /* --------------------------------------------------------------- */
    /*    Params  ：  None                                             */
    /* --------------------------------------------------------------- */
    /*    Return  ：   redirect backend/setting when add setting success  */
    /* --------------------------------------------------------------- */
    /*    Warning ：                                                     */
    /* --------------------------------------------------------------- */
    /*    Copyright : IFRC                                         */
    /* --------------------------------------------------------------- */
    /*    M001 : New  2012.08.14 (LongNguyen)                             */
    /*     * ************************************************************** */

    function edit($id) {
        $this->data->id = $id;
        $this->data->key = $this->setting_model->get_key_by_id($id);
        if ($this->data->key[0]['key'] == 'calculation_dates') {
            $this->data->input = $this->setting_model->find($id);
            count($this->data->input) == 0 ? redirect(admin_url() . 'setting') : $this->data->input = $this->data->input[0];

            $this->load->library('form_validation');
            $this->form_validation->set_rules('group', 'group', 'trim|required');
            $this->form_validation->set_rules('key', 'key', 'trim|required');
            $this->form_validation->set_rules('value', 'value', 'trim|required');
            if ($this->form_validation->run() == FALSE) :
                $this->data->error = validation_errors();
            else :
                if (self::_key_check($this->input->post('key')) != FALSE) :
                    $this->data->nxt_dates = $this->setting_model->check_update_nxt_dates_edit($this->input->post('value'));
                    if (isset($this->data->nxt_dates[0]['currdate']) && $this->data->nxt_dates[0]['currdate'] != '') {
                        $this->setting_model->update($this->input->post(), $id);
                        redirect(admin_url() . 'setting');
                    } else {
                        $this->data->calculation_dates = $this->setting_model->get_min_max_idx_calender();
                        echo "<script>alert('nxt_date does not exists!";
                        echo " calculation_dates should be from";
                        echo $this->data->calculation_dates[0]['min_date'] . " to " . $this->data->calculation_dates[0]['max_date'];
                        echo "')</script>";
                    }
                endif;
            endif;
            $this->data->title = 'Setting - Edit';
            $this->template->write_view('content', 'setting/setting_form', $this->data);
            $this->template->write('title', 'Edit');
            $this->template->render();
        } else {
            $this->data->input = $this->setting_model->find($id);
            count($this->data->input) == 0 ? redirect(admin_url() . 'setting') : $this->data->input = $this->data->input[0];
            $this->load->library('form_validation');
            $this->form_validation->set_rules('group', 'group', 'trim|required');
            $this->form_validation->set_rules('key', 'key', 'trim|required');
            $this->form_validation->set_rules('value', 'value', 'trim|required');
            if ($this->form_validation->run() == FALSE) :
                $this->data->error = validation_errors();
            else :
                if (self::_key_check($this->input->post('key')) != FALSE) :
                    if ($this->setting_model->update($this->input->post(), $id) == 1):
                        redirect(admin_url() . 'setting');
                    else:
                        $this->data->error = 'update error';
                    endif;
                endif;
            endif;

            $this->data->title = 'Setting - Edit';
            $this->template->write_view('content', 'setting/setting_form', $this->data);
            $this->template->write('title', 'Edit');
            $this->template->render();
        }
    }

    /**     * ************************************************************* */
    /*    Name ： delete                                                */
    /* --------------------------------------------------------------- */
    /*    Description  ： delete 1 setting   call by ajax               */
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
            echo $this->setting_model->delete($this->input->post('id'));
        }
    }

    /**     * ************************************************************* */
    /*    Name ： _key_check                                                */
    /* --------------------------------------------------------------- */
    /*    Description  ： check key if exists               */
    /* --------------------------------------------------------------- */
    /*    Params  ：  $_POST id                                             */
    /* --------------------------------------------------------------- */
    /*    Return  ：   true: not exist     false: exists and set error message */
    /* --------------------------------------------------------------- */
    /*    Warning ：                                                     */
    /* --------------------------------------------------------------- */
    /*    Copyright : IFRC                                         */
    /* --------------------------------------------------------------- */
    /*    M001 : New  2012.08.14 (LongNguyen)                             */
    /*     * ************************************************************** */

    private function _key_check($key) {
        if ($this->setting_model->check_key($key, $this->data->id)) {
            return true;
        } else {
            $this->data->error = 'key already exists';
            return false;
        }
    }

}