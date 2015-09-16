<?php

class Wards_model extends CI_Model 
{	
	/*
	*	Retrieve all wards
	*
	*/
	public function all_wards()
	{
		$this->db->where('ward_status = 1');
		$query = $this->db->get('ward');
		
		return $query;
	}
	
	/*
	*	Retrieve all wards
	*	@param string $table
	* 	@param string $where
	*
	*/
	public function get_all_wards($table, $where, $per_page, $page, $order = 'ward_name', $order_method = 'ASC')
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
	*	Add a new ward
	*	@param string $image_name
	*
	*/
	public function add_ward()
	{
		$ward_name = $this->input->post('ward_name');
		$data = array(
				'ward_preffix'=>$this->admin_model->create_preffix($ward_name),
				'ward_name'=>$ward_name,
				'ward_status'=>$this->input->post('ward_status'),
				'created'=>date('Y-m-d H:i:s'),
				'created_by'=>$this->session->userdata('personnel_id'),
				'modified_by'=>$this->session->userdata('personnel_id')
			);
			
		if($this->db->insert('ward', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Update an existing ward
	*	@param string $image_name
	*	@param int $ward_id
	*
	*/
	public function update_ward($ward_id)
	{
		$data = array(
				'ward_name'=>$this->input->post('ward_name'),
				'ward_status'=>$this->input->post('ward_status'),
				'modified_by'=>$this->session->userdata('personnel_id')
			);
			
		$this->db->where('ward_id', $ward_id);
		if($this->db->update('ward', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	get a single ward's details
	*	@param int $ward_id
	*
	*/
	public function get_ward($ward_id)
	{
		//retrieve all users
		$this->db->from('ward');
		$this->db->select('*');
		$this->db->where('ward_id = '.$ward_id);
		$query = $this->db->get();
		
		return $query;
	}
	
	/*
	*	Delete an existing ward
	*	@param int $ward_id
	*
	*/
	public function delete_ward($ward_id)
	{
		if($this->db->delete('ward', array('ward_id' => $ward_id)))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Activate a deactivated ward
	*	@param int $ward_id
	*
	*/
	public function activate_ward($ward_id)
	{
		$data = array(
				'ward_status' => 1
			);
		$this->db->where('ward_id', $ward_id);
		
		if($this->db->update('ward', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Deactivate an activated ward
	*	@param int $ward_id
	*
	*/
	public function deactivate_ward($ward_id)
	{
		$data = array(
				'ward_status' => 0
			);
		$this->db->where('ward_id', $ward_id);
		
		if($this->db->update('ward', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
}
?>