<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/* * ****************************************************************** */
/*     Client  Name  :  IFRC                                      */
/*     Project Name  :  cms v3.0                                     */
/*     Program Name  :  page.php                                      */
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

class Page extends Admin {

    protected $data;

    function __construct() {
        parent::__construct();
        // load model category
        $this->load->model('Category_model', 'category_model');
        $this->load->model('Page_model', 'page_model');
        $this->load->helper(array('my_array_helper', 'form'));
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
        $this->data->title = 'List pages';
        $this->template->write_view('content', 'page/page_list', $this->data);
        $this->template->write('title', 'Pages ');
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
            $this->data->list_page = $this->page_model->find();
            if ((isset($this->data->list_page) == TRUE) && ($this->data->list_page != '') && (is_array($this->data->list_page) == TRUE)) {
                $aaData = array();
                foreach ($this->data->list_page as $key => $value) {
                    $aaData[$key][] = $value['id'];
                    $aaData[$key][] = $value['name'];
                    $aaData[$key][] = $value['layout_id'];
                    $aaData[$key][] = $value['code'];
                    $aaData[$key][] = '<ul class="keywords" style="text-align:center;"><li class="green-keyword"><a title="" class="with-tip" href="' . admin_url() . 'page/edit/' . $value['id'] . '">' . trans('bt_edit', 1) . '</a></li>
                                   <li class="red_fx_keyword"><a title="" class="with-tip action-delete " page_id="' . $value['id'] . '" href="#">' . trans('bt_delete', 1) . '</a></li></ul>';
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
        $this->data->title = 'Pages - Add new';
        // load form helper
        // load validation lib
        $this->load->library('form_validation');
        // set rule validate
        $this->form_validation->set_rules('name', 'Name', 'required');
        $this->form_validation->set_rules('layout_id', 'Layout', 'require');
        $this->form_validation->set_rules('value', 'Category Article', 'required');
        // set validate theo language code
        if (isset($this->data->list_language) && is_array($this->data->list_language)):
            foreach ($this->data->list_language as $value) :
                if ($value['code'] == $this->data->default_language['code']):
                    $this->form_validation->set_rules('title[' . $value['code'] . ']', 'Title', 'required');
                endif;

            endforeach;
        endif;
        // run validate
        if ($this->form_validation->run() == FALSE) :
            // set error message
            $this->data->error = validation_errors();
        else :
            if ($this->page_model->addPage($this->input->post(), $this->data->list_language) === FALSE) {
                $this->data->error = 'Insert error';
            } else {
                redirect(admin_url() . 'page');
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
        // set data for list_category
        $this->data->list_category = $categories;
        // load view and set data
        $this->template->write_view('content', 'page/page_form', $this->data);
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
        $this->data->title = 'Pages - Edit';
        // load form helper
        $this->load->helper('form');
        // load validation lib
        $this->load->library('form_validation');
        // set rule validate
        $this->form_validation->set_rules('name', 'Name', 'required');
        $this->form_validation->set_rules('layout_id', 'Layout', 'require');
        $this->form_validation->set_rules('value', 'Category Article', 'required');
        // set validate theo language code
        if (isset($this->data->list_language) && is_array($this->data->list_language)):
            foreach ($this->data->list_language as $value) :
                if ($value['code'] == $this->data->default_language['code']):
                    $this->form_validation->set_rules('title[' . $value['code'] . ']', 'Title', 'required');
                endif;

            endforeach;
        endif;
        // run validate
        // run validate
        if ($this->form_validation->run() == FALSE) :
            // set error message
            $this->data->input = $this->input->post();
            $this->data->error = validation_errors();
        else :
            if ($this->page_model->editPage($this->input->post(), $this->data->list_language, $id) === FALSE):
                $this->data->error = 'Edit error';
            else:
                redirect(admin_url() . 'page');
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
        // set data for list_category
        $this->data->list_category = $categories;
        // load view and set data
        $info = $this->page_model->get_one($id);
        $this->data->input = $info[0];

        if (isset($info['page_description']) && is_array($info['page_description'])) {
            foreach ($info['page_description'] as $value) {
                $this->data->input['title'][$value['lang_code']] = $value['title'];
                $this->data->input['description'][$value['lang_code']] = $value['description'];
                $this->data->input['meta_description'][$value['lang_code']] = $value['meta_description'];
                $this->data->input['meta_keyword'][$value['lang_code']] = $value['meta_keyword'];
            }
        }
        $this->template->write_view('content', 'page/page_form', $this->data);
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
            if ($this->page_model->delete($this->input->post('id'))) {
                $response = 'success';
            }
            $this->output->set_output($response);
        }
    }

    /**     * ************************************************************* */
    /*    Name ： listArticle                                                */
    /* --------------------------------------------------------------- */
    /*    Description  ： list article by cate_id   call by ajax               */
    /* --------------------------------------------------------------- */
    /*    Params  ：  $_POST id                                             */
    /* --------------------------------------------------------------- */
    /*    Return  ：                                                       */
    /* --------------------------------------------------------------- */
    /*    Warning ：                                                     */
    /* --------------------------------------------------------------- */
    /*    Copyright : IFRC                                         */
    /* --------------------------------------------------------------- */
    /*    M001 : New  2012.08.14 (Tung)                             */
    /*     * ************************************************************** */

    function listArticle() {
        $this->output->enable_profiler(FALSE);
        if ($this->input->is_ajax_request()) {
            $this->load->Model('Article_model', 'article_model');
            $data = $this->article_model->getAllArticleByCate($this->input->post('id'));
            $data = json_encode($data);
            $this->output->set_output($data);
        }
    }

}