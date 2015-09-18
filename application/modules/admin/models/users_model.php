<?php

class Users_model extends CI_Model 
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
	*	Retrieve all users
	*	@param string $table
	* 	@param string $where
	*
	*/
	public function get_all_users($table, $where, $per_page, $page, $order = 'first_name', $order_method = 'ASC')
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
	*	Retrieve all administrators
	*
	*/
	public function get_active_users()
	{
		$this->db->from('personnel');
		$this->db->select('*');
		$query = $this->db->get();
		
		return $query;
	}
	
	/*
	*	Retrieve all front end users
	*
	*/
	public function get_all_front_end_users()
	{
		$this->db->from('users');
		$this->db->select('*');
		$this->db->where('user_level_id = 2');
		$query = $this->db->get();
		
		return $query;
	}
	
	public function get_all_countries()
	{
		//retrieve all users
		$query = $this->db->get('country');
		
		return $query;
	}
	
	/*
	*	Add a new user to the database
	*
	*/
	public function add_user()
	{
		$data = array(
				'first_name'=>ucwords(strtolower($this->input->post('first_name'))),
				'other_names'=>ucwords(strtolower($this->input->post('other_names'))),
				'email'=>$this->input->post('email'),
				'password'=>md5($this->input->post('password')),
				'phone'=>$this->input->post('phone'),
				'address'=>$this->input->post('address'),
				'post_code'=>$this->input->post('post_code'),
				'city'=>$this->input->post('city'),
				'created'=>date('Y-m-d H:i:s'),
				'branch_id'=>$this->session->userdata('branch_id'),
				'activated'=>$this->input->post('activated')
			);
			
		if($this->db->insert('users', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Add a new front end user to the database
	*
	*/
	public function add_frontend_user()
	{
		$data = array(
				'first_name'=>ucwords(strtolower($this->input->post('first_name'))),
				'other_names'=>ucwords(strtolower($this->input->post('other_names'))),
				'email'=>$this->input->post('email'),
				'password'=>md5($this->input->post('password')),
				'phone'=>$this->input->post('phone'),
				'created'=>date('Y-m-d H:i:s'),
				'user_level_id'=>2,
				'activated'=>1
			);
			
		if($this->db->insert('users', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Edit an existing user
	*	@param int $user_id
	*
	*/
	public function edit_user($user_id)
	{
		$data = array(
				'first_name'=>ucwords(strtolower($this->input->post('first_name'))),
				'other_names'=>ucwords(strtolower($this->input->post('other_names'))),
				'email'=>$this->input->post('email'),
				'phone'=>$this->input->post('phone'),
				'address'=>$this->input->post('address'),
				'post_code'=>$this->input->post('post_code'),
				'city'=>$this->input->post('city'),
				'activated'=>$this->input->post('activated')
			);
		
		//check if user wants to update their password
		$pwd_update = $this->input->post('admin_user');
		if(!empty($pwd_update))
		{
			if($this->input->post('old_password') == md5($this->input->post('current_password')))
			{
				$data['password'] = md5($this->input->post('new_password'));
			}
			
			else
			{
				$this->session->set_userdata('error_message', 'The current password entered does not match your password. Please try again');
			}
		}
		
		$this->db->where('user_id', $user_id);
		
		if($this->db->update('users', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Edit an existing user
	*	@param int $user_id
	*
	*/
	public function edit_frontend_user($user_id)
	{
		$data = array(
				'first_name'=>ucwords(strtolower($this->input->post('first_name'))),
				'other_names'=>ucwords(strtolower($this->input->post('last_name'))),
				'phone'=>$this->input->post('phone')
			);
		
		//check if user wants to update their password
		$pwd_update = $this->input->post('admin_user');
		if(!empty($pwd_update))
		{
			if($this->input->post('old_password') == md5($this->input->post('current_password')))
			{
				$data['password'] = md5($this->input->post('new_password'));
			}
			
			else
			{
				$this->session->set_userdata('error_message', 'The current password entered does not match your password. Please try again');
			}
		}
		
		$this->db->where('user_id', $user_id);
		
		if($this->db->update('users', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Edit an existing user's password
	*	@param int $user_id
	*
	*/
	public function edit_password($user_id)
	{
		if($this->input->post('slug') == md5($this->input->post('current_password')))
		{
			if($this->input->post('new_password') == $this->input->post('confirm_password'))
			{
				$data['password'] = md5($this->input->post('new_password'));
		
				$this->db->where('user_id', $user_id);
				
				if($this->db->update('users', $data))
				{
					$return['result'] = TRUE;
				}
				else{
					$return['result'] = FALSE;
					$return['message'] = 'Oops something went wrong and your password could not be updated. Please try again';
				}
			}
			else{
					$return['result'] = FALSE;
					$return['message'] = 'New Password and Confirm Password don\'t match';
			}
		}
		
		else
		{
			$return['result'] = FALSE;
			$return['message'] = 'You current password is not correct. Please try again';
		}
		
		return $return;
	}
	
	/*
	*	Retrieve a single user
	*	@param int $user_id
	*
	*/
	public function get_user($user_id)
	{
		//retrieve all users
		$this->db->from('users');
		$this->db->select('*');
		$this->db->where('user_id = '.$user_id);
		$query = $this->db->get();
		
		return $query;
	}
	
	/*
	*	Retrieve a single user by their email
	*	@param int $email
	*
	*/
	public function get_user_by_email($email)
	{
		//retrieve all users
		$this->db->from('users');
		$this->db->select('*');
		$this->db->where('email = \''.$email.'\'');
		$query = $this->db->get();
		
		return $query;
	}
	
	/*
	*	Delete an existing user
	*	@param int $user_id
	*
	*/
	public function delete_user($user_id)
	{
		if($this->db->delete('users', array('user_id' => $user_id)))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Activate a deactivated user
	*	@param int $user_id
	*
	*/
	public function activate_user($user_id)
	{
		$data = array(
				'activated' => 1
			);
		$this->db->where('user_id', $user_id);
		
		if($this->db->update('users', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Deactivate an activated user
	*	@param int $user_id
	*
	*/
	public function deactivate_user($user_id)
	{
		$data = array(
				'activated' => 0
			);
		$this->db->where('user_id', $user_id);
		
		if($this->db->update('users', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Reset a user's password
	*	@param string $email
	*
	*/
	public function reset_password($email)
	{
		//reset password
		$result = md5(date("Y-m-d H:i:s"));
		$pwd2 = substr($result, 0, 6);
		$pwd = md5($pwd2);
		
		$data = array(
				'password' => $pwd
			);
		$this->db->where('email', $email);
		
		if($this->db->update('users', $data))
		{
			//email the password to the user
			$user_details = $this->users_model->get_user_by_email($email);
			
			$user = $user_details->row();
			$user_name = $user->first_name;
			
			//email data
			$receiver['email'] = $this->input->post('email');
			$sender['name'] = 'Fad Shoppe';
			$sender['email'] = 'info@fadshoppe.com';
			$message['subject'] = 'You requested a password change';
			$message['text'] = 'Hi '.$user_name.'. Your new password is '.$pwd;
			
			//send the user their new password
			if($this->email_model->send_mail($receiver, $sender, $message))
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
	
	public function create_web_name($field_name)
	{
		$web_name = str_replace(" ", "-", strtolower($field_name));
		
		return $web_name;
	}
	public function change_password()
	{
		
		$data = array(
				'personnel_password' => md5($this->input->post('new_password'))
			);
		$this->db->where('personnel_password = "'.md5($this->input->post('current_password')).'" AND personnel_id ='.$this->session->userdata('personnel_id'));
		
		if($this->db->update('personnel', $data))
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
}
?>