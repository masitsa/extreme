<?php

class Tutorial_model extends CI_Model
{
	public function selected_tutorial_section($section_id)
	{
	//retrieve all tutorials
	$this->db->from('tutorial');
	$this->db->select('*');
	$this->db->where('section_id = '.$section_id);
	$query = $this->db->get();
	
	return $query;
	}
	
	public function get_all_tutorials($table, $where, $per_page, $page)
	{
		//retrieve all tutorials
		$this->db->from($table);
		$this->db->select('*');
		$this->db->where($where);
		$query = $this->db->get('', $per_page, $page);
		
		return $query;
	}
	public function count_tutorials()
	{
	}
	public function get_selected_section($section_id)
	{
		$this->db->from('section, tutorial');
		$this->db->select('section.section_id, section.section_name, tutorial.*');
		$this->db->where('tutorial.status = 1 AND section_parent = 133 AND tutorial.section_id = section.section_id');
		$query = $this->db->get();
		
		return $query;
	}
	
	public function get_section($tutorial_id)
	{
		$this->db->from('section, tutorial');
		$this->db->select('section.section_id, section.section_name, tutorial.tutorial_id, tutorial.tutorial_name');
		$this->db->where('section_parent = 133 AND section.section_id = tutorial.section_id AND tutorial.tutorial_id ='.$tutorial_id);
		$query = $this->db->get();
		
		return $query;
	}
	
}