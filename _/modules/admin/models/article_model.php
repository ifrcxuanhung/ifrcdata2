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
/*     Comment       :  model article                             */
/* ------------------------------------------------------------------- */
/*     History       :                                               */
/* ------------------------------------------------------------------- */
/*     Version V001  :  2012.08.14 (LongNguyen)        New Create      */
/* * ****************************************************************** */

class Article_model extends CI_Model {

    public $table = 'article';
    public $article_desciption = 'article_desciption';
    public $article_option = 'article_option';

    function __construct() {
        parent::__construct();
    }

    /**     * ************************************************************* */
    /*    Name ： list_article                                                */
    /* --------------------------------------------------------------- */
    /*    Description  ： list articles by id (if exist) and language               */
    /* --------------------------------------------------------------- */
    /*    Params  ：  $_POST id                                             */
    /* --------------------------------------------------------------- */
    /*    Return  ：   return article array by language                     */
    /* --------------------------------------------------------------- */
    /*    Warning ：                                                     */
    /* --------------------------------------------------------------- */
    /*    Copyright : IFRC                                         */
    /* --------------------------------------------------------------- */
    /*    M001 : New  2012.08.14 (LongNguyen)                             */
    /*     * ************************************************************** */

    function list_article($id = '', $lang_code = NULL) {
        if ($id != ''):
            where($this->table . '.article_id', $id);
        endif;
        $this->db->select('*');
        $this->db->from($this->table);
        $this->db->join($this->article_desciption, $this->table . '.article_id =' . $this->article_desciption . '.article_id');
        $this->db->join('category_description', $this->table . '.category_id = category_description.category_id');
        if ($lang_code == NULL):
            $lang_code = $this->session->userdata('curent_language');
        endif;
        /* ------------- TUAN ANH --------------- */
        //get all category article
        //to get all article with language default
        $lang_code_default = $this->session->userdata('default_language');
        $this->db->where('article_desciption.lang_code', $lang_code['code']);
        $this->db->where('category_description.lang_code', $lang_code_default['code']);
        $this->db->order_by("article.article_id", "desc");
        $query = $this->db->get();
        return $query->result();
    }

    /**     * ************************************************************* */
    /*    Name ： add                                                */
    /* --------------------------------------------------------------- */
    /*    Description  ： add new category                              */
    /* --------------------------------------------------------------- */
    /*    Params  ：  $arrArticle, $list_language                       */
    /* --------------------------------------------------------------- */
    /*    Return  ：                                                      */
    /* --------------------------------------------------------------- */
    /*    Warning ：                                                     */
    /* --------------------------------------------------------------- */
    /*    Copyright : IFRC                                         */
    /* --------------------------------------------------------------- */
    /*    M001 : New  2012.08.14 (LongNguyen)                             */
    /*     * ************************************************************** */

    function add($arrArticle, $list_language = NULL) {
        $this->db->trans_begin();

        $this->db->insert($this->table, $arrArticle['article']);
        $articleid = $this->db->insert_id();
        foreach ($list_language as $value) {
            $arrArticle['articledes'][$value['code']]['article_id'] = $articleid;
            $this->db->insert($this->article_desciption, $arrArticle['articledes'][$value['code']]);
        }
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return FALSE;
        } else {
            $this->db->trans_commit();
        }
        return TRUE;
    }

    /**     * ************************************************************* */
    /*    Name ： edit                                                */
    /* --------------------------------------------------------------- */
    /*    Description  ： edit a category                              */
    /* --------------------------------------------------------------- */
    /*    Params  ：  $arrArticle, $id, $list_language                       */
    /* --------------------------------------------------------------- */
    /*    Return  ：                                                      */
    /* --------------------------------------------------------------- */
    /*    Warning ：                                                     */
    /* --------------------------------------------------------------- */
    /*    Copyright : IFRC                                         */
    /* --------------------------------------------------------------- */
    /*    M001 : New  2012.08.14 (LongNguyen)                             */
    /*     * ************************************************************** */

    function edit($arrArticle, $id, $list_language) {
        $this->db->trans_begin();
        $this->db->update($this->table, $arrArticle['article'], array('article_id' => $id));
        foreach ($list_language as $value) {
            if (self::_check_exist($id, $value['code']) == FALSE) {
                $arrArticle['articledes'][$value['code']]['article_id'] = $id;
                $this->db->insert($this->article_desciption, $arrArticle['articledes'][$value['code']]);
            } else {
                $this->db->update($this->article_desciption, $arrArticle['articledes'][$value['code']], array('article_id' => $id, 'lang_code' => $value['code']));
            }
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return FALSE;
        } else {
            $this->db->trans_commit();
        }
        return TRUE;
    }

    /**     * ************************************************************* */
    /*    Name ： delete                                                */
    /* --------------------------------------------------------------- */
    /*    Description  ： delete aritcle by art_id                       */
    /* --------------------------------------------------------------- */
    /*    Params  ：  $id                                                */
    /* --------------------------------------------------------------- */
    /*    Return  ：                                                      */
    /* --------------------------------------------------------------- */
    /*    Warning ：                                                     */
    /* --------------------------------------------------------------- */
    /*    Copyright : IFRC                                         */
    /* --------------------------------------------------------------- */
    /*    M001 : New  2012.08.14 (LongNguyen)                             */
    /*     * ************************************************************** */

    function delete($id) {
        $this->db->trans_begin();

        $this->db->delete($this->table, array('article_id' => $id));
        $this->db->delete($this->article_desciption, array('article_id' => $id));

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return FALSE;
        } else {
            $this->db->trans_commit();
        }
        return TRUE;
    }

    public function change_active($id = NULL, $text = NULL) {
        if (!is_numeric($id)) {
            return FALSE;
        }
        $sql = "UPDATE `article`
                SET `article`.`status` = IF (`article`.`status` = 1, 0, 1)
                WHERE `article`.`article_id` = '{$id}'";
        $this->db->query($sql);
        $text = ($text == "Enable") ? "Disable" : "Enable";
        return $text;
    }

    /*     * ************************************************************** */
    /*    Name ： find                                                */
    /* --------------------------------------------------------------- */
    /*    Description  ：  lấy ra thông tin các cate  và cate décription      */
    /*                    theo parent ID của cate                            */
    /*                   nếu $lang_code is null thì lang_code = vn              */
    /* --------------------------------------------------------------- */
    /*    Params  ：  $parent_id   $lang_code                                          */
    /* --------------------------------------------------------------- */
    /*    Return  ：  array                                               */
    /* --------------------------------------------------------------- */
    /*    Warning ：                                                  */
    /* --------------------------------------------------------------- */
    /*    Copyright : IFRC                                         */
    /* --------------------------------------------------------------- */
    /*    M001 : New  2012.08.14 (LongNguyen)                            */
    /*     * ************************************************************** */

    function find($categoy_id = NULL, $lang_code = NULL, $status = NULL, $id = NULL) {
        if ($categoy_id == NULL) {
            return FALSE;
        }
        $arrCategoryid = array();
        $this->db->where('parent_id', $categoy_id);
        //$this->db->where('category.lang_code', $lang_code);
        $query = $this->db->get('category');
        $rows = $query->result();
        foreach ($rows as $item) {
            $arrCategoryid[] = $item->category_id;
        }
        $arrCategoryid[] = $categoy_id;

        $this->db->select('article.category_id,article.article_id,article.date_added,title,
            name,article_desciption.lang_code,article.sort_order,article.status,
            article.image,article_desciption.description,article_desciption.long_description,
            article_desciption.meta_description,article_desciption.meta_keyword');
        $this->db->from($this->table);
        $this->db->join($this->article_desciption, $this->table . '.article_id = ' . $this->article_desciption . '.article_id');
        $this->db->join('category_description', $this->table . '.category_id = category_description.category_id', 'left');
        $this->db->where_in($this->table . '.category_id', $arrCategoryid);
        $lang_code = $this->session->userdata('curent_language');
        $lang_code = $lang_code['code'];
        $this->db->where($this->article_desciption . '.lang_code', $lang_code);
        $this->db->where('category_description.lang_code', $lang_code);
        if ($id != NULL) {
            $this->db->where($this->table . '.article_id != ', $id);
        }
        if ($status != NULL) {
            $this->db->where('article.status', $status);
        }
        $this->db->group_by("article.article_id");
        $this->db->order_by("article.article_id", "desc");
        $query = $this->db->get();
        $rows = $query->result();
        $data = NULL;
        if ($rows) {
            $arr = (object) array();
            foreach ($rows as $value) {
                if ($rows) {
                    foreach ($rows as $value) {
                        if ($value->title == '') {
                            $this->db->select('article.category_id,article.article_id,article.date_added,title,
            name,article_desciption.lang_code,article.sort_order,article.status,
            article.image,article_desciption.description,article_desciption.long_description,
            article_desciption.meta_description,article_desciption.meta_keyword, category_description.name');
                            $this->db->where($this->table . '.category_id', $value->category_id);
                            $this->db->where($this->article_desciption . '.article_id', $value->article_id);
                            $lang_code_default = $this->session->userdata('default_language');
                            $lang_code_default = $lang_code_default['code'];
                            $this->db->where($this->article_desciption . '.lang_code', $lang_code_default);
                            $this->db->where('category_description.lang_code', $lang_code_default);
                            if ($status != NULL) {
                                $this->db->where('status', $status);
                            }
                            $this->db->from($this->table);
                            $this->db->join($this->article_desciption, $this->table . '.article_id = ' . $this->article_desciption . '.article_id');
                            $this->db->join('category_description', $this->table . '.category_id = category_description.category_id', 'left');
                            $this->db->order_by("article.sort_order", "ASC");
                            $query = $this->db->get();
                            $vrows = $query->row();
                            if (count($vrows) > 0)
                                $value = $vrows;
                        }
                    }
                }
                $arr->category_id = $value->category_id;
                $arr->article_id = $value->article_id;
                $arr->date_added = $value->date_added;
                $arr->title = $value->title;
                $arr->name = $value->name;
                $arr->lang_code = $value->lang_code;
                $arr->sort_order = $value->sort_order;
                $arr->status = $value->status;
                $arr->image = $value->image;
                $arr->description = $value->description;
                $arr->long_description = $value->long_description;
                $arr->meta_description = $value->meta_description;
                $arr->meta_keyword = $value->meta_keyword;
                $data[] = $arr;
                unset($arr);
            }
        }
        return $data;
    }

    /*     * ************************************************************** */
    /*    Name ： get_one                                                */
    /* --------------------------------------------------------------- */
    /*    Description  ：  lấy ra thông tin ảticle và article décription      */
    /*                    theo ID của article nếu $lang_code = NULL        */
    /*                   thì sẽ trả về all décription language        */
    /*                   nếu $status == NULL thì lấy tất cả          */
    /* --------------------------------------------------------------- */
    /*    Params  ：  $id   $lang_code  $active                                         */
    /* --------------------------------------------------------------- */
    /*    Return  ：  array                                               */
    /* --------------------------------------------------------------- */
    /*    Warning ：                                                  */
    /* --------------------------------------------------------------- */
    /*    Copyright : IFRC                                         */
    /* --------------------------------------------------------------- */
    /*    M001 : New  2012.08.14 (LongNguyen)                            */
    /*     * ************************************************************** */

    function get_one($id = null, $lang_code = NULL, $status = NULL) {
        if ($id == FALSE):
            return FALSE;
        endif;
        if ($status != NULL) {
            $this->db->where('status', $status);
        }
        $query = $this->db->get_where($this->table, array('article_id' => $id));
        $data = $query->result_array();
        // get description
        if ($data != FALSE) {
            $this->db->where('article_id', $id);
            if ($lang_code != NULL) {
                $this->db->where('lang_code', $lang_code);
            }

            $query_des = $this->db->get($this->article_desciption);
            $data['article_description'] = $query_des->result_array();
        }
        return $data;
    }

    /*     * ************************************************************** */
    /*    Name ： get_one                                                */
    /* --------------------------------------------------------------- */
    /*    Description  ：  get detail article with para $id      */
    /* --------------------------------------------------------------- */
    /*    Params  ：  $id   $lang_code  $active                                         */
    /* --------------------------------------------------------------- */
    /*    Return  ：  array                                               */
    /* --------------------------------------------------------------- */
    /*    Warning ：                                                  */
    /* --------------------------------------------------------------- */
    /*    Copyright : IFRC                                         */
    /* --------------------------------------------------------------- */
    /*    M001 : New  2012.09.14 (TuanAnh)                            */
    /*     * ************************************************************** */

    function getArticleDetail($id) {
        $this->db->select('*');
        $this->db->from($this->article_desciption);
        $this->db->where('article_id', $id);
        $lang_code = $this->session->userdata('curent_language');
        $lang_code = $lang_code['code'];
        $this->db->where('lang_code', $lang_code);
        $q = $this->db->get();

        if ($q->num_rows() > 0) {
            foreach ($q->result() as $item) {
                $data[] = $item;
            }
            return $data;
        }
    }

    function getArticleCategory($id) {
        $sql = "SELECT article.category_id
                FROM article
                WHERE article_id = '{$id}'
                LIMIT 1;";
        return $this->db->query($sql)->result_array();
    }

    /**     * ************************************************************* */
    /*    Name ： _check_exist                                                */
    /* --------------------------------------------------------------- */
    /*    Description  ： check article exist                              */
    /* --------------------------------------------------------------- */
    /*    Params  ：  $id, $lang_code                                   */
    /* --------------------------------------------------------------- */
    /*    Return  ：  true if exist or false if not                    */
    /* --------------------------------------------------------------- */
    /*    Warning ：                                                     */
    /* --------------------------------------------------------------- */
    /*    Copyright : IFRC                                         */
    /* --------------------------------------------------------------- */
    /*    M001 : New  2012.08.14 (LongNguyen)                             */
    /*     * ************************************************************** */

    public function _check_exist($id = NULL, $lang_code = NULL) {
        if (!is_numeric($id)) {
            return FALSE;
        }
        $this->db->where('article_id', $id);
        $this->db->where('lang_code', $lang_code);
        $query = $this->db->get($this->article_desciption);
        if ($query->num_rows() > 0)
            return TRUE;
        else
            return FALSE;
    }

    function getAllArticleByCateParent($categoy_id = NULL, $lang_code = NULL, $status = NULL) {
        if ($categoy_id == NULL) {
            return FALSE;
        }
        //get category child id
        $arrCategoryid = array();
        $this->db->where('category.parent_id', $categoy_id);
        //$this->db->where('category.lang_code', $lang_code);
        $query = $this->db->get('category');
        $rows = $query->result();
        foreach ($rows as $item) {
            $arrCategoryid[] = $item->category_id;
        }
        $arrCategoryid[] = $categoy_id;

        //get article
        $this->db->select('article.category_id,article.article_id,article.date_added,title,
            name,article_desciption.lang_code,article.sort_order,article.status,
            article.image,article_desciption.description,article_desciption.long_description,
            article_desciption.meta_description,article_desciption.meta_keyword');
        $this->db->where_in($this->table . '.category_id', $arrCategoryid);
        $lang_code = $this->session->userdata('curent_language');
        $lang_code = $lang_code['code'];
        $this->db->where($this->article_desciption . '.lang_code', $lang_code);
        $this->db->where('category_description.lang_code', $lang_code);
        if ($status != NULL) {
            $this->db->where('status', $status);
        }
        $this->db->from($this->table);
        $this->db->join($this->article_desciption, $this->table . '.article_id = ' . $this->article_desciption . '.article_id');
        $this->db->join('category_description', $this->table . '.category_id = category_description.category_id', 'left');
        $this->db->order_by("article.article_id", "desc");
        $query = $this->db->get();
        $rows = $query->result();

        $data = NULL;
        if ($rows) {
            foreach ($rows as $value) {
                if ($value->title == '') {
                    $this->db->select('article.category_id,article.article_id,article.date_added,title,
            name,article_desciption.lang_code,article.sort_order,article.status,
            article.image,article_desciption.description,article_desciption.long_description,
            article_desciption.meta_description,article_desciption.meta_keyword, category_description.name');
                    $this->db->where($this->table . '.category_id', $value->category_id);
                    $this->db->where($this->article_desciption . '.article_id', $value->article_id);
                    $lang_code_default = $this->session->userdata('default_language');
                    $lang_code_default = $lang_code_default['code'];
                    $this->db->where($this->article_desciption . '.lang_code', $lang_code_default);
                    $this->db->where('category_description.lang_code', $lang_code_default);
                    if ($status != NULL) {
                        $this->db->where('status', $status);
                    }
                    $this->db->from($this->table);
                    $this->db->join($this->article_desciption, $this->table . '.article_id = ' . $this->article_desciption . '.article_id');
                    $this->db->join('category_description', $this->table . '.category_id = category_description.category_id', 'left');
                    $this->db->order_by("article.sort_order", "ASC");
                    $query = $this->db->get();
                    $vrows = $query->row();
                    $data[] = $vrows;
                } else {
                    $data[] = $value;
                }
            }
        }
        return $data;
    }

    /**     * ************************************************************* */
    /*    Name ： getAllArticleByCateParentName                                        */
    /* --------------------------------------------------------------- */
    /*    Description  ： get all article in specified category name call by ajax               */
    /* --------------------------------------------------------------- */
    /*    Params  ：  $_POST cate name // for help                     */
    /* --------------------------------------------------------------- */
    /*    Return  ：   return array data when success, false when fail  */
    /* --------------------------------------------------------------- */
    /*    Warning ：                                                     */
    /* --------------------------------------------------------------- */
    /*    Copyright : IFRC                                         */
    /* --------------------------------------------------------------- */
    /*    M001 : New  2012.09.17 (TuanAnh)                             */
    /*    M002 : edit  2012.10.12 (longnguyen)                             */
    /*     * ************************************************************** */
    function getAllArticleByCateParentCode($category_code = NULL, $lang_code = NULL, $status = NULL) {
        if ($category_code == NULL) {
            return FALSE;
        }
        //get category child id
        $arrCategoryid = array();
        $this->db->where('category.category_code', $category_code);
        //$this->db->where('category.lang_code', $lang_code);
        $query = $this->db->get('category');
        $rows = $query->result();
        foreach ($rows as $item) {
            $arrCategoryid[] = $item->category_id;
        }
        $arrCategoryid[] = $category_code;

        //get article
        $this->db->select('article.category_id,article.article_id,article.date_added,title,
            name,article_desciption.lang_code,article.sort_order,article.status,
            article.image,article_desciption.description,article_desciption.long_description,
            article_desciption.meta_description,article_desciption.meta_keyword, category_description.name');
        $this->db->where_in($this->table . '.category_id', $arrCategoryid);
        if ($lang_code == '') {
            $lang_code = $this->session->userdata('default_language');
        }
        $this->db->where($this->article_desciption . '.lang_code', $lang_code['code']);
        $this->db->where('category_description.lang_code', $lang_code['code']);
        if ($status != NULL) {
            $this->db->where('status', $status);
        }
        $this->db->from($this->table);
        $this->db->join($this->article_desciption, $this->table . '.article_id = ' . $this->article_desciption . '.article_id');
        $this->db->join('category_description', $this->table . '.category_id = category_description.category_id', 'left');
        $this->db->order_by("article.sort_order", "ASC");
        $query = $this->db->get();
        $rows = $query->result();

        $data = array();
        if ($rows) {
            foreach ($rows as $value) {
                if ($value->title == '') {
                    $this->db->select('article.category_id,article.article_id,article.date_added,title,
            name,article_desciption.lang_code,article.sort_order,article.status,
            article.image,article_desciption.description,article_desciption.long_description,
            article_desciption.meta_description,article_desciption.meta_keyword, category_description.name');
                    $this->db->where($this->table . '.category_id', $value->category_id);
                    $this->db->where($this->article_desciption . '.article_id', $value->article_id);
                    $lang_code_default = $this->session->userdata('default_language');
                    $lang_code_default = $lang_code_default['code'];
                    $this->db->where($this->article_desciption . '.lang_code', $lang_code_default);
                    $this->db->where('category_description.lang_code', $lang_code_default);
                    if ($status != NULL) {
                        $this->db->where('status', $status);
                    }
                    $this->db->from($this->table);
                    $this->db->join($this->article_desciption, $this->table . '.article_id = ' . $this->article_desciption . '.article_id');
                    $this->db->join('category_description', $this->table . '.category_id = category_description.category_id', 'left');
                    $this->db->order_by("article.sort_order", "ASC");
                    $query = $this->db->get();
                    $vrows = $query->row();
                    $data[] = $vrows;
                } else {
                    $data[] = $value;
                }
            }
        }
        return $data;
    }

    /**     * ************************************************************* */
    /*    Name ： getAllArticleByCate                                        */
    /* --------------------------------------------------------------- */
    /*    Description  ： get all article in specified category   call by ajax               */
    /* --------------------------------------------------------------- */
    /*    Params  ：  $_POST id                                             */
    /* --------------------------------------------------------------- */
    /*    Return  ：   return array data when success, false when fail  */
    /* --------------------------------------------------------------- */
    /*    Warning ：                                                     */
    /* --------------------------------------------------------------- */
    /*    Copyright : IFRC                                         */
    /* --------------------------------------------------------------- */
    /*    M001 : New  2012.08.22 (Tung)                             */
    /*     * ************************************************************** */

    function getAllArticleByCate($category_id = '', $lang_code = NULL, $status = NULL) {
        if ($category_id == '') {
            return FALSE;
        }
        //get article
        $this->db->select('article.category_id,article.article_id,article.date_added,title,
            name,article_desciption.lang_code,article.sort_order,article.status,
            article.image,article_desciption.description,article_desciption.long_description,
            article_desciption.meta_description,article_desciption.meta_keyword');
        $this->db->where($this->table . '.category_id', $category_id);
        $lang_code = $this->session->userdata('curent_language');
        $lang_code = $lang_code['code'];
        $this->db->where($this->article_desciption . '.lang_code', $lang_code);
        $this->db->where('category_description.lang_code', $lang_code);
        if ($status != NULL) {
            $this->db->where('status', $status);
        }
        $this->db->from($this->table);
        $this->db->join($this->article_desciption, $this->table . '.article_id = ' . $this->article_desciption . '.article_id');
        $this->db->join('category_description', $this->table . '.category_id = category_description.category_id', 'left');
        $this->db->order_by("article.article_id", "desc");
        $query = $this->db->get();
        $rows = $query->result();

        $data = NULL;
        if ($rows) {
            foreach ($rows as $value) {

                if ($value->title == '') {
                    $this->db->select('article.category_id,article.article_id,article.date_added,title,
            name,article_desciption.lang_code,article.sort_order,article.status,
            article.image,article_desciption.description,article_desciption.long_description,
            article_desciption.meta_description,article_desciption.meta_keyword, category_description.name');
                    $this->db->where($this->table . '.category_id', $value->category_id);
                    $this->db->where($this->article_desciption . '.article_id', $value->article_id);
                    $lang_code_default = $this->session->userdata('default_language');
                    $lang_code_default = $lang_code_default['code'];
                    $this->db->where($this->article_desciption . '.lang_code', $lang_code_default);
                    $this->db->where('category_description.lang_code', $lang_code_default);
                    if ($status != NULL) {
                        $this->db->where('status', $status);
                    }
                    $this->db->from($this->table);
                    $this->db->join($this->article_desciption, $this->table . '.article_id = ' . $this->article_desciption . '.article_id');
                    $this->db->join('category_description', $this->table . '.category_id = category_description.category_id', 'left');
                    $this->db->order_by("article.sort_order", "ASC");
                    $query = $this->db->get();
                    $vrows = $query->row();
                    $data[] = $vrows;
                } else {
                    $data[] = $value;
                }
            }
        }
        return $data;
    }

    function count($categoy_id = NULL, $lang_code = NULL, $status = NULL, $id = NULL) {
        if ($categoy_id == NULL) {
            return FALSE;
        }
        $arrCategoryid = array();
        $this->db->where('parent_id', $categoy_id);
        $query = $this->db->get('category');
        $rows = $query->result();
        foreach ($rows as $item) {
            $arrCategoryid[] = $item->category_id;
        }
        $arrCategoryid[] = $categoy_id;

        $this->db->select('article.category_id,article.article_id,article.date_added,title,
            name,article_desciption.lang_code,article.sort_order,article.status,
            article.image,article_desciption.description,article_desciption.long_description,
            article_desciption.meta_description,article_desciption.meta_keyword');
        $this->db->from($this->table);
        $this->db->join($this->article_desciption, $this->table . '.article_id = ' . $this->article_desciption . '.article_id');
        $this->db->join('category_description', $this->table . '.category_id = category_description.category_id', 'left');
        $this->db->where_in($this->table . '.category_id', $arrCategoryid);
        $lang_code = $this->session->userdata('curent_language');
        $lang_code = $lang_code['code'];
        $this->db->where($this->article_desciption . '.lang_code', $lang_code);
        if ($id != NULL) {
            $this->db->where($this->table . '.article_id != ', $id);
        }
        if ($status != NULL) {
            $this->db->where('article.status', $status);
        }
        $this->db->group_by("article.article_id");
        $this->db->order_by("article.article_id", "desc");
        $query = $this->db->get();
        return $query->num_rows();
    }

}
