<?php

class Sections_model extends CI_Model 
{	
	/*
	*	Retrieve all sections
	*
	*/
	public function all_sections()
	{
		$this->db->where('section_status = 1');
		$query = $this->db->get('section');
		
		return $query;
	}
	
	/*
	*	Retrieve all parent sections
	*
	*/
	public function all_parent_sections($order = 'section_name')
	{
		$this->db->where('section_status = 1 AND section_parent = 0');
		$this->db->order_by($order, 'ASC');
		$query = $this->db->get('section');
		
		return $query;
	}
	/*
	*	Retrieve all children sections
	*
	*/
	public function all_child_sections($order = 'section_name')
	{
		$this->db->where('section_status = 1 AND section_parent > 0');
		$this->db->order_by($order, 'ASC');
		$query = $this->db->get('section');
		
		return $query;
	}
	
	/*
	*	Retrieve all sections
	*	@param string $table
	* 	@param string $where
	*
	*/
	public function get_all_sections($table, $where, $per_page, $page, $order = 'section_name', $order_method = 'ASC')
	{
		//retrieve all users
		$this->db->from($table);
		$this->db->select('*');
		$this->db->where($where);
		$this->db->order_by($order, $order_method);
		$query = $this->db->get('', $per_page, $page);
		
		return $query;
	}
	
	/*
	*	Add a new section
	*	@param string $image_name
	*
	*/
	public function add_section()
	{
		$data = array(
				'section_name'=>ucwords(strtolower($this->input->post('section_name'))),
				'section_parent'=>$this->input->post('section_parent'),
				'section_position'=>$this->input->post('section_position'),
				'section_status'=>$this->input->post('section_status'),
				'section_icon'=>$this->input->post('section_icon'),
				'created'=>date('Y-m-d H:i:s'),
				'created_by'=>$this->session->userdata('personnel_id'),
				'modified_by'=>$this->session->userdata('personnel_id')
			);
			
		if($this->db->insert('section', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Update an existing section
	*	@param string $image_name
	*	@param int $section_id
	*
	*/
	public function update_section($section_id)
	{
		$data = array(
				'section_name'=>ucwords(strtolower($this->input->post('section_name'))),
				'section_parent'=>$this->input->post('section_parent'),
				'section_position'=>$this->input->post('section_position'),
				'section_status'=>$this->input->post('section_status'),
				'section_icon'=>$this->input->post('section_icon'),
				'modified_by'=>$this->session->userdata('personnel_id')
			);
			
		$this->db->where('section_id', $section_id);
		if($this->db->update('section', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	get a single section's children
	*	@param int $section_id
	*
	*/
	public function get_sub_sections($section_id)
	{
		//retrieve all users
		$this->db->from('section');
		$this->db->select('*');
		$this->db->where('section_parent = '.$section_id);
		$query = $this->db->get();
		
		return $query;
	}
	
	/*
	*	get a single section's details
	*	@param int $section_id
	*
	*/
	public function get_section($section_id)
	{
		//retrieve all users
		$this->db->from('section');
		$this->db->select('*');
		$this->db->where('section_id = '.$section_id);
		$query = $this->db->get();
		
		return $query;
	}
	
	/*
	*	Delete an existing section
	*	@param int $section_id
	*
	*/
	public function delete_section($section_id)
	{
		//delete children
		if($this->db->delete('section', array('section_parent' => $section_id)))
		{
			//delete parent
			if($this->db->delete('section', array('section_id' => $section_id)))
			{
				return TRUE;
			}
			else{
				return FALSE;
			}
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Activate a deactivated section
	*	@param int $section_id
	*
	*/
	public function activate_section($section_id)
	{
		$data = array(
				'section_status' => 1
			);
		$this->db->where('section_id', $section_id);
		

		if($this->db->update('section', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Deactivate an activated section
	*	@param int $section_id
	*
	*/
	public function deactivate_section($section_id)
	{
		$data = array(
				'section_status' => 0
			);
		$this->db->where('section_id', $section_id);
		
		if($this->db->update('section', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
}
?>