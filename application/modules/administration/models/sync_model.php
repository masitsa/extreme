<?php
class Sync_model extends CI_Model 
{


	public function syn_up_on_closing_visit($visit_id)
	{
		// get the patient id and the branch id and patient
			$patient_details = $this->get_table_details($visit_id);
			
			try{

				//API Url
				$url = 'http://159.203.78.242/cloud/save_cloud_data';
				// $url = 'http://localhost/cloud/save_cloud_data';
				 
				//Initiate cURL.
				$ch = curl_init($url);
				 
				//The JSON data.
				
				 
				//Encode the array into JSON.
				$jsonDataEncoded = json_encode($patient_details);
				 
				//Tell cURL that we want to send a POST request.
				curl_setopt($ch, CURLOPT_POST, 1);
				 
				//Attach our encoded JSON string to the POST fields.
				curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);
				 
				//Set the content type to application/json
				curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json')); 
				 
				//Execute the request
				 $result = curl_exec($ch);
				return "good work";

				// save items as passed

				//in the case of an exceptions save them to the database
			}
			catch(Exception $e)
			{
				return "something went wrong";
					
			}
		
	}
	
	public function get_table_details($visit_id)
	{

		$table_sync_array = $this->get_all_tables_sync();

		if($table_sync_array->num_rows() > 0)
		{
			// loop the tables
			foreach ($table_sync_array->result() as $key) {

				// get the table sync items
				$sync_table_name = $key->sync_table_name;
				$sync_table_id = $key->sync_table_id;
				$branch_code = $key->branch_code;
				$table_key_name = $key->table_key_name;
				if($sync_table_name == 'patients')
				{
					$this->db->where('visit.patient_id = patients.patient_id AND visit.branch_code = "KA" AND visit.visit_id = '.$visit_id);
					$this->db->select('patients.*');
					$query_patients = $this->db->get('patients,visit');

					$patients[$sync_table_name] = array();

					if($query_patients->num_rows() > 0)
					{
						foreach ($query_patients->result() as $value) {
							# code...
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

							// if($table_key_name == $key->sync_table_key)
							// {

							// }
							// $date = date("Y-m-d H:i:s");
							// $sync_data = array('branch_code'=>'KA','sync_status'=>0,'sync_type'=>0,'sync_table_id'=>$sync_table_id,);
							// $this->db->insert('sync', $patient_data);
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
	public function get_all_tables_sync()
	{
		$this->db->where('branch_code ="KA"');
		$query = $this->db->get('sync_table');

		return $query;
	}


}