<?php
/* 
 * Generated by CRUDigniter v3.2 
 * www.crudigniter.com
 */
 
class Scheme_type_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    /*
     * Get scheme_type by type_id
     */
    function get_scheme_type($type_id)
    {
        return $this->db->get_where('scheme_type',array('type_id'=>$type_id))->row_array();
    }
        
    /*
     * Get all scheme_type
     */
    function get_all_scheme_type()
    {
        $this->db->order_by('type_id', 'asc');
        return $this->db->get('scheme_type')->result_array();
    }
        
    /*
     * function to add new scheme_type
     */
    function add_scheme_type($params)
    {
        $this->db->insert('scheme_type',$params);
        return $this->db->insert_id();
    }
    
    /*
     * function to update scheme_type
     */
    function update_scheme_type($type_id,$params)
    {
        $this->db->where('type_id',$type_id);
        return $this->db->update('scheme_type',$params);
    }
    
    /*
     * function to delete scheme_type
     */
    function delete_scheme_type($type_id)
    {
        return $this->db->delete('scheme_type',array('type_id'=>$type_id));
    }
}
