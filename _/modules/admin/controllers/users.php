<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/* * ****************************************************************** */
/*     Client  Name  :  IFRC                                      */
/*     Project Name  :  cms v3.0                                     */
/*     Program Name  :  users.php                                      */
/*     Entry Server  :                                               */
/*     Called By     :  System                                       */
/*     Notice        :  File Code is utf-8                           */
/*     Copyright     :  IFRC                                         */
/* ------------------------------------------------------------------- */
/*     Comment       :  controller users                             */
/* ------------------------------------------------------------------- */
/*     History       :                                               */
/* ------------------------------------------------------------------- */
/*     Version V001  :  2012.08.14 (LongNguyen)        New Create      */
/* * ****************************************************************** */

class Users extends Admin {

    function __construct() {
        parent::__construct();
        $this->load->library('ion_auth');
        $this->load->library('form_validation');
        $this->load->helper('url');
        $this->load->model('Group_model', 'group_model');
        $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
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

    //redirect if needed, otherwise display the user list
    function index() {
        $this->data->title = "mn_users";
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('auth/login', 'refresh');
        } elseif (!$this->ion_auth->is_admin()) {
            //redirect them to the home page because they must be an administrator to view this
            redirect($this->config->item('base_url'), 'refresh');
        } else {
            //set the flash data error message if there is one
            $this->data->message = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
            $this->template->write_view('content', 'users/index', $this->data);
            $this->template->write('title', 'users');
            $this->template->render();
        }
    }

    /*     * ************************************************************** */
    /*    Name ： listdata                                                */
    /* --------------------------------------------------------------- */
    /*    Description  ：  data table ajax  */
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

    function listData() {
        if ($this->input->is_ajax_request()) {
            $this->data->users = $this->ion_auth->users()->result();
            if ((isset($this->data->users) == TRUE) && ($this->data->users != '') && (is_array($this->data->users) == TRUE)) {
                $aaData = array();
                foreach ($this->data->users as $key => $value) {
                    $group = $this->ion_auth->get_users_groups($value->id)->row_array();
                    $aaData[$key][] = '<p style="text-align: left;">' . $value->id . '</p>';
                    $aaData[$key][] = $value->first_name;
                    $aaData[$key][] = $value->last_name;
                    $aaData[$key][] = $value->email;
                    $aaData[$key][] = isset($group['name']) ? $group['name'] : '';
                    $aaData[$key][] = $value->active ? anchor(admin_url() . "users/deactivate/" . $value->id, 'Active') : anchor(admin_url() . "users/activate/" . $value->id, 'Inactive');
                    $aaData[$key][] = '<ul class="keywords" style="text-align: center;"><li class="green-keyword"><a title="Edit" class="with-tip" href="' . admin_url() . 'users/edit/' . $value->id . '">Edit</a></li>
                                   <li class="red_fx_keyword"><a title="Delete" class="with-tip action-delete " user_id="' . $value->id . '" href="#">Delete</a></li></ul>';
                }
                $output = array(
                    "aaData" => $aaData
                );
                $this->output->set_output(json_encode($output));
            }
        }
    }

    /*     * ************************************************************** */
    /*    Name ： active                                                */
    /* --------------------------------------------------------------- */
    /*    Description  ：  active user                                    */
    /* --------------------------------------------------------------- */
    /*    Params  ：  $id, $code=false                                             */
    /* --------------------------------------------------------------- */
    /*    Return  ：                                                 */
    /* --------------------------------------------------------------- */
    /*    Warning ：                                                  */
    /* --------------------------------------------------------------- */
    /*    Copyright : IFRC                                         */
    /* --------------------------------------------------------------- */
    /*    M001 : New  2012.08.14 (LongNguyen)                            */
    /*     * ************************************************************** */

    //activate the user
    function activate($id, $code = false) {
        if ($code !== false) {
            $activation = $this->ion_auth->activate($id, $code);
        } else if ($this->ion_auth->is_admin()) {
            $activation = $this->ion_auth->activate($id);
        }

        if ($activation) {
            //redirect them to the auth page
            $this->session->set_flashdata('message', $this->ion_auth->messages());
            redirect(admin_url() . 'users', 'refresh');
        } else {
            //redirect them to the forgot password page
            $this->session->set_flashdata('message', $this->ion_auth->errors());
            redirect(admin_url() . 'users');
        }
    }

    /*     * ************************************************************** */
    /*    Name ： deactive                                                */
    /* --------------------------------------------------------------- */
    /*    Description  ：  de-active user                                    */
    /* --------------------------------------------------------------- */
    /*    Params  ：  $id                                               */
    /* --------------------------------------------------------------- */
    /*    Return  ：                                                 */
    /* --------------------------------------------------------------- */
    /*    Warning ：                                                  */
    /* --------------------------------------------------------------- */
    /*    Copyright : IFRC                                         */
    /* --------------------------------------------------------------- */
    /*    M001 : New  2012.08.14 (LongNguyen)                            */
    /*     * ************************************************************** */

    //deactivate the user
    function deactivate($id = NULL) {
        // do we have the right userlevel?
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {
            $activation = $this->ion_auth->deactivate($id);
        }
        $this->session->set_flashdata('message', trans('dective_user_successfully!', 1));
        //redirect them back to the auth page
        redirect(admin_url() . 'users', 'refresh');
    }

    /*     * ************************************************************** */
    /*    Name ： add                                                */
    /* --------------------------------------------------------------- */
    /*    Description  ： add new user                             */
    /* --------------------------------------------------------------- */
    /*    Params  ：  None                                             */
    /* --------------------------------------------------------------- */
    /*    Return  ：   redirect backend/users when add users success  */
    /* --------------------------------------------------------------- */
    /*    Warning ：                                                     */
    /* --------------------------------------------------------------- */
    /*    Copyright : IFRC                                         */
    /* --------------------------------------------------------------- */
    /*    M001 : New  2012.08.14 (LongNguyen)                             */
    /*     * ************************************************************** */

    //create a new user
    function add() {

        $this->data->title = trans("mn_users", 1);
        //validate form input
        $this->form_validation->set_rules('username', 'Username', 'trim|required|xss_clean|alpha_numeric|min_length[5]|max_length[100]');
        $this->form_validation->set_rules('first_name', 'First Name', 'required|xss_clean');
        $this->form_validation->set_rules('last_name', 'Last Name', 'required|xss_clean');
        $this->form_validation->set_rules('group_id', 'Group', 'required|xss_clean');
        $this->form_validation->set_rules('email', 'Email Address', 'required|valid_email');
        $this->form_validation->set_rules('phone', 'Phone', 'required|xss_clean');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
        $this->form_validation->set_rules('password_confirm', 'Password Confirmation', 'required');

        if ($this->form_validation->run() == true) {
            $username = strtolower($this->input->post('username'));
            $email = $this->input->post('email');
            $password = $this->input->post('password');

            $additional_data = array(
                'first_name' => $this->input->post('first_name'),
                'last_name' => $this->input->post('last_name'),
                'phone' => $this->input->post('phone')
            );
        }
        $group = array($this->input->post('group_id'));
        if ($this->form_validation->run() == true && $this->ion_auth->register($username, $password, $email, $additional_data, $group)) {
            //check to see if we are creating the user
            //redirect them back to the admin page
            $this->session->set_flashdata('message', $this->ion_auth->messages());
            redirect(admin_url() . 'users', 'refresh');
        } else {
            //display the create user form
            //set the flash data error message if there is one
            $this->data->message = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

            $this->data->first_name = array(
                'name' => 'first_name',
                'id' => 'first_name',
                'class' => 'full-width',
                'type' => 'text',
                'value' => $this->form_validation->set_value('first_name'),
            );
            $this->data->username = array(
                'name' => 'username',
                'id' => 'username',
                'class' => 'full-width',
                'type' => 'text',
                'value' => $this->form_validation->set_value('username'),
            );
            $this->data->last_name = array(
                'name' => 'last_name',
                'id' => 'last_name',
                'class' => 'full-width',
                'type' => 'text',
                'value' => $this->form_validation->set_value('last_name'),
            );
            $this->data->email = array(
                'name' => 'email',
                'id' => 'email',
                'class' => 'full-width',
                'type' => 'text',
                'value' => $this->form_validation->set_value('email'),
            );
            $this->data->phone = array(
                'name' => 'phone',
                'id' => 'phone',
                'type' => 'text',
                'class' => 'full-width',
                'value' => $this->form_validation->set_value('phone'),
            );
            $this->data->password = array(
                'name' => 'password',
                'id' => 'password',
                'type' => 'password',
                'class' => 'full-width',
                'value' => $this->form_validation->set_value('password'),
            );
            $this->data->password_confirm = array(
                'name' => 'password_confirm',
                'id' => 'password_confirm',
                'type' => 'password',
                'class' => 'full-width',
                'value' => $this->form_validation->set_value('password_confirm'),
            );
            $this->data->group = $this->group_model->find();
            $this->data->group_id = $this->form_validation->set_value('group_id');
            $this->template->write_view('content', 'users/users_add', $this->data);
            $this->template->write('title', trans('Add', 1));
            $this->template->render();
        }
    }

    /*     * ************************************************************** */
    /*    Name ： edit                                                */
    /* --------------------------------------------------------------- */
    /*    Description  ： edit 1 user                             */
    /* --------------------------------------------------------------- */
    /*    Params  ：  $id                                             */
    /* --------------------------------------------------------------- */
    /*    Return  ：   redirect backend/users when edit user success  */
    /* --------------------------------------------------------------- */
    /*    Warning ：                                                     */
    /* --------------------------------------------------------------- */
    /*    Copyright : IFRC                                         */
    /* --------------------------------------------------------------- */
    /*    M001 : New  2012.08.14 (LongNguyen)                             */
    /*     * ************************************************************** */

    //edit a user
    function edit($id) {
        $this->data->title = trans("mn_users", 1);

        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
            redirect('auth', 'refresh');
        }

        $user = $this->ion_auth->user($id)->row();

        //process the phone number
        if (isset($user->phone) && !empty($user->phone)) {
            $user->phone = explode('-', $user->phone);
        }

        //validate form input
        $this->form_validation->set_rules('email', 'Email Address', 'required|valid_email');
        $this->form_validation->set_rules('first_name', 'First Name', 'required|xss_clean');
        $this->form_validation->set_rules('last_name', 'Last Name', 'required|xss_clean');
        $this->form_validation->set_rules('phone', 'Phone', 'required|xss_clean');
        if (isset($_POST) && !empty($_POST)) {
            // do we have a valid request?
            if ($this->_valid_csrf_nonce() === FALSE || $id != $this->input->post('id')) {
                show_error('This form post did not pass our security checks.');
            }

            $data = array(
                'first_name' => $this->input->post('first_name'),
                'last_name' => $this->input->post('last_name'),
                'email' => $this->input->post('email'),
                'phone' => $this->input->post('phone')
            );
            $group_id = $this->input->post('group_id');
            //update the password if it was posted
            if ($this->input->post('password')) {
                $this->form_validation->set_rules('password', 'Password', 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
                $this->form_validation->set_rules('password_confirm', 'Password Confirmation', 'required');

                $data['password'] = $this->input->post('password');
            }

            if ($this->form_validation->run() === TRUE) {
                if ($this->ion_auth->update($user->id, $data, $group_id) != FALSE) {
                    $this->session->set_flashdata('message', "User Saved");
                    redirect(admin_url() . 'users', 'refresh');
                }
            }
        }

        //display the edit user form
        $this->data->csrf = $this->_get_csrf_nonce();

        //set the flash data error message if there is one
        $this->data->message = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

        //pass the user to the view
        $this->data->user = $user;
        $this->data->email = array(
            'name' => 'email',
            'id' => 'email',
            'class' => 'full-width',
            'type' => 'text',
            'value' => $this->form_validation->set_value('email', $user->email),
        );
        $this->data->first_name = array(
            'name' => 'first_name',
            'id' => 'first_name',
            'type' => 'text',
            'value' => $this->form_validation->set_value('first_name', $user->first_name),
        );
        $this->data->last_name = array(
            'name' => 'last_name',
            'id' => 'last_name',
            'type' => 'text',
            'value' => $this->form_validation->set_value('last_name', $user->last_name),
        );
        $this->data->phone = array(
            'name' => 'phone',
            'id' => 'phone',
            'type' => 'text',
            'value' => $this->form_validation->set_value('phone', $user->phone[0]),
        );
        $this->data->password = array(
            'name' => 'password',
            'id' => 'password',
            'type' => 'password'
        );
        $this->data->password_confirm = array(
            'name' => 'password_confirm',
            'id' => 'password_confirm',
            'type' => 'password'
        );
        $this->data->group = $this->group_model->find();
        $group_id = $this->group_model->get_user_group($user->id);
        $this->data->group_id = $this->form_validation->set_value('group_id', $group_id->group_id);
        $this->template->write_view('content', 'users/users_edit', $this->data);
        $this->template->write('title', trans('Edit', 1));
        $this->template->render();
    }

    /**     * ************************************************************* */
    /*    Name ： delete                                                */
    /* --------------------------------------------------------------- */
    /*    Description  ： delete 1 user   call by ajax               */
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
            echo $this->ion_auth->delete_user($this->input->post('id'));
        }
    }

    function _get_csrf_nonce() {
        $this->load->helper('string');
        $key = random_string('alnum', 8);
        $value = random_string('alnum', 20);
        $this->session->set_flashdata('csrfkey', $key);
        $this->session->set_flashdata('csrfvalue', $value);

        return array($key => $value);
    }

    function _valid_csrf_nonce() {
        if ($this->input->post($this->session->flashdata('csrfkey')) !== FALSE &&
                $this->input->post($this->session->flashdata('csrfkey')) == $this->session->flashdata('csrfvalue')) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

}
