<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/* * ****************************************************************** */
/*     Client  Name  :  IFRC                                      */
/*     Project Name  :  cms v3.0                                     */
/*     Program Name  :  permission.php                                      */
/*     Entry Server  :                                               */
/*     Called By     :  System                                       */
/*     Notice        :  set permission for role                       */
/*     Copyright     :  IFRC                                         */
/* ------------------------------------------------------------------- */
/*     Comment       :  resource                                        */
/* ------------------------------------------------------------------- */
/*     History       :                                               */
/* ------------------------------------------------------------------- */
/*     Version V001  :  2012.08.28 (LongNguyen)           New Create      */
/* * ****************************************************************** */

class Permission extends Admin {

    protected $data = '';

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

    function __construct() {
        parent::__construct();
        $this->load->model('Resource_model', 'resource_model');
        $this->load->model('Group_model', 'group_model');
        $this->load->model('Permission_model', 'permission_model');
        $this->load->model('Role_model', 'role_model');
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

    function index() {
        $this->data->title = "User permission";
        $this->data->list_role = $this->role_model->find();
        $this->template->write_view('content', 'permission/permission_list', $this->data);
        $this->template->write('title', 'User permission manager');
        $this->template->render();
    }

    function edit($role_id = NULL) {
        if (isset($role_id) == TRUE && $role_id != NULL && is_numeric($role_id) == TRUE) {
            if ($this->input->post('checkpost') == 'ok') {
                if ($this->_update($role_id, $this->input->post()) == TRUE) {
                    redirect(admin_url() . 'permission');
                }
            }
            $this->data->title = "Edit";
            $resources = array();
            $this->data->list_resource = $this->resource_model->find();
            $this->data->role = $this->role_model->find($role_id);
            if (is_array($this->data->role) == FALSE || count($this->data->role) == 0) {
                redirect(admin_url());
            }
            if (is_array($this->data->list_resource) == TRUE && count($this->data->list_resource) > 0) {
                foreach ($this->data->list_resource as $value) {
                    $resources[$value['module']][$value['resource_id']] = $value;
                }
            }
            $this->data->list_resource = $resources;
            unset($resources);
            $this->data->input = NULL;
            $info = $this->permission_model->find($role_id);
            if (is_array($info) == TRUE && count($info) > 0) {
                $this->data->input = json_decode($info['value']);
            }
            unset($info);
            $this->template->write_view('content', 'permission/permission_form', $this->data);
            $this->template->write('title', $this->data->title);
            $this->template->render();
        } else {
            redirect(admin_url());
        }
    }

    //check resource name exist
    private function _update($role_id, $data) {
        $temp = array();
        if (is_array($data) == TRUE && count($data) > 0) {
            foreach ($data['module'] as $value) {
                $temp[$value] = $data[$value];
            }
        }
        $dataPermission = array('role_id' => $role_id, 'value' => json_encode($temp));
        $data = $temp;
        unset($temp);
        if ($this->permission_model->check_exist($role_id) == TRUE) {
            return $this->permission_model->update($role_id, $dataPermission);
        } else {
            return $this->permission_model->add($dataPermission);
        }
    }

}