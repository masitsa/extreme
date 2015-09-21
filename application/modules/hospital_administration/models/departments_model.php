<?php

class Departments_model extends CI_Model 
{	
	/*
	*	Retrieve all departments
	*
	*/
	public function all_departments()
	{
		$this->db->where(array('department_status' => 1, 'branch_code' => $this->session->userdata('branch_code')));
		$this->db->order_by('department_name');
		$query = $this->db->get('departments');
		
		return $query;
	}
	
	/*
	*	Retrieve all departments
	*	@param string $table
	* 	@param string $where
	*
	*/
	public function get_all_departments($table, $where, $per_page, $page, $order = 'department_name', $order_method = 'ASC')
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
	*	Add a new department
	*	@param string $image_name
	*
	*/
	public function add_department()
	{
		$data = array(
				'department_name'=>$this->input->post('department_name'),
				'department_status'=>$this->input->post('department_status'),
				'created'=>date('Y-m-d H:i:s'),
				'created_by'=>$this->session->userdata('personnel_id'),
				'branch_code'=>$this->session->userdata('branch_code'),
				'modified_by'=>$this->session->userdata('personnel_id')
			);
			
		if($this->db->insert('departments', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Update an existing department
	*	@param string $image_name
	*	@param int $department_id
	*
	*/
	public function update_department($department_id)
	{
		$data = array(
				'department_name'=>$this->input->post('department_name'),
				'department_status'=>$this->input->post('department_status'),
				'branch_code'=>$this->session->userdata('branch_code'),
				'modified_by'=>$this->session->userdata('personnel_id')
			);
			
		$this->db->where('department_id', $department_id);
		if($this->db->update('departments', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	get a single department's details
	*	@param int $department_id
	*
	*/
	public function get_department($department_id)
	{
		//retrieve all users
		$this->db->from('departments');
		$this->db->select('*');
		$this->db->where('department_id = '.$department_id);
		$query = $this->db->get();
		
		return $query;
	}
	
	/*
	*	Delete an existing department
	*	@param int $department_id
	*
	*/
	public function delete_department($department_id)
	{
		if($this->db->delete('departments', array('department_id' => $department_id)))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Activate a deactivated department
	*	@param int $department_id
	*
	*/
	public function activate_department($department_id)
	{
		$data = array(
				'department_status' => 1
			);
		$this->db->where('department_id', $department_id);
		
		if($this->db->update('departments', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Deactivate an activated department
	*	@param int $department_id
	*
	*/
	public function deactivate_department($department_id)
	{
		$data = array(
				'department_status' => 0
			);
		$this->db->where('department_id', $department_id);
		
		if($this->db->update('departments', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
}
?>