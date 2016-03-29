<?php

class Items_categories_model extends CI_Model 
{	
	/*
	*	Retrieve all categories
	*
	*/
	public function all_categories()
	{
		$this->db->where('category_status = 1');
		$this->db->order_by('category_name');
		$query = $this->db->get('item_category');
		
		return $query;
	}
	/*
	*	Retrieve latest category
	*
	*/
	public function latest_category()
	{
		$this->db->limit(1);
		$this->db->order_by('created', 'DESC');
		$query = $this->db->get('category');
		
		return $query;
	}
	/*
	*	Retrieve all parent categories
	*
	*/
	public function all_parent_categories()
	{
		$this->db->where('category_status = 1 AND category_parent = 0 AND branch_code = "'.$this->session->userdata('branch_code').'"');
		$this->db->order_by('category_name', 'ASC');
		$query = $this->db->get('category');
		
		return $query;
	}
	/*
	*	Retrieve all children categories
	*
	*/
	public function all_child_categories()
	{
		$this->db->where('category_status = 1 AND category_parent > 0');
		$this->db->order_by('category_name', 'ASC');
		$query = $this->db->get('category');
		
		return $query;
	}
	
	/*
	*	Retrieve all categories
	*	@param string $table
	* 	@param string $where
	*
	*/
	public function get_all_categories($table, $where, $per_page, $page)
	{
		//retrieve all users
		$this->db->from($table);
		$this->db->select('*');
		$this->db->where($where);
		$this->db->order_by('category_name, category_parent');
		$query = $this->db->get('', $per_page, $page);
		
		return $query;
	}
	
	/*
	*	Add a new category
	*	@param string $image_name
	*
	*/
	public function add_category()
	{
		$data = array(
				'category_name'=>ucwords(strtolower($this->input->post('category_name'))),
				'category_parent'=>$this->input->post('category_parent'),
				'category_status'=>$this->input->post('category_status'),
				'created'=>date('Y-m-d H:i:s'),
				'created_by'=>$this->session->userdata('personnel_id'),
				'modified_by'=>$this->session->userdata('personnel_id'),
				'branch_code'=>$this->session->userdata('branch_code')
			);
			
		if($this->db->insert('item_category', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Update an existing category
	*	@param string $image_name
	*	@param int $category_id
	*
	*/
	public function update_category($category_id)
	{
		$data = array(
				'category_name'=>ucwords(strtolower($this->input->post('category_name'))),
				'category_parent'=>$this->input->post('category_parent'),
				'category_status'=>$this->input->post('category_status'),
				'modified_by'=>$this->session->userdata('personnel_id')
			);
			
		$this->db->where('branch_code = "'.$this->session->userdata('branch_code').'"AND item_category_id = '.$category_id);
		if($this->db->update('item_category', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	get a single category's children
	*	@param int $category_id
	*
	*/
	public function get_sub_categories($category_id)
	{
		//retrieve all users
		$this->db->from('item_category');
		$this->db->select('*');
		$this->db->where('category_parent = '.$category_id);
		$query = $this->db->get();
		
		return $query;
	}
	
	/*
	*	get a single category's details
	*	@param int $category_id
	*
	*/
	public function get_category($category_id)
	{
		//retrieve all users
		$this->db->from('item_category');
		$this->db->select('*');
		$this->db->where('item_category_id = '.$category_id);
		$query = $this->db->get();
		
		return $query;
	}
	
	/*
	*	get a single category's details
	*	@param int $category_id
	*
	*/
	public function get_category_by_name($category_name)
	{
		//retrieve all users
		$this->db->from('category');
		$this->db->select('*');
		$this->db->where('category_name = \''.$category_name.'\'');
		$query = $this->db->get();
		
		return $query;
	}
	
	/*
	*	Delete an existing category
	*	@param int $category_id
	*
	*/
	public function delete_category($category_id)
	{
		if($this->db->delete('item_category', array('item_category_id' => $category_id)))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Activate a deactivated category
	*	@param int $category_id
	*
	*/
	public function activate_category($category_id)
	{
		$data = array(
				'category_status' => 1
			);
		$this->db->where('item_category_id', $category_id);
		
		if($this->db->update('item_category', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Deactivate an activated category
	*	@param int $category_id
	*
	*/
	public function deactivate_category($category_id)
	{
		$data = array(
				'category_status' => 0
			);
		$this->db->where('item_category_id', $category_id);
		
		if($this->db->update('item_category', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
}
?>