<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends MY_Controller {

    public $data;

    function __construct() {
        parent::__construct();
        $this->load->library('ion_auth');
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
    }

    function index() {
        redirect($this->config->item('base_url'), 'refresh');
    }

    function login() {
        $this->form_validation->set_rules('identity', 'Identity', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required');
        $module_login = $this->session->userdata('module_login');
        if ($this->form_validation->run() == true) {
            //check to see if the user is logging in
            //check for "remember me"
            $remember = (bool) $this->input->post('remember');

            if ($this->ion_auth->login($this->input->post('identity'), $this->input->post('password'), $remember)) {
                //if the login is successful
                //redirect them back to the home page
                $this->session->set_flashdata('message', $this->ion_auth->messages());
                redirect($this->session->userdata('url_login'), 'refresh');
            } else {
                //if the login was un-successful
                //redirect them back to the login page
                $this->session->set_flashdata('message', $this->ion_auth->errors());
                redirect('auth/login'); //use redirects instead of loading views for compatibility with MY_Controller libraries
            }
        } else {
            // redirect(base_url());
            //the user is not logging in so display the login page
            //set the flash data error message if there is one
            $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

            $this->data['identity'] = array('name' => 'identity',
                'id' => 'login',
                'type' => 'text',
                'class' => 'full-width',
                'value' => $this->form_validation->set_value('identity'),
            );
            $this->data['password'] = array('name' => 'password',
                'id' => 'pass',
                'type' => 'password',
                'class' => 'full-width'
            );
            if ($module_login == 'admin') {
                $this->template->set_template('login');
                $this->template->write_view('content', 'admin/login', $this->data);
                $this->template->write('title', 'Login Panel ');
                $this->template->render();
            } else {
                redirect(base_url());
            }
        }
    }

    //log the user out
    function logout() {
        $this->ion_auth->logout();
        redirect($this->config->item('base_url'), 'refresh');
    }

    /**     * ***************************************************************** */
    /*     Client  Name  :  IFRC                                      */
    /*     Project Name  :  cms v3.0                                     */
    /*     Program Name  :  role.php                                      */
    /*     Entry Server  :                                               */
    /*     Called By     :  System                                       */
    /*     Notice        :  File Code is utf-8                           */
    /*                   :  Program Execute Env is utf-8                 */
    /*     Copyright     :  IFRC                                         */
    /* ------------------------------------------------------------------- */
    /*     Comment       :  auth                                        */
    /* ------------------------------------------------------------------- */
    /*     History       :                                               */
    /* ------------------------------------------------------------------- */
    /*     Version V001  :  2012.08.14 (Tuan Anh)           Change password      */
    /*     * ****************************************************************** */

    //change password
    function change_password() {
        $this->form_validation->set_rules('old', 'Old password', 'required');
        $this->form_validation->set_rules('new', 'New Password', 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[new_confirm]');
        $this->form_validation->set_rules('new_confirm', 'Confirm New Password', 'required');

        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
        }

        $user = $this->ion_auth->user()->row();

        if ($this->form_validation->run() == false) {
            //display the form
            //set the flash data error message if there is one
            $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

            $this->data['min_password_length'] = $this->config->item('min_password_length', 'ion_auth');
            $this->data['old_password'] = array(
                'name' => 'old',
                'id' => 'old',
                'type' => 'password',
            );
            $this->data['new_password'] = array(
                'name' => 'new',
                'id' => 'new',
                'type' => 'password',
                'pattern' => '^.{' . $this->data['min_password_length'] . '}.*$',
            );
            $this->data['new_password_confirm'] = array(
                'name' => 'new_confirm',
                'id' => 'new_confirm',
                'type' => 'password',
                'pattern' => '^.{' . $this->data['min_password_length'] . '}.*$',
            );
            $this->data['user_id'] = array(
                'name' => 'user_id',
                'id' => 'user_id',
                'type' => 'hidden',
                'value' => $user->id,
            );

            //render
            $this->load->view('auth/change_password', $this->data);
        } else {
            $identity = $this->session->userdata($this->config->item('identity', 'ion_auth'));

            $change = $this->ion_auth->change_password($identity, $this->input->post('old'), $this->input->post('new'));

            if ($change) {
                //if the password was successfully changed
                $this->session->set_flashdata('message', $this->ion_auth->messages());
                $this->logout();
            } else {
                $this->session->set_flashdata('message', $this->ion_auth->errors());
                redirect('auth/change_password', 'refresh');
            }
        }
    }

}
