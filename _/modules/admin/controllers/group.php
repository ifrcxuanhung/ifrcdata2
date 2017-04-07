<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/* * ****************************************************************** */
/*     Client  Name  :  IFRC                                      */
/*     Project Name  :  cms v3.0                                     */
/*     Program Name  :  group.php                                      */
/*     Entry Server  :                                               */
/*     Called By     :  System                                       */
/*     Notice        :  File Code is utf-8                           */
/*     Copyright     :  IFRC                                         */
/* ------------------------------------------------------------------- */
/*     Comment       :  controller group                             */
/* ------------------------------------------------------------------- */
/*     History       :                                               */
/* ------------------------------------------------------------------- */
/*     Version V001  :  2012.08.14 (LongNguyen)        New Create      */
/* * ****************************************************************** */

class Group extends Admin {

    protected $data = '';

    function __construct() {
        parent::__construct();
        $this->load->model('Group_model', 'group_model');
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
        $this->data->title = 'List user group';
        $this->data->list_group = $this->group_model->find();
        $this->template->write_view('content', 'group/group_list', $this->data);
        $this->template->write('title', 'User group');
        $this->template->render();
    }

    /*     * ************************************************************** */
    /*    Name ： add                                                */
    /* --------------------------------------------------------------- */
    /*    Description  ： add new group                             */
    /* --------------------------------------------------------------- */
    /*    Params  ：  None                                             */
    /* --------------------------------------------------------------- */
    /*    Return  ：   redirect backend/group when add group success  */
    /* --------------------------------------------------------------- */
    /*    Warning ：                                                     */
    /* --------------------------------------------------------------- */
    /*    Copyright : IFRC                                         */
    /* --------------------------------------------------------------- */
    /*    M001 : New  2012.08.14 (LongNguyen)                             */
    /*     * ************************************************************** */

    function add() {
        $this->data->input = $this->input->post();
        $this->data->title = 'User group - Add new';
        $this->load->library('form_validation');
        $this->form_validation->set_rules('name', 'name', 'trim|required|is_unique[group.name]');
        $this->form_validation->set_rules('description', 'Description', 'trim|required');
        if ($this->form_validation->run() == FALSE) :
            $this->data->error = validation_errors();
        else:
            if ($this->group_model->add($this->input->post()) == 1):
                redirect(admin_url() . 'group');
            else:
                $this->data->error = 'insert error';
            endif;
        endif;
        $this->template->write_view('content', 'group/group_form', $this->data);
        $this->template->write('title','Add new');
        $this->template->render();
    }

    /*     * ************************************************************** */
    /*    Name ： edit                                                */
    /* --------------------------------------------------------------- */
    /*    Description  ： edit 1 group                                 */
    /* --------------------------------------------------------------- */
    /*    Params  ：  $id group_id                                       */
    /* --------------------------------------------------------------- */
    /*    Return  ：   redirect backend/group when edit group success  */
    /* --------------------------------------------------------------- */
    /*    Warning ：                                                     */
    /* --------------------------------------------------------------- */
    /*    Copyright : IFRC                                         */
    /* --------------------------------------------------------------- */
    /*    M001 : New  2012.08.14 (LongNguyen)                             */
    /*     * ************************************************************** */

    function edit($id) {
        $this->data->id = $id;
        $this->data->input = $this->group_model->find($id);
        count($this->data->input) == 0 ? redirect(admin_url() . 'group') : $this->data->input = $this->data->input[0];
        $this->data->title = 'User group - Edit';
        $this->load->library('form_validation');
        $this->form_validation->set_rules('name', 'name', 'trim|required');
        $this->form_validation->set_rules('description', 'Description', 'trim|required');
        if ($this->form_validation->run() == FALSE) :
            $this->data->error = validation_errors();
        else :
            if (self::_name_check($this->input->post('name')) != FALSE) :
                if ($this->group_model->update($this->input->post(), $id) == 1):
                    redirect(admin_url() . 'group');
                else:
                    $this->data->error = 'update error';
                endif;
            endif;
        endif;
        $this->template->write_view('content', 'group/group_form', $this->data);
        $this->template->write('title', 'Edit');
        $this->template->render();
    }

    /**     * ************************************************************* */
    /*    Name ： delete                                                */
    /* --------------------------------------------------------------- */
    /*    Description  ： delete 1 group   call by ajax               */
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
            echo $this->group_model->delete($this->input->post('id'));
        }
    }

    /**     * ************************************************************* */
    /*    Name ： _name_check                                                */
    /* --------------------------------------------------------------- */
    /*    Description  ： check group exist                            */
    /* --------------------------------------------------------------- */
    /*    Params  ：  $name                                             */
    /* --------------------------------------------------------------- */
    /*    Return  ：   return true when when group not exist            */
    /*                return false when exist                          */
    /* --------------------------------------------------------------- */
    /*    Warning ：                                                     */
    /* --------------------------------------------------------------- */
    /*    Copyright : IFRC                                         */
    /* --------------------------------------------------------------- */
    /*    M001 : New  2012.08.14 (LongNguyen)                             */
    /*     * ************************************************************** */

    private function _name_check($name) {
        if ($this->group_model->check_name($name, $this->data->id)) {
            return true;
        } else {
            $this->data->error = 'Group name already exists!';
            return false;
        }
    }

}