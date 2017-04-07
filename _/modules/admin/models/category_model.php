<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class category_model extends CI_Model {

    public $table = 'category';
    public $category_description = 'category_description';

    function __construct() {
        parent::__construct();
    }

    function list_category($parentID = '', $stop = "", $data = null, $space = "") {
        if ($parentID != '')
            $this->db->where('parent_id', $parentID);
        else
            $this->db->where('parent_id', 0);

        if ($stop != '') {
            $this->db->where($this->table . '.category_id !=', $stop);
        }

        $lang_code = $this->session->userdata('curent_language');
        $lang_code = $lang_code['code'];
        $this->db->where('lang_code', $lang_code);
        $this->db->from($this->table);
        $this->db->join($this->category_description, $this->table . '.category_id = ' . $this->category_description . '.category_id');
        $this->db->order_by("category.category_id", "desc");
        $query = $this->db->get();
        $rows = $query->result();
        if (isset($rows) == TRUE && is_array($rows) == TRUE) {
            foreach ($rows as $value) {
                $arr = (object) array();
                $arr->category_id = $value->category_id;
                if ($value->parent_id == 0) {
                    $arr->name = $space . '<strong>' . $value->name . '</strong>';
                } else {
                    $arr->name = $space . $value->name;
                }
                $arr->sort_order = $value->sort_order;
                $arr->image = $value->image;
                $arr->status = $value->status;
                $arr->parent_id = $value->parent_id;
                $data[] = $arr;
                $data = $this->list_category($value->category_id, $stop, $data, $space . '>> ');
            }
        }
        return $data;
    }

    function list_category_fix($parentID = '', $stop = "", $data = null, $space = "") {
        if ($parentID == '')
            $parentID = 0;

        if ($stop != '') {
            $stop = 'category_id !=' . $stop;
        }

        $lang_code = $this->session->userdata('curent_language');
        $lang_code = $lang_code['code'];

        $sql = "SELECT c.category_id, cd.`name`, c.sort_order, c.`status`, c.parent_id, c.image, c.category_code
                FROM category c, category_description cd
                WHERE c.category_id = cd.category_id
                AND c.parent_id = '{$parentID}'
                AND cd.lang_code = '{$lang_code}'
                ORDER BY c.category_id;";
        $rows = $this->db->query($sql)->result();

        $lang_code_default = $this->session->userdata('default_language');
        $lang_code_default = $lang_code_default['code'];

        $sql_2 = "SELECT c.category_id, cd.`name`, c.sort_order, c.`status`, c.parent_id, c.image, c.category_code
                FROM category c, category_description cd
                WHERE c.category_id = cd.category_id
                AND c.parent_id = '{$parentID}'
                AND cd.lang_code = '{$lang_code_default}'
                ORDER BY c.category_id;";
        $rows_2 = $this->db->query($sql_2)->result();

        if (isset($rows_2) == TRUE && is_array($rows_2) == TRUE) {
            foreach ($rows_2 as $key => $value) {
                $arr = (object) array();
                $arr->category_id = $value->category_id;
                if (isset($rows[$key]->name) == TRUE && $rows[$key]->name != '')
                    $value->name = $rows[$key]->name;
                if ($value->parent_id == 0) {
                    $arr->name = $space . '<strong>' . $value->name . '</strong>';
                } else {
                    $arr->name = $space . $value->name;
                }
                $arr->category_code = $value->category_code;
                $arr->sort_order = $value->sort_order;
                $arr->image = $value->image;
                $arr->status = $value->status;
                $arr->parent_id = $value->parent_id;
                $data[] = $arr;
                $data = $this->list_category_fix($value->category_id, $stop, $data, $space . '>> ');
            }
        }
        return $data;
    }

    function list_category_code_fix($parentCode = NULL, $stop = "", $data = null, $space = "") {
        if ($parentCode != NULL)
            $parentCode = " AND c.category_code = '" . $parentCode . "'";

        if ($stop != '') {
            $stop = 'category_id !=' . $stop;
        }

        $lang_code = $this->session->userdata('curent_language');
        $lang_code = $lang_code['code'];

        $sql = "SELECT c.category_id, cd.`name`, c.sort_order, c.`status`, c.parent_id, c.image, c.category_code
                FROM category c, category_description cd
                WHERE c.category_id = cd.category_id";
        $sql .= $parentCode;
        $sql .= " AND cd.lang_code = '{$lang_code}'
                ORDER BY c.category_id;";

        $rows = $this->db->query($sql)->result();

        $lang_code_default = $this->session->userdata('default_language');
        $lang_code_default = $lang_code_default['code'];

        $sql_2 = "SELECT c.category_id, cd.`name`, c.sort_order, c.`status`, c.parent_id, c.image, c.category_code
                FROM category c, category_description cd
                WHERE c.category_id = cd.category_id";
        $sql_2 .= $parentCode;
        $sql_2 .= " AND cd.lang_code = '{$lang_code_default}'
                ORDER BY c.category_id;";
        $rows_2 = $this->db->query($sql_2)->result();

        if (isset($rows_2) == TRUE && is_array($rows_2) == TRUE) {
            foreach ($rows_2 as $key => $value) {
                $arr = (object) array();
                $arr->category_id = $value->category_id;
                if (isset($rows[$key]->name) == TRUE && $rows[$key]->name != '')
                    $value->name = $rows[$key]->name;
                if ($value->parent_id == 0) {
                    $arr->name = $space . '<strong>' . $value->name . '</strong>';
                } else {
                    $arr->name = $space . $value->name;
                }
                $arr->category_code = $value->category_code;
                $arr->sort_order = $value->sort_order;
                $arr->image = $value->image;
                $arr->status = $value->status;
                $arr->parent_id = $value->parent_id;
                $data[] = $arr;
                $data = $this->list_category_code_fix($value->category_id, $stop, $data, $space . '>> ');
            }
        }
        return $data;
    }

    /* TUAN  ANH get cate theo code */

    function list_category_code($parentCode = null, $lang_code = null, $stop = "", $data = null, $space = "") {
        if ($parentCode != NULL) {
            $this->db->where('category.category_code', $parentCode);
        }
        $query = $this->db->get('category');
        $rows = $query->result();
        $arrCategoryid = array();
        foreach ($rows as $item) {
            $arrCategoryid = $item->category_id;
        }
        if ($arrCategoryid)
            $this->db->where('parent_id', $arrCategoryid);
        else
            $this->db->where('parent_id', 0);

        if ($stop) {
            $this->db->where($this->table . '.category_id !=', $stop);
        }
        if ($lang_code == NULL):
            $lang_code = $this->session->userdata('curent_language');
        endif;
        $lang_code_default = $this->session->userdata('default_language');
        $lang_code_default = $lang_code_default['code'];
        $this->db->where('lang_code', $lang_code['code']);
        $this->db->from($this->table);
        $this->db->join($this->category_description, $this->table . '.category_id = ' . $this->category_description . '.category_id');
        $this->db->order_by("category.category_id", "desc");
        $query = $this->db->get();
        $rows = $query->result();
        if ($rows) {
            foreach ($rows as $value) {
                $arr = (object) array();
                $arr->category_id = $value->category_id;
                if ($value->parent_id == 0) {
                    $arr->name = $space . '<strong>' . $value->name . '</strong>';
                } else {
                    $arr->name = $space . $value->name;
                }
                $arr->category_code = $value->category_code;
                $arr->sort_order = $value->sort_order;
                $arr->image = $value->image;
                $arr->status = $value->status;
                $arr->parent_id = $value->parent_id;
                $data[] = $arr;
                unset($arr);
                $data = $this->list_category($value->category_id, $stop, $data, $space . '>> ');
            }
        }
        return $data;
    }

    /* -------------------------- */

    function add($data = NULL, $list_language = NULL) {
        if (is_array($data) == TRUE && is_array($list_language) == TRUE) {
            $data_category = array(
                'category_code' => $data['category_code'],
                'image' => isset($data['image']) == TRUE ? $data['image'] : '',
                'sort_order' => $data['sort_order'],
                'parent_id' => $data['parent_id'],
                'status' => $data['status'],
                'date_added' => date('Y-m-d H:i:s')
            );
            $this->db->trans_begin();
            $this->db->insert($this->table, $data_category);
            $category_id = $this->db->insert_id();
            if ($category_id != FALSE) {
                foreach ($list_language as $value) {
                    $data_category_description = array(
                        'category_id' => $category_id,
                        'lang_code' => $value['code'],
                        'name' => $data['name_' . $value['code']],
                        'description' => $data['description_' . $value['code']],
                        'meta_description' => $data['meta_description_' . $value['code']],
                        'meta_keyword' => $data['meta_keyword_' . $value['code']]
                    );
                    $this->db->insert($this->category_description, $data_category_description);
                    unset($data_category_description);
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
// return true;
    }

    function edit($data = NULL, $list_language = NULL, $id = NULL) {
        if (is_array($data) == TRUE && is_array($list_language) == TRUE && is_numeric($id) == TRUE) {
            $data_category = array(
                'category_code' => $data['category_code'],
                'image' => isset($data['image']) == TRUE ? $data['image'] : '',
                'sort_order' => $data['sort_order'],
                'parent_id' => $data['parent_id'],
                'status' => $data['status'],
                'date_modified' => date('Y-m-d H:i:s')
            );
            $this->db->trans_begin();
            $this->db->update($this->table, $data_category, array('category_id' => $id));
            foreach ($list_language as $value) {
                $data_category_description = array(
                    'category_id' => $id,
                    'lang_code' => $value['code'],
                    'name' => $data['name_' . $value['code']],
                    'description' => $data['description_' . $value['code']],
                    'meta_description' => $data['meta_description_' . $value['code']],
                    'meta_keyword' => $data['meta_keyword_' . $value['code']]
                );
                if (self::_check_exist($id, $value['code']) == FALSE) {
                    $this->db->insert($this->category_description, $data_category_description);
                } else {
                    unset($data_category_description['category_id']);
                    $this->db->update($this->category_description, $data_category_description, array('category_id' => $id, 'lang_code' => $value['code']));
                }
                unset($data_category_description);
            }
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return FALSE;
            } else {
                $this->db->trans_commit();
            }
            return TRUE;
        }
    }

    function delete($id) {
        if ($this->list_category($id))
            return false;
        else {
            $this->db->where('category_id', $id);
            $this->db->delete($this->table);
            $this->db->where('category_id', $id);
            $this->db->delete($this->category_description);
            return true;
        }
    }

    public function change_active($id = NULL, $text = NULL) {
        if (!is_numeric($id)) {
            return FALSE;
        }
        $sql = "UPDATE `category`
                SET `category`.`status` = IF (`category`.`status` = 1, 0, 1)
                WHERE `category`.`category_id` = '{$id}'";
        $this->db->query($sql);
        $text = ($text == "Enable") ? "Disable" : "Enable";
        return $text;
    }

    public function _check_exist($id = NULL, $lang_code = NULL) {
        if (!is_numeric($id)) {
            return FALSE;
        }
        $this->db->where('category_id', $id);
        $this->db->where('lang_code', $lang_code);
        $query = $this->db->get($this->category_description);
        if ($query->num_rows() > 0)
            return TRUE;
        else
            return FALSE;
    }

    /*     * ************************************************************** */
    /*    Name ： get_one                                                */
    /* --------------------------------------------------------------- */
    /*    Description  ：  lấy ra thông tin cate và cate décription      */
    /*                    theo ID của cate nếu $lang_code = NULL        */
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
        $this->db->where('category_id', $id);
        $query = $this->db->get($this->table);
        $data = $query->result_array();

// get description
        $this->db->where('category_id', $id);
        if ($lang_code != NULL) {
            $this->db->where('lang_code', $lang_code);
        }
        $query_des = $this->db->get($this->category_description);
        $data['category_description'] = $query_des->result_array();
        return $data;
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

    function find($parent_id = NULL, $lang_code = NULL, $status = NULL) {
        if ($parent_id == NULL) {
            return FALSE;
        }
        if ($lang_code == NULL):
            $lang_code = $this->session->userdata('curent_language');
        endif;
        $this->db->where('parent_id', $parent_id);
        $this->db->where('lang_code', $lang_code['code']);
        if ($status != NULL) {
            $this->db->where('status', $status);
        }
        $this->db->from($this->table);
        $this->db->join($this->category_description, $this->table . '.category_id = ' . $this->category_description . '.category_id');
        $query = $this->db->get();
        $rows = $query->result();

        if ($rows) {
            $arr = NULL;
            foreach ($rows as $key => $value) {
                $arr->category_id = $value->category_id;
                $data[$key] = $value;
                $data[$key]->sub_cate = $this->list_category($value->category_id, $lang_code);
            }
        }
        return $data;
    }

    public function get_name_article($id = NULL, $lang_code = NULL) {
        if (!is_numeric($id)) {
            return FALSE;
        }
        $sql = "SELECT category_description.`name`
                FROM category_description, article
                WHERE article.category_id = category_description.category_id
                AND article.article_id = '{$id}'
                AND category_description.lang_code = '{$lang_code}' LIMIT 1";
        $name = $this->db->query($sql)->result_array();
        if (is_array($name) == TRUE) {
            foreach ($name as $value) {
                if ($value['name'] == '') {
                    $sql = "SELECT category_description.`name`
                            FROM category_description, article
                            WHERE article.category_id = category_description.category_id
                            AND article.article_id = '{$id}'
                            AND category_description.lang_code = '{$lang_code}' LIMIT 1";
                    $name = $this->db->query($sql)->result_array();
                }
            }
        }
        return $name;
    }

}