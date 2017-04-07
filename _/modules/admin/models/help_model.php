<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Help_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

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

    function get_one($id = null, $lang_code = NULL, $status = NULL) {
        if ($id == FALSE):
            return FALSE;
        endif;
        if ($status != NULL) {
            $this->db->where('status', $status);
        }
        $query = $this->db->get_where($this->table, array('article_id' => $id));
        $data = $query->result_array();
        $lang_code = $this->session->userdata('curent_language');
        $lang_code = $lang_code['code'];
        // get description
        if ($data != FALSE) {
            $this->db->where('article_id', $id);
            if ($lang_code != NULL) {
                $this->db->where('lang_code', $lang_code);
            }

            $query_des = $this->db->get('article_desciption');
            $data['article_description'] = $query_des->result_array();
        }
        return $data;
    }

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
        $this->db->where_in('article' . '.category_id', $arrCategoryid);
        $lang_code = $this->session->userdata('curent_language');
        $lang_code = $lang_code['code'];
        $this->db->where($this->article_desciption . '.lang_code', $lang_code);
        $this->db->where('category_description.lang_code', $lang_code);
        if ($status != NULL) {
            $this->db->where('status', $status);
        }
        $this->db->from('article');
        $this->db->join($this->article_desciption, 'article' . '.article_id = ' . $this->article_desciption . '.article_id');
        $this->db->join('category_description', 'article' . '.category_id = category_description.category_id', 'left');
        $this->db->order_by("article.sort_order", "ASC");
        $query = $this->db->get();
        $rows = $query->result();

        $data = NULL;
        if ($rows) {
            $arr = NULL;
            foreach ($rows as $value) {
                $arr->category_id = $value->category_id;
                $arr->article_id = $value->article_id;
                $data[] = $value;
            }
        }
        return $data;
    }

}
