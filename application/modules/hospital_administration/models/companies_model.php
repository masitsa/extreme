<?php

class Companies_model extends CI_Model 
{	
	/*
	*	Retrieve all companies
	*
	*/
	public function all_companies()
	{
		$this->db->where('company_status = 1');
		$query = $this->db->get('company');
		
		return $query;
	}
	/*
	*	Retrieve latest company
	*
	*/
	public function latest_company()
	{
		$this->db->limit(1);
		$this->db->order_by('created', 'DESC');
		$query = $this->db->get('company');
		
		return $query;
	}
	/*
	*	Retrieve all parent companies
	*
	*/
	public function all_parent_companies()
	{
		$this->db->where('company_status = 1 AND company_parent = 0');
		$this->db->order_by('company_name', 'ASC');
		$query = $this->db->get('company');
		
		return $query;
	}
	/*
	*	Retrieve all children companies
	*
	*/
	public function all_child_companies()
	{
		$this->db->where('company_status = 1 AND company_parent > 0');
		$this->db->order_by('company_name', 'ASC');
		$query = $this->db->get('company');
		
		return $query;
	}
	
	/*
	*	Retrieve all companies
	*	@param string $table
	* 	@param string $where
	*
	*/
	public function get_all_companies($table, $where, $per_page, $page, $order = 'company_name', $order_method = 'ASC')
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
	*	Add a new company
	*	@param string $image_name
	*
	*/
	public function add_company($image_name)
	{
		$data = array(
				'company_name'=>ucwords(strtolower($this->input->post('company_name'))),
				'company_parent'=>$this->input->post('company_parent'),
				'company_preffix'=>strtoupper($this->input->post('company_preffix')),
				'company_status'=>$this->input->post('company_status'),
				'created'=>date('Y-m-d H:i:s'),
				'created_by'=>$this->session->userdata('user_id'),
				'modified_by'=>$this->session->userdata('user_id'),
				'company_image_name'=>$image_name
			);
			
		if($this->db->insert('company', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Update an existing company
	*	@param string $image_name
	*	@param int $company_id
	*
	*/
	public function update_company($image_name, $company_id)
	{
		$data = array(
				'company_name'=>ucwords(strtolower($this->input->post('company_name'))),
				'company_parent'=>$this->input->post('company_parent'),
				'company_preffix'=>strtoupper($this->input->post('company_preffix')),
				'company_status'=>$this->input->post('company_status'),
				'modified_by'=>$this->session->userdata('user_id'),
				'company_image_name'=>$image_name
			);
			
		$this->db->where('company_id', $company_id);
		if($this->db->update('company', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	get a single company's children
	*	@param int $company_id
	*
	*/
	public function get_sub_companies($company_id)
	{
		//retrieve all users
		$this->db->from('company');
		$this->db->select('*');
		$this->db->where('company_parent = '.$company_id);
		$query = $this->db->get();
		
		return $query;
	}
	
	/*
	*	get a single company's details
	*	@param int $company_id
	*
	*/
	public function get_company($company_id)
	{
		//retrieve all users
		$this->db->from('company');
		$this->db->select('*');
		$this->db->where('company_id = '.$company_id);
		$query = $this->db->get();
		
		return $query;
	}
	
	/*
	*	Delete an existing company
	*	@param int $company_id
	*
	*/
	public function delete_company($company_id)
	{
		if($this->db->delete('company', array('company_id' => $company_id)))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Activate a deactivated company
	*	@param int $company_id
	*
	*/
	public function activate_company($company_id)
	{
		$data = array(
				'company_status' => 1
			);
		$this->db->where('company_id', $company_id);
		
		if($this->db->update('company', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Deactivate an activated company
	*	@param int $company_id
	*
	*/
	public function deactivate_company($company_id)
	{
		$data = array(
				'company_status' => 0
			);
		$this->db->where('company_id', $company_id);
		
		if($this->db->update('company', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
}
?>