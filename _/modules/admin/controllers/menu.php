<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/* * ****************************************************************** */
/*     Client  Name  :  IFRC                                      */
/*     Project Name  :  cms v3.0                                     */
/*     Program Name  :  menu.php                                      */
/*     Entry Server  :                                               */
/*     Called By     :  System                                       */
/*     Notice        :  File Code is utf-8                           */
/*     Copyright     :  IFRC                                         */
/* ------------------------------------------------------------------- */
/*     Comment       :  controller page                             */
/* ------------------------------------------------------------------- */
/*     History       :                                               */
/* ------------------------------------------------------------------- */
/*     Version V001  :  2012.08.14 (Tung)        New Create      */
/* * ****************************************************************** */

class Menu extends Admin {

    protected $data;

    function __construct() {
        parent::__construct();
        // load model menu
        $this->load->model('Menu_model', 'menu_model');
        $this->load->model('Category_model', 'category_model');
        $this->load->model('Page_model', 'page_model');
        $this->load->helper(array('my_array_helper', 'form'));
        $this->load->helper('my_helper', 'form');
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
        $this->data->title = 'List menu';
        $this->template->write_view('content', 'menu/menu_list', $this->data);
        $this->template->write('title', 'Menu');
        $this->template->render();
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
    /*    M001 : New  2012.09.04 (LongNguyen)                            */
    /*     * ************************************************************** */

    function listdata() {
        if ($this->input->is_ajax_request()) {
            $this->data->list_menu = $this->menu_model->listMenu();
            if ((isset($this->data->list_menu) == TRUE) && ($this->data->list_menu != '') && (is_array($this->data->list_menu) == TRUE)) {
                $aaData = array();
                foreach ($this->data->list_menu as $key => $value) {
                    $aaData[$key][] = $value->id;
                    $aaData[$key][] = $value->name;
                    $aaData[$key][] = $value->link;
                    $aaData[$key][] = $value->sort_order;
                    $aaData[$key][] = '<input type="checkbox" menu_id="' . $value->id . '" class="active action-change-status" name="status" ' . (($value->status == 1) ? 'checked' : NULL) . ' />';
                    $aaData[$key][] = '<ul class="keywords" style="text-align: center;"><li class="green-keyword"><a title="Edit" class="with-tip" href="' . admin_url() . 'menu/edit/' . $value->id . '">' . trans('bt_edit', 1) . '</a></li>
                                   <li class="red_fx_keyword"><a  title="" class="with-tip action-delete " menu_id="' . $value->id . '" href="#">' .trans('bt_delete', 1) . '</a></li></ul>';
                }
                $output = array(
                    "aaData" => $aaData
                );
                $this->output->set_output(json_encode($output));
            }
        }
    }

    /*     * ************************************************************** */
    /*    Name ： add                                                */
    /* --------------------------------------------------------------- */
    /*    Description  ： add new category                             */
    /* --------------------------------------------------------------- */
    /*    Params  ：  None                                             */
    /* --------------------------------------------------------------- */
    /*    Return  ：   redirect backend/category when add category success  */
    /* --------------------------------------------------------------- */
    /*    Warning ：                                                     */
    /* --------------------------------------------------------------- */
    /*    Copyright : IFRC                                         */
    /* --------------------------------------------------------------- */
    /*    M001 : New  2012.08.14 (Tung)                             */
    /*     * ************************************************************** */

    function add() {
        $categories = NULL;
        $this->data->input = $this->input->post();
        $this->data->title = 'Menu - Add new';
        // load form helper
        // load validation lib
        $this->load->library('form_validation');
        // set rule validate
        $this->form_validation->set_rules('link', 'Url', 'required');
        // run validate
        if ($this->form_validation->run() == FALSE) :
            // set error message
            $this->data->error = validation_errors();
        else :
            if ($this->menu_model->addMenu($this->input->post(), $this->data->list_language) === FALSE) {
                $this->data->error = 'Insert error';
            } else {
                redirect(admin_url() . 'menu');
            }
        endif;
        //get all category
        $list_category = $this->category_model->list_category();
        // data for dropdown list parent category
        if ($list_category != '' && is_array($list_category)) {
            foreach ($list_category as $value) {
                $categories[$value->category_id] = $value->name;
            }
        }
        //get all menu
        $list_menu = $this->menu_model->listMenu();
        // data for dropdown list parent menu
        if ($list_menu != '' && is_array($list_menu)) {
            foreach ($list_menu as $value) {
                $menus[$value->id] = $value->name;
            }
        }
        //get all page
        $list_page = $this->page_model->find();
        // data for dropdown list_page
        if ($list_page != '' && is_array($list_page)) {
            foreach ($list_page as $value) {
                $pages[$value['id']] = $value['name'];
            }
        }

        // set data for list_page
        $this->data->list_page = $pages;
        // set data for list_menu
        $this->data->list_menu = $menus;
        // set data for list_category
        $this->data->list_category = $categories;
        // load view and set data
        $this->template->write_view('content', 'menu/menu_form', $this->data);
        // set data for title
        $this->template->write('title', 'Add ');
        //render template
        $this->template->render();
    }

    /*     * ************************************************************** */
    /*    Name ： edit                                                */
    /* --------------------------------------------------------------- */
    /*    Description  ： edit 1 category                             */
    /* --------------------------------------------------------------- */
    /*    Params  ：  $id category_id                                             */
    /* --------------------------------------------------------------- */
    /*    Return  ：   redirect backend/category when edit category success  */
    /* --------------------------------------------------------------- */
    /*    Warning ：                                                     */
    /* --------------------------------------------------------------- */
    /*    Copyright : IFRC                                         */
    /* --------------------------------------------------------------- */
    /*    M001 : New  2012.08.14 (Tung)                             */
    /*     * ************************************************************** */

    function edit($id) {
        $categories = NULL;
        $this->data->title = 'Menu - Edit';
        // load form helper
        $this->load->helper('form');
        // load validation lib
        $this->load->library('form_validation');
        // set rule validate
        $this->form_validation->set_rules('link', 'Url', 'required');
        // run validate
        if ($this->form_validation->run() == FALSE) :
            // set error message
            $this->data->input = $this->input->post();
            $this->data->error = validation_errors();
        else :
            if ($this->menu_model->editMenu($this->input->post(), $this->data->list_language, $id) === FALSE):
                $this->data->error = 'Edit error';
            else:
                redirect(admin_url() . 'menu');
            endif;
        endif;
        //get all category
        $list_category = $this->category_model->list_category();
        // data for dropdown list parent category
        if ($list_category != '' && is_array($list_category)) {
            foreach ($list_category as $value) {
                $categories[$value->category_id] = $value->name;
            }
        }
        //get all menu
        $list_menu = $this->menu_model->listMenu();
        // data for dropdown list parent menu
        if ($list_menu != '' && is_array($list_menu)) {
            foreach ($list_menu as $value) {
                $menus[$value->id] = $value->name;
            }
        }
        //get all page
        $list_page = $this->page_model->find();
        // data for dropdown list_page
        if ($list_page != '' && is_array($list_page)) {
            foreach ($list_page as $value) {
                $pages[$value['id']] = $value['name'];
            }
        }

        // set data for list_page
        $this->data->list_page = $pages;
        // set data for list_menu
        $this->data->list_menu = $menus;
        // set data for list_category
        $this->data->list_category = $categories;
        // load view and set data
        $info = $this->menu_model->get_one($id);
        $this->data->input = $info[0];

        if (isset($info['menu_description']) && is_array($info['menu_description'])) {
            foreach ($info['menu_description'] as $value) {
                $this->data->input['name'][$value['lang_code']] = $value['name'];
                $this->data->input['description'][$value['lang_code']] = $value['description'];
            }
        }
        $this->template->write_view('content', 'menu/menu_form', $this->data);
        // set data for title
        $this->template->write('title', 'Edit');
        //render template
        $this->template->render();
    }

    /**     * ************************************************************* */
    /*    Name ： delete                                                */
    /* --------------------------------------------------------------- */
    /*    Description  ： delete 1 category   call by ajax               */
    /* --------------------------------------------------------------- */
    /*    Params  ：  $_POST id                                             */
    /* --------------------------------------------------------------- */
    /*    Return  ：   return 0 when delete false return 1 when delete success  */
    /* --------------------------------------------------------------- */
    /*    Warning ：                                                     */
    /* --------------------------------------------------------------- */
    /*    Copyright : IFRC                                         */
    /* --------------------------------------------------------------- */
    /*    M001 : New  2012.08.14 (Tung)                             */
    /*     * ************************************************************** */

    function delete() {
        $this->output->enable_profiler(FALSE);
        if ($this->input->is_ajax_request()) {
            $response = $this->menu_model->delete($this->input->post('id'));
            $this->output->set_output($response);
        }
    }

    function getCateCode() {
        $this->output->enable_profiler(FALSE);
        if ($this->input->is_ajax_request()) {
            $data = $this->category_model->get_one($this->input->post('id'));
            $this->output->set_output($data[0]['category_code']);
        }
    }

    function active() {
        $this->output->enable_profiler(FALSE);
        if ($this->input->is_ajax_request()) {
            if ($this->input->post('status') == 'checked') {
                $data['status'] = 1;
            } else {
                $data['status'] = 0;
            }
            if ($this->menu_model->update($data, $this->input->post('id'))) {
                $response = 'success';
            }
            $this->output->set_output($response);
        }
    }

}