<?php
/* 
 * Generated by CRUDigniter v3.2 
 * www.crudigniter.com
 */
 
class Scheme_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    /*
     * Get scheme by scheme_id
     */
    function get_scheme($scheme_id)
    {
        return $this->db->get_where('scheme',array('scheme_id'=>$scheme_id))->row_array();
    }
        
    /*
     * Get all scheme
     */
    function get_all_scheme()
    {
        $this->db->order_by('scheme_id', 'asc');
        return $this->db->get('scheme')->result_array();
    }
    function get_all_disease()
    {
        $this->db->order_by('disease_id', 'asc');
        return $this->db->get('disease')->result_array();
    }

    function add_disease($disease){
        $this->db->insert('disease_coverage',$disease);
        return $this->db->insert_id();
    }
        
    /*
     * function to add new scheme
     */
    function add_scheme($params)
    {
        $this->db->insert('scheme',$params);
        return $this->db->insert_id();
    }
    
    /*
     * function to update scheme
     */
    function update_scheme($scheme_id,$params)
    {
        $this->db->where('scheme_id',$scheme_id);
        return $this->db->update('scheme',$params);
    }
    
    /*
     * function to delete scheme
     */
    function delete_scheme($scheme_id)
    {   $this->db->delete('disease_coverage',array('scheme_id'=>$scheme_id));
        return $this->db->delete('scheme',array('scheme_id'=>$scheme_id));
    }
}
