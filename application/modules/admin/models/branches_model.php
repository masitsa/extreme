<?php

class Branches_model extends CI_Model 
{	
	/*
	*	Retrieve all branches
	*
	*/
	public function all_branches()
	{
		$this->db->where('branch_status = 1');
		$query = $this->db->get('branch');
		
		return $query;
	}
	/*
	*	Retrieve latest branch
	*
	*/
	public function latest_branch()
	{
		$this->db->limit(1);
		$this->db->order_by('created', 'DESC');
		$query = $this->db->get('branch');
		
		return $query;
	}
	/*
	*	Retrieve all parent branches
	*
	*/
	public function all_parent_branches()
	{
		$this->db->where('branch_status = 1 AND branch_parent = 0');
		$this->db->order_by('branch_name', 'ASC');
		$query = $this->db->get('branch');
		
		return $query;
	}
	/*
	*	Retrieve all children branches
	*
	*/
	public function all_child_branches()
	{
		$this->db->where('branch_status = 1 AND branch_parent > 0');
		$this->db->order_by('branch_name', 'ASC');
		$query = $this->db->get('branch');
		
		return $query;
	}
	
	/*
	*	Retrieve all branches
	*	@param string $table
	* 	@param string $where
	*
	*/
	public function get_all_branches($table, $where, $per_page, $page, $order = 'branch_name', $order_method = 'ASC')
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
	*	Add a new branch
	*	@param string $image_name
	*
	*/
	public function add_branch($image_name, $thumb_name)
	{
		$data = array(
				'branch_name'		=> $this->input->post('branch_name'),
				'branch_status'		=> $this->input->post('branch_status'),
				'branch_phone'		=> $this->input->post('branch_phone'),
				'branch_email'		=> $this->input->post('branch_email'),
				'branch_working_weekday'=> $this->input->post('branch_working_weekday'),
				'branch_working_weekend'=> $this->input->post('branch_working_weekend'),
				'branch_address'	=> $this->input->post('branch_address'),
				'branch_city'		=> $this->input->post('branch_city'),
				'branch_post_code'	=> $this->input->post('branch_post_code'),
				'branch_location'	=> $this->input->post('branch_location'),
				'branch_building'	=> $this->input->post('branch_building'),
				'branch_floor'		=> $this->input->post('branch_floor'),
				'created'			=> date('Y-m-d H:i:s'),
				'created_by'		=> $this->session->userdata('personnel_id'),
				'modified_by'		=> $this->session->userdata('personnel_id'),
				'branch_image_name'	=>$image_name,
				'branch_thumb_name'	=>$thumb_name
			);
		if($this->db->insert('branch', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Update an existing branch
	*	@param string $image_name
	*	@param int $branch_id
	*
	*/
	public function update_branch($image_name, $thumb_name, $branch_id)
	{
		$data = array(
				'branch_name'		=> $this->input->post('branch_name'),
				'branch_status'		=> $this->input->post('branch_status'),
				'branch_phone'		=> $this->input->post('branch_phone'),
				'branch_email'		=> $this->input->post('branch_email'),
				'branch_working_weekday'=> $this->input->post('branch_working_weekday'),
				'branch_working_weekend'=> $this->input->post('branch_working_weekend'),
				'branch_address'	=> $this->input->post('branch_address'),
				'branch_city'		=> $this->input->post('branch_city'),
				'branch_post_code'	=> $this->input->post('branch_post_code'),
				'branch_location'	=> $this->input->post('branch_location'),
				'branch_building'	=> $this->input->post('branch_building'),
				'branch_floor'		=> $this->input->post('branch_floor'),
				'modified_by'		=> $this->session->userdata('personnel_id'),
				'branch_image_name'	=>$image_name,
				'branch_thumb_name'	=>$thumb_name
			);
			
		$this->db->where('branch_id', $branch_id);
		if($this->db->update('branch', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	get a single branch's children
	*	@param int $branch_id
	*
	*/
	public function get_sub_branches($branch_id)
	{
		//retrieve all users
		$this->db->from('branch');
		$this->db->select('*');
		$this->db->where('branch_parent = '.$branch_id);
		$query = $this->db->get();
		
		return $query;
	}
	
	/*
	*	get a single branch's details
	*	@param int $branch_id
	*
	*/
	public function get_branch($branch_id)
	{
		//retrieve all users
		$this->db->from('branch');
		$this->db->select('*');
		$this->db->where('branch_id = '.$branch_id);
		$query = $this->db->get();
		
		return $query;
	}
	
	/*
	*	Delete an existing branch
	*	@param int $branch_id
	*
	*/
	public function delete_branch($branch_id)
	{
		if($this->db->delete('branch', array('branch_id' => $branch_id)))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Activate a deactivated branch
	*	@param int $branch_id
	*
	*/
	public function activate_branch($branch_id)
	{
		$data = array(
				'branch_status' => 1
			);
		$this->db->where('branch_id', $branch_id);
		

		if($this->db->update('branch', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Deactivate an activated branch
	*	@param int $branch_id
	*
	*/
	public function deactivate_branch($branch_id)
	{
		$data = array(
				'branch_status' => 0
			);
		$this->db->where('branch_id', $branch_id);
		
		if($this->db->update('branch', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	public function upload_branch_logo($branch_path, $branch_location, $edit = NULL)
	{
		$resize['width'] = 200;
		$resize['height'] = 200;
		
		if(!empty($_FILES['branch_image']['tmp_name']))
		{
			$image = $this->session->userdata('branch_file_name');
			
			if((!empty($image)) || ($edit != NULL))
			{
				if($edit != NULL)
				{
					$image = $edit;
				}
				
				//delete any other uploaded image
				if($this->file_model->delete_file($branch_path."\\".$image, $branch_location))
				{
					//delete any other uploaded thumbnail
					$this->file_model->delete_file($branch_path."\\thumbnail_".$image, $branch_location);
				}
				
				else
				{
					$this->file_model->delete_file($branch_path."/".$image, $branch_location);
					$this->file_model->delete_file($branch_path."/thumbnail_".$image, $branch_location);
				}
			}
			//Upload image
			$response = $this->file_model->upload_file($branch_path, 'branch_image', $resize);
			if($response['check'])
			{
				$file_name = $response['file_name'];
				$thumb_name = $response['thumb_name'];
				
				//Set sessions for the image details
				$this->session->set_userdata('branch_file_name', $file_name);
				$this->session->set_userdata('branch_thumb_name', $thumb_name);
			
				return TRUE;
			}
		
			else
			{
				$this->session->set_userdata('branch_error_message', $response['error']);
				
				return FALSE;
			}
		}
		
		else
		{
			$this->session->set_userdata('branch_error_message', '');
			return FALSE;
		}
	}
}
?>