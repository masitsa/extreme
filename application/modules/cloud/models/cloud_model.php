<?php
class Cloud_model extends CI_Model 
{
	public function save_visit_data($json)
	{
		//decode the json data
		$decoded = json_decode($json);
		//var_dump($decoded); die();
		$branch_code = $decoded->branch_code;
		
		//get sync tables
		$query = $this->get_sync_tables();
		if($query->num_rows() > 0)
		{
			//initiate the response array
			
			foreach($query->result() as $res)
			{
				$sync_table_id = $res->sync_table_id;
				$sync_table_name = $res->sync_table_name;
				$table_key_name = $res->table_key_name;
				$function = $res->sync_table_cloud_save_function;
				
				if(isset($decoded->$sync_table_name))
				{
					$sync_data = $decoded->$sync_table_name;
					//var_dump($sync_data); die();
					//calculate total rows to be inserted of sync table
					$total_rows = count($sync_data);
					
					for($r = 0; $r < $total_rows; $r++)
					{
						$patient_data = $sync_data[$r];
						
						//insert data into the sync table
						$table_key_value = $this->$function($patient_data, $branch_code);
						
						if($table_key_value > 0)
						{
							$response[$sync_table_name][$r] = array(
										'response' => 'true',
										'remote_pk' => $table_key_value,
										'local_pk' => $patient_data->$table_key_name
							);
							
							$save_data = array(
								'branch_code'=>$branch_code,
								'sync_status'=>1,
								'sync_type'=>0,
								'sync_table_id'=>$sync_table_id,
								'sync_table_key'=>$table_key_value,
								'created'=>date('Y-m-d H:i:s')
							);
							$this->db->insert('sync', $save_data);
						}
						
						else
						{
							$save_data = array(
								'branch_code'=>$branch_code,
								'sync_status'=>0,
								'sync_type'=>0,
								'sync_table_id'=>$sync_table_id,
								'sync_table_key'=>$table_key_value,
								'created'=>date('Y-m-d H:i:s')
							);
							$this->db->insert('sync', $save_data);
							
							$response[$sync_table_name][$r]['response'] = 'false';
							$response[$sync_table_name][$r]['local_pk'] = $patient_data->$table_key_name;
						}
					}
				}
				
				else
				{
					$response[$sync_table_name]['response'] = 'false';
					$response[$sync_table_name]['message'] = $sync_table_name.' data does not exist';
				}
			}
		}

		else
		{
			$response['response'] = 'false';
			$response['message'] = 'No sync tables are set';
		}

		return $response;
	}

	public function get_sync_tables()
	{
		$this->db->where('sync_table_status', 1);
		$query = $this->db->get('sync_table');

		return $query;
	}

	public function save_patients($patients, $branch_code)
	{
		$res = $patients;

		$patient_number = $res->patient_number;
		
		//check if patient number exists
		$this->db->where('patient_number', $patient_number);
		$query = $this->db->get('patients');

		$data['patient_number'] = $patient_number;
		$data['patient_date'] = $res->patient_date;
		$data['patient_surname'] = $res->patient_surname;
		$data['patient_othernames'] = $res->patient_othernames;
		$data['patient_date_of_birth'] = $res->patient_date_of_birth;
		$data['patient_address'] = $res->patient_address;
		$data['patient_postalcode'] = $res->patient_postalcode;
		$data['patient_town'] = $res->patient_town;
		$data['patient_phone1'] = $res->patient_phone1;
		$data['patient_phone2'] = $res->patient_phone2;
		$data['patient_email'] = $res->patient_email;
		$data['patient_national_id'] = $res->patient_national_id;
		$data['patient_kin_othernames'] = $res->patient_kin_othernames;
		$data['patient_kin_sname'] = $res->patient_kin_sname;
		$data['patient_kin_phonenumber1'] = $res->patient_kin_phonenumber1;
		$data['patient_kin_phonenumber2'] = $res->patient_kin_phonenumber2;
		$data['patient_status'] = $res->patient_status;
		$data['patient_delete'] = $res->patient_delete;
		$data['relationship_id'] = $res->relationship_id;
		$data['civil_status_id'] = $res->civil_status_id;
		$data['title_id'] = $res->title_id;
		$data['religion_id'] = $res->religion_id;
		$data['gender_id'] = $res->gender_id;
		$data['nurse_notes'] = $res->nurse_notes;
		$data['created_by'] = $res->created_by;
		$data['modified_by'] = $res->modified_by;
		$data['last_visit'] = $res->last_visit;
		$data['last_modified'] = $res->last_modified;
		$data['deleted_by'] = $res->deleted_by;
		$data['date_deleted'] = $res->date_deleted;
		$data['branch_code'] = $res->branch_code;

		if($query->num_rows() > 0)
		{
			$row = $query->row();
			$patient_id = $row->patient_id;
			//update patient
			$this->db->where('patient_number', $patient_number);
			if($this->db->update('patients', $data))
			{
				return $patient_id;
			}
			else
			{
				return FALSE;
			}
		}

		//add new patient
		else
		{
			if($this->db->insert('patients', $data))
			{
				$patient_id = $this->db->insert_id();
				return $patient_id;
			}
			else
			{
				return FALSE;
			}
		}
	}

	public function save_visits($visits, $branch_code)
	{
		$res = $visits;

		$visit_id = $res->visit_id;
		
		//check if patient number exists
		$this->db->where(array('visit_id' => $visit_id, 'branch_code' => $branch_code));
		$query = $this->db->get('visit');
		
		$data['visit_date'] = $res->visit_date;
		$data['nurse_visit'] = $res->nurse_visit;
		$data['doc_visit'] = $res->doc_visit;
		$data['dental_visit'] = $res->dental_visit;
		$data['lab_visit'] = $res->lab_visit;
		$data['pharmarcy'] = $res->pharmarcy;
		$data['schedule_id'] = $res->schedule_id;
		$data['color_code_id'] = $res->color_code_id;
		$data['visit_symptoms'] = $res->visit_symptoms;
		$data['visit_objective_findings'] = $res->visit_objective_findings;
		$data['visit_assessment'] = $res->visit_assessment;
		$data['visit_plan'] = $res->visit_plan;
		$data['visit_time'] = $res->visit_time;
		$data['visit_time_out'] = $res->visit_time_out;
		$data['appointment_id'] = $res->appointment_id;
		$data['time_start'] = $res->time_start;
		$data['time_end'] = $res->time_end;
		$data['consultation_type_id'] = $res->consultation_type_id;
		$data['visit_type'] = $res->visit_type;
		$data['patient_id'] = $res->patient_id;
		$data['close_card'] = $res->close_card;
		$data['vitals_alert'] = $res->vitals_alert;
		$data['payment_insurance'] = $res->payment_insurance;
		$data['payment_cash'] = $res->payment_cash;
		$data['payment_mobilemoney'] = $res->payment_mobilemoney;
		$data['payment_cheque'] = $res->payment_cheque;
		$data['strath_no'] = $res->strath_no;
		$data['lab_visit_comment'] = $res->lab_visit_comment;
		$data['personnel_id'] = $res->personnel_id;
		$data['patient_insurance_id'] = $res->patient_insurance_id;
		$data['patient_insurance_number'] = $res->patient_insurance_number;
		$data['visit_delete'] = $res->visit_delete;
		$data['date_deleted'] = $res->date_deleted;
		$data['deleted_by'] = $res->deleted_by;
		$data['bill_to_id'] = $res->bill_to_id;
		$data['total_payments'] = $res->total_payments;
		$data['total_debit_notes'] = $res->total_debit_notes;
		$data['total_credit_notes'] = $res->total_credit_notes;
		$data['consultation'] = $res->consultation;
		$data['counseling'] = $res->counseling;
		$data['dental'] = $res->dental;
		$data['ecg'] = $res->ecg;
		$data['laboratory'] = $res->laboratory;
		$data['nursing_fee'] = $res->nursing_fee;
		$data['paediatrics'] = $res->paediatrics;
		$data['pharmacy'] = $res->pharmacy;
		$data['physician'] = $res->physician;
		$data['physiotherapy'] = $res->physiotherapy;
		$data['procedures'] = $res->procedures;
		$data['radiology'] = $res->radiology;
		$data['ultrasound'] = $res->ultrasound;
		$data['cash'] = $res->cash;
		$data['cheque'] = $res->cheque;
		$data['mpesa'] = $res->mpesa;
		$data['clinic_meds'] = $res->clinic_meds;
		$data['branch_code'] = $res->branch_code;
		$data['insurance_limit'] = $res->insurance_limit;

		if($query->num_rows() > 0)
		{
			$row = $query->row();
			$patient_id = $row->patient_id;
			//update patient
			$this->db->where('visit_id', $visit_id);
			if($this->db->update('visit', $data))
			{
				return $visit_id;
			}
			else
			{
				return FALSE;
			}
		}

		//add new patient
		else
		{
			if($this->db->insert('visit', $data))
			{
				$visit_id = $this->db->insert_id();
				return $visit_id;
			}
			else
			{
				return FALSE;
			}
		}
	}
	
	public function save_visit_charges($visit_charges, $branch_code)
	{
		$res = $visit_charges;

		$visit_charge_id = $res->visit_charge_id;
		
		//check if patient number exists
		$this->db->where('visit_charge.visit_id = visit.visit_id AND visit_charge.visit_charge_id = '.$visit_charge_id.' AND visit.branch_code = \''.$branch_code.'\'');
		$query = $this->db->get('visit_charge, visit');
		
		$data['visit_id'] = $res->visit_id;
		$data['service_charge_id'] = $res->service_charge_id;
		$data['visit_charge_timestamp'] = $res->visit_charge_timestamp;
		$data['visit_charge_amount'] = $res->visit_charge_amount;
		$data['visit_charge_qty'] = $res->visit_charge_qty;
		$data['visit_charge_units'] = $res->visit_charge_units;
		$data['visit_charge_results'] = $res->visit_charge_results;
		$data['visit_charge_comment'] = $res->visit_charge_comment;
		$data['date'] = $res->date;
		$data['time'] = $res->time;
		$data['visit_charge_delete'] = $res->visit_charge_delete;
		$data['deleted_by'] = $res->deleted_by;
		$data['deleted_on'] = $res->deleted_on;
		$data['created_by'] = $res->created_by;
		$data['modified_by'] = $res->modified_by;
		$data['date_modified'] = $res->date_modified;

		if($query->num_rows() > 0)
		{
			//update patient
			$this->db->where('visit_charge_id', $visit_charge_id);
			if($this->db->update('visit_charge', $data))
			{
				return $visit_charge_id;
			}
			else
			{
				return FALSE;
			}
		}

		//add new patient
		else
		{
			if($this->db->insert('visit_charge', $data))
			{
				$visit_charge_id = $this->db->insert_id();
				return $visit_charge_id;
			}
			else
			{
				return FALSE;
			}
		}
	}
	
	public function save_visit_department($visit_department, $branch_code)
	{
		$res = $visit_department;

		$visit_department_id = $res->visit_department_id;
		
		//check if patient number exists
		$this->db->where('visit_department.visit_id = visit.visit_id AND visit_department.visit_department_id = '.$visit_department_id.' AND visit.branch_code = \''.$branch_code.'\'');
		$query = $this->db->get('visit_department, visit');
		
		$data['visit_id'] = $res->visit_id;
		$data['department_id'] = $res->department_id;
		$data['visit_department_status'] = $res->visit_department_status;
		$data['created_by'] = $res->created_by;
		$data['modified_by'] = $res->modified_by;
		$data['date_modified'] = $res->date_modified;
		$data['created'] = date("Y-m-d H:i:s");

		if($query->num_rows() > 0)
		{
			//update patient
			$this->db->where('visit_department_id', $visit_department_id);
			if($this->db->update('visit_department', $data))
			{
				return $visit_charge_id;
			}
			else
			{
				return FALSE;
			}
		}

		//add new patient
		else
		{
			if($this->db->insert('visit_department', $data))
			{
				$visit_department_id = $this->db->insert_id();
				return $visit_department_id;
			}
			else
			{
				return FALSE;
			}
		}
	}

	public function save_visit_lab_test($visit_lab_test, $branch_code)
	{
		$res = $visit_lab_test;

		$visit_lab_test_id = $res->visit_lab_test_id;
		
		//check if patient number exists
		$this->db->where('visit_lab_test.visit_id = visit.visit_id AND visit_lab_test.visit_lab_test_id = '.$visit_lab_test_id.' AND visit.branch_code = \''.$branch_code.'\'');
		$query = $this->db->get('visit_lab_test, visit');
		
		$data['visit_id'] = $res->visit_id;
		$data['service_charge_id'] = $res->service_charge_id;
		$data['units'] = $res->units;
		$data['created_by'] = $res->created_by;
		$data['modified_by'] = $res->modified_by;

		$data['visit_lab_test_status'] = $res->visit_lab_test_status;
		$data['visit_lab_test_comment'] = $res->visit_lab_test_comment;
		$data['visit_lab_test_results'] = $res->visit_lab_test_results;
		$data['date_modified'] = $res->date_modified;
		$data['created'] = date("Y-m-d H:i:s");


		if($query->num_rows() > 0)
		{
			//update patient
			$this->db->where('visit_lab_test_id', $visit_lab_test_id);
			if($this->db->update('visit_lab_test', $data))
			{
				return $visit_charge_id;
			}
			else
			{
				return FALSE;
			}
		}

		//add new patient
		else
		{
			if($this->db->insert('visit_lab_test', $data))
			{
				$visit_lab_test_id = $this->db->insert_id();
				return $visit_lab_test_id;
			}
			else
			{
				return FALSE;
			}
		}
	}
	public function save_visit_objective_findings($visit_objective_findings, $branch_code)
	{
		$res = $visit_objective_findings;

		$visit_objective_findings_id = $res->visit_objective_findings_id;
		
		//check if patient number exists
		$this->db->where('visit_objective_findings.visit_id = visit.visit_id AND visit_objective_findings.visit_objective_findings_id = '.$visit_objective_findings_id.' AND visit.branch_code = \''.$branch_code.'\'');
		$query = $this->db->get('visit_objective_findings, visit');
		
		$data['visit_id'] = $res->visit_id;
		$data['objective_findings_id'] = $res->objective_findings_id;
		$data['description'] = $res->description;

		if($query->num_rows() > 0)
		{
			//update patient
			$this->db->where('visit_objective_findings_id', $visit_objective_findings_id);
			if($this->db->update('visit_objective_findings', $data))
			{
				return $visit_charge_id;
			}
			else
			{
				return FALSE;
			}
		}

		//add new patient
		else
		{
			if($this->db->insert('visit_objective_findings', $data))
			{
				$visit_objective_findings_id = $this->db->insert_id();
				return $visit_objective_findings_id;
			}
			else
			{
				return FALSE;
			}
		}
	}
	public function save_visit_procedure($visit_procedure, $branch_code)
	{
		$res = $visit_procedure;

		$visit_procedure_id = $res->visit_procedure_id;
		
		//check if patient number exists
		$this->db->where('visit_procedure.visit_id = visit.visit_id AND visit_procedure.visit_procedure_id = '.$visit_procedure_id.' AND visit.branch_code = \''.$branch_code.'\'');
		$query = $this->db->get('visit_procedure, visit');
		
		$data['visit_id'] = $res->visit_id;
		$data['procedure_id'] = $res->procedure_id;
		$data['units'] = $res->units;
		$data['created_by'] = $res->created_by;
		$data['modified_by'] = $res->modified_by;
		$data['date_modified'] = $res->date_modified;
		$data['visit_procedure_status'] = $res->visit_procedure_status;
		$data['created'] = date("Y-m-d H:i:s");


		if($query->num_rows() > 0)
		{
			//update patient
			$this->db->where('visit_procedure_id', $visit_procedure_id);
			if($this->db->update('visit_procedure', $data))
			{
				return $visit_charge_id;
			}
			else
			{
				return FALSE;
			}
		}

		//add new patient
		else
		{
			if($this->db->insert('visit_procedure', $data))
			{
				$visit_procedure_id = $this->db->insert_id();
				return $visit_procedure_id;
			}
			else
			{
				return FALSE;
			}
		}
	}


	public function save_visit_symptoms($visit_symptoms, $branch_code)
	{
		$res = $visit_symptoms;

		$symptoms_id = $res->symptoms_id;
		
		//check if patient number exists
		$this->db->where('visit_symptoms.visit_id = visit.visit_id AND visit_symptoms.symptoms_id = '.$symptoms_id.' AND visit.branch_code = \''.$branch_code.'\'');
		$query = $this->db->get('visit_symptoms, visit');
		
		$data['visit_id'] = $res->visit_id;
		$data['visit_symptoms_id'] = $res->visit_symptoms_id;
		$data['status_id'] = $res->status_id;
		$data['description'] = $res->description;


		if($query->num_rows() > 0)
		{
			//update patient
			$this->db->where('symptoms_id', $symptoms_id);
			if($this->db->update('visit_symptoms', $data))
			{
				return $visit_charge_id;
			}
			else
			{
				return FALSE;
			}
		}

		//add new patient
		else
		{
			if($this->db->insert('visit_symptoms', $data))
			{
				$symptoms_id = $this->db->insert_id();
				return $symptoms_id;
			}
			else
			{
				return FALSE;
			}
		}
	}

	public function save_visit_vaccine($visit_vaccine, $branch_code)
	{
		$res = $visit_vaccine;

		$visit_vaccine_id = $res->visit_vaccine_id;
		
		//check if patient number exists
		$this->db->where('visit_vaccine.visit_id = visit.visit_id AND visit_vaccine.visit_vaccine_id = '.$visit_vaccine_id.' AND visit.branch_code = \''.$branch_code.'\'');
		$query = $this->db->get('visit_vaccine, visit');
		
		$data['visit_id'] = $res->visit_id;
		$data['vaccine_id'] = $res->vaccine_id;
		$data['units'] = $res->units;
		$data['created_by'] = $res->created_by;
		$data['modified_by'] = $res->modified_by;
		$data['date_modified'] = $res->date_modified;
		$data['visit_vaccine_status'] = $res->visit_vaccine_status;
		$data['created'] = date("Y-m-d H:i:s");


		if($query->num_rows() > 0)
		{
			//update patient
			$this->db->where('visit_vaccine_id', $visit_vaccine_id);
			if($this->db->update('visit_vaccine', $data))
			{
				return $visit_charge_id;
			}
			else
			{
				return FALSE;
			}
		}

		//add new patient
		else
		{
			if($this->db->insert('visit_vaccine', $data))
			{
				$visit_vaccine_id = $this->db->insert_id();
				return $visit_vaccine_id;
			}
			else
			{
				return FALSE;
			}
		}
	}
	public function save_visit_vital($visit_vital, $branch_code)
	{
		$res = $visit_vital;

		$visit_vital_id = $res->visit_vital_id;
		
		//check if patient number exists
		$this->db->where('visit_vital.visit_id = visit.visit_id AND visit_vital.visit_vital_id = '.$visit_vital_id.' AND visit.branch_code = \''.$branch_code.'\'');
		$query = $this->db->get('visit_vital, visit');
		
		$data['visit_id'] = $res->visit_id;
		$data['vital_id'] = $res->vital_id;
		$data['visit_vital_value'] = $res->visit_vital_value;
		$data['visit_vitals_time'] = $res->visit_vitals_time;
		$data['visit_counter'] = $res->visit_counter;
		$data['created_by'] = $res->created_by;
		$data['created'] = date("Y-m-d H:i:s");


		if($query->num_rows() > 0)
		{
			//update patient
			$this->db->where('visit_vital_id', $visit_vital_id);
			if($this->db->update('visit_vital', $data))
			{
				return $visit_charge_id;
			}
			else
			{
				return FALSE;
			}
		}

		//add new patient
		else
		{
			if($this->db->insert('visit_vital', $data))
			{
				$visit_vital_id = $this->db->insert_id();
				return $visit_vital_id;
			}
			else
			{
				return FALSE;
			}
		}
	}
	
	public function save_pres($pres, $branch_code)
	{
		$res = $pres;

		$prescription_id = $res->prescription_id;
		
		//check if patient number exists
		$this->db->where('pres.visit_id = visit.visit_id AND pres.prescription_id = '.$prescription_id.' AND visit.branch_code = \''.$branch_code.'\'');
		$query = $this->db->get('pres, visit');
		
		$data['visit_id'] = $res->visit_id;
		$data['visit_charge_id'] = $res->visit_charge_id;
		$data['service_charge_id'] = $res->service_charge_id;
		$data['prescription_quantity'] = $res->prescription_quantity;
		$data['units_given'] = $res->units_given;
		$data['prescription_substitution'] = $res->prescription_substitution;

		$data['comment'] = $res->comment;
		$data['prescription_startdate'] = $res->prescription_startdate;
		$data['prescription_finishdate'] = $res->prescription_finishdate;
		$data['drug_times_id'] = $res->drug_times_id;
		$data['prescription_date'] = $res->prescription_date;

		$data['drug_duration_id'] = $res->drug_duration_id;
		$data['drug_consumption_id'] = $res->drug_consumption_id;
		$data['number_of_days'] = $res->number_of_days;

		$data['created'] = date("Y-m-d H:i:s");



		if($query->num_rows() > 0)
		{
			//update patient
			$this->db->where('prescription_id', $prescription_id);
			if($this->db->update('pres', $data))
			{
				return $visit_charge_id;
			}
			else
			{
				return FALSE;
			}
		}

		//add new patient
		else
		{
			if($this->db->insert('pres', $data))
			{
				$prescription_id = $this->db->insert_id();
				return $prescription_id;
			}
			else
			{
				return FALSE;
			}
		}
	}

	public function save_lab_visit_results($lab_visit_results, $branch_code)
	{
		$res = $lab_visit_results;

		$lab_visit_results_id = $res->lab_visit_results_id;
		
		//check if patient number exists
		$this->db->where('lab_visit_results.visit_id = visit.visit_id AND lab_visit_results.lab_visit_results_id = '.$lab_visit_results_id.' AND visit.branch_code = \''.$branch_code.'\'');
		$query = $this->db->get('lab_visit_results, visit');
		
		$data['visit_id'] = $res->visit_id;
		$data['lab_visit_results_result'] = $res->lab_visit_results_result;
		$data['visit_charge_id'] = $res->visit_charge_id;
		$data['lab_visit_result_format'] = $res->lab_visit_result_format;



		if($query->num_rows() > 0)
		{
			//update patient
			$this->db->where('lab_visit_results_id', $lab_visit_results_id);
			if($this->db->update('lab_visit_results', $data))
			{
				return $visit_charge_id;
			}
			else
			{
				return FALSE;
			}
		}

		//add new patient
		else
		{
			if($this->db->insert('lab_visit_results', $data))
			{
				$lab_visit_results_id = $this->db->insert_id();
				return $lab_visit_results_id;
			}
			else
			{
				return FALSE;
			}
		}
	}

	public function save_doctor_notes($doctor_notes, $branch_code)
	{
		$res = $doctor_notes;

		$doctor_note_id = $res->doctor_note_id;
		
		//check if patient number exists
		$this->db->where('doctor_notes.patient_id = patients.patient_id AND doctor_notes.doctor_note_id = '.$doctor_note_id.' AND patients.branch_code = \''.$branch_code.'\'');
		$query = $this->db->get('doctor_notes, patients');
		
		$data['patient_id'] = $res->patient_id;
		$data['doctor_notes'] = $res->doctor_notes;

		if($query->num_rows() > 0)
		{
			//update patient
			$this->db->where('doctor_notes_id', $doctor_notes_id);
			if($this->db->update('doctor_notes', $data))
			{
				return $visit_charge_id;
			}
			else
			{
				return FALSE;
			}
		}

		//add new patient
		else
		{
			if($this->db->insert('doctor_notes', $data))
			{
				$doctor_note_id = $this->db->insert_id();
				return $doctor_note_id;
			}
			else
			{
				return FALSE;
			}
		}
	}
	public function save_nurse_notes($nurse_notes, $branch_code)
	{
		$res = $nurse_notes;

		$note_id = $res->note_id;
		
		//check if patient number exists
		$this->db->where('nurse_notes.patient_id = patients.patient_id AND nurse_notes.visit_id = visit.visit_id AND nurse_notes.note_id = '.$note_id.' AND patients.branch_code = \''.$branch_code.'\'');
		$query = $this->db->get('nurse_notes, patients,visit');
		
		$data['patient_id'] = $res->patient_id;
		$data['visit_id'] = $res->visit_id;
		$data['created_by'] = $res->created_by;
		$data['created'] = $res->created;


		if($query->num_rows() > 0)
		{
			//update patient
			$this->db->where('note_id', $note_id);
			if($this->db->update('nurse_notes', $data))
			{
				return $visit_charge_id;
			}
			else
			{
				return FALSE;
			}
		}

		//add new patient
		else
		{
			if($this->db->insert('nurse_notes', $data))
			{
				$note_id = $this->db->insert_id();
				return $note_id;
			}
			else
			{
				return FALSE;
			}
		}
	}
	public function save_doctor_patient_notes($doctor_patient_notes, $branch_code)
	{
		$res = $doctor_patient_notes;

		$doctor_notes_id = $res->doctor_notes_id;
		
		//check if patient number exists
		$this->db->where('doctor_patient_notes.patient_id = patients.patient_id AND doctor_patient_notes.visit_id = visit.visit_id AND doctor_patient_notes.doctor_notes_id = '.$doctor_notes_id.' AND patients.branch_code = \''.$branch_code.'\'');
		$query = $this->db->get('doctor_patient_notes, patients,visit');
		
		$data['patient_id'] = $res->patient_id;
		$data['visit_id'] = $res->visit_id;
		$data['added_by'] = $res->added_by;
		$data['created'] = $res->created;
		$data['doctor_notes'] = $res->doctor_notes;


		if($query->num_rows() > 0)
		{
			//update patient
			$this->db->where('doctor_notes_id', $doctor_notes_id);
			if($this->db->update('doctor_patient_notes', $data))
			{
				return $visit_charge_id;
			}
			else
			{
				return FALSE;
			}
		}

		//add new patient
		else
		{
			if($this->db->insert('doctor_patient_notes', $data))
			{
				$doctor_notes_id = $this->db->insert_id();
				return $doctor_notes_id;
			}
			else
			{
				return FALSE;
			}
		}
	}
	public function save_nurse_patient_notes($nurse_patient_notes, $branch_code)
	{
		$res = $nurse_patient_notes;

		$nurse_notes_id = $res->nurse_notes_id;
		
		//check if patient number exists
		$this->db->where('nurse_patient_notes.patient_id = patients.patient_id AND nurse_patient_notes.visit_id = visit.visit_id AND nurse_patient_notes.nurse_notes_id = '.$nurse_notes_id.' AND patients.branch_code = \''.$branch_code.'\'');
		$query = $this->db->get('nurse_patient_notes, patients,visit');
		
		$data['patient_id'] = $res->patient_id;
		$data['visit_id'] = $res->visit_id;
		$data['added_by'] = $res->added_by;
		$data['created'] = $res->created;
		$data['nurse_notes'] = $res->nurse_notes;


		if($query->num_rows() > 0)
		{
			//update patient
			$this->db->where('nurse_notes_id', $nurse_notes_id);
			if($this->db->update('nurse_patient_notes', $data))
			{
				return $visit_charge_id;
			}
			else
			{
				return FALSE;
			}
		}

		//add new patient
		else
		{
			if($this->db->insert('nurse_patient_notes', $data))
			{
				$nurse_notes_id = $this->db->insert_id();
				return $nurse_notes_id;
			}
			else
			{
				return FALSE;
			}
		}
	}
	public function save_payments($payments, $branch_code)
	{
		$res = $payments;

		$payment_id = $res->payment_id;
		
		//check if patient number exists
		$this->db->where('payments.visit_id = visit.visit_id AND payments.payment_id = '.$payment_id.' AND visit.branch_code = \''.$branch_code.'\'');
		$query = $this->db->get('payments, visit');
		
		$data['visit_id'] = $res->visit_id;
		$data['personnel_id'] = $res->personnel_id;
		$data['amount_paid'] = $res->amount_paid;
		$data['time'] = $res->time;
		$data['payment_method_id'] = $res->payment_method_id;
		$data['confirm_number'] = $res->confirm_number;

		$data['payment_type'] = $res->payment_type;
		$data['payment_created'] = $res->payment_created;
		$data['payment_created_by'] = $res->payment_created_by;
		$data['payment_service_id'] = $res->payment_service_id;
		$data['transaction_code'] = $res->transaction_code;

		$data['payment_status'] = $res->payment_status;
		$data['modified_by'] = $res->modified_by;
		$data['modified_on'] = $res->modified_on;
		$data['approved_by'] = $res->approved_by;
		$data['date_approved'] = $res->date_approved;



		if($query->num_rows() > 0)
		{
			//update patient
			$this->db->where('payment_id', $payment_id);
			if($this->db->update('payments', $data))
			{
				return $visit_charge_id;
			}
			else
			{
				return FALSE;
			}
		}

		//add new patient
		else
		{
			if($this->db->insert('payments', $data))
			{
				$payment_id = $this->db->insert_id();
				return $payment_id;
			}
			else
			{
				return FALSE;
			}
		}
	}


	public function save_surgery($surgery, $branch_code)
	{
		$res = $surgery;

		$surgery_id = $res->surgery_id;
		
		//check if patient number exists
		$this->db->where('surgery.patient_id = patients.patient_id AND surgery.surgery_id = '.$surgery_id.' AND patients.branch_code = \''.$branch_code.'\'');
		$query = $this->db->get('surgery, patients');
		
		$data['patient_id'] = $res->patient_id;
		$data['surgery_year'] = $res->surgery_year;
		$data['month_id'] = $res->month_id;
		$data['surgery_description'] = $res->surgery_description;


		if($query->num_rows() > 0)
		{
			//update patient
			$this->db->where('surgery_id', $surgery_id);
			if($this->db->update('surgery', $data))
			{
				return $visit_charge_id;
			}
			else
			{
				return FALSE;
			}
		}

		//add new patient
		else
		{
			if($this->db->insert('surgery', $data))
			{
				$surgery_id = $this->db->insert_id();
				return $surgery_id;
			}
			else
			{
				return FALSE;
			}
		}
	}
	
	public function parse_sync_up_response($result)
	{	
		$sync_response = json_decode($result);
		/*$patients = $sync_response->patients;
		$location = $patients[0];
		var_dump($location->response);*/
		
		//get sync tables
		$query = $this->get_sync_tables();
		if($query->num_rows() > 0)
		{
			//initiate the response array
			$count = 0;
			
			foreach($query->result() as $res)
			{
				$sync_table_name = $res->sync_table_name;
				$sync_table_id = $res->sync_table_id;
				$table_key_name = $res->table_key_name;
				$function = $res->sync_table_cloud_save_function;
				
				if(isset($sync_response->$sync_table_name))
				{
					$response_data = $sync_response->$sync_table_name;
					//var_dump($sync_data); die();
					//calculate total rows to be inserted of sync table
					$total_rows = count($response_data);
					
					for($r = 0; $r < $total_rows; $r++)
					{
						$patient_data = $response_data[$r];
						$reply = $patient_data->response;
						$remote_pk = $patient_data->remote_pk;
						$local_pk = $patient_data->local_pk;
						$sync_id = $patient_data->sync_id;
						
						//update sync table if response is true
						if($response == 'true')
						{
							$data = array(
								'remote_pk' => $remote_pk,
								'sync_status' => 1
							);
							$this->db->where('sync_table_id = '.$sync_table_id.' AND sync_table_key = '.$local_pk);
							$this->db->update('sync', $data);
							
							echo 'Sync successfull<br/>';
						}
						
						else
						{
							echo 'Sync failed<br/>';
						}
					}
				}
				
				else
				{
					echo $sync_table_name.' data does not exist<br/>';
				}
			}
		}

		else
		{
			echo 'No sync tables are set<br/>';
		}
	}
	
	public function get_unsynced_visits()
	{
		$this->db->select('sync.*, sync_table.sync_table_name');
		$this->db->where('sync_status = 0 AND sync_table.sync_table_id = sync.sync_table_id');
		return $this->db->get('sync, sync_table');
	}
	
	public function send_unsynced_visits($patient_details)
	{
			$url = 'http://159.203.78.242/cloud/save_cloud_data';
			//Encode the array into JSON.

			//The JSON data.
			$data_string = json_encode($patient_details);
		
			try{                                                                                                         

				$ch = curl_init($url);                                                                      
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
				curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                                                                  
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
				curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
					'Content-Type: application/json',                                                                                
					'Content-Length: ' . strlen($data_string))                                                                       
				);                                                                                                                     
				$result = curl_exec($ch);
				curl_close($ch);
				$response = $this->parse_sync_up_response($result);
			}
			catch(Exception $e)
			{
				$response = "something went wrong";
				echo json_encode($response.' '.$e);
			}
	}
}
?>