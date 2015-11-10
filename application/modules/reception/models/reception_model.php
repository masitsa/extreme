<?php
class Reception_model extends CI_Model 
{
	/*
	*	Count all items from a table
	*	@param string $table
	* 	@param string $where
	*
	*/
	public function count_items($table, $where, $limit = NULL)
	{
		if($limit != NULL)
		{
			$this->db->limit($limit);
		}
		$this->db->from($table);
		$this->db->where($where);
		return $this->db->count_all_results();
	}
	
	/*
	*	Retrieve all patients
	*	@param string $table
	* 	@param string $where
	*	@param int $per_page
	* 	@param int $page
	*
	*/
	public function get_all_patients($table, $where, $per_page, $page, $items = '*')
	{
		//retrieve all users
		$this->db->from($table);
		$this->db->select($items);
		$this->db->where($where);
		$this->db->order_by('patient_date','desc');
		$query = $this->db->get('', $per_page, $page);
		
		return $query;
	}
	
	/*
	*	Retrieve ongoing visits
	*	@param string $table
	* 	@param string $where
	*	@param int $per_page
	* 	@param int $page
	*
	*/
	public function get_all_ongoing_visits($table, $where, $per_page, $page, $order = NULL)
	{
		//retrieve all users
		$this->db->from($table);
		$this->db->select('visit.*, visit_department.created AS visit_created, visit_department.accounts, patients.*, visit_type.visit_type_name');
		$this->db->where($where);
		//$this->db->order_by('visit_department.created','ASC');
		$this->db->order_by('visit.visit_time','ASC');
		$query = $this->db->get('', $per_page, $page);
		
		return $query;
	}
	public function get_all_ongoing_visits2($table, $where, $per_page, $page, $order = NULL)
	{
		//retrieve all users
		$this->db->from($table);
		$this->db->select('visit.*, patients.*, visit_type.visit_type_name');
		$this->db->where($where);
		$this->db->order_by('visit.visit_date','DESC');
		$query = $this->db->get('', $per_page, $page);
		
		return $query;
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
	*	Save other patient
	*
	*/
	public function save_other_patient()
	{

		$data = array(
			'patient_surname'=>ucwords(strtolower($this->input->post('patient_surname'))),
			'patient_othernames'=>ucwords(strtolower($this->input->post('patient_othernames'))),
			'title_id'=>$this->input->post('title_id'),
			'patient_date_of_birth'=>$this->input->post('patient_dob'),
			'gender_id'=>$this->input->post('gender_id'),
			'patient_email'=>$this->input->post('patient_email'),
			'patient_phone1'=>$this->input->post('patient_phone1'),
			'patient_phone2'=>$this->input->post('patient_phone2'),
			'patient_kin_sname'=>$this->input->post('patient_kin_sname'),
			'patient_kin_othernames'=>$this->input->post('patient_kin_othernames'),
			'relationship_id'=>$this->input->post('relationship_id'),
			'patient_national_id'=>$this->input->post('patient_national_id'),
			'patient_date'=>date('Y-m-d H:i:s'),
			'patient_number'=>$this->create_patient_number(),
			'created_by'=>$this->session->userdata('personnel_id'),
			'modified_by'=>$this->session->userdata('personnel_id'),
			'visit_type_id'=>2,
			'dependant_id'=>$this->input->post('dependant_id'),
			'current_patient_number'=>$this->input->post('current_patient_number'),
			'branch_code'=>$this->session->userdata('branch_code'),
			'patient_kin_phonenumber1'=>$this->input->post('next_of_kin_contact'),
			'current_patient_number'=>$this->input->post('current_patient_number'),
		);
		
		if($this->db->insert('patients', $data))
		{
			return $this->db->insert_id();
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Edit other patient
	*
	*/
	public function edit_other_patient($patient_id)
	{
		$data = array(
			'patient_surname'=>ucwords(strtolower($this->input->post('patient_surname'))),
			'patient_othernames'=>ucwords(strtolower($this->input->post('patient_othernames'))),
			'title_id'=>$this->input->post('title_id'),
			'patient_date_of_birth'=>$this->input->post('patient_dob'),
			'gender_id'=>$this->input->post('gender_id'),
			'patient_email'=>$this->input->post('patient_email'),
			'patient_phone1'=>$this->input->post('patient_phone1'),
			'patient_phone2'=>$this->input->post('patient_phone2'),
			'patient_kin_sname'=>$this->input->post('patient_kin_sname'),
			'patient_kin_othernames'=>$this->input->post('patient_kin_othernames'),
			'relationship_id'=>$this->input->post('relationship_id'),
			'patient_national_id'=>$this->input->post('patient_national_id'),
			'patient_date'=>date('Y-m-d H:i:s'),
			'created_by'=>$this->session->userdata('personnel_id'),
			'modified_by'=>$this->session->userdata('personnel_id'),
			'visit_type_id'=>3,
			'dependant_id'=>$this->input->post('dependant_id'),
			'current_patient_number'=>$this->input->post('current_patient_number'),
			'branch_code'=>$this->session->userdata('branch_code'),
			'patient_kin_phonenumber1'=>$this->input->post('next_of_kin_contact'),
			'current_patient_number'=>$this->input->post('current_patient_number'),
		);
		
		$this->db->where('patient_id', $patient_id);
		if($this->db->update('patients', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	/*
	*	Edit other patient
	*
	*/
	public function edit_staff_patient($patient_id)
	{
		$data = array(
			'patient_phone1'=>$this->input->post('phone_number'),
			'patient_phone2'=>$this->input->post('patient_phone2')
		);
		
		$this->db->where('patient_id', $patient_id);
		if($this->db->update('patients', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	public function edit_student_patient($patient_id)
	{
		$data = array(
			'patient_phone1'=>$this->input->post('phone_number'),
			'patient_phone2'=>$this->input->post('patient_phone2')
		);
		
		$this->db->where('patient_id', $patient_id);
		if($this->db->update('patients', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	 
	function edit_staff_dependant_patient($patient_id)
	{
		$data = array(
			'patient_surname'=>ucwords(strtolower($this->input->post('patient_surname'))),
			'patient_othernames'=>ucwords(strtolower($this->input->post('patient_othernames'))),
			'title_id'=>$this->input->post('title_id'),
			'patient_date_of_birth'=>$this->input->post('patient_dob'),
			'gender_id'=>$this->input->post('gender_id'),
			'religion_id'=>$this->input->post('religion_id'),
			'civil_status_id'=>$this->input->post('civil_status_id'),
			'relationship_id'=>$this->input->post('relationship_id'),
			'modified_by'=>$this->session->userdata('personnel_id')
		);
		
		$this->db->where('patient_id', $patient_id);
		if($this->db->update('patients', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Save dependant patient
	*
	*/
	public function save_dependant_patient($dependant_staff)
	{
		$this->db->select('staff_system_id');
		$this->db->where('Staff_Number', $dependant_staff);
		$query = $this->db->get('staff');
		
		if($query->num_rows() > 0)
		{
			$res = $query->row();
			$staff_system_id = $res->staff_system_id;
			// $data = array(
			// 	'surname'=>ucwords(strtolower($this->input->post('patient_surname'))),
			// 	'other_names'=>ucwords(strtolower($this->input->post('patient_othernames'))),
			// 	'title_id'=>$this->input->post('title_id'),
			// 	'DOB'=>$this->input->post('patient_dob'),
			// 	'gender_id'=>$this->input->post('gender_id'),
			// 	'religion_id'=>$this->input->post('religion_id'),
			// 	'staff_id'=>$staff_system_id,
			// 	'civil_status_id'=>$this->input->post('civil_status_id')
			// );
			// $this->db->insert('staff_dependants', $data);
			
			$data2 = array(
				'patient_surname'=>ucwords(strtolower($this->input->post('patient_surname'))),
				'patient_othernames'=>ucwords(strtolower($this->input->post('patient_othernames'))),
				'title_id'=>$this->input->post('title_id'),
				'patient_date_of_birth'=>$this->input->post('patient_dob'),
				'gender_id'=>$this->input->post('gender_id'),
				'dependant_id'=>$dependant_staff,
				'visit_type_id'=>2,
				'relationship_id'=>$this->input->post('relationship_id'),
				'patient_date'=>date('Y-m-d H:i:s'),
				'patient_number'=>$this->create_patient_number(),
				'created_by'=>$this->session->userdata('personnel_id'),
				'modified_by'=>$this->session->userdata('personnel_id')
			);
			
			if($this->db->insert('patients', $data2))
			{
				return $this->db->insert_id();
			}
			else{
				return FALSE;
			}
		}
		
		else
		{
			return FALSE;
		}
	}
	
	/*
	*	Save dependant patient
	*
	*/
	public function save_other_dependant_patient($patient_id)
	{
		$data = array(
			'visit_type_id'=>3,
			'patient_surname'=>ucwords(strtolower($this->input->post('patient_surname'))),
			'patient_othernames'=>ucwords(strtolower($this->input->post('patient_othernames'))),
			'title_id'=>$this->input->post('title_id'),
			'patient_date_of_birth'=>$this->input->post('patient_dob'),
			'gender_id'=>$this->input->post('gender_id'),
			'religion_id'=>$this->input->post('religion_id'),
			'civil_status_id'=>$this->input->post('civil_status_id'),
			'relationship_id'=>$this->input->post('relationship_id'),
			'patient_date'=>date('Y-m-d H:i:s'),
			'patient_number'=>$this->create_patient_number(),
			'created_by'=>$this->session->userdata('personnel_id'),
			'modified_by'=>$this->session->userdata('personnel_id'),
			'dependant_id'=>$patient_id
		);
		
		if($this->db->insert('patients', $data))
		{
			return $this->db->insert_id();
		}
		else{
			return FALSE;
		}
	}
	
	public function get_service_charges($patient_id)
	{
		$table = "service_charge";
		$where = "service_charge.service_id = 1 AND service_charge.visit_type_id = (SELECT visit_type_id FROM patients WHERE patient_id = $patient_id)";
		$items = "service_charge.service_charge_name, service_charge_id";
		$order = "service_charge_name";
		
		
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		return $result;
	}
	
	public function get_service_charges2($visit_id)
	{
		$table = "service_charge";
		$where = "service_charge.service_id = 1 AND service_charge.visit_type_id = (SELECT visit_type FROM visit WHERE visit_id = $visit_id)";
		$items = "service_charge.service_charge_name, service_charge_id";
		$order = "service_charge_name";
		
		
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		return $result;
	}
	public function get_service_charge($id)
	{
		$table = "service_charge";
		$where = "service_charge_id = $id";
		$items = "service_charge_amount AS number";
		$order = "service_charge_amount";
		
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		foreach ($result as $rs1):
			$visit_type2 = $rs1->number;
		endforeach;
		return $visit_type2;
	}
	public function save_consultation_charge($visit_id, $service_charge_id, $service_charge)
	{
		$insert = array(
        	"visit_id" => $visit_id,
        	"service_charge_id" => $service_charge_id,
        	"visit_charge_amount" => $service_charge
    	);
		$table = "visit_charge";
		$this->load->model('database', '',TRUE);
		$this->database->insert_entry($table, $insert);
		
		return TRUE;
	}
	public function get_doctor()
	{
		$table = "personnel, personnel_job";
		$where = "personnel_job.personnel_id = personnel.personnel_id AND personnel_job.job_title_id = 12";
		$items = "personnel.personnel_onames, personnel.personnel_fname, personnel.personnel_id";
		$order = "personnel_onames";
		
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		return $result;
	}	
	public function get_personnel_details($personnel_id)
	{
		$table = "personnel, job_title";
		$where = "job_title.job_title_id = personnel.job_title_id AND  personnel.personnel_id = '$personnel_id'";
		$items = "personnel.personnel_onames, personnel.personnel_fname, personnel.personnel_id";
		$order = "personnel_onames";
		
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		return $result;
	}	
	public function get_types()
	{
		$table = "visit_type";
		$where = "visit_type_id > 0";
		$items = "visit_type_name, visit_type_id";
		$order = "visit_type_name";
		
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		return $result;
	}
	
	public function patient_names2($patient_id, $visit_id = NULL)
	{
		if($visit_id == NULL)
		{
			$table = "patients";
			$where = "patient_id = ".$patient_id;
			$items = "*";
			$order = "patient_surname";
		}
		
		else
		{
			$table = "patients, visit";
			$where = "patients.patient_id = visit.patient_id AND visit.visit_id = ".$visit_id;
			$items = "patients.*, visit.visit_type, visit.ward_id, visit.patient_insurance_number, visit.inpatient, visit.close_card";
			$order = "patient_surname";
		}
		
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		foreach ($result as $row)
		{
			$patient_id = $row->patient_id;
			$patient_number = $row->patient_number;
			$created_by = $row->created_by;
			$modified_by = $row->modified_by;
			$created = $row->patient_date;
			$last_modified = $row->last_modified;
			$last_visit = $row->last_visit;
			$ward_id = $row->ward_id;
			$patient_national_id = $row->patient_national_id;
			$patient_othernames = $row->patient_othernames;
			$patient_surname = $row->patient_surname;
			$patient_date_of_birth = $row->patient_date_of_birth;
			$gender_id = $row->gender_id;
			
			$faculty ='';
			$dependant_id = '';
			$close_card = '';
			if($gender_id == 1)
			{
				$gender = 'M';
			}
			else
			{
				$gender = 'F';
			}
			if($visit_id == NULL)
			{
				$visit_type_id = '';
				$visit_type_name = '';
				$patient_insurance_number = '';
				$inpatient = '';
			}
			
			else
			{
				$inpatient = $row->inpatient;
				$patient_insurance_number = $row->patient_insurance_number;
				$visit_type_id = $row->visit_type;
				$this->db->where('visit_type_id', $visit_type_id);
				$this->db->select('visit_type_name');
				$query = $this->db->get('visit_type');
				$visit_type_name = '';
				$close_card = $row->close_card;
				
				if($query->num_rows() > 0)
				{
					$row2 = $query->row();
					$visit_type_name = $row2->visit_type_name;
				}
			}
		}
		// calculate patient balance
		$this->load->model('administration/administration_model');
		$account_balance = $this->administration_model->patient_account_balance($patient_id);
		// end of patient balance
		$patient['patient_insurance_number'] = $patient_insurance_number;
		$patient['inpatient'] = $inpatient;
		$patient['patient_id'] = $patient_id;
		$patient['account_balance'] = $account_balance;
		$patient['patient_national_id'] = $patient_national_id;
		$patient['visit_type'] = $visit_type_id;
		$patient['visit_type_name'] = $visit_type_name;
		$patient['patient_type'] = $visit_type_id;
		$patient['visit_type_id'] = $visit_type_id;
		$patient['patient_othernames'] = $patient_othernames;
		$patient['patient_surname'] = $patient_surname;
		$patient['patient_date_of_birth'] = $patient_date_of_birth;
		$patient['gender'] = $gender;
		$patient['patient_number'] = $patient_number;
		$patient['faculty'] = $faculty;
		$patient['staff_dependant_no'] = $dependant_id;
		$patient['close_card'] = $close_card;
		$patient['ward_id'] = $ward_id;
		return $patient;
	}
	
	public function patient_names3($payment_id)
	{
		$table = "patients, visit, payments";
		$where = "patients.patient_id = visit.patient_id AND visit.visit_id = payments.visit_id AND payments.payment_id = ".$payment_id;
		$items = "patients.*, visit.visit_type, visit.visit_id";
		$order = "patient_surname";
		
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		foreach ($result as $row)
		{
			$visit_id = $row->visit_id;
			$patient_id = $row->patient_id;
			$dependant_id = $row->dependant_id;
			$patient_number = $row->patient_number;
			$dependant_id = $row->dependant_id;
			$strath_no = $row->strath_no;
			$created_by = $row->created_by;
			$modified_by = $row->modified_by;
			$created = $row->patient_date;
			$last_modified = $row->last_modified;
			$last_visit = $row->last_visit;
			
			$patient_national_id = $row->patient_national_id;
			$patient_othernames = $row->patient_othernames;
			$patient_surname = $row->patient_surname;
			$patient_date_of_birth = $row->patient_date_of_birth;
			$gender_id = $row->gender_id;
			$faculty ='';
			if($gender_id == 1)
			{
				$gender = 'M';
			}
			else
			{
				$gender = 'F';
			}
			if($visit_id == NULL)
			{
				$visit_type_id = '';
				$visit_type_name = '';
			}
			
			else
			{
				$visit_type_id = $row->visit_type;
				$this->db->where('visit_type_id', $visit_type_id);
				$this->db->select('visit_type_name');
				$query = $this->db->get('visit_type');
				$visit_type_name = '';
				
				if($query->num_rows() > 0)
				{
					$row2 = $query->row();
					$visit_type_name = $row2->visit_type_name;
				}
			}
		}
		// calculate patient balance
		$this->load->model('administration/administration_model');
		$account_balance = $this->administration_model->patient_account_balance($patient_id);
		// end of patient balance
		$patient['patient_id'] = $patient_id;
		$patient['account_balance'] = $account_balance;
		$patient['patient_national_id'] = $patient_national_id;
		$patient['visit_type'] = $visit_type_id;
		$patient['visit_type_name'] = $visit_type_name;
		$patient['patient_type'] = $visit_type_id;
		$patient['visit_type_id'] = $visit_type_id;
		$patient['patient_othernames'] = $patient_othernames;
		$patient['patient_surname'] = $patient_surname;
		$patient['patient_date_of_birth'] = $patient_date_of_birth;
		$patient['gender'] = $gender;
		$patient['patient_number'] = $patient_number;
		$patient['faculty'] = $faculty;
		$patient['staff_dependant_no'] = $dependant_id;
		$patient['visit_id'] = $visit_id;
		return $patient;
	}
	
	public function get_strath_patient_data($check_id, $visit_id, $strath_no, $row, $dependant_id, $visit_type_id, $patient_id)
	{
		//staff & dependant
		if($check_id == 2)
		{
			//dependant
			if($dependant_id != 0)
			{
				$patient_type = $this->reception_model->get_patient_type($visit_type_id, $dependant_id);
				$visit_type = 'Dependant';
				
				$dependant_query = $this->reception_model->get_dependant($strath_no);
				
				if($dependant_query->num_rows() > 0)
				{
					$dependants_result = $dependant_query->row();
					
					$patient_othernames = $dependants_result->other_names;
					$patient_surname = $dependants_result->surname;
					$patient_date_of_birth = $dependants_result->DOB;
					$relationship = $dependants_result->relation;
					$gender = $dependants_result->Gender;
					$faculty = $this->get_staff_faculty_details($dependant_id);
				}
				
				else if(($row->patient_surname != '0.00') && ($row->patient_othernames != '0.00'))
				{
					$patient_othernames = $row->patient_othernames;
					$patient_surname = $row->patient_surname;
					$patient_date_of_birth = $row->patient_date_of_birth;
					$gender_id = $row->gender_id;
					// get parent faculty 
					$faculty = $this->get_staff_faculty_details($dependant_id);
					// end of parent faculty
					if($gender_id == 1)
					{
						$gender = 'M';
					}
					else
					{
						$gender = 'F';
					}
				}
				
				else
				{
					$patient_othernames = '<span class="label label-important">Dependant not found: '.$strath_no.'</span>';
					$patient_surname = $patient_id;
					$patient_date_of_birth = '';
					$relationship = '';
					$gender = '';
					$faculty ='';
				}
			}
			
			//staff
			else
			{
				$patient_type = $this->reception_model->get_patient_type($visit_type_id, $dependant_id);
				$visit_type = 'Staff';
				
				$staff_query = $this->reception_model->get_staff($strath_no);
				
				if($staff_query->num_rows() > 0)
				{
					$staff_result = $staff_query->row();
					
					$patient_surname = $staff_result->Surname;
					$patient_othernames = $staff_result->Other_names;
					$patient_date_of_birth = $staff_result->DOB;
					$patient_phone1 = $staff_result->contact;
					$gender = $staff_result->gender;
					$faculty = $staff_result->department;
				}
				
				else if(($row->patient_surname != '0.00') && ($row->patient_othernames != '0.00'))
				{
					$patient_othernames = $row->patient_othernames;
					$patient_surname = $row->patient_surname;
					$patient_date_of_birth = $row->patient_date_of_birth;
					$gender_id = $row->gender_id;
					$faculty = '';
					if($gender_id == 1)
					{
						$gender = 'M';
					}
					else
					{
						$gender = 'F';
					}
				}
				
				else
				{
					$patient_othernames = '<span class="label label-important">Staff not found: '.$strath_no.'</span>';
					$patient_surname = '';
					$patient_date_of_birth = '';
					$relationship = '';
					$gender = '';
					$patient_type = '';
					$faculty ='';
				}
			}
		}
		
		//student
		else if($check_id == 1)
		{
			$patient_type = $this->reception_model->get_patient_type($visit_type_id);
			$visit_type = 'Student';
			$student_query = $this->reception_model->get_student($strath_no);
			
			if($student_query->num_rows() > 0)
			{
				$student_result = $student_query->row();
				
				$patient_surname = $student_result->Surname;
				$patient_othernames = $student_result->Other_names;
				$patient_date_of_birth = $student_result->DOB;
				$patient_phone1 = $student_result->contact;
				$gender = $student_result->gender;
				$faculty = $student_result->faculty;
			}
				
			else if(($row->patient_surname != '0.00') && ($row->patient_othernames != '0.00'))
			{
				$patient_othernames = $row->patient_othernames;
				$patient_surname = $row->patient_surname;
				$patient_date_of_birth = $row->patient_date_of_birth;
				$gender_id = $row->gender_id;
				$faculty = '';
				
				if($gender_id == 1)
				{
					$gender = 'M';
				}
				else
				{
					$gender = 'F';
				}
			}
			
			else
			{
				$patient_othernames = '<span class="label label-important">Student not found: '.$strath_no.'</span>';
				$patient_surname = $patient_id;
				$patient_date_of_birth = '';
				$relationship = '';
				$gender = '';
				$faculty ='';
			}
		}
		
		else
		{
			$visit_type = $check_id;
			$patient_type = 'Other';
			$patient_othernames = $row->patient_othernames;
			$patient_surname = $row->patient_surname;
			$patient_date_of_birth = $row->patient_date_of_birth;
			$gender_id = $row->gender_id;
			$faculty = '';
			if($gender_id == 1)
			{
				$gender = 'M';
			}
			else
			{
				$gender = 'F';
			}
		}
		
		$patient['visit_type'] = $visit_type;
		$patient['patient_type'] = $patient_type;
		$patient['patient_othernames'] = $patient_othernames;
		$patient['patient_surname'] = $patient_surname;
		$patient['patient_date_of_birth'] = $patient_date_of_birth;
		$patient['gender'] = $gender;
		$patient['faculty'] = $faculty;
		return $patient;
	}
	public function get_staff_faculty_details($strath_no)
	{
		$this->db->from('staff');
		$this->db->select('department');
		$this->db->where('Staff_Number = \''.$strath_no.'\'');
		$query = $this->db->get();
		if($query->num_rows() > 0)
		{
			$department_result = $query->row();
			$department = $department_result->department;
		}
		else
		{
			$department = '';
		}
		return $department;
	}
	public function get_patient_insurance($patient_id)
	{
		$table = "insurance_company";
		$where = "insurance_company_status = 1";
		$items = "*";
		$order = "insurance_company_name";
		
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		return $result;
	}
	public function doctors_schedule($personelle_id,$date){
		$table = "visit";
		$where = "personnel_id = '$personelle_id' and visit_date >= '$date' and time_start <> 0 and time_end <> 0";
		$items = "*";
		$order = "visit_id";
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		return $result;
	}
	public function doctors_names($personelle_id){
		$table = "personnel";
		$where = "personnel_id = '$personelle_id'";
		$items = "*";
		$order = "personnel_id";
			//echo $sql;
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		return $result;
	}
	public function get_services_per_department($department_id)
	{
		$table = "service";
		$where = "department_id = $department_id AND service_status = 1";
		$items = "*";
		$order = "department_id";
			//echo $sql;
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		return $result;
	}
	public function get_service_charges_per_type($patient_type, $service_id)
	{
		$table = "service_charge";
		$where = "visit_type_id = $patient_type and service_id = $service_id and service_charge_status = 1";
		$items = "*";
		$order = "visit_type_id";
			//echo $sql;
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		return $result;
	}
	public function get_service_charges_per_visit_type($patient_type)
	{
		$table = "service_charge";
		$where = "visit_type_id = $patient_type and service_charge_status = 1";
		$items = "*";
		$order = "visit_type_id";
			//echo $sql;
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		return $result;
	}
	public function get_counseling_service_charges_per_type($patient_type){
		$table = "service_charge";
		$where = "visit_type_id = $patient_type and service_id = 11 and service_charge_status = 1";
		$items = "*";
		$order = "visit_type_id";
			//echo $sql;
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		return $result;
	}
	public function get_counselors(){
		$table = "personnel";
		$where = "job_title_id = 8 AND authorise = 0";
		$items = "*";
		$order = "personnel_id";
			//echo $sql;
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		return $result;
	}
	
	public function get_doctor2($doc_name)
	{
		$table = "personnel, job_title";
		$where = "job_title.job_title_id = personnel.job_title_id AND job_title.job_title_id = 2 AND personnel.personnel_onames = '$doc_name'";
		$items = "personnel.personnel_onames, personnel.personnel_fname, personnel.personnel_id";
		$order = "personnel_onames";
		
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		return $result;
	}
	
	public function get_patient_id_from_visit($visit_id)
	{
		$this->db->where("visit_id = ".$visit_id);
		$this->db->select("patient_id");
		$query = $this->db->get('visit');
		
		$row = $query->row();
		
		return $row->patient_id;
	}
	
	/*
	*	Retrieve a single patient's details
	*	@param int $patient_id
	*
	*/
	public function get_patient_data($patient_id)
	{
		$this->db->from('patients');
		$this->db->select('*');
		$this->db->where('patient_id = '.$patient_id);
		$query = $this->db->get();
		
		return $query;
	}
	
	/*
	*	Retrieve all patient dependants
	*	@param int $strath_no
	*
	*/
	public function get_all_patient_dependant($patient_id)
	{
		$this->db->from('patients');
		$this->db->select('*');
		$this->db->where('dependant_id = \''.$patient_id.'\'');
		$query = $this->db->get();
		
		return $query;
	}
	
	/*
	*	Retrieve all appointments
	*	@param string $table
	* 	@param string $where
	*	@param int $per_page
	* 	@param int $page
	*
	*/
	public function get_all_appointments($table, $where, $per_page, $page)
	{
		//retrieve all users
		$this->db->from($table);
		$this->db->select('visit.*, patients.*');
		$this->db->where($where);
		$this->db->order_by('visit_time','desc');
		$query = $this->db->get('', $per_page, $page);
		
		return $query;
	}
	
	public function get_patient_details($appointments_result, $visit_type_id, $dependant_id, $strath_no)
	{
		//staff & dependant
		if($visit_type_id == 2)
		{
			//dependant
			if($dependant_id > 0)
			{
				$patient_type = $this->reception_model->get_patient_type($visit_type_id, $dependant_id);
				$visit_type = 'Dependant';
				$dependant_query = $this->reception_model->get_dependant($strath_no);
				
				if($dependant_query->num_rows() > 0)
				{
					$dependants_result = $dependant_query->row();
					$patient_othernames = $dependants_result->other_names;
					$patient_surname = $dependants_result->names;
				}
				
				else
				{
					$patient_othernames = '<span class="label label-important">Dependant not found</span>';
					$patient_surname = '';
				}
			}
			
			//staff
			else
			{
				$patient_type = $this->reception_model->get_patient_type($visit_type_id, $dependant_id);
				$staff_query = $this->reception_model->get_staff($strath_no);
				$visit_type = 'Staff';
				
				if($staff_query->num_rows() > 0)
				{
					$staff_result = $staff_query->row();
					
					$patient_surname = $staff_result->Surname;
					$patient_othernames = $staff_result->Other_names;
				}
				
				else
				{
					$patient_othernames = '<span class="label label-important">Staff not found</span>';
					$patient_surname = '';
				}
			}
		}
		
		//student
		else if($visit_type_id == 1)
		{
			$student_query = $this->reception_model->get_student($strath_no);
			$patient_type = $this->reception_model->get_patient_type($visit_type_id);
			$visit_type = 'Student';
			
			if($student_query->num_rows() > 0)
			{
				$student_result = $student_query->row();
				
				$patient_surname = $student_result->Surname;
				$patient_othernames = $student_result->Other_names;
			}
			
			else
			{
				$patient_othernames = '<span class="label label-important">Student not found</span>';
				$patient_surname = '';
			}
		}
		
		//other patient
		else
		{
			$patient_type = $this->reception_model->get_patient_type($visit_type_id);
			
			if($visit_type_id == 3)
			{
				$visit_type = 'Other';
			}
			else if($visit_type_id == 4)
			{
				$visit_type = 'Insurance';
			}
			else
			{
				$visit_type = 'General';
			}
			$row = $appointments_result->row();
			$patient_othernames = $row->patient_othernames;
			$patient_surname = $row->patient_surname;
		}
		
		$patient = $visit_type.': '.$patient_surname.' '.$patient_othernames;
		
		return $patient;
	}
	
	public function delete_patient($patient_id)
	{
		$data = array
		(
			"patient_delete" => 1,
			"deleted_by" => $this->session->userdata('personnel_id'),
			"date_deleted" => date('Y-m-d H:i:s')
		);
		
		$this->db->where('patient_id', $patient_id);
		if($this->db->update('patients', $data))
		{
			return TRUE;
		}
		
		else
		{
			return FALSE;
		}
	}
	
	public function delete_visit($visit_id)
	{
		$data = array
		(
			"visit_delete" => 1,
			"deleted_by" => $this->session->userdata('personnel_id'),
			"date_deleted" => date('Y-m-d H:i:s')
		);
		
		$this->db->where('visit_id', $visit_id);
		if($this->db->update('visit', $data))
		{
			return TRUE;
		}
		
		else
		{
			return FALSE;
		}
	}
	public function get_visit_date($visit_id)
	{
		$table = "visit";
		$where = "visit_id = ".$visit_id;
		$items = "visit_date";
		$order = "visit_id";
		
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		$num_rows = count($result);
		if($num_rows > 0){
			foreach($result as $key):
				$visit_date = $key->visit_date;
			endforeach;
			return $visit_date;
		}
		else
		{
			return "None";
		}
	}
	public function change_patient_type_to_others($patient_id,$visit_type_idd)
	{
		
		//  get the details
		if($visit_type_idd == 1)
		{
			// get student details from students table
			$student_rs = $this->get_student_details($patient_id);
			$num_rows = count($student_rs);
			
			if($num_rows > 0){
				foreach($student_rs as $key):
					$student_number = $key->student_Number;
					$Surname = $key->Surname;
					$Other_names = $key->Other_names;
					$DOB = $key->DOB;
					$contact = $key->contact;
					$gender = $key->gender;
					$GUARDIAN_NAME = $key->GUARDIAN_NAME;
				endforeach;
				if($gender == "Male")
				{
					$gender_id = 1;
				}
				else
				{
					$gender_id = 2;
				}
				
				$data = array
				(
					"visit_type_id" => 3,
					"strath_no" => $student_number,
					"patient_surname" => $Surname,
					"patient_othernames" => $Other_names,
					"patient_date_of_birth" => $DOB,
					"patient_phone1" => $contact,
					"gender_id" => $gender_id,
					"patient_kin_sname" => $GUARDIAN_NAME,
					"modified_by " => $this->session->userdata('personnel_id')
				);
				
				$this->db->where('patient_id', $patient_id);
				if($this->db->update('patients', $data))
				{
					return TRUE;
				}
				
				else
				{
					return FALSE;
				}
			}else{
				return FALSE;
			}
			
		}
		else
		{
			// get student details from students table
			$staff_rs = $this->get_staff_details($patient_id);
			$num_rows = count($staff_rs);
			
			if($num_rows > 0){
				foreach($staff_rs as $key):
					$Staff_Number = $key->Staff_Number;
					$Surname = $key->Surname;
					$Other_names = $key->Other_names;
					$DOB = $key->DOB;
					$contact = $key->contact;
					$gender = $key->gender;
				endforeach;
				if($gender == "M")
				{
					$gender_id = 1;
				}
				else
				{
					$gender_id = 2;
				}
				
				$data = array
				(
					"visit_type_id" => 3,
					"strath_no" => $Staff_Number,
					"patient_surname" => $Surname,
					"patient_othernames" => $Other_names,
					"patient_date_of_birth" => $DOB,
					"patient_phone1" => $contact,
					"gender_id" => $gender_id,
					"modified_by " => $this->session->userdata('personnel_id')
				);
				
				$this->db->where('patient_id', $patient_id);
				if($this->db->update('patients', $data))
				{
					return TRUE;
				}
				
				else
				{
					return FALSE;
				}
			}else{
				return FALSE;
			}
		}
	}
	public function get_student_details($patient_id)
	{
		$table = "patients,student";
		$where = "patients.patient_id = ".$patient_id." AND patients.strath_no = student.student_Number";
		$items = "student.Surname,student.Other_names,student.DOB,student.contact,student.gender,student.GUARDIAN_NAME,student.student_Number";
		$order = "student.student_id";
		
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		return $result;
	
	}
	public function get_staff_details($patient_id)
	{
		$table = "patients,staff";
		$where = "patients.patient_id = ".$patient_id." AND patients.strath_no = staff.Staff_Number";
		$items = "staff.Surname,staff.Other_names,staff.DOB,staff.contact,staff.gender,staff.Staff_Number";
		$order = "staff.staff_id";
		
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		return $result;
	
	}
	public function change_patient_type($patient_id)
	{
	
		// check if the staff of student exist 
		$visit_type_id = $this->input->post('visit_type_id');
		$strath_no = $this->input->post('strath_no');
		if($visit_type_id == 1){
			// check in the staff table
			$student_rs = $this->get_student_number_from_student($strath_no);
			$num_rows = count($student_rs);
			
			if($num_rows > 0){
				foreach($student_rs as $key):
					$student_number = $key->student_Number;
				endforeach;
				
				$data = array
				(
					"visit_type_id" => $visit_type_id,
					"strath_no" => $student_number
				);
				
				$this->db->where('patient_id', $patient_id);
				if($this->db->update('patients', $data))
				{
					return TRUE;
				}
				
				else
				{
					return FALSE;
				}
			}else{
			
			}
			
		}else if($visit_type_id == 3 || $visit_type_id == 4){
			// check if they exisit on the table for staff
			$check_this_people = $this->check_staff_if_exist($visit_type_id,$strath_no);
			if(count($check_this_people) > 0)
			{
				// change the patient type to 2
				$data_array = array(
				'visit_type_id'=>2
				);
				$this->db->where('patient_id', $patient_id);
				$this->db->update('patients', $data_array);
				// end of changing the patient type
				return TRUE;
			}
			else
			{
				// get the patient data
					$patient_data = $this->get_staff_details_from_patients($visit_type_id,$strath_no);
					if(count($patient_data) > 0)
					{
						foreach ($patient_data as $key) {
							# code...
							$patient_surname = $key->patient_surname;
							$patient_othernames = $key->patient_othernames;
							$patient_date_of_birth = $key->patient_date_of_birth;
							$gender_id = $key->gender_id;
							$patient_id = $key->patient_id;
							$contact = $key->patient_phone1;
						}
						if($gender_id == 1)
						{
							$gender = 'M';
						}
						else
						{
							$gender = 'F';
						}
						// insert into staff table
						if($visit_type_id == 3)
						{
							$data = array(
							'Other_names'=>ucwords(strtolower($patient_othernames)),
							'Surname'=>ucwords(strtolower($patient_surname)),
							'DOB'=>$patient_date_of_birth,
							'gender'=>$gender,
							'Staff_Number'=>$strath_no,
							'contact'=>$contact,
							'house_keeping'=>'1',
							'department'=>'Housekeeping'
							);
						}
						else
						{
							$data = array(
							'Other_names'=>ucwords(strtolower($patient_othernames)),
							'Surname'=>ucwords(strtolower($patient_surname)),
							'DOB'=>$patient_date_of_birth,
							'gender'=>$gender,
							'Staff_Number'=>$strath_no,
							'contact'=>$contact,
							'sbs'=>'1',
							'department'=>'Strathmore Business School'
							);
						}
						if($this->db->insert('staff', $data))
						{
							// change the patient type to 2
							$data_array = array(
							'visit_type_id'=>2
							);
							$this->db->where('patient_id', $patient_id);
							$this->db->update('patients', $data_array);
							// end of changing the patient type
							return TRUE;
						}
						else
						{
							return FALSE;
						}
						
						// end of inserting
					}
					else
					{
						return FALSE;
					}
				// end of getting the patient data
				
			}
			// end of checking
		}else{
			// check in the staff table
			$staff_rs = $this->get_staff_number_from_staff($strath_no);
			$num_rows = count($staff_rs);
			
			if($num_rows > 0){
				foreach($staff_rs as $key):
					$staff_number = $key->Staff_Number;
				endforeach;
				
				$data = array
				(
					"visit_type_id" => $visit_type_id,
					"strath_no" => $staff_number
				);
				
				$this->db->where('patient_id', $patient_id);
				if($this->db->update('patients', $data))
				{
					return TRUE;
				}
				
				else
				{
					return FALSE;
				}
			}else{
			// check if the patient is a staff and appears as a 
				$staff_rs = $this->get_staff_number_from_patients($strath_no);
				$num_rows = count($staff_rs);
				
				if($num_rows > 0){
					foreach($staff_rs as $key):
						$national_id = $key->patient_national_id;
					endforeach;
					
					$data = array
					(
						"visit_type_id" => $visit_type_id,
						"patient_national_id" => $national_id
					);
					
					$this->db->where('patient_id', $patient_id);
					if($this->db->update('patients', $data))
					{
						return TRUE;
					}
					
					else
					{
						return FALSE;
					}
				}else{
				}
				
			
				
			
			}
		}
	
		
	}
	public function get_staff_details_from_patients($visit_type_id,$strath_no)
	{
		if($visit_type_id == 3)
		{
			//housekeeping
			$table = "patients";
			$where = "patient_national_id = '$strath_no'";
			$items = "*";
			$order = "patients.patient_id";
			
			$result = $this->database->select_entries_where($table, $where, $items, $order);
		}
		else if($visit_type_id == 4)
		{
			// sbs
			$table = "patients";
			$where = "strath_no = '$strath_no'";
			$items = "*";
			$order = "patients.patient_id";
			
			$result = $this->database->select_entries_where($table, $where, $items, $order);
		}
		
		return $result;
	}
	public function get_staff_number_from_staff($strath_no){
		$table = "staff";
		$where = "Staff_Number = ".$strath_no;
		$items = "*";
		$order = "staff.staff_id";
		
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		return $result;
	}
	
	public function get_staff_number_from_patients($national_id){
		$table = "patients";
		$where = "patient_national_id = ".$national_id;
		$items = "*";
		$order = "patients.patient_id";
		
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		return $result;
	}
	public function get_student_number_from_student($strath_no)
	{
		$table = "student";
		$where = "student_Number = ".$strath_no;
		$items = "*";
		$order = "student.student_id";
		
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		return $result;
	
	}
	public function add_sbs_patient()
	{
		$staff_type = $this->input->post('staff_type');
		$strath_no = $this->input->post('strath_no');
		
		if($staff_type == "housekeeping"){
			$data = array(
			'Other_names'=>ucwords(strtolower($this->input->post('surname'))),
			'Surname'=>ucwords(strtolower($this->input->post('other_names'))),
			'DOB'=>$this->input->post('date_of_birth'),
			'gender'=>$this->input->post('gender'),
			'Staff_Number'=>$this->input->post('strath_no'),
			'contact'=>$this->input->post('contact'),
			'house_keeping'=>'1'
			);
		}else{
			$data = array(
				'Other_names'=>ucwords(strtolower($this->input->post('surname'))),
				'Surname'=>ucwords(strtolower($this->input->post('other_names'))),
				'DOB'=>$this->input->post('date_of_birth'),
				'gender'=>$this->input->post('gender'),
				'Staff_Number'=>$this->input->post('strath_no'),
				'contact'=>$this->input->post('contact'),
				'sbs'=>'1'
			);
		}
		$check_this_people = $this->check_staff_if_exist($staff_type,$strath_no);
		if(count($check_this_people) > 0)
		{
			return FALSE;
		}
		else
		{
			if($this->db->insert('staff', $data))
			{
				// check if exist in the patients table
				
				 $check_patient = $this->check_patient_if_exist($staff_type,$strath_no);
				// count($check_patient);
				if(count($check_patient) > 0){
						return TRUE;
				}else{
					$data2 = array(
						'strath_no'=>$this->input->post('strath_no'),
						'visit_type_id'=>2,
						'patient_date'=>date('Y-m-d H:i:s'),
						'patient_number'=>$this->create_patient_number(),
						'created_by'=>$this->session->userdata('personnel_id'),
						'modified_by'=>$this->session->userdata('personnel_id')
					);
					
					if($this->db->insert('patients', $data2))
					{
						return $this->db->insert_id();
					}
					else{
						return FALSE;
					}
				}
					
			}
			
			else
			{
				return FALSE;
			}
		}
	}
	public function check_patient_if_exist($staff_type,$strath_no){
		
		$table = "patients";
		if($staff_type == "housekeeping"){
		$where = "patient_national_id = ".$strath_no;
		}else{
		$where = "strath_no = '".$strath_no."'";
		}
		$items = "*";
		$order = "patients.patient_id";
		
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		return $result;
	
	}
	public function check_staff_if_exist($staff_type,$strath_no){
		
		$table = "staff";
		$where = "Staff_Number = '".$strath_no."'";
		$items = "*";
		$order = "staff_id";
		
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		return $result;
	
	}
	public function bulk_add_sbs_staff()
	{
		$query = $this->db->get('staff2');
		
		if($query->num_rows() > 0)
		{
			$result = $query->result();
			
			foreach($result as $res)
			{
				$exists = $this->strathmore_population->staff_exists($res->Staff_Number);
				
				if(!$exists)
				{
					echo 'doesn\'t exist '.$res->Staff_Number.'<br/>';
					$data = array(
						'Other_names'=>ucwords(strtolower($res->Other_names)),
						'Surname'=>ucwords(strtolower($res->Surname)),
						'Staff_Number'=>$res->Staff_Number,
						'title'=>$res->title,
						'sbs'=>'1'
					);
					if(!$this->db->insert('staff', $data))
					{
						break;
						return FALSE;
					}
				}
				
				else
				{
					echo 'Exists '.$res->Staff_Number.'<br/>';
				}
			}
		}
		
		return TRUE;
	}
	
	function random_color()
	{
		$rand = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e', 'f');
    	$color = '#'.$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)];
		
		return $color;
	}
	
	public function get_patient_data_from_visit($visit_id)
	{
		$this->db->select('visit.*, patients.*');
		$this->db->where("visit.patient_id = patients.patient_id AND visit.visit_id = ".$visit_id);
		$query = $this->db->get('visit, patients');
		
		$row = $query->row();
		
		return $row;
	}
	public function calculate_age($patient_date_of_birth)
	{
		$value = $this->dateDiff(date('y-m-d  h:i'), $patient_date_of_birth." 00:00", 'year');
		
		return $value;
	}
	public function dateDiff($time1, $time2, $interval) 
	{
	    // If not numeric then convert texts to unix timestamps
	    if (!is_int($time1)) {
	      $time1 = strtotime($time1);
	    }
	    if (!is_int($time2)) {
	      $time2 = strtotime($time2);
	    }
	 
	    // If time1 is bigger than time2
	    // Then swap time1 and time2
	    if ($time1 > $time2) {
	      $ttime = $time1;
	      $time1 = $time2;
	      $time2 = $ttime;
	    }
	 
	    // Set up intervals and diffs arrays
	    $intervals = array('year','month','day','hour','minute','second');
	    if (!in_array($interval, $intervals)) {
	      return false;
	    }
	 
	    $diff = 0;
	    // Create temp time from time1 and interval
	    $ttime = strtotime("+1 " . $interval, $time1);
	    // Loop until temp time is smaller than time2
	    while ($time2 >= $ttime) {
	      $time1 = $ttime;
	      $diff++;
	      // Create new temp time from time1 and interval
	      $ttime = strtotime("+1 " . $interval, $time1);
	    }
	 
	    return $diff;
  	}
  	function check_patient_exist($patient_id,$visit_date){
  		$table = "visit";
		$where = "visit.patient_id =" .$patient_id ." AND visit.visit_date = '$visit_date' AND close_card = 0 AND visit.visit_delete = 0";
		$items = "*";
		$order = "visit.visit_id";
		
		$result = $this->database->select_entries_where($table, $where, $items, $order);
		
		return $result;
  	}
	/*
	*	Retrieve a single dependant
	*	@param int $strath_no
	*
	*/
	public function get_visit_departments()
	{
		$this->db->from('departments');
		$this->db->select('*');
		$this->db->where('visit = 1');
		$this->db->order_by('department_name');
		$query = $this->db->get();
		
		return $query;
	}
	
	public function get_visit_types()
	{
		$this->db->from('visit_type');
		$this->db->select('*');
		$this->db->where('visit_type_status = 1');
		$query = $this->db->get();
		
		return $query;
	}
	
	/*
	*	Create remove visit department
	*
	*/
	public function remove_visit_department($visit_id)
	{
		$update['visit_department_status'] = 0;
		$update['modified_by'] = $this->session->userdata('personnel_id');
		$update['last_modified'] = date('Y-m-d H:i:s');
		
		$this->db->where(array('visit_department_status' => 1, 'visit_id' => $visit_id));
		
		if($this->db->update('visit_department', $update))
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
	
	public function visit_account_balance($visit_id)
	{
		//retrieve all users
		$this->db->from('visit');
		$this->db->select('*');
		$this->db->where('visit_id = '.$visit_id);
		$this->db->order_by('visit_date','desc');
		$query = $this->db->get();
		$total_invoiced_amount = 0;
		$total_paid_amount = 0;
		if ($query->num_rows() > 0)
		{
			foreach ($query->result() as $row)
			{
				$visit_id = $row->visit_id;
				$visit_date = $row->visit_date;
				$visit_date = $row->visit_date;
				$total_invoice = $this->accounts_model->total_invoice($visit_id);
				$total_payments = $this->accounts_model->total_payments($visit_id);
				$total_paid_amount = $total_paid_amount + $total_payments;
				$total_invoiced_amount = $total_invoiced_amount + $total_invoice;
				
				$invoice_number =  $visit_id;
			}
			$difference = $total_invoiced_amount -$total_paid_amount;
		}
		else
		{
			$difference = $total_invoiced_amount -$total_paid_amount;
		}
		return $difference;
	}
	
	/*
	*	Create visit department
	*
	*/
	public function set_visit_department($visit_id, $department_id, $visit_type_id)
	{
		if($this->remove_visit_department($visit_id))
		{
			$data = array(
				'visit_id'=>$visit_id,
				'department_id'=>$department_id,
				'created'=>date('Y-m-d H:i:s'),
				'created_by'=>$this->session->userdata('personnel_id'),
				'modified_by'=>$this->session->userdata('personnel_id')
			);
			
			$data['accounts'] = 1;
			
			if($visit_type_id == 1)
			{
				//check for balance > 0
				$account_balance = $this->visit_account_balance($visit_id);
				
				if($account_balance > 0)
				{
					$data['accounts'] = 0;
				}
			}
			//var_dump($data);die();
			if($this->db->insert('visit_department', $data))
			{
				return TRUE;
			}
			else{
				return FALSE;
			}
		}
		
		else
		{
			return FALSE;
		}
	}
	
	public function save_visit_consultation_charge($visit_id, $service_charge_id)
	{
		//add charge for visit
		$service_charge = $this->reception_model->get_service_charge($service_charge_id);		
		
		$visit_charge_data = array(
			"visit_id" => $visit_id,
			"service_charge_id" => $service_charge_id,
			"created_by" => $this->session->userdata("personnel_id"),
			"date" => date("Y-m-d"),
			"visit_charge_amount" => $service_charge
		);
		if($this->db->insert('visit_charge', $visit_charge_data))
		{
			return TRUE;
		}
		
		else
		{
			return FALSE;
		}
	}
	
	public function set_last_visit_date($patient_id, $visit_date)
	{
		$patient_date = array(
			"last_visit" => $visit_date
		);
		$this->db->where('patient_id', $patient_id);
		$this->db->update('patients', $patient_date);
	}
	
	public function create_visit($visit_date, $patient_id, $doctor_id, $insurance_limit, $insurance_number, $visit_type_id, $timepicker_start, $timepicker_end, $appointment_id, $close_card)
	{
		$visit_data = array(
			"branch_code" => $this->session->userdata('branch_code'),
			"visit_date" => $visit_date,
			"patient_id" => $patient_id,
			"personnel_id" => $doctor_id,
			"insurance_limit" => $insurance_limit,
			"patient_insurance_number" => $insurance_number,
			"visit_type" => $visit_type_id,
			"time_start"=>$timepicker_start,
			"time_end"=>$timepicker_end,
			"appointment_id"=>$appointment_id,
			"close_card"=>$close_card,
		);
		$this->db->insert('visit', $visit_data);
		$visit_id = $this->db->insert_id();
		
		return $visit_id;
	}
	
	public function coming_from($visit_id)
	{
		$where = 'visit_department.visit_id = '.$visit_id.' AND visit_department.department_id = departments.department_id AND visit_department.visit_department_status = 0';
		$this->db->select('departments.department_name');
		$this->db->where($where);
		$this->db->order_by('visit_department.last_modified','DESC');
		$query = $this->db->get('visit_department, departments', 1, 0);
		
		if($query->num_rows() > 0)
		{
			$row = $query->row();
			return $row->department_name;
		}
		
		else
		{
			return 'Reception';
		}
	}
	
	public function going_to($visit_id)
	{
		$where = 'visit_department.visit_id = '.$visit_id.' AND visit_department.department_id = departments.department_id AND visit_department.visit_department_status = 1';
		$this->db->select('departments.department_name');
		$this->db->where($where);
		$query = $this->db->get('visit_department, departments', 1, 0);
		
		if($query->num_rows() > 0)
		{
			$row = $query->row();
			return $row->department_name;
		}
		
		else
		{
			return 'Reception';
		}
	}
	
	public function get_visit_trail($visit_id)
	{
		$where = 'visit_department.visit_id = '.$visit_id.' AND visit_department.department_id = departments.department_id';
		$this->db->select('departments.department_name, visit_department.*, personnel.personnel_fname');
		$this->db->where($where);
		$this->db->join('personnel', 'visit_department.created_by = personnel.personnel_id', 'left');
		$this->db->order_by('visit_department.created','ASC');
		$query = $this->db->get('visit_department, departments');
		
		return $query;
	}
	/*
	*	Retrieve insurance
	*
	*/
	public function get_insurance()
	{
		$this->db->order_by('insurance_company_name');
		$query = $this->db->get('insurance_company');
		
		return $query;
	}
	
	public function get_student_data($strath_no)
	{
		$where = 'student_Number = '.$strath_no;
		$this->db->select('*');
		$this->db->where($where);
		$query = $this->db->get('student');
		
		if($query->num_rows() > 0)
		{
			$row = $query->row();
			$student['student_number'] = $row->student_Number;
			$student['patient_othernames'] = $row->Other_names;
			$student['patient_surname'] = $row->Surname;
			$student['patient_date_of_birth'] = $row->DOB;
			$student['gender'] = $row->gender;
		}
		
		else
		{
			$student['student_number'] = '';
			$student['patient_othernames'] = '';
			$student['patient_surname'] = '<span class="label label-important">Student not found: '.$strath_no.'</span>';
			$student['patient_date_of_birth'] = '';
			$student['gender'] = '';
		}
		return $student;
	}
	public function get_staff_dependant_data($strath_no)
	{
		$where = 'staff.staff_system_id = staff_dependants.staff_id AND staff.Staff_Number = '.$strath_no;
		$this->db->select('*');
		$this->db->where($where);
		$query = $this->db->get('staff_dependants,staff');
		
		if($query->num_rows() > 0)
		{
			$row = $query->row();
			$student['staff_id'] = $row->staff_id;
			$student['patient_othernames'] = $row->other_names;
			$student['patient_surname'] = $row->surname;
			$student['patient_date_of_birth'] = $row->DOB;
			$student['gender'] = $row->gender;
		}
		
		else
		{
			$student['staff_id'] = '';
			$student['patient_othernames'] = '';
			$student['patient_surname'] = '<span class="label label-important">Dependant not found: '.$strath_no.'</span>';
			$student['patient_date_of_birth'] = '';
			$student['gender'] = '';
		}
		return $student;
	}
	
	/*
	*	Retrieve all students in SUMC db
	*
	*/
	public function get_all_students($per_page, $page)
	{
		$this->db->from('student');
		$query = $this->db->get('', $per_page, $page);
		
		return $query;
	}
	
	/*
	*	Retrieve all students patients in SUMC db
	*
	*/
	public function get_all_student_patients($student_no)
	{
		$this->db->from('patients');
		$this->db->where('strath_no = \''.$student_no.'\' AND visit_type_id = 1');
		$query = $this->db->get();
		
		return $query;
	}
	
	public function change_patient_id($standing_patient_id, $patient_id)
	{
		$where['patient_id'] = $patient_id;
		$items['patient_id'] = $standing_patient_id;
		
		$this->db->where($where);
		if($this->db->update('visit', $items))
		{
			return TRUE;
		}
		
		else
		{
			return FALSE;
		}
	}
	
	public function delete_duplicate_patient($patient_id)
	{
		$where['patient_id'] = $patient_id;
		
		$this->db->where($where);
		if($this->db->delete('patients'))
		{
			return TRUE;
		}
		
		else
		{
			return FALSE;
		}
	}
	public function create_patient_number()
	{
		//select product code
		$this->db->where('branch_code = "'.$this->session->userdata('branch_code').'"');
		$this->db->from('patients');
		$this->db->select('MAX(patient_number) AS number');
		$query = $this->db->get();
		if($query->num_rows() > 0)
		{
			$result = $query->result();
			$number =  $result[0]->number;
			$number++;//go to the next number
			if($number == 1){
				$number = "".$this->session->userdata('branch_code')."-000001";
			}
			
			if($number == 1)
			{
				$number = "".$this->session->userdata('branch_code')."-000001";
			}
			
		}
		else{//start generating receipt numbers
			$number = "".$this->session->userdata('branch_code')."-000001";
		}
		return $number;
	}
	
	/*
	*	Retrieve all students in SUMC db
	*
	*/
	public function get_all_dependants()
	{
		$this->db->select('patients.patient_id, staff_dependants.DOB, staff_dependants.Gender, staff_dependants.surname, staff_dependants.other_names');
		$this->db->from('patients, staff_dependants');
		$this->db->where('patients.visit_type_id = 2 AND patients.strath_no > 0 AND patients.strath_no = staff_dependants.staff_dependants_id');
		$query = $this->db->get();
		
		return $query;
	}
	
	/*
	*	Import Template
	*
	*/
	function import_template()
	{
		$this->load->library('Excel');
		
		$title = 'Oasis Patients Import Template';
		$count=1;
		$row_count=0;
		
		$report[$row_count][0] = 'Patient Number';
		$report[$row_count][1] = 'Patient Surname';
		$report[$row_count][2] = 'Patient Othernames';
		$report[$row_count][3] = 'Title (i.e. Mr)';
		$report[$row_count][4] = 'Date of Birth (i.e. YYYY/MM/DD)';
		$report[$row_count][5] = 'Civil Status';
		$report[$row_count][6] = 'Address';
		$report[$row_count][7] = 'Postal Code';
		$report[$row_count][8] = 'City';
		$report[$row_count][9] = 'Phone Number';
		$report[$row_count][10] = 'Alternate Phone';
		$report[$row_count][11] = 'Email';
		$report[$row_count][12] = 'National Id';
		$report[$row_count][13] = 'Religion';
		$report[$row_count][14] = 'Gender (i.e. M or F)';
		$report[$row_count][15] = 'Next of Kin Othernames';
		$report[$row_count][16] = 'Next of Kin Surname';
		$report[$row_count][17] = 'N.O.K Phone';
		$report[$row_count][18] = 'N.O.K Phone 2';
		$report[$row_count][19] = 'Relationship';
		
		$row_count++;
		
		//create the excel document
		$this->excel->addArray ( $report );
		$this->excel->generateXML ($title);
	}
	public function import_csv_products($upload_path)
	{
		//load the file model
		$this->load->model('admin/file_model');
		/*
			-----------------------------------------------------------------------------------------
			Upload csv
			-----------------------------------------------------------------------------------------
		*/
		$response = $this->file_model->upload_csv($upload_path, 'import_csv');
		
		if($response['check'])
		{
			$file_name = $response['file_name'];
			
			$array = $this->file_model->get_array_from_csv($upload_path.'/'.$file_name);
			//var_dump($array); die();
			$response2 = $this->sort_csv_data($array);
		
			if($this->file_model->delete_file($upload_path."\\".$file_name, $upload_path))
			{
			}
			
			return $response2;
		}
		
		else
		{
			$this->session->set_userdata('error_message', $response['error']);
			return FALSE;
		}
	}
	public function sort_csv_data($array)
	{
		//count total rows
		$total_rows = count($array);
		$total_columns = count($array[0]);//var_dump($array);die();
		
		//if products exist in array
		if(($total_rows > 0) && ($total_columns == 20))
		{
			$items['modified_by'] = $this->session->userdata('personnel_id');
			$response = '
				<table class="table table-hover table-bordered ">
					  <thead>
						<tr>
						  <th>#</th>
						  <th>Surname</th>
						  <th>Other Names</th>
						</tr>
					  </thead>
					  <tbody>
			';
			
			//retrieve the data from array
			for($r = 1; $r < $total_rows; $r++)
			{
				$current_patient_number = $array[$r][0];
				$items['patient_surname'] = mysql_real_escape_string(ucwords(strtolower($array[$r][1])));
				$items['patient_othernames'] = mysql_real_escape_string(ucwords(strtolower($array[$r][2])));
				$title = $array[$r][3];
				$items['patient_date_of_birth'] = $array[$r][4];
				$civil_status_id = $array[$r][5];
				$items['patient_address'] = $array[$r][6];
				$items['patient_postalcode'] = $array[$r][7];
				$items['patient_town'] = $array[$r][8];
				$items['patient_phone1'] = $array[$r][9];
				$items['patient_phone2'] = $array[$r][10];
				$items['patient_email'] = $array[$r][11];
				$items['patient_national_id'] = $array[$r][12];
				$religion = $array[$r][13];
				$gender = $array[$r][14];
				$items['patient_kin_othernames'] = $array[$r][15];
				$items['patient_kin_sname'] = $array[$r][16];
				$items['patient_kin_phonenumber1'] = $array[$r][17];
				$items['patient_kin_phonenumber2'] = $array[$r][18];
				$relationship_id = $array[$r][19];
				$items['patient_date'] = date('Y-m-d H:i:s');
				$items['created_by'] = $this->session->userdata('personnel_id');
				$items['branch_code'] = $this->session->userdata('branch_code');
				$comment = '';
				
				if(isset($gender))
				{
					if($gender == 'M')
					{
						$items['gender_id'] = 1;
					}
					else if($gender == 'F')
					{
						$items['gender_id'] = 2;
					}else
					{
						$gender_id = '';
					}
				}
				$items['patient_number'] = $this->create_patient_number();
				$items['current_patient_number'] = $current_patient_number;
				if(!empty($current_patient_number))
				{
					// check if the number already exists
					if($this->check_current_number_exisits($current_patient_number))
					{
						//number exists
						$comment .= '<br/>Not saved ensure you have a patient number entered'.$items['patient_surname'];
						$class = 'danger';
					}
					else
					{
						// number does not exisit
						//save product in the db
						if($this->db->insert('patients', $items))
						{
							$comment .= '<br/>Patient successfully added to the database';
							$class = 'success';
						}
						
						else
						{
							$comment .= '<br/>Internal error. Could not add patient to the database. Please contact the site administrator. Product code '.$items['patient_surname'];
							$class = 'warning';
						}
					}
				}else
				{
					$comment .= '<br/>Not saved ensure you have a patient number entered'.$items['patient_surname'];
						$class = 'danger';
				}
				
				
				$response .= '
					
						<tr class="'.$class.'">
							<td>'.$r.'</td>
							<td>'.$items['patient_number'].'</td>
							<td>'.$items['patient_othernames'].'</td>
							<td>'.$items['patient_surname'].'</td>
							<td>'.$current_patient_number.'</td>
						</tr> 
				';
			}
			
			$response .= '</table>';
			
			$return['response'] = $response;
			$return['check'] = TRUE;
		}
		
		//if no products exist
		else
		{
			$return['response'] = 'Patient data not found';
			$return['check'] = FALSE;
		}
		
		return $return;
	}
	
	public function check_current_number_exisits($patient_number)
	{
		$this->db->where('patient_number', $patient_number);
		
		$query = $this->db->get('patients');
		
		if($query->num_rows() > 0)
		{
			return TRUE;
		}
		
		else
		{
			return FALSE;
		}
	}
	
	public function get_branches()
	{
		$this->db->where('branch_status = 1');
		$this->db->order_by('branch_name', 'ASC');
		return $this->db->get('branch');
	}
	
	public function get_patient_type()
	{
		$this->db->order_by('visit_type_name', 'ASC');
		return $this->db->get('visit_type');
	}

	
	public function get_wards()
	{
		$this->db->order_by('ward_name', 'ASC');
		return $this->db->get('ward');
	}
	
	public function create_inpatient_visit($visit_date, $patient_id, $doctor_id, $insurance_limit, $insurance_number, $visit_type_id, $close_card, $ward_id)
	{
		$visit_data = array(
			"branch_code" => $this->session->userdata('branch_code'),
			"visit_date" => $visit_date,
			"patient_id" => $patient_id,
			"personnel_id" => $doctor_id,
			"insurance_limit" => $insurance_limit,
			"patient_insurance_number" => $insurance_number,
			"visit_type" => $visit_type_id,
			"appointment_id"=> 0,
			"close_card" => $close_card,
			"ward_id" => $ward_id,
			"inpatient" => 1
		);
		$this->db->insert('visit', $visit_data);
		$visit_id = $this->db->insert_id();
		
		return $visit_id;
	}
	
	public function save_admission_fee($visit_type_id, $visit_id)
	{
		//get admission fee charge
		$admission_fee = 0;
		$service_charge_id = 0;
		
		$this->db->select('service_charge_amount, service_charge_id');
		$this->db->where('visit_type_id = '.$visit_type_id.' AND service_charge_name = \'Admission fee\' AND service_charge_status = 1');
		$query = $this->db->get('service_charge');
		
		if($query->num_rows() > 0)
		{
			$row = $query->row();
			$admission_fee = $row->service_charge_amount;
			$service_charge_id = $row->service_charge_id;
		}
		
		if($service_charge_id > 0)
		{
			$data = array(
				"visit_id" => $visit_id,
				"service_charge_id" => $service_charge_id,
				"created_by" => $this->session->userdata("personnel_id"),
				"date" => date("Y-m-d"),
				"visit_charge_amount" => $admission_fee,
			);
			
			if($this->db->insert('visit_charge', $data))
			{
				return TRUE;
			}
			
			else
			{
				return FALSE;
			}
		}
		
		else
		{
			return FALSE;
		}
	}
	
	public function get_inpatient_visits($table, $where, $per_page, $page, $order = NULL)
	{
		//retrieve all users
		$this->db->from($table);
		$this->db->select('visit.*, visit.visit_date AS visit_created, patients.*, visit_type.visit_type_name, ward.ward_name');
		$this->db->where($where);
		$this->db->order_by('visit_created','ASC');
		$query = $this->db->get('', $per_page, $page);
		
		return $query;
	}
	
	public function get_visit_bed($visit_id)
	{
		//retrieve all users
		$this->db->from('visit_bed, ward, room, bed');
		$this->db->select('ward.ward_name, room.room_name, bed.bed_number');
		$this->db->where('visit_bed.visit_bed_status = 1 AND visit_bed.bed_id = bed.bed_id AND bed.room_id = room.room_id AND room.ward_id = ward.ward_id AND visit_bed.visit_id = '.$visit_id);
		$query = $this->db->get();
		
		return $query;
	}
	// changing ksh to osh


	public function changing_to_osh()
	{
		$this->db->where('branch_code = "OSH"');
		$query = $this->db->get('patients');

		if($query->num_rows() > 0)
		{
			// get the patient in a loop 

			foreach ($query->result() as $key) {
				# code...
				$patient_number = $key->patient_number;
				$patient_id = $key->patient_id;

				$pieces = explode("-", $patient_number);
				$prefix = $pieces[0]; // piece1
				$postfix = $pieces[1]; // piece2

				$new_prefix = "OSH-".$postfix."";

				// create update statement
				$data2 = array('patient_number' => $new_prefix);
		    	$this->db->where('patient_id  ='.$patient_id);
				$this->db->update('patients',$data2);
			}

		}
	}
	
	public function is_card_held($visit_id)
	{
		$this->db->where('visit_id', $visit_id);
		$this->db->join('personnel', 'personnel.personnel_id = visit.held_by', 'left');
		$query = $this->db->get('visit');
		if($query->num_rows() > 0)
		{
			$row = $query->row();
			$close_card = $row->close_card;
			$held_by = $row->personnel_fname.' '.$row->personnel_onames;
			
			if($close_card == 7)
			{
				$this->session->set_userdata('error_message', 'You cannot close this card. It has been held by '.$held_by);
				return TRUE;
			}
			
			else
			{
				return FALSE;
			}
		}
		
		else
		{
			return FALSE;
		}
	}
	
	public function change_patient_visit($visit_date, $doctor_id, $visit_id, $ward_id)
	{
		$visit_data = array(
			"visit_date" => $visit_date,
			"personnel_id" => $doctor_id,
			"ward_id" => $ward_id,
			"inpatient" => 1
		);
		$this->db->where('visit_id', $visit_id);
		if($this->db->update('visit', $visit_data))
		{
			return TRUE;
		}
		
		else
		{
			return FALSE;
		}
	}
	
	public function get_personnel($personnel_id)
	{
		$this->db->where('personnel_id', $personnel_id);
		$query = $this->db->get('personnel');
		
		return $query;
	}
	public function close_todays_visits()
	{
		$date = date('Y-m-d');

		$this->db->select('visit_id');
		$this->db->where('visit_date < "'.$date.'" AND close_card = 0');
		$query = $this->db->get('visit');

		if($query->num_rows() > 0)
		{
			foreach ($query->result() as $key) {
				# code...
				$visit_id = $key->visit_id;

				$response = $this->sync_model->syn_up_on_closing_visit($visit_id);
			}
		}
		else
		{
			$response = 'data not found';
		}
		return $response;
	}
	/*
	*	Retrieve a single dependant
	*	@param int $strath_no
	*
	*/
	public function get_visit($visit_id)
	{
		$this->db->from('visit');
		$this->db->select('*');
		$this->db->where('visit_id', $visit_id);
		$query = $this->db->get();
		
		return $query;
	}
	/*
	*	Retrieve a single dependant
	*	@param int $strath_no
	*
	*/
	public function get_visit_depts($visit_id)
	{
		$this->db->from('visit_department');
		$this->db->select('*');
		$this->db->where('visit_id', $visit_id);
		$query = $this->db->get();
		
		return $query;
	}
	/*
	*	Retrieve a single dependant
	*	@param int $strath_no
	*
	*/
	public function get_visit_charges($visit_id)
	{
		$this->db->from('visit_charge, service_charge');
		$this->db->select('*');
		$this->db->where('visit_charge.service_charge_id = service_charge.service_charge_id AND visit_id = '.$visit_id);
		$query = $this->db->get();
		
		return $query;
	}
	
	public function update_visit($visit_date, $visit_id, $doctor_id, $insurance_limit, $insurance_number, $visit_type_id, $timepicker_start, $timepicker_end, $appointment_id, $close_card, $visit_id)
	{
		$visit_data = array(
			"branch_code" => $this->session->userdata('branch_code'),
			"visit_date" => $visit_date,
			"personnel_id" => $doctor_id,
			"insurance_limit" => $insurance_limit,
			"patient_insurance_number" => $insurance_number,
			"visit_type" => $visit_type_id,
			"time_start"=>$timepicker_start,
			"time_end"=>$timepicker_end,
			"appointment_id"=>$appointment_id,
			"close_card"=>$close_card,
		);
		$this->db->where('visit_id', $visit_id);
		$this->db->update('visit', $visit_data);
		
		return $visit_id;
	}
}
?>