<?php

class Inpatient_model extends CI_Model 
{
	/*
	*	Check if member has logged in
	*
	*/
	public function check_member_login()
	{
		if($this->session->userdata('login_status'))
		{
			return TRUE;
		}
		
		else
		{
			return FALSE;
		}
	}
	/*
	*	Reset a user's password
	*
	*/
	public function get_profile_details()
	{
		// 9530
		$this->db->where('personnel_id = '.$this->session->userdata('personnel_id'));
		$query = $this->db->get('personnel');
		
		return $query;
	}
	

	/*
	*	Validate a member's login request
	*
	*/
	public function validate_member()
	{
		//select the personnel by username from the database
		$this->db->select('*');
		$this->db->where(
			array(
				'personnel_username' => $this->input->post('personnel_username'), 
				'personnel_status' => 1, 
				'personnel_password' => md5($this->input->post('personnel_password'))
			)
		);
		$this->db->join('branch', 'branch.branch_id = personnel.branch_id');
		$query = $this->db->get('personnel');
		
		//if personnel exists
		if ($query->num_rows() > 0)
		{
			$result = $query->result();

			//create personnel's login session
			$newdata = array(
                   'login_status'     			=> TRUE,
                   'personnel_fname'     		=> $result[0]->personnel_fname,
                   'username'     				=> $result[0]->personnel_username,
                   'personnel_id'  				=> $result[0]->personnel_id,
                   'branch_id'  				=> $result[0]->branch_id,
                   'branch_code'  				=> $result[0]->branch_code,
                   'branch_name'  				=> $result[0]->branch_name,
				   'authorize_invoice_changes'	=> $result[0]->authorize_invoice_changes,
                   'personnel_type_id'     		=> $result[0]->personnel_type_id
               );

			$this->session->set_userdata($newdata);
			
			//update personnel's last login date time
			$this->update_personnel_login($result[0]->personnel_id);
			return $newdata;
		}
		
		//if personnel doesn't exist
		else
		{
			return FALSE;
		}
	}
	private function update_personnel_login($personnel_id)
	{
		$data['last_login'] = date('Y-m-d H:i:s');
		$this->db->where('personnel_id', $personnel_id);
		if($this->db->update('personnel', $data))
		{
			$session_log_insert = array(
				"personnel_id" => $personnel_id, 
				"session_name_id" => 1
			);
			$table = "session";
			if($this->db->insert($table, $session_log_insert))
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
}
?>