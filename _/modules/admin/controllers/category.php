<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/* * ****************************************************************** */
/*     Client  Name  :  IFRC                                      */
/*     Project Name  :  cms v3.0                                     */
/*     Program Name  :  category.php                                      */
/*     Entry Server  :                                               */
/*     Called By     :  System                                       */
/*     Notice        :  File Code is utf-8                           */
/*     Copyright     :  IFRC                                         */
/* ------------------------------------------------------------------- */
/*     Comment       :  controller category                             */
/* ------------------------------------------------------------------- */
/*     History       :                                               */
/* ------------------------------------------------------------------- */
/*     Version V001  :  2012.08.14 (LongNguyen)        New Create      */
/* * ****************************************************************** */

class Category extends Admin {

    protected $data;

    function __construct() {
        parent::__construct();
        // load model category
        $this->load->model('Category_model', 'category_model');
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
    /*    M001 : New  2012.08.14 (LongNguyen)                            */
    /*     * ************************************************************** */

    function index() {
        $categories = array();
        $list_category = $this->category_model->list_category_fix();

        $this->data->list_category = $list_category;
        // data for dropdown list parent category
        if (isset($this->data->list_category) == TRUE && $this->data->list_category != '' && is_array($this->data->list_category) == TRUE) {
            foreach ($this->data->list_category as $key => $value) {
                $categories[$value->category_id] = $value->name;
            }
        }
        $this->data->title = 'List categories';
        $this->data->list_category_selectbox = $categories;
        $this->template->write_view('content', 'category/category_list', $this->data);
        $this->template->write('title', 'Categories ');
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
    /*    M001 : New  2012.08.14 (LongNguyen)                            */
    /*     * ************************************************************** */

    function listdata($id) {
        if ($this->input->is_ajax_request()) {
            if (!is_numeric($id)) {
                $id = '';
            }
            $this->data->list_category = $this->category_model->list_category_fix($id);
            $aaData = array();
            if (isset($this->data->list_category) == TRUE && $this->data->list_category != '' && is_array($this->data->list_category) == TRUE) {
                foreach ($this->data->list_category as $key => $value) {
                    $value->thumb = $this->_thumb($value->image);
                    $aaData[$key][] = $value->category_id;
                    $aaData[$key][] = $value->name;
                    $aaData[$key][] = $value->sort_order;
                    if ($value->status == 1) {
                        $aaData[$key][] = '<a style="color: green;" class="category-active" category_id="' . $value->category_id . '" href="javascript: void(0);">Enable</a>';
                    } else {
                        $aaData[$key][] = '<a style="color: red;" class="category-active" category_id="' . $value->category_id . '" href="javascript: void(0);">Disable</a>';
                    }
                    $aaData[$key][] = '<a class="fancybox" style="display: block;width: 35px" href="' . (isset($value->image) ? base_url() . $value->image : base_url() . 'assets/images/no-image.jpg') . '" title="' . $value->name . '">
                                        <img class="thumbnails " src="' . (isset($value->thumb) ? base_url() . $value->thumb : base_url() . 'assets/images/no-image.jpg') . '" alt="" /></a>';
                    $aaData[$key][] = '<ul class="keywords" style="text-align: center;"><li class="green-keyword"><a title="" class="with-tip" href="' . admin_url() . 'category/edit/' . $value->category_id . '">' . trans('bt_edit', 1) . '</a></li>
                                   <li class="red_fx_keyword"><a title="" class="with-tip action-delete ' . ($value->parent_id == 0 ? 'is_admin' : '') . '" category_id="' . $value->category_id . '" href="#">' . trans('bt_delete', 1) . '</a></li></ul>';
                }
            }
            $output = array(
                "aaData" => $aaData
            );
            $this->output->set_output(json_encode($output));
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
    /*    M001 : New  2012.08.14 (LongNguyen)                             */
    /*     * ************************************************************** */

    function add() {
        $categories = NULL;
        $this->data->input = $this->input->post();
        $this->data->title = 'Categories - Add new';
        // load form helper
        // load validation lib
        $this->load->library('form_validation');
        // set rule validate
        $this->form_validation->set_rules('parent_id');
        // $this->form_validation->set_rules('category_code', 'Code Category', 'required');
        $this->form_validation->set_rules('status');
        $this->form_validation->set_rules('sort_order', 'Sort Order', 'required|is_natural');
        // set validate theo language code
        if (isset($this->data->list_language) && is_array($this->data->list_language)):
            foreach ($this->data->list_language as $value) :
                $this->form_validation->set_rules('name_' . $value['code'], "name category in tab {$value['name']}", 'required');
            endforeach;
        endif;
        // run validate
        if ($this->form_validation->run() == FALSE) :
            // set error message
            $this->data->error = validation_errors();
        else :
            if ($this->category_model->add($this->data->input, $this->data->list_language) == 1):
                redirect(admin_url() . 'category');
            else:
                $this->data->error = 'insert error';
            endif;
        endif;
        //get all category
        $list_category = $this->category_model->list_category_fix();
        // data for dropdown list parent category
        if ($list_category != '' && is_array($list_category)) {
            foreach ($list_category as $value) {
                $categories[$value->category_id] = $value->name;
            }
        }
        // set data for list_category
        $this->data->list_category = $categories;
        // load view and set data
        $this->template->write_view('content', 'category/category_form', $this->data);
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
    /*    M001 : New  2012.08.14 (LongNguyen)                             */
    /*     * ************************************************************** */

    function edit($id) {
        $categories = NULL;
        $this->data->title = 'Categories - Edit';
        // load form helper
        $this->load->helper('form');
        // load validation lib
        $this->load->library('form_validation');
        // set rule validate
        $this->form_validation->set_rules('parent_id');
        //  $this->form_validation->set_rules('category_code', 'Code Category', 'required');
        $this->form_validation->set_rules('status');
        $this->form_validation->set_rules('sort_order', 'Sort Order', 'required|is_natural');
        // set validate theo language code
        if (isset($this->data->list_language) && is_array($this->data->list_language)):
            foreach ($this->data->list_language as $value) :
                $this->form_validation->set_rules('name_' . $value['code'], "name category in tab {$value['name']}", 'required');
            endforeach;
        endif;
        // run validate
        if ($this->form_validation->run() == FALSE) :
            // set error message
            $this->data->input = $this->input->post();
            $this->data->input['thumb'] = $this->_thumb($this->data->input['image']);
            $this->data->error = validation_errors();
        else :
            if ($this->category_model->edit($this->input->post(), $this->data->list_language, $id) == TRUE):
                redirect(admin_url() . 'category');
            else:
                $this->data->error = 'edit error';
            endif;
        endif;
        //get all category
        $list_category = $this->category_model->list_category_fix();
        // data for dropdown list parent category
        if ($list_category != '' && is_array($list_category)) {
            foreach ($list_category as $value) {
                $categories[$value->category_id] = $value->name;
            }
        }
        // set data for list_category
        $this->data->list_category = $categories;
        // load view and set data
        $info = $this->category_model->get_one($id);
        $this->data->input = $info[0];
        $this->data->input['thumb'] = $this->_thumb($this->data->input['image']);
        if (isset($info['category_description']) && is_array($info['category_description'])) {
            foreach ($info['category_description'] as $value) {
                $this->data->input['name_' . $value['lang_code']] = $value['name'];
                $this->data->input['description_' . $value['lang_code']] = $value['description'];
                $this->data->input['meta_description_' . $value['lang_code']] = $value['meta_description'];
                $this->data->input['meta_keyword_' . $value['lang_code']] = $value['meta_keyword'];
            }
        }
        $this->template->write_view('content', 'category/category_form', $this->data);
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
    /*    M001 : New  2012.08.14 (LongNguyen)                             */
    /*     * ************************************************************** */

    function delete() {
        $this->output->enable_profiler(FALSE);
        if ($this->input->is_ajax_request()) {
            $response = $this->category_model->delete($this->input->post('id'));
            $this->output->set_output($response);
        }
    }

    function chang_active() {
        $this->output->enable_profiler(FALSE);
        if ($this->input->is_ajax_request()) {
            echo $this->category_model->change_active($this->input->post('id'), $this->input->post('text'));
        }
    }

}