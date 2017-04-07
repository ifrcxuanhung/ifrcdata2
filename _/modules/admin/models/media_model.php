<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Media_model extends CI_Model {

    public $table = 'media';
    public $media_desciption = 'media_description';

    function __construct() {
        parent::__construct();
    }

    function list_media($id = NULL, $lang_code = NULL) {
        if ($id != 0) {
            $id = " AND `media`.`category_id` = " . $id . " ";
        } else {
            $id = '';
        }
        if ($lang_code == NULL):
            $lang_code = $this->session->userdata('curent_language');
        endif;
        $sql = "SELECT `media`.`media_id`, `media`.`category_id`, `media`.`sort_order`, `media`.`status`,
                `media`.`image`, `media`.`link`, `media`.`type`,
                `media_description`.`lang_code`, `media_description`.`title`, `media_description`.`description`,
                `category_description`.`name`, `category_description`.`meta_description`, `category_description`.`meta_keyword`
                FROM `media`
                JOIN `media_description` ON `media`.`media_id` = `media_description`.`media_id`
                AND `media_description`.`lang_code` = '{$lang_code['code']}'
                JOIN `category_description` ON `media`.`category_id` = `category_description`.`category_id`
                WHERE `category_description`.`lang_code` = '{$lang_code['code']}'";
        $sql .= $id;
        $sql .= "ORDER BY `media`.`media_id` desc";
        return $this->db->query($sql)->result();
    }

    function add($arrMedia, $list_language) {
        $this->db->trans_begin();
        $this->db->insert($this->table, $arrMedia['media']);
        $media_id = $this->db->insert_id();
        foreach ($list_language as $value) {
            $arrMedia['media_des'][$value['code']]['media_id'] = $media_id;
            $this->db->insert($this->media_desciption, $arrMedia['media_des'][$value['code']]);
        }
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return FALSE;
        } else {
            $this->db->trans_commit();
        }
        return TRUE;
    }

    function edit($arrMedia, $id, $list_language) {
        $this->db->trans_begin();
        $this->db->update($this->table, $arrMedia['media'], array('media_id' => $id));
        foreach ($list_language as $value) {
            if (self::_check_exist($id, $value['code']) == FALSE) {
                $arrMedia['media_des'][$value['code']]['media_id'] = $id;
                $this->db->insert($this->media_desciption, $arrMedia['media_des'][$value['code']]);
            } else {
                $this->db->update($this->media_desciption, $arrMedia['media_des'][$value['code']], array('media_id' => $id, 'lang_code' => $value['code']));
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

    function delete($id) {
        $this->db->trans_begin();
        $this->db->delete($this->table, array('media_id' => $id));
        $this->db->delete($this->media_desciption, array('media_id' => $id));
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return FALSE;
        } else {
            $this->db->trans_commit();
        }
        return TRUE;
    }

    function find($id) {
        $this->db->select('*');
        $this->db->where($this->table . '.media_id', $id);
        $this->db->from($this->table);
        $this->db->join($this->media_desciption, $this->table . '.media_id =' . $this->media_desciption . '.media_id');
        $query = $this->db->get();
        return (array) $query->row();
    }

    function get_one($id = null, $lang_code = NULL, $status = NULL) {
        if ($id == FALSE):
            return FALSE;
        endif;
        if ($status != NULL) {
            $this->db->where('status', $status);
        }
        $query = $this->db->get_where($this->table, array('media_id' => $id));
        $data = $query->result_array();
        // get description
        if ($data != FALSE) {
            $this->db->where('media_id', $id);
            if ($lang_code != NULL) {
                $this->db->where('lang_code', $lang_code);
            }
            $query_des = $this->db->get($this->media_desciption);
            $data['media_description'] = $query_des->result_array();
        }
        return $data;
    }

    public function _check_exist($id = NULL, $lang_code = NULL) {
        if (!is_numeric($id)) {
            return FALSE;
        }
        $this->db->where('media_id', $id);
        $this->db->where('lang_code', $lang_code);
        $query = $this->db->get($this->media_desciption);
        if ($query->num_rows() > 0)
            return TRUE;
        else
            return FALSE;
    }

    public function change_active($id = NULL, $text = NULL) {
        if (!is_numeric($id)) {
            return FALSE;
        }
        $sql = "UPDATE `media`
                SET `media`.`status` = IF (`media`.`status` = 1, 0, 1)
                WHERE `media`.`media_id` = '{$id}'";
        $this->db->query($sql);
        $text = ($text == "Enable") ? "Disable" : "Enable";
        return $text;
    }

}
