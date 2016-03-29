<?php

class clients_model extends CI_Model 
{	
	/*
	*	Retrieve all clients
	*
	*/
	public function all_clients()
	{
		$this->db->where('client_status = 1');
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
	public function get_all_clients($table, $where, $per_page, $page)
	{
		//retrieve all users
		$this->db->from($table);
		$this->db->select('*');
		$this->db->where($where);
		$this->db->order_by('client_name');
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
				'client_name'=>ucwords(strtolower($this->input->post('client_name'))),
				'client_phone'=>$this->input->post('client_phone'),
				'client_email'=>$this->input->post('client_email'),
				'client_physical_address'=>$this->input->post('client_physical_address'),
				'client_contact_person'=>$this->input->post('client_contact_person'),
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
		if($this->db->delete('client', array('client_id' => $clients_id)))
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
}
?>