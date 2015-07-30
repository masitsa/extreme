<?php

class User_types_model extends CI_Model 
{	
	/*
	*	Retrieve all active user_types
	*
	*/
	public function all_active_user_types()
	{
		$this->db->where('user_type_status = 1');
		$query = $this->db->get('user_type');
		
		return $query;
	}
	
	/*
	*	Retrieve latest user_type
	*
	*/
	public function get_all_states()
	{
		$this->db->order_by('state_name', 'ASC');
		$query = $this->db->get('state');
		
		return $query;
	}
	
	/*
	*	Retrieve all user_types
	*	@param string $table
	* 	@param string $where
	*
	*/
	public function get_all_user_types($table, $where, $per_page, $page)
	{
		//retrieve all users
		$this->db->from($table);
		$this->db->select('user_type.*, user_status.user_status_name');
		$this->db->where($where);
		$this->db->order_by('user_type_name, created');
		$query = $this->db->get('', $per_page, $page);
		
		return $query;
	}
	
	/*
	*	Add a new user_type
	*
	*/
	public function add_user_type()
	{
		$data = array(
				'user_type_name'=>ucwords(strtolower($this->input->post('user_type_name'))),
				'user_type_status'=>$this->input->post('user_type_status'),
				'user_type_cost'=>$this->input->post('user_type_cost'),
				'created'=>date('Y-m-d H:i:s'),
				'created_by'=>$this->session->userdata('user_id'),
				'modified_by'=>$this->session->userdata('user_id')
			);
			
		if($this->db->insert('user_type', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Update an existing user_type
	*	@param string $image_name
	*	@param int $user_type_id
	*
	*/
	public function update_user_type($user_type_id)
	{
		$data = array(
				'user_type_name'=>ucwords(strtolower($this->input->post('user_type_name'))),
				'user_type_status'=>$this->input->post('user_type_status'),
				'user_type_cost'=>$this->input->post('user_type_cost'),
				'modified_by'=>$this->session->userdata('user_id')
			);
			
		$this->db->where('user_type_id', $user_type_id);
		if($this->db->update('user_type', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	get a single user_type's details
	*	@param int $user_type_id
	*
	*/
	public function get_user_type($user_type_id)
	{
		//retrieve all users
		$this->db->from('user_type');
		$this->db->select('*');
		$this->db->where('user_type_id = '.$user_type_id);
		$query = $this->db->get();
		
		return $query;
	}
	
	/*
	*	Delete an existing user_type
	*	@param int $user_type_id
	*
	*/
	public function delete_user_type($user_type_id)
	{
		if($this->db->delete('user_type', array('user_type_id' => $user_type_id)))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Activate a deactivated user_type
	*	@param int $user_type_id
	*
	*/
	public function activate_user_type($user_type_id)
	{
		$data = array(
				'user_type_status' => 1
			);
		$this->db->where('user_type_id', $user_type_id);
		
		if($this->db->update('user_type', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Deactivate an activated user_type
	*	@param int $user_type_id
	*
	*/
	public function deactivate_user_type($user_type_id)
	{
		$data = array(
				'user_type_status' => 0
			);
		$this->db->where('user_type_id', $user_type_id);
		
		if($this->db->update('user_type', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
}
?>