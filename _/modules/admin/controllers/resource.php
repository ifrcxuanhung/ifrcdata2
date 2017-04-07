<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Resource extends Admin {

    protected $data = '';

    function __construct() {
        parent::__construct();
        $this->load->model('Resource_model', 'resource_model');
        $this->load->model('Role_model', 'role_model');
    }

    function index() {
        $this->data->title = 'User resource';
        $this->template->write_view('content', 'resource/resource_list', $this->data);
        $this->template->write('title', 'Resource');
        $this->template->render();
    }

    function listData() {
        if ($this->input->is_ajax_request()) {
            $this->data->list_resource = $this->resource_model->find();
            if ((isset($this->data->list_resource) == TRUE) && ($this->data->list_resource != '') && (is_array($this->data->list_resource) == TRUE)) {
                $aaData = array();
                foreach ($this->data->list_resource as $key => $value) {
                    $aaData[$key][] = $value['resource_id'];
                    $aaData[$key][] = $value['module'];
                    $aaData[$key][] = $value['controller'];
                    $aaData[$key][] = $value['action'];
                    $aaData[$key][] = '<ul class="keywords" style="text-align: center;"><li class="green-keyword"><a title="Edit" class="with-tip" href="' . admin_url() . 'resource/edit/' . $value['resource_id'] . '">Edit</a></li>
                                   <li class="red_fx_keyword"><a  title="Delete" class="with-tip action-delete " resource_id="' . $value['resource_id'] . '" href="#">Delete</a></li></ul>';
                }
                $output = array(
                    "aaData" => $aaData
                );
                $this->output->set_output(json_encode($output));
            }
        }
    }

    function add() {
        $this->data->input = $this->input->post();
        $this->data->title = 'Add';
        $this->load->library('form_validation');
        $this->form_validation->set_rules('module', 'Module', 'trim|required');
        $this->form_validation->set_rules('controller', 'Controller', 'trim|required');
        $this->form_validation->set_rules('action', 'Action', 'trim|required');
        if ($this->form_validation->run() == FALSE) :
            $this->data->error = validation_errors();
        else:
            if ($this->resource_model->add($this->input->post()) == 1):
                redirect(admin_url() . 'resource');
            else:
                $this->data->error = 'insert error';
            endif;
        endif;
        $this->template->write_view('content', 'resource/resource_form', $this->data);
        $this->template->write('title', 'Add');
        $this->template->render();
    }

    function edit($id) {
        $this->data->id = $id;
        $this->data->input = $this->resource_model->find($id);
        count($this->data->input) == 0 ? redirect(admin_url() . 'resource') : $this->data->input = $this->data->input[0];
        $this->data->title = 'Edit';
        $this->load->library('form_validation');
        $this->form_validation->set_rules('module', 'Module', 'trim|required');
        $this->form_validation->set_rules('controller', 'Controller', 'trim|required');
        $this->form_validation->set_rules('action', 'Action', 'trim|required');
        if ($this->form_validation->run() == FALSE) :
            $this->data->error = validation_errors();
        else :
            if (self::_name_check($this->input->post('name')) != FALSE) :
                if ($this->resource_model->update($this->input->post(), $id) == 1):
                    redirect(admin_url() . 'resource');
                else:
                    $this->data->error = 'update error';
                endif;
            endif;
        endif;
        $this->template->write_view('content', 'resource/resource_form', $this->data);
        $this->template->write('title', 'Edit');
        $this->template->render();
    }

    //delete resource
    function delete() {
        $this->output->enable_profiler(FALSE);
        if ($this->input->is_ajax_request()) {
            echo $this->resource_model->delete($this->input->post('id'));
        }
    }

    //check resource name exist
    private function _name_check($module, $controller) {
        if ($this->resource_model->check_name($module, $controller, $this->data->id)) {
            return true;
        } else {
            $this->data->error = trans('resource_name_already_exists', 1);
            return false;
        }
    }
}