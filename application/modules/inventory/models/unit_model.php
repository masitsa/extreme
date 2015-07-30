<?php

class Unit_model extends CI_Model 
{	
	/*
	*	Retrieve all unit
	*
	*/
	public function all_unit()
	{
		$this->db->where('unit_status = 1');
		$query = $this->db->get('unit');
		
		return $query;
	}
	
	/*
	*	Retrieve all parent unit
	*
	*/
	public function all_parent_unit($order = 'unit_name')
	{
		$this->db->where('unit_status = 1 AND unit_parent = 0');
		$this->db->order_by($order, 'ASC');
		$query = $this->db->get('unit');
		
		return $query;
	}
	/*
	*	Retrieve all children unit
	*
	*/
	public function all_child_unit()
	{
		$this->db->where('unit_status = 1 AND unit_parent > 0');
		$this->db->order_by('unit_name', 'ASC');
		$query = $this->db->get('unit');
		
		return $query;
	}
	
	/*
	*	Retrieve all unit
	*	@param string $table
	* 	@param string $where
	*
	*/
	public function get_all_unit($table, $where, $per_page, $page, $order = 'unit_name', $order_method = 'ASC')
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
	*	Add a new unit
	*	@param string $image_name
	*
	*/
	public function add_unit()
	{
		$data = array(
			'unit_onames'=>ucwords(strtolower($this->input->post('unit_onames'))),
			'unit_fname'=>ucwords(strtolower($this->input->post('unit_fname'))),
			'unit_dob'=>$this->input->post('unit_dob'),
			'unit_email'=>$this->input->post('unit_email'),
			'gender_id'=>$this->input->post('gender_id'),
			'unit_phone'=>$this->input->post('unit_phone'),
			'civilstatus_id'=>$this->input->post('civil_status_id'),
			'unit_address'=>$this->input->post('unit_address'),
			'unit_locality'=>$this->input->post('unit_locality'),
			'title_id'=>$this->input->post('title_id'),
			'unit_number'=>$this->input->post('unit_number'),
			'unit_city'=>$this->input->post('unit_city'),
			'unit_post_code'=>$this->input->post('unit_post_code')
		);
		
		if($this->db->insert('unit', $data))
		{
			return $this->db->insert_id();
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Update an existing unit
	*	@param string $image_name
	*	@param int $unit_id
	*
	*/
	public function edit_unit($unit_id)
	{
		$data = array(
			'unit_onames'=>ucwords(strtolower($this->input->post('unit_onames'))),
			'unit_fname'=>ucwords(strtolower($this->input->post('unit_fname'))),
			'unit_dob'=>$this->input->post('unit_dob'),
			'unit_email'=>$this->input->post('unit_email'),
			'gender_id'=>$this->input->post('gender_id'),
			'unit_phone'=>$this->input->post('unit_phone'),
			'civilstatus_id'=>$this->input->post('civil_status_id'),
			'unit_address'=>$this->input->post('unit_address'),
			'unit_locality'=>$this->input->post('unit_locality'),
			'title_id'=>$this->input->post('title_id'),
			'unit_username'=>$this->input->post('unit_username'),
			'unit_kin_fname'=>$this->input->post('unit_kin_fname'),
			'unit_kin_onames'=>$this->input->post('unit_kin_onames'),
			'unit_kin_contact'=>$this->input->post('unit_kin_contact'),
			'unit_kin_address'=>$this->input->post('unit_kin_address'),
			'kin_relationship_id'=>$this->input->post('kin_relationship_id'),
			'job_title_id'=>$this->input->post('job_title_id'),
			'unit_staff_id'=>$this->input->post('staff_id')
		);
		
		$this->db->where('unit_id', $unit_id);
		if($this->db->update('unit', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	get a single unit's children
	*	@param int $unit_id
	*
	*/
	public function get_sub_unit($unit_id)
	{
		//retrieve all users
		$this->db->from('unit');
		$this->db->select('*');
		$this->db->where('unit_parent = '.$unit_id);
		$query = $this->db->get();
		
		return $query;
	}
	
	/*
	*	get a single unit's details
	*	@param int $unit_id
	*
	*/
	public function get_unit($unit_id)
	{
		//retrieve all users
		$this->db->from('unit');
		$this->db->select('*');
		$this->db->where('unit_id = '.$unit_id);
		$query = $this->db->get();
		
		return $query;
	}
	
	/*
	*	Delete an existing unit
	*	@param int $unit_id
	*
	*/
	public function delete_unit($unit_id)
	{
		//delete children
		if($this->db->delete('unit', array('unit_parent' => $unit_id)))
		{
			//delete parent
			if($this->db->delete('unit', array('unit_id' => $unit_id)))
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
	*	Activate a deactivated unit
	*	@param int $unit_id
	*
	*/
	public function activate_unit($unit_id)
	{
		$data = array(
				'unit_status' => 1
			);
		$this->db->where('unit_id', $unit_id);
		

		if($this->db->update('unit', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Deactivate an activated unit
	*	@param int $unit_id
	*
	*/
	public function deactivate_unit($unit_id)
	{
		$data = array(
				'unit_status' => 0
			);
		$this->db->where('unit_id', $unit_id);
		
		if($this->db->update('unit', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Retrieve gender
	*
	*/
	public function get_gender()
	{
		$this->db->order_by('gender_name');
		$query = $this->db->get('gender');
		
		return $query;
	}
	
	/*
	*	Retrieve title
	*
	*/
	public function get_title()
	{
		$this->db->order_by('title_name');
		$query = $this->db->get('title');
		
		return $query;
	}
	
	/*
	*	Retrieve civil_status
	*
	*/
	public function get_civil_status()
	{
		$this->db->order_by('civil_status_name');
		$query = $this->db->get('civil_status');
		
		return $query;
	}
	
	/*
	*	Retrieve religion
	*
	*/
	public function get_religion()
	{
		$this->db->order_by('religion_name');
		$query = $this->db->get('religion');
		
		return $query;
	}
	
	/*
	*	Retrieve relationship
	*
	*/
	public function get_relationship()
	{
		$this->db->order_by('relationship_name');
		$query = $this->db->get('relationship');
		
		return $query;
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
	*	get a single unit's details
	*	@param int $unit_id
	*
	*/
	public function get_emergency_contacts($unit_id)
	{
		//retrieve all users
		$this->db->from('unit_emergency');
		$this->db->select('*');
		$this->db->where(array('unit_emergency.unit_id' => $unit_id, 'unit_emergency.relationship_id' => 'relationship.relationship_id'));
		$this->db->order_by('unit_emergency_fname');
		$query = $this->db->get();
		
		return $query;
	}
	
	/*
	*	get a single unit's details
	*	@param int $unit_id
	*
	*/
	public function get_unit_dependants($unit_id)
	{
		//retrieve all users
		$this->db->from('unit_dependant');
		$this->db->select('*');
		$this->db->where(array('unit_dependant.unit_id' => $unit_id, 'unit_dependant.relationship_id' => 'relationship.relationship_id'));
		$this->db->order_by('unit_dependant_fname');
		$query = $this->db->get();
		
		return $query;
	}
	
	/*
	*	get a single unit's details
	*	@param int $unit_id
	*
	*/
	public function get_unit_jobs($unit_id)
	{
		//retrieve all users
		$this->db->from('unit_job, job_title');
		$this->db->select('unit_job.*, job_title.job_title_name');
		$this->db->order_by('job_title.job_title_name');
		$this->db->where(array('unit_job.unit_id' => $unit_id, 'unit_job.job_title_id' => 'job_title.job_title_id'));
		$query = $this->db->get();
		
		return $query;
	}
	
	public function get_leave_types()
	{
		$table = "leave_type";
		$where = "leave_type_status = 0";
		$items = "leave_type_id, leave_type_name";
		$order = "leave_type_name";
		
		$this->db->where($where);
		$this->db->order_by($order);
		$result = $this->db->get($table);
		
		return $result;
	}
	
	/*
	*	get a single unit's leave details
	*	@param int $unit_id
	*
	*/
	public function get_unit_leave($unit_id)
	{
		//retrieve all users
		$this->db->from('leave_duration, leave_type');
		$this->db->select('leave_duration.*, leave_type.leave_type_name');
		$this->db->order_by('start_date', 'DESC');
		$this->db->where(array('leave_duration.unit_id' => $unit_id, 'leave_duration.leave_type_id' => 'leave_type.leave_type_id'));
		$query = $this->db->get();
		
		return $query;
	}
	
	/*
	*	get a single unit's roles
	*	@param int $unit_id
	*
	*/
	public function get_unit_roles($unit_id)
	{
		//retrieve all users
		$this->db->from('unit_section, section');
		$this->db->select('unit_section.*, section.section_name, section.section_position');
		$this->db->order_by('section_position', 'ASC');
		$this->db->where(array('unit_section.unit_id' => $unit_id, 'unit_section.section_id' => 'section.section_id'));
		$query = $this->db->get();
		
		return $query;
	}
}
?>