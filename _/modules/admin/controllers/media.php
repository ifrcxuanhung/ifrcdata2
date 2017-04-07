<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/* * ****************************************************************** */
/*     Client  Name  :  IFRC                                      */
/*     Project Name  :  cms v3.0                                     */
/*     Program Name  :  media.php                                      */
/*     Entry Server  :                                               */
/*     Called By     :  System                                       */
/*     Notice        :  File Code is utf-8                           */
/*     Copyright     :  IFRC                                         */
/* ------------------------------------------------------------------- */
/*     Comment       :  controller media                             */
/* ------------------------------------------------------------------- */
/*     History       :                                               */
/* ------------------------------------------------------------------- */
/*     Version V001  :  2012.08.14 (LongNguyen)        New Create      */
/* * ****************************************************************** */

class Media extends Admin {

    protected $data;

    function __construct() {
        parent::__construct();
        // load model category
        $this->load->model('Category_model', 'category');
        $this->load->model('Media_model', 'media');
    }

    function index() {
        $list = $this->category->list_category_fix();
        // data for dropdown list category
        $categories = array();
        if (isset($list) && is_array($list)) {
            foreach ($list as $value) {
                $categories[$value->category_id] = $value->name;
            }
        }

        $this->data->list_category = $categories;
        $this->data->title = 'Media';
        $this->template->write_view('content', 'media/media_list', $this->data);
        $this->template->write('title', 'Media ');
        $this->template->render();
    }

    function listdata($id = '') {
        if ($this->input->is_ajax_request()) {
            $data = array();
            $lang_code_default = $this->session->userdata('default_language');
            if ($id != 0) {
                $list_media = $this->media->list_media($id);
                $list_media_default = $this->media->list_media($id, $lang_code_default);
            } else {
                $list_media = $this->media->list_media();
                $list_media_default = $this->media->list_media(0, $lang_code_default);
            }
            if (isset($list_media_default) == TRUE && count($list_media_default) > 0) {
                foreach ($list_media_default as $key => $value) {
                    if (isset($list_media[0])) {
                        if ($list_media[$key] == NULL) {
                            $list_media[$key] = $list_media_default[$key];
                        }
                    } else {
                        $list_media = $list_media_default;
                    }
                }
            }
            $list_media = replaceValueNull($list_media, $list_media_default);
            if (isset($list_media) == TRUE && $list_media != '' && is_array($list_media) == TRUE) {
                foreach ($list_media as $key => $value) {
                    $value['thumb'] = $this->_thumb($value['image']);
                    $data[$key] = $value;
                }
            }
            $this->data->list_media = $data;
            unset($data);
            if ((isset($this->data->list_media) == TRUE) && ($this->data->list_media != '') && (is_array($this->data->list_media) == TRUE)) {
                $aaData = array();
                foreach ($this->data->list_media as $key => $value) {
                    $aaData[$key][] = $value['media_id'];
                    $aaData[$key][] = $value['title'];
                    $aaData[$key][] = $value['description'];
                    $aaData[$key][] = $value['name'];
                    $aaData[$key][] = $value['sort_order'];
                    $aaData[$key][] = $value['type'];
                    if ($value['status'] == 1) {
                        $aaData[$key][] = '<a style="color: green;" class="media-active" media_id="' . $value['media_id'] . '" href="javascript: void(0);">Enable</a>';
                    } else {
                        $aaData[$key][] = '<a style="color: red;" class="media-active" media_id="' . $value['media_id'] . '" href="javascript: void(0);">Disable</a>';
                    }
                    $aaData[$key][] = '<a class="fancybox" style="display: block;width: 35px" href="' . (isset($value['image']) ? base_url() . $value['image'] : base_url() . 'assets/images/no-image.jpg') . '" title="' . $value['title'] . '">
                                        <img class="thumbnails " src="' . (isset($value['thumb']) ? base_url() . $value['thumb'] : base_url() . 'assets/images/no-image.jpg') . '" alt="" /></a>';
                    $aaData[$key][] = '<ul class="keywords" style="text-align: center;"><li class="green-keyword"><a title="" class="with-tip" href="' . admin_url() . 'media/edit/' . $value['media_id'] . '">' . trans('bt_edit', 1) . '</a></li>
                                   <li class="red_fx_keyword"><a title="" class="with-tip action-delete " media_id="' . $value['media_id'] . '" href="#">' . trans('bt_delete', 1) . '</a></li></ul>';
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
    /*    Description  ： add new media                             */
    /* --------------------------------------------------------------- */
    /*    Params  ：  None                                             */
    /* --------------------------------------------------------------- */
    /*    Return  ：   redirect backend/media when add media success  */
    /* --------------------------------------------------------------- */
    /*    Warning ：                                                     */
    /* --------------------------------------------------------------- */
    /*    Copyright : IFRC                                         */
    /* --------------------------------------------------------------- */
    /*    M001 : New  2012.08.14 (LongNguyen)                             */
    /*     * ************************************************************** */

    function add() {
        //Load Helper
        $this->load->helper('form');
        $categories = NULL;
        $this->data->title = 'Media - Add new';
        // nếu người dùng muốn add hoặc edit
        if ($this->input->post('ok', TRUE)) {
            //call function insert data
            $this->data->input = $this->input->post();
            $this->data->input['thumb'] = $this->_thumb($this->data->input['image']);
            $this->_insert();
        }
        //get all category
        $list_category = $this->category->list_category_code('media');
        // data for dropdown list parent category
        if ($list_category != '' && is_array($list_category)) {
            foreach ($list_category as $value) {
                $categories[$value->category_id] = $value->name;
            }
        }
        // set data for list_category
        $this->data->list_category = $categories;
        // load view and set data
        $this->template->write_view('content', 'media/media_form', $this->data);
        // set data for title
        $this->template->write('title', trans('Add', 1));
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
        //Load Helper
        $this->load->helper('form');
        $categories = NULL;
        $this->data->title = 'Media - Edit new';
        // nếu người dùng muốn add hoặc edit
        if ($this->input->post('ok', TRUE)) {
            //call function insert data
            $this->_insert($id);
        }

        //get all category
        $list_category = $this->category->list_category_code('media');
        // data for dropdown list parent category
        if ($list_category != '' && is_array($list_category)) {
            foreach ($list_category as $value) {
                $categories[$value->category_id] = $value->name;
            }
        }
        // set data for list_category
        $this->data->list_category = $categories;
        //Load info media
        $info = $this->media->get_one($id);
        if ($info != FALSE) {
            $this->data->input = $info[0];
            $this->data->input['thumb'] = $this->_thumb($this->data->input['image']);
            if (isset($info['media_description']) && is_array($info['media_description'])) {
                foreach ($info['media_description'] as $value) {
                    $this->data->input['title_' . $value['lang_code']] = $value['title'];
                    $this->data->input['description_' . $value['lang_code']] = $value['description'];
                }
            }
        }
        // load view and set data
        $this->template->write_view('content', 'media/media_form', $this->data);
        // set data for title
        $this->template->write('title', trans('Edit', 1));
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
            $response = $this->media->delete($this->input->post('id'));
            $this->output->set_output($response);
        }
    }

    /*     * ************************************************************** */
    /*    Name ： _insert                                                 */
    /* --------------------------------------------------------------- */
    /*    Description  ：  this function will be called by method
     *                   add, edit                                   */
    /* --------------------------------------------------------------- */
    /*    Params  ：  mixed(int) $id (id of advertise)                 */
    /* --------------------------------------------------------------- */
    /*    Return  ： TRUE of FALSE                                    */
    /* --------------------------------------------------------------- */
    /*    Warning ：                                                                                                    */
    /* --------------------------------------------------------------- */
    /*    Copyright : W3Team                                         */
    /* --------------------------------------------------------------- */
    /*    M001 : New  2012.08.14 (LongNguyen)                       */
    /*     * ************************************************************** */

    private function _insert($id = null) {
        //Load Class Validation
        $this->load->library('form_validation');
        $arrMedia = array();

        if (isset($this->data->list_language) && is_array($this->data->list_language)):
            foreach ($this->data->list_language as $value) :
                if ($value['code'] == $this->data->default_language['code']) {
                    $this->form_validation->set_rules('title_' . $value['code'], 'Title', 'required');
                }
            endforeach;
        endif;
        //Nếu Validation Ok
        if ($this->form_validation->run()) {
            $arrMedia['media'] = array(
                'category_id' => (int) $this->input->post('category_id'),
                'status' => (int) $this->input->post('status'),
                'image' => $this->input->post('image'),
                'type' => $this->input->post('type'),
                'link' => $this->input->post('link'),
                'sort_order' => (int) $this->input->post('sort_order')
            );
            if (isset($this->data->list_language) && is_array($this->data->list_language)):
                foreach ($this->data->list_language as $value) :
                    $arrMedia['media_des'][$value['code']] = array(
                        'lang_code' => $value['code'],
                        'title' => strip_tags($this->input->post('title_' . $value['code'])),
                        'description' => $this->input->post('description_' . $value['code'])
                    );
                endforeach;
            endif;
            ////add action call here
            if ($id == NULL) {
                if ($this->media->add($arrMedia, $this->data->list_language)) {
                    redirect(admin_url() . 'media');
                } else {
                    $this->data->error = 'insert error';
                }
            }
            //edit action call here
            else {
                if ($this->media->edit($arrMedia, $id, $this->data->list_language)) {
                    redirect(admin_url() . 'media');
                } else {
                    $this->data->error = 'edit error';
                }
            }
        } else {
            // set error message
            $this->data->error = validation_errors();
        }
    }

    function chang_active() {
        $this->output->enable_profiler(FALSE);
        if ($this->input->is_ajax_request()) {
            echo $this->media->change_active($this->input->post('id'), $this->input->post('text'));
        }
    }

    function test() {

    }

}