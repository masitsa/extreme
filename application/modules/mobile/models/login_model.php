<?php

class Login_model extends CI_Model 
{
	/*
	*	Check if member has logged in
	*
	*/
	public function check_member_login()
	{
		if($this->session->userdata('member_login_status'))
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
	public function validate_member($personnel_username, $personnel_password)
	{
		//select the user by email from the database
		$this->db->select('*');
		$this->db->where(array('personnel_username' =>strtolower($personnel_username),'personnel_password' => md5($personnel_password)));
		$query = $this->db->get('personnel');
		
		//if users exists
		if ($query->num_rows() > 0)
		{
			$result = $query->result();
			
			return $result;
		}
		
		else
		{
			return FALSE;
		}
	}
	
	
	
}
?>