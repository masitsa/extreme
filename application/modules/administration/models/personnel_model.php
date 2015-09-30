<?php

class Personnel_model extends CI_Model 
{
	/*
	*	Select all of a personnel's departments
	*	@param $personnel_id
	*
	*/
	public function get_personnel_department($personnel_id)
	{
		$this->db->select('departments.*');
		$this->db->where('personnel_department.personnel_id = '.$personnel_id.' 
		AND personnel_department.department_id = departments.department_id');
		$this->db->order_by('departments_name');
		$query = $this->db->get('personnel_department, departments');
		
		return $query;
	}
	
	public function get_personnel_departments($personnel_id)
	{
		$this->db->from('personnel_department, departments');
		$this->db->select('departments.department_id, departments.departments_name, personnel_department.personnel_department_id');
		$this->db->where('departments.department_id = personnel_department.department_id AND personnel_department.personnel_id = '.$personnel_id);
		$this->db->order_by('departments_name','ASC');
		$query = $this->db->get();
		
		return $query;
	}
	
	/*
	*	Select all personnel
	*
	*/
	public function get_all_personnel()
	{
		$this->db->select('*');
		$query = $this->db->get('personnel');
		
		return $query;
	}
	
	public function list_all_personnel($table, $where, $per_page, $page, $order = NULL)
	{
		$this->db->from($table);
		$this->db->select('*');
		$this->db->where($where);
		$this->db->order_by('personnel_onames, personnel_fname','ASC');
		$query = $this->db->get('', $per_page, $page);
		
		return $query;
	}
	
	/*
	*	Select single personnel data
	*
	*/
	public function get_single_personnel($personnel_id)
	{
		$this->db->select('*');
		$this->db->where('personnel_id', $personnel_id);
		$query = $this->db->get('personnel');
		
		return $query->row();
	}
	
	/*
	*	Select get_job_titles
	*
	*/
	public function get_job_titles()
	{
		$this->db->select('*');
		$this->db->order_by('job_title_name', 'ASC');
		$query = $this->db->get('job_title');
		
		return $query;
	}
	
	/*
	*	Select get departments
	*
	*/
	public function get_departments()
	{
		$this->db->select('*');
		$this->db->order_by('departments_name', 'ASC');
		$query = $this->db->get('departments');
		
		return $query;
	}
	
	public function delete_personnel_department($personnel_department_id)
	{
		$this->db->where('personnel_department_id', $personnel_department_id);
		
		if($this->db->delete('personnel_department'))
		{
			return TRUE;
		}
		
		else
		{
			return FALSE;
		}
	}
	
	/*
	*	Edit personnel
	*
	*/
	public function edit_personnel($personnel_id)
	{
		$data = array(
			'personnel_onames'=>ucwords(strtolower($this->input->post('personnel_onames'))),
			'personnel_fname'=>ucwords(strtolower($this->input->post('personnel_fname'))),
			'personnel_dob'=>$this->input->post('personnel_dob'),
			'personnel_email'=>$this->input->post('personnel_email'),
			'gender_id'=>$this->input->post('gender_id'),
			'personnel_phone'=>$this->input->post('personnel_phone'),
			'civilstatus_id'=>$this->input->post('civil_status_id'),
			'personnel_address'=>$this->input->post('personnel_address'),
			'personnel_locality'=>$this->input->post('personnel_locality'),
			'title_id'=>$this->input->post('title_id'),
			'personnel_username'=>$this->input->post('personnel_username'),
			'personnel_kin_fname'=>$this->input->post('personnel_kin_fname'),
			'personnel_kin_onames'=>$this->input->post('personnel_kin_onames'),
			'personnel_kin_contact'=>$this->input->post('personnel_kin_contact'),
			'personnel_kin_address'=>$this->input->post('personnel_kin_address'),
			'kin_relationship_id'=>$this->input->post('kin_relationship_id'),
			'job_title_id'=>$this->input->post('job_title_id'),
			'personnel_staff_id'=>$this->input->post('staff_id')
		);
		
		$this->db->where('personnel_id', $personnel_id);
		if($this->db->update('personnel', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Add personnel
	*
	*/
	public function add_personnel()
	{
		$data = array(
			'personnel_onames'=>ucwords(strtolower($this->input->post('personnel_onames'))),
			'personnel_fname'=>ucwords(strtolower($this->input->post('personnel_fname'))),
			'personnel_dob'=>$this->input->post('personnel_dob'),
			'personnel_email'=>$this->input->post('personnel_email'),
			'gender_id'=>$this->input->post('gender_id'),
			'personnel_phone'=>$this->input->post('personnel_phone'),
			'civilstatus_id'=>$this->input->post('civil_status_id'),
			'personnel_address'=>$this->input->post('personnel_address'),
			'personnel_locality'=>$this->input->post('personnel_locality'),
			'title_id'=>$this->input->post('title_id'),
			'personnel_username'=>$this->input->post('personnel_username'),
			'personnel_kin_fname'=>$this->input->post('personnel_kin_fname'),
			'personnel_kin_onames'=>$this->input->post('personnel_kin_onames'),
			'personnel_kin_contact'=>$this->input->post('personnel_kin_contact'),
			'personnel_kin_address'=>$this->input->post('personnel_kin_address'),
			'kin_relationship_id'=>$this->input->post('kin_relationship_id'),
			'job_title_id'=>$this->input->post('job_title_id'),
			'personnel_staff_id'=>$this->input->post('staff_id')
		);
		
		if($this->db->insert('personnel', $data))
		{
			return $this->db->insert_id();
		}
		else{
			return FALSE;
		}
	}
	
	public function add_personnel_department($department_id, $personnel_id)
	{
		$data = array(
			'department_id'=>$department_id,
			'personnel_id'=>$personnel_id
		);
		
		if($this->db->insert('personnel_department', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	get a single personnel's roles
	*	@param int $personnel_id
	*
	*/
	public function get_personnel_roles($personnel_id)
	{
		//retrieve all users
		$this->db->from('personnel_section, section');
		$this->db->select('personnel_section.*, section.section_name, section.section_position, section.section_parent, section.section_icon');
		$this->db->order_by('section_parent', 'ASC');
		$this->db->order_by('section_position', 'ASC');
		$this->db->where('personnel_section.section_id = section.section_id AND personnel_section.personnel_id = '.$personnel_id);
		$query = $this->db->get();
		
		return $query;
	}
	
	public function get_personnel_approval_levels()
	{
		$this->db->where('inventory_level_status.inventory_level_status_id = personnel_approval.approval_status_id AND personnel_approval.personnel_id = 1');//.$this->session->userdata('personnel_id'));
		$query = $this->db->get('inventory_level_status,personnel_approval');

		return $query;
	}
}
?>