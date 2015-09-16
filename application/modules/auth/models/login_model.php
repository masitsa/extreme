<?php

class Login_model extends CI_Model 
{
	/*
	*	Check if user has logged in
	*
	*/
	public function check_login()
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

	public function check_if_exisit($personnel_id)
	{
		$this->db->select('*');
		$this->db->where(array('personnel_id' => $personnel_id));
		$query = $this->db->get('personnel_password');
		
		//if users exists
		if ($query->num_rows() > 0)
		{
			return 1;
		}
		else
		{
			return 0;
		}
	}
	
	public function check_limit_exceeded($personnel_id)
	{

		$this->db->select('*');
		$this->db->where(array('personnel_id' => $personnel_id));
		$query = $this->db->get('personnel_password');
		$this->db->order_by('created_on','DESC');
		
		//if users exists
		if ($query->num_rows() > 0)
		{
			$result = $query->result();
			$date1 = $result[0]->created_on;
			$date2 = $result[0]->expiry_date;


			$diff = abs(strtotime($date2) - strtotime($date1));

			$years = floor($diff / (365*60*60*24));
			$months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
			$days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));

			if($months < 2)
			{
				return FALSE;
			}
			else
			{
				return TRUE;
			}
		}
		else
		{
			return FALSE;
		}
	}

	public function valid_pass($candidate) {
		$dummy = array();
	    if (!preg_match_all('$\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])(?=\S*[\W])\S*$', $candidate,$dummy))
	    {
	        return FALSE;
	    }
	    else
	    {
	    	return TRUE;
	    }
	}
	/*
	*	Validate a user's login request
	*
	*/
	public function validate_user()
	{
		//select the user by email from the database


		$this->db->select('*');
		$this->db->where(array('personnel_username' => $this->input->post('username'), 'authorise' => 0, 'personnel_password' => md5($this->input->post('password'))));
		$query = $this->db->get('personnel');


		
		//if users exists
		if ($query->num_rows() > 0)
		{
			$result = $query->result();
			//  check whether this personnel exisit in the personnel password item
			$personnel_id = $result[0]->personnel_id;


			$exisit = $this->check_if_exisit($personnel_id);
				
			//  end of checking
			//create user's login session

			if($exisit == 1)
			{
				// check whether the time limit has been exceeded
				$limit_exceed = $this->check_limit_exceeded($personnel_id);
				// end of checking the password time limit

				if($limit_exceed == TRUE)
				{
					// personnel should change his password
					// $newdata = array('personnel_id'=> $result[0]->personnel_id);
					

					$newdata = array(
					   'login_status'     	=> TRUE,
	                   'personnel_fname'  	=> $result[0]->personnel_fname,
	                   'first_name'     	=> $result[0]->personnel_fname,
	                   'personnel_email'	=> $result[0]->personnel_email,
	                   'personnel_id'  		=> $result[0]->personnel_id,
	                   'branch_id'  		=> $branch_details[0]->branch_id,
	                   'branch_code'  		=> $branch_details[0]->branch_code,
	                   'branch_name'  		=> $branch_details[0]->branch_name,
			         );

					$this->session->set_userdata($newdata);

					//  set branch detail to session 


					


					
					return 'limit';

				}
				else
				{
					// checking if the passwords follows the criteria set
					$password = $this->input->post('password');
					$password_validity = $this->valid_pass($password);
					if($password_validity == TRUE)
					{
						//  this user can access the system

						$newdata = array(
	                   'login_status'     	=> TRUE,
	                   'personnel_fname'  	=> $result[0]->personnel_fname,
	                   'first_name'     	=> $result[0]->personnel_fname,
	                   'personnel_email'	=> $result[0]->personnel_email,
	                   'personnel_id'  		=> $result[0]->personnel_id
			               );

						$this->session->set_userdata($newdata);
						
						//update user's last login date time
						$this->update_user_login($result[0]->personnel_id);
						return 1;

					}
					else
					{
						// this user should be asked to change the password
						$newdata = array(
							'login_status'     	=> TRUE,
		                   'personnel_fname'  	=> $result[0]->personnel_fname,
		                   'first_name'     	=> $result[0]->personnel_fname,
		                   'personnel_email'	=> $result[0]->personnel_email,
		                   'personnel_id'  		=> $result[0]->personnel_id
				         );

						$this->session->set_userdata($newdata);
						return 'password_strength';

					}


				}
			}
			else
			{
				$newdata = array(
					'login_status'     	=> TRUE,
                   'personnel_fname'  	=> $result[0]->personnel_fname,
                   'first_name'     	=> $result[0]->personnel_fname,
                   'personnel_email'	=> $result[0]->personnel_email,
                   'personnel_id'  		=> $result[0]->personnel_id
		         );

				$this->session->set_userdata($newdata);

				return 0;
			}
			
				
		}
		
		//if user doesn't exist
		else
		{
			return FALSE;
		}
	}
	
	
	/*
	*	Update user's last login date
	*
	*/
	private function update_user_login($personnel_id)
	{
		$session_log_insert = array(
			"personnel_id" => $personnel_id, 
			"session_name_id" => 1
		);
		$table = "session";
		$this->db->insert($table, $session_log_insert);
		
		return TRUE;
	}
	
	/*
	*	Reset a user's password
	*
	*/
	public function reset_password($user_id)
	{
		$new_password = substr(md5(date('Y-m-d H:i:s')), 0, 6);
		
		$data['password'] = md5($new_password);
		$this->db->where('user_id', $user_id);
		$this->db->update('users', $data); 
		
		return $new_password;
	}
	
	public function get_new_orders()
	{
		$this->db->select('COUNT(order_id) AS total_orders');
		$this->db->where('order_status = 1');
		$query = $this->db->get('orders');
		
		$result = $query->row();
		
		return $result->total_orders;
	}
	
	public function get_balance()
	{
		//select the user by email from the database
		$this->db->select('SUM(price*quantity) AS total_orders');
		$this->db->where('order_status = 2 AND orders.order_id = order_item.order_id');
		$this->db->from('orders, order_item');
		$query = $this->db->get();
		
		$result = $query->row();
		
		return $result->total_orders;
	}
	public function change_user_password($personnel_id)
	{
		$current_password = $this->input->post('current_password');
		$new_password = $this->input->post('new_password');
		$confirm_new_password = $this->input->post('confirm_new_password');

		if($new_password == $confirm_new_password)
		{

			// checking of password match
			$check = $this->check_password_match($personnel_id,$current_password);
			// end of checking matching password

			if($check == TRUE)
			{
				// password match
				// check if password has passed the password strength
				$password_validity = $this->valid_pass($new_password);
				// check if the password has passed the password strent

				if($password_validity == TRUE)
				{
					// check if the personnel password exists in the passwords table
					$exisit = $this->check_if_exisit($personnel_id);
					
					if($exisit == 1)
					{
						// check if this 
						// update all the status of the previous passwords to 1
						$data2['password_status'] = 1;
						
						$this->db->where('personnel_id', $personnel_id);
						$this->db->update('personnel_password', $data2);

						// the insert new password to to personnel passwords table and set active to 0
						// current date 
							$current_date = date("y-m-d");
						// end of current date
						// calculate the expiry date
							$expiry_date = date("y-m-d", strtotime('+2 months'));
						// end of expiry date

							$insert = array(
											"personnel_id" => $this->session->userdata('personnel_id'),
											"password" => md5($new_password),
											"created_on" => $current_date,
											"expiry_date" => $expiry_date,
											"password_status" => 0
										);
							$this->db->insert('personnel_password',$insert);

						// update the personnel table with the new password
						$data['personnel_password'] = md5($new_password);
						
						$this->db->where('personnel_id', $personnel_id);
						$this->db->update('personnel', $data);


						// set session for the personnel
						$this->db->select('*');
						$this->db->where(array('personnel_id' => $this->session->userdata('personnel_id')));
						$query = $this->db->get('personnel');
							//if users exists
							if ($query->num_rows() > 0)
							{
								$result = $query->result();

								$newdata = array(
			                   'login_status'     	=> TRUE,
			                   'personnel_fname'  	=> $result[0]->personnel_fname,
			                   'first_name'     	=> $result[0]->personnel_fname,
			                   'personnel_email'	=> $result[0]->personnel_email,
			                   'personnel_id'  		=> $result[0]->personnel_id
					               );

								$this->session->set_userdata($newdata);
								
								//update user's last login date time
								$this->update_user_login($result[0]->personnel_id);
							}
						return 1;
					}
					else
					{
						// the insert new password to to personnel passwords table and set active to 0
						// the insert new password to to personnel passwords table and set active to 0
						// current date 
							$current_date = date("y-m-d");
						// end of current date
						// calculate the expiry date
							$expiry_date = date("y-m-d", strtotime('+2 months'));
						// end of expiry date

							$insert = array(
											"personnel_id" => $this->session->userdata('personnel_id'),
											"password" => md5($new_password),
											"created_on" => $current_date,
											"expiry_date" => $expiry_date,
											"password_status" => 0
										);
							// $this->database->insert_entry('personnel_password', $insert);
							$this->db->insert('personnel_password',$insert);

							// update the personnel table with the new password
							$data['personnel_password'] = md5($new_password);
							
							$this->db->where('personnel_id', $personnel_id);
							$this->db->update('personnel', $data);


								// set session for the personnel
								$this->db->select('*');
								$this->db->where(array('personnel_id' => $this->session->userdata('personnel_id')));
								$query = $this->db->get('personnel');
									//if users exists
									if ($query->num_rows() > 0)
									{
										$result = $query->result();

										$newdata = array(
					                   'login_status'     	=> TRUE,
					                   'personnel_fname'  	=> $result[0]->personnel_fname,
					                   'first_name'     	=> $result[0]->personnel_fname,
					                   'personnel_email'	=> $result[0]->personnel_email,
					                   'personnel_id'  		=> $result[0]->personnel_id
							               );

										$this->session->set_userdata($newdata);
										
										//update user's last login date time
										$this->update_user_login($result[0]->personnel_id);
									}
							return 1;
						
					}
				}
				else
				{
					return  'password_invalid';
				}
					
				
			}
			else
			{

				return  'user_details_invalid';	
			}
			
		}
		else
		{
			return 'password_match';
		}
	}
	public function check_password_match($personnel_id,$current_password)
	{
		$this->db->select('*');
		$this->db->where(array('personnel_id' => $personnel_id, 'personnel_password' => md5($current_password)));
		$query = $this->db->get('personnel');
		
		//if users exists
		if ($query->num_rows() > 0)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
}