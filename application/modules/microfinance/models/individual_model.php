<?php

class Individual_model extends CI_Model 
{
	public function upload_image($path, $location, $resize, $name, $upload, $edit = NULL)
	{
		if(!empty($_FILES[$upload]['tmp_name']))
		{
			$image = $this->session->userdata($name);
			
			if((!empty($image)) || ($edit != NULL))
			{
				if($edit != NULL)
				{
					$image = $edit;
				}
				
				//delete any other uploaded image
				if($this->file_model->delete_file($path."\\".$image, $location))
				{
					//delete any other uploaded thumbnail
					$this->file_model->delete_file($path."\\thumbnail_".$image, $location);
				}
				
				else
				{
					$this->file_model->delete_file($path."/".$image, $location);
					$this->file_model->delete_file($path."/thumbnail_".$image, $location);
				}
			}
			//Upload image
			$response = $this->file_model->upload_file($path, $upload, $resize);
			if($response['check'])
			{
				$file_name = $response['file_name'];
				$thumb_name = $response['thumb_name'];
					
				//Set sessions for the image details
				$this->session->set_userdata($name, $file_name);
			
				return TRUE;
			}
		
			else
			{
				$this->session->set_userdata('upload_error_message', $response['error']);
				
				return FALSE;
			}
		}
		
		else
		{
			$this->session->set_userdata('upload_error_message', '');
			return FALSE;
		}
	}
	
	/*
	*	Retrieve all individual
	*
	*/
	public function all_individual()
	{
		$this->db->where('individual_status = 1');
		$query = $this->db->get('individual');
		
		return $query;
	}
	
	/*
	*	Retrieve all parent individual
	*
	*/
	public function all_parent_individual($order = 'individual_name')
	{
		$this->db->where('individual_status = 1 AND individual_parent = 0');
		$this->db->order_by($order, 'ASC');
		$query = $this->db->get('individual');
		
		return $query;
	}
	/*
	*	Retrieve all children individual
	*
	*/
	public function all_child_individual()
	{
		$this->db->where('individual_status = 1 AND individual_parent > 0');
		$this->db->order_by('individual_name', 'ASC');
		$query = $this->db->get('individual');
		
		return $query;
	}
	
	/*
	*	Retrieve all individual
	*	@param string $table
	* 	@param string $where
	*
	*/
	public function get_all_individual($table, $where, $per_page, $page, $order = 'individual_name', $order_method = 'ASC')
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
	*	Add a new individual
	*	@param string $image_name
	*
	*/
	public function add_individual()
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
			'individual_email2'=>$this->input->post('individual_email2'),
			'document_id'=>$this->input->post('document_id'),
			'document_number'=>$this->input->post('document_number'),
			'document_place'=>$this->input->post('document_place')
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
	*	Update an existing individual
	*	@param string $image_name
	*	@param int $individual_id
	*
	*/
	public function edit_individual($individual_id, $image, $signature)
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
			'image'=>$image,
			'signature'=>$signature,
			'individual_email2'=>$this->input->post('individual_email2'),
			'document_id'=>$this->input->post('document_id'),
			'document_number'=>$this->input->post('document_number'),
			'document_place'=>$this->input->post('document_place')
		);
		
		$this->db->where('individual_id', $individual_id);
		if($this->db->update('individual', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	get a single individual's children
	*	@param int $individual_id
	*
	*/
	public function get_sub_individual($individual_id)
	{
		//retrieve all users
		$this->db->from('individual');
		$this->db->select('*');
		$this->db->where('individual_parent = '.$individual_id);
		$query = $this->db->get();
		
		return $query;
	}
	
	/*
	*	get a single individual's details
	*	@param int $individual_id
	*
	*/
	public function get_individual($individual_id)
	{
		//retrieve all users
		$this->db->from('individual');
		$this->db->select('*');
		$this->db->where('individual_id = '.$individual_id);
		$query = $this->db->get();
		
		return $query;
	}
	
	/*
	*	Delete an existing individual
	*	@param int $individual_id
	*
	*/
	public function delete_individual($individual_id)
	{
		//delete children
		if($this->db->delete('individual', array('individual_parent' => $individual_id)))
		{
			//delete parent
			if($this->db->delete('individual', array('individual_id' => $individual_id)))
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
	*	Activate a deactivated individual
	*	@param int $individual_id
	*
	*/
	public function activate_individual($individual_id)
	{
		$data = array(
				'individual_status' => 1
			);
		$this->db->where('individual_id', $individual_id);
		

		if($this->db->update('individual', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Deactivate an activated individual
	*	@param int $individual_id
	*
	*/
	public function deactivate_individual($individual_id)
	{
		$data = array(
				'individual_status' => 0
			);
		$this->db->where('individual_id', $individual_id);
		
		if($this->db->update('individual', $data))
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
	*	get a single individual's details
	*	@param int $individual_id
	*
	*/
	public function get_emergency_contacts($individual_id)
	{
		//retrieve all users
		$this->db->from('individual_emergency, relationship');
		$this->db->select('*');
		$this->db->where('individual_emergency.individual_id = '.$individual_id.' AND individual_emergency.relationship_id = relationship.relationship_id');
		$this->db->order_by('individual_emergency_fname');
		$query = $this->db->get();
		
		return $query;
	}
	
	/*
	*	get a single individual's details
	*	@param int $individual_id
	*
	*/
	public function get_individual_dependants($individual_id)
	{
		//retrieve all users
		$this->db->from('individual_dependant');
		$this->db->select('*');
		$this->db->where(array('individual_dependant.individual_id' => $individual_id, 'individual_dependant.relationship_id' => 'relationship.relationship_id'));
		$this->db->order_by('individual_dependant_fname');
		$query = $this->db->get();
		
		return $query;
	}
	
	/*
	*	get a single individual's details
	*	@param int $individual_id
	*
	*/
	public function get_individual_jobs($individual_id)
	{
		//retrieve all users
		$this->db->from('individual_job');
		$this->db->select('individual_job.*');
		$this->db->order_by('employment_date', 'DESC');
		$this->db->where(array('individual_job.individual_id' => $individual_id));
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
	*	get a single individual's leave details
	*	@param int $individual_id
	*
	*/
	public function get_individual_leave($individual_id)
	{
		//retrieve all users
		$this->db->from('leave_duration, leave_type');
		$this->db->select('leave_duration.*, leave_type.leave_type_name');
		$this->db->order_by('start_date', 'DESC');
		$this->db->where(array('leave_duration.individual_id' => $individual_id, 'leave_duration.leave_type_id' => 'leave_type.leave_type_id'));
		$query = $this->db->get();
		
		return $query;
	}
	
	/*
	*	get a single individual's roles
	*	@param int $individual_id
	*
	*/
	public function get_individual_roles($individual_id)
	{
		//retrieve all users
		$this->db->from('individual_section, section');
		$this->db->select('individual_section.*, section.section_name, section.section_position');
		$this->db->order_by('section_position', 'ASC');
		$this->db->where(array('individual_section.individual_id' => $individual_id, 'individual_section.section_id' => 'section.section_id'));
		$query = $this->db->get();
		
		return $query;
	}
	
	public function get_individual_savings_plans($individual_id)
	{
		$this->db->from('individual_savings, savings_plan');
		$this->db->select('individual_savings.*, savings_plan.savings_plan_name');
		$this->db->order_by('created', 'DESC');
		$this->db->where('individual_savings.individual_id = '.$individual_id.' AND individual_savings.savings_plan_id = savings_plan.savings_plan_id');
		$query = $this->db->get();
		
		return $query;
	}
	
	/*
	*	Add a new individual
	*	@param string $image_name
	*
	*/
	public function add_individual_plan($individual_id) 
	{
		$data = array(
			'individual_id'=>$individual_id,
			'savings_plan_id'=>$this->input->post('savings_plan_id'),
			'individual_savings_status'=>$this->input->post('individual_savings_status'),
			'individual_savings_opening_balance'=>$this->input->post('individual_savings_opening_balance'),
			'start_date'=>$this->input->post('start_date'),
			'created'=>date('Y-m-d H:i:s'),
			'created_by'=>$this->session->userdata('personnel_id'),
			'modified_by'=>$this->session->userdata('personnel_id')
		);
		
		if($this->db->insert('individual_savings', $data))
		{
			return $this->db->insert_id();
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Activate a individual plan
	*	@param int $individual_savings_id
	*
	*/
	public function activate_individual_plan($individual_savings_id)
	{
		$data = array(
				'individual_savings_status' => 1
			);
		$this->db->where('individual_savings_id', $individual_savings_id);
		

		if($this->db->update('individual_savings', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Deactivate a individual plan
	*	@param int $individual_savings_id
	*
	*/
	public function deactivate_individual_plan($individual_savings_id)
	{
		$data = array(
				'individual_savings_status' => 0
			);
		$this->db->where('individual_savings_id', $individual_savings_id);
		

		if($this->db->update('individual_savings', $data))
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
	public function add_position($individual_id)
	{
		$data = array(
			'employer'=>$this->input->post('employer'),
			'job_title'=>$this->input->post('job_title'),
			'employment_date'=>$this->input->post('employment_date'),
			'individual_id'=>$individual_id,
			'created'=>date('Y-m-d H:i:s'),
			'created_by'=>$this->session->userdata('personnel_id'),
			'modified_by'=>$this->session->userdata('personnel_id')
		);
		
		if($this->db->insert('individual_job', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Activate a individual plan
	*	@param int $individual_savings_id
	*
	*/
	public function activate_individual_position($individual_job_id)
	{
		$data = array(
				'individual_job_status' => 1
			);
		$this->db->where('individual_job_id', $individual_job_id);

		if($this->db->update('individual_job', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Deactivate a individual plan
	*	@param int $individual_savings_id
	*
	*/
	public function deactivate_individual_position($individual_job_id)
	{
		$data = array(
				'individual_job_status' => 0
			);
		$this->db->where('individual_job_id', $individual_job_id);

		if($this->db->update('individual_job', $data))
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
	public function add_emergency($individual_id)
	{
		$data = array(
			'individual_emergency_onames'=>ucwords(strtolower($this->input->post('individual_emergency_onames'))),
			'individual_emergency_fname'=>ucwords(strtolower($this->input->post('individual_emergency_fname'))),
			'individual_emergency_dob'=>$this->input->post('individual_emergency_dob'),
			'individual_emergency_email'=>$this->input->post('individual_emergency_email'),
			'individual_emergency_phone'=>$this->input->post('individual_emergency_phone'),
			'individual_emergency_phone2'=>$this->input->post('individual_emergency_phone2'),
			'individual_emergency_address'=>$this->input->post('individual_emergency_address'),
			'individual_emergency_city'=>$this->input->post('individual_emergency_city'),
			'individual_emergency_post_code'=>$this->input->post('individual_emergency_post_code'),
			'individual_emergency_email2'=>$this->input->post('individual_emergency_email2'),
			'document_id'=>$this->input->post('document_id'),
			'document_number'=>$this->input->post('document_number'),
			'relationship_id'=>$this->input->post('relationship_id'),
			'individual_id'=>$individual_id
		);
		
		if($this->db->insert('individual_emergency', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Delete an existing individual
	*	@param int $individual_id
	*
	*/
	public function delete_emergency($individual_emergency_id)
	{
		//delete children
		if($this->db->delete('individual_emergency', array('individual_emergency_id' => $individual_emergency_id)))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
}
?>