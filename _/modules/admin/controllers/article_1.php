<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/* * ****************************************************************** */
/*     Client  Name  :  IFRC                                      */
/*     Project Name  :  cms v3.0                                     */
/*     Program Name  :  article.php                                      */
/*     Entry Server  :                                               */
/*     Called By     :  System                                       */
/*     Notice        :  File Code is utf-8                           */
/*     Copyright     :  IFRC                                         */
/* ------------------------------------------------------------------- */
/*     Comment       :  controller article                             */
/* ------------------------------------------------------------------- */
/*     History       :                                               */
/* ------------------------------------------------------------------- */
/*     Version V001  :  2012.08.14 (LongNguyen)        New Create      */
/* * ****************************************************************** */

class Article extends Admin {

    protected $data;

    function __construct() {
        parent::__construct();
        // load model category

        $this->load->model('Category_model', 'category');
        $this->load->model('Article_model', 'article');
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
        $list_category = $this->category->list_category();
        // data for dropdown list category
        $categories = array();
        if (isset($list_category) && is_array($list_category)) {
            foreach ($list_category as $value) {
                $categories[$value->category_id] = $value->name;
            }
        }
        $this->data->title = 'List articles';
        $this->data->list_category = $categories;
        $this->template->write_view('content', 'article/article_list', $this->data);
        $this->template->write('title', 'Articles ');
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

    function listData() {
        if ($this->input->is_ajax_request()) {
            $data = array();
            $id = '';
            if ($this->input->post('id')) {
                $id = $this->input->post('id');
            }
            if ($id != '') {
                $this->data->list_article = $this->article->getAllArticleByCate($id);
            } else {
                $this->data->list_article = $this->article->list_article();
            }
            if (isset($this->data->list_article) == TRUE && $this->data->list_article != '' && is_array($this->data->list_article) == TRUE) {
                foreach ($this->data->list_article as $key => $value) {
                    $value->thumb = $this->_thumb($value->image);
                    $data[$key] = $value;
                }
            }
            $this->data->list_article = $data;
            unset($data);


            if ((isset($this->data->list_article) == TRUE) && ($this->data->list_article != '') && (is_array($this->data->list_article) == TRUE)) {
                $aaData = array();
                foreach ($this->data->list_article as $key => $value) {
                    $aaData[$key][] = $value->article_id;
                    $aaData[$key][] = $value->title;
                    $aaData[$key][] = $value->name;
                    $aaData[$key][] = $value->sort_order;
                    $aaData[$key][] = $value->status == 1 ? 'Enable' : 'Disable';
                    $aaData[$key][] = '<a class="fancybox" style="display: block;width: 35px" href="' . (isset($value->image) ? base_url() . $value->image : base_url() . 'assets/images/no-image.jpg') . '" title="' . $value->name . '">
                                        <img class="thumbnails " src="' . (isset($value->thumb) ? base_url() . $value->thumb : base_url() . 'assets/images/no-image.jpg') . '" alt="" /></a>';

                    $aaData[$key][] = '<ul class="keywords" style="text-align: center;"><li class="green-keyword"><a title="Edit" class="with-tip" href="' . admin_url() . 'article/edit/' . $value->article_id . '">Edit</a></li>
                                   <li class="red_fx_keyword"><a title="Delete" class="with-tip action-delete ' . ($value->article_id == 0 ? 'is_admin' : '') . '" article_id="' . $value->article_id . '" href="#">Delete</a></li></ul>';
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
    /*    Description  ： add new article                             */
    /* --------------------------------------------------------------- */
    /*    Params  ：  None                                             */
    /* --------------------------------------------------------------- */
    /*    Return  ：   redirect backend/article when add article success  */
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
        $this->data->title = 'Articles - Add new';
        // nếu người dùng muốn add hoặc edit
        if ($this->input->post('ok', TRUE)) {
            //call function insert data
            $this->_insert();
        }

        //get all category
        $list_category = $this->category->list_category();
        // data for dropdown list parent category
        if ($list_category != '' && is_array($list_category)) {
            foreach ($list_category as $value) {
                $categories[$value->category_id] = $value->name;
            }
        }
        // set data for list_category
        $this->data->list_category = $categories;
        // load view and set data
        $this->template->write_view('content', 'article/article_form', $this->data);
        // set data for title
        $this->template->write('title', 'Add new  ');
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
        $this->data->title = 'Articles - Edit';
        // nếu người dùng muốn add hoặc edit
        if ($this->input->post('ok', TRUE)) {
            //call function insert data
            $this->_insert($id);
        }

        //get all category
        $list_category = $this->category->list_category();
        // data for dropdown list parent category
        if ($list_category != '' && is_array($list_category)) {
            foreach ($list_category as $value) {
                $categories[$value->category_id] = $value->name;
            }
        }
        // set data for list_category
        $this->data->list_category = $categories;
        //Load info Article
        $info = $this->article->get_one($id);
        if ($info != FALSE) {
            $this->data->input = $info[0];
            $this->data->input['thumb'] = $this->_thumb($this->data->input['image']);
            if (isset($info['article_description']) && is_array($info['article_description'])) {
                foreach ($info['article_description'] as $value) {
                    $this->data->input['title_' . $value['lang_code']] = $value['title'];
                    $this->data->input['description_' . $value['lang_code']] = $value['description'];
                    $this->data->input['long_description_' . $value['lang_code']] = $value['long_description'];
                    $this->data->input['meta_description_' . $value['lang_code']] = $value['meta_description'];
                    $this->data->input['meta_keyword_' . $value['lang_code']] = $value['meta_keyword'];
                }
            }
        }
        // load view and set data
        $this->template->write_view('content', 'article/article_form', $this->data);
        // set data for title
        $this->template->write('title', 'Edit Article ');
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
            $response = $this->article->delete($this->input->post('id'));
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
    /*    Warning ：                                                     */
    /* --------------------------------------------------------------- */
    /*    Copyright : W3Team                                         */
    /* --------------------------------------------------------------- */
    /*    M001 : New  2012.08.14 (LongNguyen)                       */
    /*     * ************************************************************** */

    private function _insert($id = null) {
        //Load Class Validation
        $this->load->library('form_validation');
        $arrArticle = array();
        // set rule for validation
        $this->form_validation->set_rules('sort_order', 'Sort Order', 'required|is_natural');
        $this->form_validation->set_rules('status', 'Status', 'required|is_natural');
        if (isset($this->data->list_language) && is_array($this->data->list_language)):
            $c = 0;
            foreach ($this->data->list_language as $language_code) :
                if ($c == 0):
                    $this->form_validation->set_rules('title_' . $language_code['code'], 'Title', 'required');
                    $this->form_validation->set_rules('description_' . $language_code['code'], 'Description', 'required|max_length[250]');
                //$this->form_validation->set_rules('long_description_' . $language_code['code'], 'Long Description', 'required');
                //$this->form_validation->set_rules('description_' . $language_code['code'], 'Description', 'required|max_length[250]');
                //$this->form_validation->set_rules('meta_keyword_' . $language_code['code'], 'Meta Keyword', 'required|max_length[250]');
                //$this->form_validation->set_rules('meta_description_' . $language_code['code'], 'Meta Description', 'required|max_length[250]');
                endif;
                $c++;
            endforeach;
        endif;

        //Nếu Validation Ok
        if ($this->form_validation->run()) {
            $arrArticle['article'] = array(
                'category_id' => (int) $this->input->post('category_id'),
                'status' => (int) $this->input->post('status'),
                'image' => $this->input->post('image'),
                'sort_order' => (int) $this->input->post('sort_order'),
                'date_added' => date('Y-m-d H:i:s'),
                'date_modified' => date('Y-m-d H:i:s')
            );
            $this->data->input['image'] = $this->input->post('image');
            $this->data->input['thumb'] = self::_thumb($this->data->input['image']);
            if (isset($this->data->list_language) && is_array($this->data->list_language)):
                foreach ($this->data->list_language as $language_code) :
                    $arrArticle['articledes'][$language_code['code']] = array(
                        'lang_code' => $language_code['code'],
                        'title' => strip_tags($this->input->post('title_' . $language_code['code'])),
                        'description' => $this->input->post('description_' . $language_code['code']),
                        'long_description' => $this->input->post('long_description_' . $language_code['code']),
                        'meta_description' => strip_tags($this->input->post('meta_description_' . $language_code['code'])),
                        'meta_keyword' => strip_tags($this->input->post('meta_keyword_' . $language_code['code']))
                    );
                endforeach;
            endif;
            //add action call here
            if ($id == NULL) {
                if ($this->article->add($arrArticle, $this->data->list_language)) {
                    redirect(admin_url() . 'article');
                } else {
                    $this->data->error = 'insert error';
                }
            }
            //edit action call here
            else {
                if ($this->article->edit($arrArticle, $id, $this->data->list_language)) {
                    redirect(admin_url() . 'article');
                } else {
                    $this->data->error = 'edit error';
                }
            }
        } else {
            // set error message
            $this->data->error = validation_errors();
        }
    }

}