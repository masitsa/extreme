<?php

class Group_model extends CI_Model 
{	
	/*
	*	Retrieve all group
	*
	*/
	public function all_group()
	{
		$this->db->where('group_status = 1');
		$query = $this->db->get('group');
		
		return $query;
	}
	
	/*
	*	Retrieve all parent group
	*
	*/
	public function all_parent_group($order = 'group_name')
	{
		$this->db->where('group_status = 1 AND group_parent = 0');
		$this->db->order_by($order, 'ASC');
		$query = $this->db->get('group');
		
		return $query;
	}
	/*
	*	Retrieve all children group
	*
	*/
	public function all_child_group()
	{
		$this->db->where('group_status = 1 AND group_parent > 0');
		$this->db->order_by('group_name', 'ASC');
		$query = $this->db->get('group');
		
		return $query;
	}
	
	/*
	*	Retrieve all group
	*	@param string $table
	* 	@param string $where
	*
	*/
	public function get_all_group($table, $where, $per_page, $page, $order = 'group_name', $order_method = 'ASC')
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
	*	Add a new group
	*	@param string $image_name
	*
	*/
	public function add_group()
	{
		$data = array(
			'group_name'=>ucwords(strtolower($this->input->post('group_name'))),
			'group_contact_onames'=>ucwords(strtolower($this->input->post('group_contact_onames'))),
			'group_contact_fname'=>ucwords(strtolower($this->input->post('group_contact_fname'))),
			'group_dob'=>$this->input->post('group_dob'),
			'group_email'=>$this->input->post('group_email'),
			'group_phone'=>$this->input->post('group_phone'),
			'group_phone2'=>$this->input->post('group_phone2'),
			'group_address'=>$this->input->post('group_address'),
			'group_locality'=>$this->input->post('group_locality'),
			'group_number'=>$this->input->post('group_number'),
			'group_city'=>$this->input->post('group_city'),
			'group_post_code'=>$this->input->post('group_post_code')
		);
		
		if($this->db->insert('group', $data))
		{
			return $this->db->insert_id();
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Update an existing group
	*	@param string $image_name
	*	@param int $group_id
	*
	*/
	public function edit_group($group_id)
	{
		$data = array(
			'group_name'=>ucwords(strtolower($this->input->post('group_name'))),
			'group_contact_onames'=>ucwords(strtolower($this->input->post('group_contact_onames'))),
			'group_contact_fname'=>ucwords(strtolower($this->input->post('group_contact_fname'))),
			'group_dob'=>$this->input->post('group_dob'),
			'group_email'=>$this->input->post('group_email'),
			'group_phone'=>$this->input->post('group_phone'),
			'group_phone2'=>$this->input->post('group_phone2'),
			'group_address'=>$this->input->post('group_address'),
			'group_locality'=>$this->input->post('group_locality'),
			'group_number'=>$this->input->post('group_number'),
			'group_city'=>$this->input->post('group_city'),
			'group_post_code'=>$this->input->post('group_post_code')
		);
		
		$this->db->where('group_id', $group_id);
		if($this->db->update('group', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Add a new individual
	*	@param string $image_name
	*
	*/
	public function add_member($group_id)
	{
		$data = array(
			'individual_onames'=>ucwords(strtolower($this->input->post('individual_onames'))),
			'individual_fname'=>ucwords(strtolower($this->input->post('individual_fname'))),
			'individual_dob'=>$this->input->post('individual_dob'),
			'individual_email'=>$this->input->post('individual_email'),
			'gender_id'=>$this->input->post('gender_id'),
			'individual_phone'=>$this->input->post('individual_phone'),
			'individual_phone2'=>$this->input->post('individual_phone2'),
			'civilstatus_id'=>$this->input->post('civil_status_id'),
			'individual_address'=>$this->input->post('individual_address'),
			'individual_locality'=>$this->input->post('individual_locality'),
			'title_id'=>$this->input->post('title_id'),
			'individual_number'=>$this->input->post('individual_number'),
			'individual_city'=>$this->input->post('individual_city'),
			'individual_post_code'=>$this->input->post('individual_post_code'),
			'group_id'=>$group_id
		);
		
		if($this->db->insert('individual', $data))
		{
			return $this->db->insert_id();
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	get a single group's members
	*	@param int $group_id
	*
	*/
	public function get_group_members($group_id)
	{
		$this->db->from('individual');
		$this->db->select('*');
		$this->db->where('group_id = '.$group_id);
		$query = $this->db->get();
		
		return $query;
	}
	
	/*
	*	get a single group's details
	*	@param int $group_id
	*
	*/
	public function get_group($group_id)
	{
		//retrieve all users
		$this->db->from('group');
		$this->db->select('*');
		$this->db->where('group_id = '.$group_id);
		$query = $this->db->get();
		
		return $query;
	}
	
	/*
	*	Delete an existing group
	*	@param int $group_id
	*
	*/
	public function delete_group($group_id)
	{
		//delete children
		if($this->db->delete('group', array('group_parent' => $group_id)))
		{
			//delete parent
			if($this->db->delete('group', array('group_id' => $group_id)))
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
	*	Activate a deactivated group
	*	@param int $group_id
	*
	*/
	public function activate_group($group_id)
	{
		$data = array(
				'group_status' => 1
			);
		$this->db->where('group_id', $group_id);
		

		if($this->db->update('group', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Deactivate an activated group
	*	@param int $group_id
	*
	*/
	public function deactivate_group($group_id)
	{
		$data = array(
				'group_status' => 0
			);
		$this->db->where('group_id', $group_id);
		
		if($this->db->update('group', $data))
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
	*	get a single group's details
	*	@param int $group_id
	*
	*/
	public function get_emergency_contacts($group_id)
	{
		//retrieve all users
		$this->db->from('group_emergency');
		$this->db->select('*');
		$this->db->where(array('group_emergency.group_id' => $group_id, 'group_emergency.relationship_id' => 'relationship.relationship_id'));
		$this->db->order_by('group_emergency_fname');
		$query = $this->db->get();
		
		return $query;
	}
	
	/*
	*	get a single group's details
	*	@param int $group_id
	*
	*/
	public function get_group_dependants($group_id)
	{
		//retrieve all users
		$this->db->from('group_dependant');
		$this->db->select('*');
		$this->db->where(array('group_dependant.group_id' => $group_id, 'group_dependant.relationship_id' => 'relationship.relationship_id'));
		$this->db->order_by('group_dependant_fname');
		$query = $this->db->get();
		
		return $query;
	}
	
	/*
	*	get a single group's details
	*	@param int $group_id
	*
	*/
	public function get_group_jobs($group_id)
	{
		//retrieve all users
		$this->db->from('group_job');
		$this->db->select('group_job.*');
		$this->db->order_by('employment_date', 'DESC');
		$this->db->where(array('group_job.group_id' => $group_id));
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
	*	get a single group's leave details
	*	@param int $group_id
	*
	*/
	public function get_group_leave($group_id)
	{
		//retrieve all users
		$this->db->from('leave_duration, leave_type');
		$this->db->select('leave_duration.*, leave_type.leave_type_name');
		$this->db->order_by('start_date', 'DESC');
		$this->db->where(array('leave_duration.group_id' => $group_id, 'leave_duration.leave_type_id' => 'leave_type.leave_type_id'));
		$query = $this->db->get();
		
		return $query;
	}
	
	/*
	*	get a single group's roles
	*	@param int $group_id
	*
	*/
	public function get_group_roles($group_id)
	{
		//retrieve all users
		$this->db->from('group_section, section');
		$this->db->select('group_section.*, section.section_name, section.section_position');
		$this->db->order_by('section_position', 'ASC');
		$this->db->where(array('group_section.group_id' => $group_id, 'group_section.section_id' => 'section.section_id'));
		$query = $this->db->get();
		
		return $query;
	}
	
	public function get_group_savings_plans($group_id)
	{
		$this->db->from('group_savings, savings_plan');
		$this->db->select('group_savings.*, savings_plan.savings_plan_name');
		$this->db->order_by('created', 'DESC');
		$this->db->where('group_savings.group_id = '.$group_id.' AND group_savings.savings_plan_id = savings_plan.savings_plan_id');
		$query = $this->db->get();
		
		return $query;
	}
	
	/*
	*	Add a new group
	*	@param string $image_name
	*
	*/
	public function add_group_plan($group_id) 
	{
		$data = array(
			'group_id'=>$group_id,
			'savings_plan_id'=>$this->input->post('savings_plan_id'),
			'group_savings_status'=>$this->input->post('group_savings_status'),
			'group_savings_opening_balance'=>$this->input->post('group_savings_opening_balance'),
			'start_date'=>$this->input->post('start_date'),
			'created'=>date('Y-m-d H:i:s'),
			'created_by'=>$this->session->userdata('personnel_id'),
			'modified_by'=>$this->session->userdata('personnel_id')
		);
		
		if($this->db->insert('group_savings', $data))
		{
			return $this->db->insert_id();
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Activate a group plan
	*	@param int $group_savings_id
	*
	*/
	public function activate_group_plan($group_savings_id)
	{
		$data = array(
				'group_savings_status' => 1
			);
		$this->db->where('group_savings_id', $group_savings_id);
		

		if($this->db->update('group_savings', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Deactivate a group plan
	*	@param int $group_savings_id
	*
	*/
	public function deactivate_group_plan($group_savings_id)
	{
		$data = array(
				'group_savings_status' => 0
			);
		$this->db->where('group_savings_id', $group_savings_id);
		

		if($this->db->update('group_savings', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
}
?>