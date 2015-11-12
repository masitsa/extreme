<?php
class Sync_model extends CI_Model 
{
	public function syn_up_on_closing_visit($visit_id)
	{
		// get the patient id and the branch id and patient
		$patient_details = $this->sync_model->get_table_details($visit_id);
		// var_dump($patient_details); die();
		if(count($patient_details) > 0)
		{
			// $url = 'http://localhost/erp/cloud/save_cloud_data';
			  // var_dump(json_encode($patient_details)) or die();
			$url = 'http://159.203.78.242/cloud/save_cloud_data';			
			$test_url = 'http://159.203.78.242/cloud/test';
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
				$response = $this->sync_model->parse_sync_up_response($result);

				 // $response = $result;
				 // echo json_encode($response);
			}
			catch(Exception $e)
			{
				$response = "something went wrong";
				echo json_encode($response.' '.$e);
			}
		}
		else
		{
			$response = "no data to sync";
			echo json_encode($response);
		}
		
		return $response;
	}

	public function get_doctor_fee($personnel_id,$visit_id)
	{
		$this->db->select('visit_charge.visit_charge_amount');
		$this->db->where('visit_charge.service_charge_id = service_charge.service_charge_id AND service_charge.service_id = service.service_id AND service.service_name = "Consultation" AND visit_charge.visit_id = '.$visit_id);
		$query = $this->db->get('visit_charge, service_charge, service');

		if($query->num_rows() > 0)
		{
			foreach ($query->result() as $key) {
				# code...
				$visit_charge_amount = $key->visit_charge_amount;
			}
			$this->db->select('personnel_type.personnel_type_id');
			$this->db->where('personnel.personnel_type_id = personnel_type.personnel_type_id AND personnel.personnel_id = '.$personnel_id);
			$personnel = $this->db->get('personnel,personnel_type');
			if($personnel->num_rows() > 0)
			{
				foreach ($personnel->result() as $key_result) {
					# code...
					$personnel_type_id = $key_result->personnel_type_id;
				}
			}
			else
			{
				$personnel_type_id = 0;
			}
			//consultant
				if($personnel_type_id == 2)
				{
					$charge_amount = $visit_charge_amount;
					
				}
				
				//radiographer
				elseif($personnel_type_id == 3)
				{
					$charge_amount = 0.3 * $visit_charge_amount;
					
				}
				
				//medical officer
				elseif($personnel_type_id == 4)
				{
					$hours_worked = $this->reports_model->calculate_hours_worked($personnel_id, $date_from = date('Y-m-d'), $date_to = date('Y-m-d'));
					$charge_amount = 500 * $hours_worked;
					
				}
				
				//clinic officer
				elseif($personnel_type_id == 5)
				{
					$days_worked = $this->reports_model->calculate_days_worked($personnel_id, $date_from = date('Y-m-d'), $date_to = date('Y-m-d'));
					$charge_amount = 1000 * $days_worked;
					
				}
			// pud the doctors calculations

		}
		else
		{
			$charge_amount = 0;
		}
		return $charge_amount;
	}
	public function sync_patient_bookings($visit_id)
	{
		$where = 'visit.patient_id = patients.patient_id AND visit.branch_code = "'.$this->session->userdata('branch_code').'" AND visit.visit_id = '.$visit_id;

		$this->db->where($where);
		$this->db->select('patients.*,visit.visit_date,visit.visit_time,visit.personnel_id AS doctor_id');
		$query_patients = $this->db->get('patients,visit');
		$patients['bookings'] = array();

		if($query_patients->num_rows() > 0)
		{
			foreach ($query_patients->result() as $value) {
				$personnel_id = $value->doctor_id;
				$amount = $this->get_doctor_fee($personnel_id,$visit_id);
				$value = array();
				$value['amount'] = $amount;
				$value['visit_id'] = $visit_id;
				$value['branch_code'] = $this->session->userdata('branch_code');
				// get doctors figure 
			 	array_push($patients['bookings'], $value);
			}
		}

		$patient_details = $patients;

		 // var_dump($patient_details) or die();
		if(count($patient_details) > 0)
		{
			$url = 'http://159.203.78.242/cloud/save_booking_data';
			// $test_url = 'http://159.203.78.242/cloud/test';
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
				// $response = $this->sync_model->parse_sync_up_response($result);

				  $response = $result;
				 // echo json_encode($response);
			}
			catch(Exception $e)
			{
				$response = "something went wrong";
				echo json_encode($response.' '.$e);
			}
		}
		else
		{
			$response = "no data to sync";
			echo json_encode($response);
		}
		
		return $response;
	}
	
	public function get_table_details($visit_id)
	{
		$table_sync_array = $this->sync_model->get_all_tables_sync();
		
		$patients = array();
		$counter = $table_sync_array->num_rows();
		// var_dump($counter); die();
		if($table_sync_array->num_rows() > 0)
		{
			$patients['branch_code'] = $this->session->userdata('branch_code');

			
			// loop the tables
			foreach ($table_sync_array->result() as $key)
			{
				// get the table sync items
				$sync_table_name = $key->sync_table_name;
				$sync_table_id = $key->sync_table_id;
				$branch_code = $key->branch_code;
				$table_key_name = $key->table_key_name;
				
				if($sync_table_name == 'patients')
				{
					$where = 'visit.patient_id = patients.patient_id AND patients.branch_code = "'.$this->session->userdata('branch_code').'"  AND visit.branch_code = "'.$this->session->userdata('branch_code').'" AND visit.visit_id = '.$visit_id;
					// var_dump($where); die();
					$this->db->where($where);
					$this->db->select('patients.*');
					$query_patients = $this->db->get('patients,visit');

					$patients[$sync_table_name] = array();
					// var_dump($where); die();
					if($query_patients->num_rows() > 0)
					{
						foreach ($query_patients->result() as $value) {
							# code...
							$table_key = $key->table_key_name;
							
							$sync_data = array(
								'branch_code'=>$this->session->userdata('branch_code'),
								'sync_status'=>0,
								'sync_type'=>0,
								'sync_table_id'=>$sync_table_id,
								'sync_table_key'=>$table_key,
								'created'=>date('Y-m-d H:i:s'),
								'created_by'=>$this->session->userdata('personnel_id'),
								'last_modified_by'=>$this->session->userdata('personnel_id'),
								//'sync_data' => $value
							);
							$this->db->insert('sync', $sync_data);
						 	array_push($patients[$sync_table_name], $value);
						}
					}
					
				}
				else
				{
					$this->db->where('visit_id = '.$visit_id);
					$query = $this->db->get($sync_table_name);

					$patients[$sync_table_name] = array();
					
					if($query->num_rows() > 0)
					{
						foreach ($query->result() as $key) {
							# code...
							// save item instruction to the sync table

							$table_key = $key->$table_key_name;

							$date = date("Y-m-d H:i:s");
							$sync_data = array(
								'branch_code'=>$this->session->userdata('branch_code'),
								'sync_status'=>0,
								'sync_type'=>0,
								'sync_table_id'=>$sync_table_id,
								'sync_table_key'=>$table_key,
								'created'=>date('Y-m-d H:i:s'),
								'created_by'=>$this->session->userdata('personnel_id'),
								'last_modified_by'=>$this->session->userdata('personnel_id'),
								//'sync_data' => $key
							);
							$this->db->insert('sync', $sync_data);
							array_push($patients[$sync_table_name], $key);
						}
						
					}

				}
			}


		}
		else
		{

		}

		return $patients;

	}
	public function parse_sync_up_response($result)
	{	
		$sync_response = json_decode($result);
		//var_dump($sync_response);
		/*$patients = $sync_response->patients;
		$location = $patients[0];
		var_dump($location->response);*/
		
		//get sync tables
		$query = $this->sync_model->get_all_tables_sync();
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
						
						//update sync table if response is true
						if($reply == 'true')
						{
							$data = array(
								'remote_pk' => $remote_pk,
								'sync_status' => 1
							);
							$this->db->where('sync_table_id = '.$sync_table_id.' AND sync_table_key = '.$local_pk);
							$this->db->update('sync', $data);
							
							$response = 'Sync successfull<br/>';
						}
						
						else
						{
							$response = 'Sync failed<br/>';
						}
					}
				}
				
				else
				{
					$response = $sync_table_name.' data does not exist<br/>';
				}
			}
		}

		else
		{
			$response = 'No sync tables are set<br/>';
		}
		
		return $response;
	}
	public function get_all_tables_sync()
	{
		$where = 'branch_code = "'.$this->session->userdata('branch_code').'" AND sync_table_status = 1';

		$this->db->where($where);

		$query = $this->db->get('sync_table');

		return $query;
	}
	public function ondemand_sync_up()
	{
		$patient_details = $this->sync_model->cron_up_sync();

		if(count($patient_details) > 0)
		{
			$test_url = 'http://159.203.78.242/cloud/test';
			$url = 'http://159.203.78.242/cloud/save_cloud_data';
			//Encode the array into JSON.

			//The JSON data.
			$data_string = json_encode($patient_details);
			//var_dump($data_string);
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
				
			}
			catch(Exception $e)
			{
				return "something went wrong";
					
			}
		}
		else
		{
			echo "no data to sync";
		}
	}
	public function cron_up_sync()
	{
		// get all the none synced data from 
		$this->db->where('sync_table.sync_table_id = sync.sync_table_id AND sync.branch_code ="'.$this->session->userdata('branch_code').'" AND sync.sync_status = 0');
		$query = $this->db->get('sync,sync_table');

		if($query->num_rows() > 0)
		{
			foreach ($query->result() as $key) {
				# code...
				$sync_table_name = $key->sync_table_name;
				$sync_table_key = $key->sync_table_key;
				$table_key_name = $key->table_key_name;

				// use the table name to get the data of the table and array push it
				$this->db->where($table_key_name,$sync_table_id);
				$data_query = $this->db->get($sync_table_name);

				// initialize an array to push 
				if($data_query->num_rows() > 0)
				{
					$arrayCron[$sync_table_name] = array();

					foreach ($data_query->result as $value) {
						# code...
						array_push($arrayCron[$sync_table_name], $value);
					}
				}
			}
		}
		else
		{
			return FALSE;
		}

		return $arrayCron;


	}

	public function sync_data_down($branch_code = 'OSH')
	{
		// $json = file_get_contents('php://input');

		//  $json = '[{"branch_detail":"patients","response":"true","remote_pk":"1","local_pk":"1"}]';
    	
		// $decoded = json_decode($json);
		// var_dump($decoded->branch_detail);die();
		// if(isset($decoded->branch_detail))
		// {
		// 	$sync_data = $decoded->$branch_detail;
			$branch_code = 'KA';

			// now get all the data not syced by the the branch and the branch code on sync table is not the branch code
			$this->db->where('sync.sync_table_id = sync_table.sync_table_id AND sync.sync_id NOT IN (SELECT sync_id FROM branch_sync WHERE branch_code = "'.$branch_code.'" AND branch_sync_status = 1) AND sync.branch_code <> "'.$branch_code.'" ');
			$query = $this->db->get('sync,sync_table');

			if($query->num_rows() > 0)
			{
				// data was found, use now the information of the table and branch code to get informaiton about the remote key
				foreach ($query->result() as $key) {
					# code...
					$sync_table_name = $key->sync_table_name;
					$sync_table_id = $key->sync_table_id;
					$branch_code = $key->branch_code;
					$table_key_name = $key->table_key_name;
					$remote_pk = $key->remote_pk;

					// get data for each of the table that has not been synced down
					$this->db->where($table_key_name,$remote_pk);
					$data_query = $this->db->get($sync_table_name);

					if($data_query->num_rows() > 0)
					{
						// there is data for this pk
						$arraySend[$sync_table_name] = array();
						foreach ($data_query->result() as $value) {
							# code...
							array_push($arraySend[$sync_table_name], $value);
						}
					}


				}
				
			}

		// }
		var_dump($arraySend)or die();

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

	

	// petty cash sync

	public function syn_up_petty_cash()
	{
		// get the data to sync up from petty cash

		$petty_cash_response = $this->sync_model->get_petty_cash_table_details();
		// var_dump(json_encode($petty_cash_response)); die();
		if(count($petty_cash_response) > 0)
		{
			$url = 'http://159.203.78.242/cloud/sync_up_petty_cash';	
			//Encode the array into JSON.
			
			//The JSON data.

			$data_string = json_encode($petty_cash_response);
			var_dump($data_string);
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
				// $response = $this->sync_model->parse_sync_up_response($result);

				return  $response = $result;
				 // echo json_encode($response);
			}
			catch(Exception $e)
			{
				$response = "something went wrong";
				echo json_encode($response.' '.$e);
			}
		}
		else
		{
			$response = "no data to sync";
			echo json_encode($response);
		}
		
		return $response;
	}
	public function get_all_petty_cash_tables_sync()
	{
		$this->db->where('branch_code ="'.$this->session->userdata('branch_code').'" AND petty_cash_sync_table_status = 1');
		$query = $this->db->get('petty_cash_sync_table');

		return $query;

	}
	public function get_petty_cash_table_details()
	{
		$table_sync_array = $this->sync_model->get_all_petty_cash_tables_sync();
		
		$petty_cash = array();
		$counter = $table_sync_array->num_rows();
		// var_dump($counter); die();
		if($table_sync_array->num_rows() > 0)
		{
			$petty_cash['branch_code'] = $this->session->userdata('branch_code');

			
			// loop the tables
			foreach ($table_sync_array->result() as $key)
			{
				// get the table sync items
				$sync_table_name = $key->petty_cash_sync_table_name;
				$sync_table_id = $key->petty_cash_sync_table_id;
				$branch_code = $key->branch_code;
				$table_key_name = $key->table_key_name;
				
				if($sync_table_name == 'account')
				{
					$where = 'branch_code = "'.$this->session->userdata('branch_code').'"';
					
					$this->db->where($where);
					$this->db->select('*');
					$query_petty_cash = $this->db->get('account');
					
					$petty_cash[$sync_table_name] = array();
					
					if($query_petty_cash->num_rows() > 0)
					{
						foreach ($query_petty_cash->result() as $value) {
							# code...
							//var_dump($value);die();
							$table_key = $value->$table_key_name;
							
							$sync_data = array(
								'branch_code'=>$this->session->userdata('branch_code'),
								'petty_cash_sync_status'=>0,
								'petty_cash_sync_type'=>0,
								'petty_cash_sync_table_id'=>$sync_table_id,
								'petty_cash_sync_table_key'=>$table_key,
								'created'=>date('Y-m-d H:i:s'),
								'created_by'=>$this->session->userdata('personnel_id'),
								'last_modified_by'=>$this->session->userdata('personnel_id'),
								//'sync_data' => $value
							);
							$this->db->insert('petty_cash_sync', $sync_data);
						 	array_push($petty_cash[$sync_table_name], $value);
						}
					}
					
				}
				else
				{
					$where = 'branch_code = "'.$this->session->userdata('branch_code').'" AND is_synced = 0';
					$this->db->where($where);
					$query = $this->db->get($sync_table_name);

					$petty_cash[$sync_table_name] = array();
					
					if($query->num_rows() > 0)
					{
						foreach ($query->result() as $key) {
							# code...
							// save item instruction to the sync table


							$date = date("Y-m-d H:i:s");
							$sync_data = array(
								'branch_code'=>$this->session->userdata('branch_code'),
								'petty_cash_sync_status'=>0,
								'petty_cash_sync_type'=>0,
								'petty_cash_sync_table_id'=>$sync_table_id,
								'petty_cash_sync_table_key'=>$table_key,
								'created'=>date('Y-m-d H:i:s'),
								'created_by'=>$this->session->userdata('personnel_id'),
								'last_modified_by'=>$this->session->userdata('personnel_id'),
								//'sync_data' => $key
							);
							$this->db->insert('petty_cash_sync', $sync_data);
							array_push($petty_cash[$sync_table_name], $key);
						}
						
					}

				}
			}


		}
		else
		{

		}

		return $petty_cash;

	}
}

