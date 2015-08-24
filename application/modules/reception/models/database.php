<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Database extends CI_Model {
	
	function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
	
	 function insert_entry($table, $items)
    {
     $this->db->insert($table, $items);
    }
	
	 function select_entries_where($table, $where, $items, $order)
    {
		$this->db->select($items);
		$this->db->from($table);
        $this->db->where($where);
		$this->db->order_by($order, "asc"); 
		
		$query = $this->db->get();
		
		return $query->result();
    }
	
	 function select_entries($table, $items, $order)
    {
		$this->db->select($items);
		$this->db->from($table);
		$this->db->order_by($order, "asc"); 
		
		$query = $this->db->get();
		
		return $query->result();
    }
	
	 function update_entry($table, $items, $key)
    {
		$this->db->where($table."_id", $key);
        $this->db->update($table, $items);
    }
	
	 function update_entry2($table, $key, $key_value, $items)
    {
		$this->db->where($key, $key_value);
        $this->db->update($table, $items);
    }
}

?>