<?php

class Savings_plan_model extends CI_Model 
{	
	/*
	*	Retrieve all savings_plan
	*
	*/
	public function all_savings_plan()
	{
		$this->db->where('savings_plan_status = 1');
		$query = $this->db->get('savings_plan');
		
		return $query;
	}
	/*
	*	Retrieve all compounding_periods
	*
	*/
	public function get_compounding_periods()
	{
		$query = $this->db->get('compounding_period');
		
		return $query;
	}
	
	/*
	*	Retrieve all parent savings_plan
	*
	*/
	public function all_parent_savings_plan($order = 'savings_plan_name')
	{
		$this->db->where('savings_plan_status = 1 AND savings_plan_parent = 0');
		$this->db->order_by($order, 'ASC');
		$query = $this->db->get('savings_plan');
		
		return $query;
	}
	/*
	*	Retrieve all children savings_plan
	*
	*/
	public function all_child_savings_plan()
	{
		$this->db->where('savings_plan_status = 1 AND savings_plan_parent > 0');
		$this->db->order_by('savings_plan_name', 'ASC');
		$query = $this->db->get('savings_plan');
		
		return $query;
	}
	
	/*
	*	Retrieve all savings_plan
	*	@param string $table
	* 	@param string $where
	*
	*/
	public function get_all_savings_plan($table, $where, $per_page, $page, $order = 'savings_plan_name', $order_method = 'ASC')
	{
		//retrieve all users
		$this->db->from($table);
		$this->db->select('savings_plan.*, compounding_period.compounding_period_name');
		$this->db->where($where);
		$this->db->order_by($order, $order_method);
		$query = $this->db->get('', $per_page, $page);
		
		return $query;
	}
	
	/*
	*	Add a new savings_plan
	*	@param string $image_name
	*
	*/
	public function add_savings_plan()
	{
		$data = array(
			'savings_plan_name'=>$this->input->post('savings_plan_name'),
			'savings_plan_min_opening_balance'=>$this->input->post('savings_plan_min_opening_balance'),
			'savings_plan_min_account_balance'=>$this->input->post('savings_plan_min_account_balance'),
			'charge_withdrawal'=>$this->input->post('charge_withdrawal'),
			'compounding_period_id'=>$this->input->post('compounding_period_id'),
			'created'=>date('Y-m-d H:i:s'),
			'created_by'=>$this->session->userdata('personnel_id'),
			'modified_by'=>$this->session->userdata('personnel_id')
		);
		
		if($this->db->insert('savings_plan', $data))
		{
			return $this->db->insert_id();
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Update an existing savings_plan
	*	@param string $image_name
	*	@param int $savings_plan_id
	*
	*/
	public function edit_savings_plan($savings_plan_id)
	{
		$data = array(
			'savings_plan_name'=>$this->input->post('savings_plan_name'),
			'savings_plan_min_opening_balance'=>$this->input->post('savings_plan_min_opening_balance'),
			'savings_plan_min_account_balance'=>$this->input->post('savings_plan_min_account_balance'),
			'charge_withdrawal'=>$this->input->post('charge_withdrawal'),
			'compounding_period_id'=>$this->input->post('compounding_period_id'),
			'modified_by'=>$this->session->userdata('personnel_id')
		);
		
		$this->db->where('savings_plan_id', $savings_plan_id);
		if($this->db->update('savings_plan', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	get a single savings_plan's children
	*	@param int $savings_plan_id
	*
	*/
	public function get_sub_savings_plan($savings_plan_id)
	{
		//retrieve all users
		$this->db->from('savings_plan');
		$this->db->select('*');
		$this->db->where('savings_plan_parent = '.$savings_plan_id);
		$query = $this->db->get();
		
		return $query;
	}
	
	/*
	*	get a single savings_plan's details
	*	@param int $savings_plan_id
	*
	*/
	public function get_savings_plan($savings_plan_id)
	{
		//retrieve all users
		$this->db->from('savings_plan');
		$this->db->select('*');
		$this->db->where('savings_plan_id = '.$savings_plan_id);
		$query = $this->db->get();
		
		return $query;
	}
	
	/*
	*	Delete an existing savings_plan
	*	@param int $savings_plan_id
	*
	*/
	public function delete_savings_plan($savings_plan_id)
	{
		//delete children
		if($this->db->delete('savings_plan', array('savings_plan_parent' => $savings_plan_id)))
		{
			//delete parent
			if($this->db->delete('savings_plan', array('savings_plan_id' => $savings_plan_id)))
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
	*	Activate a deactivated savings_plan
	*	@param int $savings_plan_id
	*
	*/
	public function activate_savings_plan($savings_plan_id)
	{
		$data = array(
				'savings_plan_status' => 1
			);
		$this->db->where('savings_plan_id', $savings_plan_id);
		

		if($this->db->update('savings_plan', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Deactivate an activated savings_plan
	*	@param int $savings_plan_id
	*
	*/
	public function deactivate_savings_plan($savings_plan_id)
	{
		$data = array(
				'savings_plan_status' => 0
			);
		$this->db->where('savings_plan_id', $savings_plan_id);
		
		if($this->db->update('savings_plan', $data))
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
	*	get a single savings_plan's details
	*	@param int $savings_plan_id
	*
	*/
	public function get_emergency_contacts($savings_plan_id)
	{
		//retrieve all users
		$this->db->from('savings_plan_emergency');
		$this->db->select('*');
		$this->db->where(array('savings_plan_emergency.savings_plan_id' => $savings_plan_id, 'savings_plan_emergency.relationship_id' => 'relationship.relationship_id'));
		$this->db->order_by('savings_plan_emergency_fname');
		$query = $this->db->get();
		
		return $query;
	}
	
	/*
	*	get a single savings_plan's details
	*	@param int $savings_plan_id
	*
	*/
	public function get_savings_plan_dependants($savings_plan_id)
	{
		//retrieve all users
		$this->db->from('savings_plan_dependant');
		$this->db->select('*');
		$this->db->where(array('savings_plan_dependant.savings_plan_id' => $savings_plan_id, 'savings_plan_dependant.relationship_id' => 'relationship.relationship_id'));
		$this->db->order_by('savings_plan_dependant_fname');
		$query = $this->db->get();
		
		return $query;
	}
	
	/*
	*	get a single savings_plan's details
	*	@param int $savings_plan_id
	*
	*/
	public function get_savings_plan_jobs($savings_plan_id)
	{
		//retrieve all users
		$this->db->from('savings_plan_job');
		$this->db->select('savings_plan_job.*');
		$this->db->order_by('employment_date', 'DESC');
		$this->db->where(array('savings_plan_job.savings_plan_id' => $savings_plan_id));
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
	*	get a single savings_plan's leave details
	*	@param int $savings_plan_id
	*
	*/
	public function get_savings_plan_leave($savings_plan_id)
	{
		//retrieve all users
		$this->db->from('leave_duration, leave_type');
		$this->db->select('leave_duration.*, leave_type.leave_type_name');
		$this->db->order_by('start_date', 'DESC');
		$this->db->where(array('leave_duration.savings_plan_id' => $savings_plan_id, 'leave_duration.leave_type_id' => 'leave_type.leave_type_id'));
		$query = $this->db->get();
		
		return $query;
	}
	
	/*
	*	get a single savings_plan's roles
	*	@param int $savings_plan_id
	*
	*/
	public function get_savings_plan_roles($savings_plan_id)
	{
		//retrieve all users
		$this->db->from('savings_plan_section, section');
		$this->db->select('savings_plan_section.*, section.section_name, section.section_position');
		$this->db->order_by('section_position', 'ASC');
		$this->db->where(array('savings_plan_section.savings_plan_id' => $savings_plan_id, 'savings_plan_section.section_id' => 'section.section_id'));
		$query = $this->db->get();
		
		return $query;
	}
}
?>