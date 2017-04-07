<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/* * ****************************************************************** */
/*     Client  Name  :  IFRC                                      */
/*     Project Name  :  cms v3.0                                     */
/*     Program Name  :  role.php                                      */
/*     Entry Server  :                                               */
/*     Called By     :  System                                       */
/*     Notice        :  File Code is utf-8                           */
/*                   :                                               */
/*     Copyright     :  IFRC                                         */
/* ------------------------------------------------------------------- */
/*     Comment       :  role                                        */
/* ------------------------------------------------------------------- */
/*     History       :                                               */
/* ------------------------------------------------------------------- */
/*     Version V001  :  2012.08.14 (Tung)           New Create      */
/* * ****************************************************************** */

class Role extends Admin {

    protected $data = '';

    function __construct() {
        parent::__construct();
        //load model
        $this->load->model('Role_model', 'role_model');
        //load model
        $this->load->model('Group_model', 'group_model');
    }

    /*     * ************************************************************** */
    /*    Name ： __construct                                            */
    /* --------------------------------------------------------------- */
    /*    Description  ：  this function will be called automatically  */
    /*                   when the controller is called               */
    /* --------------------------------------------------------------- */
    /*    Params  ：  None                                             */
    /* --------------------------------------------------------------- */
    /*    Return  ：                                                 */
    /* --------------------------------------------------------------- */
    /*    Warning ：                                                                                                                                     */
    /* --------------------------------------------------------------- */
    /*    Copyright : IFRC                                         */
    /* --------------------------------------------------------------- */
    /*    M001 : New  2012.08.14 (Tung)                                */
    /*     * ************************************************************** */

    function index() {
        $this->data->title = 'User role';
        // get all role
        $this->data->list_role = $this->role_model->find();
        //load view and set data view\
        $this->template->write_view('content', 'role/role_list', $this->data);
        //set data view
        $this->template->write('title', 'User role manager');
        $this->template->render();
    }

    /*     * ************************************************************** */
    /*    Name ： index                                                */
    /* --------------------------------------------------------------- */
    /*    Description  ：  action index                                  */
    /* --------------------------------------------------------------- */
    /*    Params  ：  None                                             */
    /* --------------------------------------------------------------- */
    /*    Return  ：                                                 */
    /* --------------------------------------------------------------- */
    /*    Warning ：                                                                                                                                     */
    /* --------------------------------------------------------------- */
    /*    Copyright : IFRC                                         */
    /* --------------------------------------------------------------- */
    /*    M001 : New  2012.08.14 (Tung)                                */
    /*     * ************************************************************** */

    function add() {
        $this->data->input = $this->input->post();
        $this->data->title = 'User role - Add new';
        $this->load->library('form_validation');
        $this->form_validation->set_rules('name', 'name', 'trim|required|is_unique[role.name]');
        $this->form_validation->set_rules('description', 'Description', 'trim|required');
        if ($this->form_validation->run() == FALSE) :
            $this->data->error = validation_errors();
        else:
            if ($this->role_model->add($this->input->post()) == 1):
                redirect(admin_url() . 'role');
            else:
                $this->data->error = 'insert error';
            endif;
        endif;
        $this->data->group = $this->group_model->find();
        $this->template->write_view('content', 'role/role_form', $this->data);
        $this->template->write('title', 'Add new');
        $this->template->render();
    }

    /*     * ************************************************************** */
    /*    Name ： add                                                   */
    /* --------------------------------------------------------------- */
    /*    Description  ：  action add                                 */
    /* --------------------------------------------------------------- */
    /*    Params  ：  None                                             */
    /* --------------------------------------------------------------- */
    /*    Return  ：                                                 */
    /* --------------------------------------------------------------- */
    /*    Warning ：                                                                                                                                     */
    /* --------------------------------------------------------------- */
    /*    Copyright : IFRC                                         */
    /* --------------------------------------------------------------- */
    /*    M001 : New  2012.08.14 (Tung)                                */
    /*     * ************************************************************** */

    function edit($id) {
        $this->data->id = $id;
        $this->data->input = $this->role_model->find($id);
        count($this->data->input) == 0 ? redirect(admin_url() . 'role') : $this->data->input = $this->data->input[0];
        $this->data->title = 'User role - Edit';
        $this->load->library('form_validation');
        $this->form_validation->set_rules('name', 'name', 'trim|required');
        $this->form_validation->set_rules('description', 'Description', 'trim|required');
        if ($this->form_validation->run() == FALSE) :
            $this->data->error = validation_errors();
        else :
            if (self::_name_check($this->input->post('name')) != FALSE) :
                if ($this->role_model->update($this->input->post(), $id) == 1):
                    redirect(admin_url() . 'role');
                else:
                    $this->data->error = 'update error';
                endif;
            endif;
        endif;
        $this->data->group = $this->group_model->find();
        $this->template->write_view('content', 'role/role_form', $this->data);
        $this->template->write('title', 'Edit');
        $this->template->render();
    }

    /*     * ************************************************************** */
    /*    Name ： edit                                                  */
    /* --------------------------------------------------------------- */
    /*    Description  ：  action edit                                  */
    /* --------------------------------------------------------------- */
    /*    Params  ：  $id                                             */
    /* --------------------------------------------------------------- */
    /*    Return  ：                                                 */
    /* --------------------------------------------------------------- */
    /*    Warning ：                                                                                                                                     */
    /* --------------------------------------------------------------- */
    /*    Copyright : IFRC                                         */
    /* --------------------------------------------------------------- */
    /*    M001 : New  2012.08.14 (Tung)                                */
    /*     * ************************************************************** */

    function delete() {
        $this->output->enable_profiler(FALSE);
        if ($this->input->is_ajax_request()) {
            echo $this->role_model->delete($this->input->post('id'));
        }
    }

    /*     * ************************************************************** */
    /*    Name ： delete                                                */
    /* --------------------------------------------------------------- */
    /*    Description  ：  action delete                                  */
    /* --------------------------------------------------------------- */
    /*    Params  ：  None                                             */
    /* --------------------------------------------------------------- */
    /*    Return  ：                                                 */
    /* --------------------------------------------------------------- */
    /*    Warning ：                                                                                                                                     */
    /* --------------------------------------------------------------- */
    /*    Copyright : IFRC                                         */
    /* --------------------------------------------------------------- */
    /*    M001 : New  2012.08.14 (Tung)                                */
    /*     * ************************************************************** */

    private function _name_check($name) {
        if ($this->role_model->check_name($name, $this->data->id)) {
            return true;
        } else {
            $this->data->error = 'Role name already exists!';
            return false;
        }
    }

    /*     * ************************************************************** */
    /*    Name ： _name_check                                                */
    /* --------------------------------------------------------------- */
    /*    Description  ：  this function check if role name existed     */
    /* --------------------------------------------------------------- */
    /*    Params  ：  $name                                             */
    /* --------------------------------------------------------------- */
    /*    Return  ：   TRUE or FALSE                                     */
    /* --------------------------------------------------------------- */
    /*    Warning ：                                                                                                                                     */
    /* --------------------------------------------------------------- */
    /*    Copyright : IFRC                                         */
    /* --------------------------------------------------------------- */
    /*    M001 : New  2012.08.14 (Tung)                                */
    /*     * ************************************************************** */
}