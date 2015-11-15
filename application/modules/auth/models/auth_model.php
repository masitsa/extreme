<?php

class Auth_model extends CI_Model 
{
	/*
	*	Validate a personnel's login request
	*
	*/
	public function validate_personnel()
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

			// get an active branch

			//$branch_details = $this->get_active_branch();

			//create personnel's login session
			$newdata = array(
                   'login_status'     			=> TRUE,
                   'first_name'     			=> $result[0]->personnel_fname,
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
			return TRUE;
		}
		
		//if personnel doesn't exist
		else
		{
			return FALSE;
		}
	}

	public function get_active_branch()
	{
		$this->db->where('branch_status = 1');
		$this->db->from('branch');
		$query = $this->db->get();
		
		$result = $query->row();

		return $result;
	}
	
	/*
	*	Update personnel's last login date
	*
	*/
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
	
	/*
	*	Reset a personnel's password
	*
	*/
	public function reset_password($personnel_id)
	{
		$new_password = substr(md5(date('Y-m-d H:i:s')), 0, 6);
		
		$data['personnel_password'] = md5($new_password);
		$this->db->where('personnel_id', $personnel_id);
		$this->db->update('personnel', $data); 
		
		return $new_password;
	}
	
	/*
	*	Check if a has logged in
	*
	*/
	public function check_login()
	{
		$personnel_type_id = $this->session->userdata('personnel_type_id');
		
		if($this->session->userdata('login_status'))
		{
			return TRUE;
		}
		
		else
		{
			return FALSE;
		}
	}
	
	public function get_personnel_roles($personnel_id)
	{
	}
}
?>