<?php

class clients_model extends CI_Model 
{	
	/*
	*	Retrieve all clients
	*
	*/
	public function all_clients()
	{
		$this->db->where('client_status = 1 AND deleted = 0 AND branch_code = "'.$this->session->userdata('branch_code').'"');
		$this->db->order_by('client_name');
		$query = $this->db->get('client');
		
		return $query;
	}
	/*
	*	Retrieve latest clients
	*
	*/
	public function latest_clients()
	{
		$this->db->limit(1);
		$this->db->order_by('created', 'DESC');
		$query = $this->db->get('client');
		
		return $query;
	}
	
	
	/*
	*	Retrieve all clients
	*	@param string $table
	* 	@param string $where
	*
	*/
	public function get_all_clients($table, $where, $per_page, $page, $order, $order_method)
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
	*	Add a new clients
	*	@param string $image_name
	*
	*/
	public function add_clients()
	{
		$data = array(
				'client_name'=>ucwords(strtolower($this->input->post('clients_name'))),
				'client_phone'=>$this->input->post('clients_phone'),
				'client_email'=>$this->input->post('clients_email'),
				'client_physical_address'=>$this->input->post('clients_physical_address'),
				'client_contact_person'=>$this->input->post('clients_contact_person'),
				'client_status'=>$this->input->post('clients_status'),
				'created'=>date('Y-m-d H:i:s'),
				'deleted'=>0,
				'created_by'=>$this->session->userdata('personnel_id'),
				'modified_by'=>$this->session->userdata('personnel_id'),
				'branch_code'=>$this->session->userdata('branch_code')
			);
			
		if($this->db->insert('client', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Update an existing clients
	*	@param string $image_name
	*	@param int $clients_id
	*
	*/
	public function update_clients($clients_id)
	{
		$data = array(
				'client_name'=>ucwords(strtolower($this->input->post('clients_name'))),
				'client_phone'=>$this->input->post('clients_phone'),
				'client_email'=>$this->input->post('clients_email'),
				'client_physical_address'=>$this->input->post('clients_physical_address'),
				'client_contact_person'=>$this->input->post('clients_contact_person'),
				'client_status'=>$this->input->post('client_status'),
				'created'=>date('Y-m-d H:i:s'),
				'created_by'=>$this->session->userdata('personnel_id'),
				'modified_by'=>$this->session->userdata('personnel_id'),
				'branch_code'=>$this->session->userdata('branch_code')
			);
			
		$this->db->where('branch_code = "'.$this->session->userdata('branch_code').'"AND client_id = '.$clients_id);
		if($this->db->update('client', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	
	/*
	*	get a single clients's details
	*	@param int $clients_id
	*
	*/
	public function get_clients($clients_id)
	{
		//retrieve all users
		$this->db->from('client');
		$this->db->select('*');
		$this->db->where('client_id = '.$clients_id);
		$query = $this->db->get();
		
		return $query;
	}
	
	/*
	*	get a single clients's details
	*	@param int $clients_id
	*
	*/
	public function get_clients_by_name($clients_name)
	{
		//retrieve all users
		$this->db->from('client');
		$this->db->select('*');
		$this->db->where('client_name = \''.$clients_name.'\'');
		$query = $this->db->get();
		
		return $query;
	}
	
	/*
	*	Delete an existing clients
	*	@param int $clients_id
	*
	*/
	public function delete_clients($clients_id)
	{
		$data = array(
			'deleted'=>1,
			'deleted_on'=>date('Y-m-d H:i:s'),
			'deleted_by'=>$this->session->userdata('personnel_id'),
		);
		$this->db->where('client_id', $clients_id);	
			if($this->db->update('client',$data))
			{
				return TRUE;
			}
			else{
				return FALSE;
			}
	}
	
	/*
	*	Activate a deactivated clients
	*	@param int $clients_id
	*
	*/
	public function activate_clients($clients_id)
	{
		$data = array(
				'client_status' => 1
			);
		$this->db->where('client_id', $clients_id);
		
		if($this->db->update('client', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Deactivate an activated clients
	*	@param int $clients_id
	*
	*/
	public function deactivate_clients($clients_id)
	{
		$data = array(
				'client_status' => 0
			);
		$this->db->where('client_id', $clients_id);
		
		if($this->db->update('client', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	function import_template()
	{
		$this->load->library('Excel');
		
		$title = 'Extreme Clients Import Template';
		$count=1;
		$r = 0;
		
		$array[$r][0] = 'Client Name';
		$array[$r][1] = 'Client Contact _Person';

		$r++;
		
		//create the excel document
		$this->excel->addArray ($array);
		$this->excel->generateXML ($title);
	}
	
	public function import_csv_clients($upload_path)
	{
		//load the file model
		$this->load->model('admin/file_model');
		/*
			-----------------------------------------------------------------------------------------
			Upload csv
			-----------------------------------------------------------------------------------------
		*/
		$response = $this->file_model->upload_csv($upload_path, 'import_csv');
		
		if($response['check'])
		{
			$file_name = $response['file_name'];
			
			$array = $this->file_model->get_array_from_csv($upload_path.'/'.$file_name);
			//var_dump($array); die();
			$response2 = $this->sort_csv_data($array);
		
			if($this->file_model->delete_file($upload_path."\\".$file_name, $upload_path))
			{
			}
			
			return $response2;
		}
		
		else
		{
			$this->session->set_userdata('error_message', $response['error']);
			return FALSE;
		}
	}
	
	public function check_current_client_exists($client_name)
	{
		$this->db->from('client');
		$this->db->where('client_name', $client_name);
		
		$query = $this->db->get('');
		
		if($query->num_rows() > 0)
		{
			return TRUE;
		}
		
		else
		{
			return FALSE;
		}
	}
	
	public function sort_csv_data($array)
	{
		//count total rows
		$total_rows = count($array);
		$total_columns = count($array[0]);//var_dump($array);die();
		
		//if products exist in array
		if($total_rows > 0)
		{
			$response = '
				<table class="table table-hover table-bordered ">
					  <thead>
						<tr>
						  <th>#</th>
						  <th>Client Name</th>
						  <th>Contact Person</th>
						  <th>Comment</th>
						</tr>
					  </thead>
					  <tbody>
			';
			
			//retrieve the data from array
			for($r = 1; $r < $total_rows; $r++)
			{
				$client_name = $array[$r][0];
				$contact_person = $array[$r][1];
			
				$clients['client_name'] = $client_name;
				$clients['client_contact_person'] = $contact_person;
				$clients['branch_code'] =$this->session->userdata('branch_code');
				
				$comment ='';
				
				// check if item exist
				if(!empty($clients['client_name']))
				{
					if(!empty($clients['client_name']))
					{
						// check if the number already exists
						if($this->check_current_client_exists($client_name))
						{
							//number exists
							$comment = '<br/>Duplicate client entered';
							$class = 'danger';
						}
						else
						{
							// number does not exisit
							//save product in the db
							if($this->db->insert('client', $clients))
							{
								$client_id = $this->db->insert_id();
							
								$comment = '<br/>Client successfully added to the database';
								$class = 'success';
							}
							
							else
							{
								$comment = '<br/>Internal error. Could not add client to the database. Please contact the site administrator. Item code '.$clients['item_name'];
								$class = 'warning';
							}
						}
					}
					else{
						$comment = '<br/>Internal error. Could not add client to the database. Please contact the site administrator. Item code '.$clients['item_name'];
								$class = 'warning';
					}
				}
				else
				{
					$comment = '<br/>Not saved. Ensure you have a client entered'.$clients['client_name'];
					$class = 'danger';
				}
				
				
				$response .= '
					
						<tr class="'.$class.'">
							<td>'.$r.'</td>
							<td>'.$clients['client_name'].'</td>
							<td>'.$clients['client_contact_person'].'</td>
							<td>'.$comment.'</td>
						</tr> 
				';
				
			}
			
			$response .= '</table>';
			
			$return['response'] = $response;
			$return['check'] = TRUE;
		}
		
		//if no products exist
		else
		{
			$return['response'] = 'Client data not found';
			$return['check'] = FALSE;
		}
		
		return $return;
	}
	
}
?>