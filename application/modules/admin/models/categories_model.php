<?php

class Categories_model extends CI_Model 
{	
	/*
	*	Retrieve all categories
	*
	*/
	public function all_categories()
	{
		$this->db->where('category_status = 1');
		$query = $this->db->get('category');
		
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
		$this->db->where('category_status = 1 AND category_parent = 0');
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
	public function get_all_categories($table, $where, $per_page, $page, $order = 'category_name', $order_method = 'ASC')
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
	*	Add a new category
	*	@param string $image_name
	*
	*/
	public function add_category($image_name)
	{
		$data = array(
				'category_name'=>ucwords(strtolower($this->input->post('category_name'))),
				'category_parent'=>$this->input->post('category_parent'),
				'category_preffix'=>strtoupper($this->input->post('category_preffix')),
				'category_status'=>$this->input->post('category_status'),
				'created'=>date('Y-m-d H:i:s'),
				'created_by'=>$this->session->userdata('user_id'),
				'modified_by'=>$this->session->userdata('user_id'),
				'category_image_name'=>$image_name
			);
			
		if($this->db->insert('category', $data))
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
	public function update_category($image_name, $category_id)
	{
		$data = array(
				'category_name'=>ucwords(strtolower($this->input->post('category_name'))),
				'category_parent'=>$this->input->post('category_parent'),
				'category_preffix'=>strtoupper($this->input->post('category_preffix')),
				'category_status'=>$this->input->post('category_status'),
				'modified_by'=>$this->session->userdata('user_id'),
				'category_image_name'=>$image_name
			);
			
		$this->db->where('category_id', $category_id);
		if($this->db->update('category', $data))
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
		$this->db->from('category');
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
		$this->db->from('category');
		$this->db->select('*');
		$this->db->where('category_id = '.$category_id);
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
		if($this->db->delete('category', array('category_id' => $category_id)))
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
		$this->db->where('category_id', $category_id);
		
		if($this->db->update('category', $data))
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
		$this->db->where('category_id', $category_id);
		
		if($this->db->update('category', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
}
?>