<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Page_model extends CI_Model {

    public $table = 'page';
    public $page_description = 'page_description';

    function __construct() {
        parent::__construct();
    }

    public function find($id=''){
        if(is_numeric($id)){
            $this->db->where('page_id', $id);
        }
        $this->db->order_by('`page`.`id`', 'DESC');
        $query = $this->db->get($this->table);
        return $query->result_array();
    }

    public function getPageByCate($id=''){
        if(is_numeric($id)){
            $this->db->where('category_id', $id);
        }
        $query = $this->db->get($this->table);
        return $query->result_array();
    }

    public function addPage($data, $list_language = NULL){
        $info = array(
                    'name' => htmlspecialchars($data['name']),
                    'layout_id' => $data['layout_id'],
                    'code' => htmlspecialchars(strtolower(str_replace(' ', '-', $data['name']))),
                    'value' => htmlspecialchars($data['value']),
            );
        $this->db->trans_begin();
        $this->db->insert($this->table, $info);
        $id = $this->db->insert_id();
        if(isset($list_language) && is_array($list_language)){
            foreach($list_language as $value){
                $info_des = array(
                                'page_id' => $id,
                                'lang_code' => $value['code'],
                                'title' => htmlspecialchars($data['title'][$value['code']]),
                                'description' => htmlspecialchars($data['description'][$value['code']]),
                                'meta_description' => htmlspecialchars($data['meta_description'][$value['code']]),
                                'meta_keyword' => htmlspecialchars($data['meta_keyword'][$value['code']]),
                    );
                $this->db->insert($this->page_description, $info_des);
                unset($info_des);
            }
        }
        if($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return FALSE;
        }else{
            $this->db->trans_commit();
        }
    }

    function get_one($id = NULL, $lang_code = NULL) {

        if ($id == NULL):
            return FALSE;
        endif;
        $this->db->where('id', $id);
        $query = $this->db->get($this->table);
        $data = $query->result_array();

        // get description
        $this->db->where('page_id', $id);
        if ($lang_code != NULL) {
            $this->db->where('lang_code', $lang_code);
        }
        $query_des = $this->db->get($this->page_description);
        $data['page_description'] = $query_des->result_array();
        return $data;
    }

    function editPage($data = NULL, $list_language = NULL, $id = NULL) {
        if (is_array($data) == TRUE && is_array($list_language) == TRUE && is_numeric($id) == TRUE) {
            $info = array(
                        'name' => htmlspecialchars($data['name']),
                        'layout_id' => $data['layout_id'],
                        'code' => htmlspecialchars(strtolower(str_replace(' ', '-', $data['name']))),
                        'value' => htmlspecialchars($data['value']),
                );
            $this->db->trans_begin();
            $this->db->update($this->table, $info, array('id' => $id));
            if(isset($list_language) && is_array($list_language)){
                foreach($list_language as $value){
                    $info_des = array(
                                    'page_id' => $id,
                                    'lang_code' => $value['code'],
                                    'title' => htmlspecialchars($data['title'][$value['code']]),
                                    'description' => htmlspecialchars($data['description'][$value['code']]),
                                    'meta_description' => htmlspecialchars($data['meta_description'][$value['code']]),
                                    'meta_keyword' => htmlspecialchars($data['meta_keyword'][$value['code']]),
                        );
                    if ($this->_check_exist($id, $value['code']) == FALSE) {
                        $this->db->insert($this->page_des, $info_des);
                    } else {
                        unset($info_des['page_id']);
                        $this->db->update($this->page_description, $info_des, array('page_id' => $id, 'lang_code' => $value['code']));
                    }
                    unset($info_des);
                }
            }
            if($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                return FALSE;
            }else{
                $this->db->trans_commit();
            }
        }
    }

    public function _check_exist($id = NULL, $lang_code = NULL) {
        if (!is_numeric($id)) {
            return FALSE;
        }
        $this->db->where('page_id', $id);
        if($lang_code != NULL){
            $this->db->where('lang_code', $lang_code);
        }
        $query = $this->db->get($this->page_description);
        if ($query->num_rows() > 0)
            return TRUE;
        else
            return FALSE;
    }

    public function delete($id){
        $this->db->delete($this->table, array('id' => $id));
        if($this->_check_exist($id) == TRUE){
            $this->db->delete($this->page_description, array('page_id' => $id));
        }
        return true;
    }
}