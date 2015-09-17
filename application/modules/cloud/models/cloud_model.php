<?php
class Cloud_model extends CI_Model 
{
	public function save_visit_data($json)
	{
		//decode the json data
		$decoded = json_decode($json);

		//initiate the response array
		$response = array();

		//get sync tables
		$query = $this->get_sync_tables();
		if($query->num_rows() > 0)
		{
			foreach($query->result() as $res)
			{
				$field = $res->sync_table_name;
				$function = $res->sync_table_cloud_save_function;
				//patients data
				$sync_data = $decoded->$field;
			    $patient_data = $sync_data[0];
				//insert data into the patients table
				if($this->$function($patient_data))
				{
					$response[$field] = 'true';
				}

				else
				{
					$response[$field] = 'false';
				}
			}
		}

		else
		{
			$response['sync_tables'] = 'no tables to be synced';
		}

		return $response;
	}

	public function get_sync_tables()
	{
		$this->db->where('sync_table_status', 1);
		$query = $this->db->get('sync_table');

		return $query;
	}

	public function save_patients($patients)
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
			//update patient
			$this->db->where('patient_number', $patient_number);
			if($this->db->update('patients', $data))
			{
				return TRUE;
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
				return TRUE;
			}
			else
			{
				return FALSE;
			}
		}
	}
}
?>