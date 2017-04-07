<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/* * ****************************************************************** */
/*     Client  Name  :  IFRC                                      */
/*     Project Name  :  cms v3.0                                     */
/*     Program Name  :  help.php                                      */
/*     Entry Server  :                                               */
/*     Called By     :  System                                       */
/*     Notice        :  File Code is utf-8                           */
/*     Copyright     :  IFRC                                         */
/* ------------------------------------------------------------------- */
/*     Comment       :  controller help                             */
/* ------------------------------------------------------------------- */
/*     History       :                                               */
/* ------------------------------------------------------------------- */
/*     Version V001  :  2012.08.14 (LongNguyen)        New Create      */
/* * ****************************************************************** */

class Help extends Admin {

    function __construct() {
        parent::__construct();
        $this->load->model('Category_model', 'category');
        $this->load->model('Article_model', 'article');
        $this->load->model('Help_model', 'help_model');

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
    /*    M001 : New  2012.09.14 (TuanAnh)                            */
    /*     * ************************************************************** */

    function index() {
        $this->load->helper('form');
        $this->data->title = 'Help';
        self::_left();
        $this->template->write_view('content', 'help/help_list', $this->data);
        $this->template->write('title', 'Help');
        $this->template->render();
    }

    function detail($id) {
        $this->load->helper('form');
        $this->data->id = $id;
        $cate_id = $this->article->getArticleCategory($id);
        $this->data->cate_id = $cate_id[0]['category_id'];
        //$this->data->cate_id = $this->article->get();
        self::_left();
        $this->template->write_view('content', 'help/help_detail', $this->data);
        $this->template->write('title', 'Index Handbook');
        $this->template->render();
    }

    private function _left() {
        $list_article = array();
        $lang_code = $this->data->default_language;
        $this->data->list_cate = $this->category->list_category_code('help', $lang_code);
        $lang_code_curent = $this->session->userdata('curent_language');
        $this->data->list_cate_curent_lang = $this->category->list_category_code('help', $lang_code_curent);
        $list_cates = array();
        $list_cates_curent = array();
        if ($this->data->list_cate != NULL && is_array($this->data->list_cate) == TRUE && count($this->data->list_cate) > 0) {
            foreach ($this->data->list_cate as $key => $value) {
                $list_cates[$value->category_id] = $value;
            }
        }
        if ($this->data->list_cate_curent_lang != NULL && is_array($this->data->list_cate_curent_lang) == TRUE && count($this->data->list_cate_curent_lang) > 0) {
            foreach ($this->data->list_cate_curent_lang as $key => $value) {
                $list_cates_curent[$value->category_id] = $value;
            }
        }

        foreach ($list_cates as $key => $value) {
            if (isset($list_cates_curent[$key]) == true) {
                if ($list_cates_curent[$key]->name != '') {
                    $list_cates[$key]->name = $list_cates_curent[$key]->name;
                }
            }
        }
        $this->data->list_cate = $list_cates;
        $list_article_curent = array();
        $list_article_curent = NULL;
        if (isset($list_cates) && is_array($list_cates)):
            foreach ($list_cates as $key => $value) {
                $list_article[$value->category_code] = $this->article->getAllArticleByCateParentCode($value->category_code, $lang_code);
                if (count($this->article->getAllArticleByCateParentCode($value->category_code, $lang_code_curent)) > 0) {
                    $list_article_curent[$value->category_code] = $this->article->getAllArticleByCateParentCode($value->category_code, $lang_code_curent);
                } else {
                    $list_article_curent[$value->category_code] = $this->article->getAllArticleByCateParentCode($value->category_code, $lang_code);
                }
            }
        endif;

        $list_article = replaceValueNull($list_article_curent, $list_article);

        $this->data->article = $list_article;
    }

}