<?php

/**
 * Description of Knowledge base model
 *
 * @author NaYeM
 */
class Kb_model extends MY_Model
{

    public $_table_name;
    public $_order_by;
    public $_primary_key;

    public function get_kb_info($type = null, $id = null, $frontend = null)
    {
        $this->db->select('tbl_knowledgebase.*', FALSE);
        $this->db->select('tbl_kb_category.kb_category_id', FALSE);
        $this->db->from('tbl_knowledgebase');
        $this->db->join('tbl_kb_category', 'tbl_kb_category.kb_category_id = tbl_knowledgebase.kb_category_id', 'left');
        $this->db->where('tbl_kb_category.status', 1);
        $this->db->where('tbl_knowledgebase.status', 1);
        if (!empty($frontend)) {
            $this->db->where('tbl_knowledgebase.for_all', 'No');
        }
        if (!empty($type) && $type == 'articles') {
            $this->db->where('tbl_knowledgebase.kb_id', $id);
        } elseif (!empty($type) && $type == 'category') {
            $this->db->where('tbl_kb_category.kb_category_id', $id);
        }
        $query_result = $this->db->get();
        if (!empty($type) && $type == 'articles') {
            $result = $query_result->row();
        } else {
            $result = $query_result->result();
        }
        return $result;
    }

    function increase_total_view($id)
    {
        $tbl_knowledgebase = $this->db->dbprefix('tbl_knowledgebase');

        $sql = "UPDATE $tbl_knowledgebase
        SET total_view = total_view+1
        WHERE $tbl_knowledgebase.kb_id=$id";

        return $this->db->query($sql);
    }

    function get_suggestions($search, $frontend = null)
    {
        $this->db->select('tbl_knowledgebase.kb_id as value,tbl_knowledgebase.title as label', FALSE);
        $this->db->from('tbl_knowledgebase');
        $this->db->join('tbl_kb_category', 'tbl_kb_category.kb_category_id = tbl_knowledgebase.kb_category_id', 'left');
        $this->db->where('tbl_kb_category.status', 1);
        $this->db->where('tbl_knowledgebase.status', 1);
        if (!empty($frontend)) {
            $this->db->where('tbl_knowledgebase.for_all', 'No');
        }
        $this->db->group_start();
        $this->db->like('tbl_knowledgebase.title', $search);
        $this->db->group_end(); //close bracket

        $this->db->order_by('tbl_knowledgebase.title', 'ASC');
        $this->db->limit(10);

        $query_result = $this->db->get();
        $result = $query_result->result_array();
        return $result;
    }
}
